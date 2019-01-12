<?php
/**
 * Helper Functions for the Google Drive Api
 */
class CRM_GoogleDriveFolderSync_GoogleDriveHelper {

  const TOKEN_URL = "https://www.googleapis.com/oauth2/v4/token";
  const GOOGLE_DRIVE_REST_API_URL = 'https://www.googleapis.com/drive/v3';

  public static function oauthHelper() {
    static $oauthHelperObj = null;
    if($oauthHelperObj == null) {
      $oauthHelperObj = new CRM_OauthSync_OAuthHelper("google_drive_folder_sync", self::TOKEN_URL);
    }
    return $oauthHelperObj;
  }

  /**
   * Performs an oauth authorization code grant exchange.
   * Redirects back if successful.
   *
   * @param $code the code to use for the exchange
   */
  public static function doOAuthCodeExchange($code) {
    $client_id = Civi::settings()->get('google_drive_folder_sync_client_id');
    $client_secret = Civi::settings()->get('google_drive_folder_sync_secret');
    $redirect_url = CRM_OauthSync_OAuthHelper::generateRedirectUrl();

    $requestJsonDict = array(
      'client_id' => $client_id,
      'client_secret' => $client_secret,
      'redirect_uri' => $redirect_url,
      'grant_type' => 'authorization_code',
      'code' => $code
    );
    $postBody = json_encode($requestJsonDict, JSON_UNESCAPED_SLASHES);

    // make a request
    $ch = curl_init(self::TOKEN_URL);
    curl_setopt_array($ch, array(
      CURLOPT_POST => TRUE,
      CURLOPT_RETURNTRANSFER => TRUE,
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
      // the token endpoint requires a user agent
      CURLOPT_USERAGENT => 'curl/7.55.1',
      CURLOPT_POSTFIELDS => $postBody
    ));
    $response = curl_exec($ch);
    if(curl_errno($ch)) {
      echo 'Request Error:' . curl_error($ch);
      // TODO: handle this better
    } else {
      $response_json = json_decode($response, true);
      if(in_array("error", $response_json)) {
        // TODO: handle this better
        echo "<br/><br/>Error\n\n";
        echo $response_json["error_description"];
      } else {
        self::oauthHelper()->parseOAuthTokenResponse($response_json);
        Civi::settings()->set("google_drive_folder_sync_connected", true);
        $return_path = CRM_Utils_System::url('civicrm/google-drive-folder-sync/connection', 'reset=1', TRUE, NULL, FALSE, FALSE);
        header("Location: " . $return_path);
        die();
      }
    }

  }

  /**
   * Call a Google Drive api endpoint
   *
   * @param string $path the path after the Google Drivebase url
   *  Ex. /rest/api/3/groups/picker
   * @param string $method the http method to use
   * @param array $body the body of the post request
   * @return array | CRM_Core_Error
   */
  public static function callGoogleApi($path, $method = "GET", $body = NULL) {

    // build the url
    $url = self::GOOGLE_DRIVE_REST_API_URL . $path;

    $ch = curl_init($url);
    curl_setopt_array($ch, array(
      CURLOPT_RETURNTRANSFER => TRUE,
      CURLOPT_CUSTOMREQUEST => $method
    ));
    if($body != NULL) {
      $encodedBody = json_encode($body);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedBody);
    }
    self::oauthHelper()->addAccessToken($ch);

    $response = curl_exec($ch);
    if (curl_errno($ch) || curl_getinfo($ch, CURLINFO_HTTP_CODE) >= 300) {
      print 'Request Error:' . curl_error($ch);
      print '<br/>\nStatus Code: ' . curl_getinfo($ch, CURLINFO_HTTP_CODE);
      print_r($ch);
      throw new CRM_Extension_Exception("Google Drive API Request Failed");
      return CRM_Core_Error::createError("Failed to access Google DriveAPI");
      // TODO: handle this better
    } else {
      return json_decode($response, true);
    }
  }

  public static function getFolderList() {
    $query = urlencode("mimeType='application/vnd.google-apps.folder' and 'root' in parents");
    $groups_json = self::callGoogleApi(
      '/files?corpus=user&spaces=drive&q=' . $query,
      "GET"
    );
    // TODO: handle pagination

    if(!array_key_exists("files", $groups_json)) {
      throw new CRM_Extension_Exception("Didn't get any files");
    }

    $folderNames = array();
    foreach ($groups_json['files'] as $file) {
      $folderNames = array_merge($folderNames, CRM_GoogleDriveFolderSync_BAO_GoogleDriveFolder::createFromGoogFile($file));
    }

    return $folderNames;
  }

  /**
   * Retrieves the email address to use with google.
   * Unless otherwise specified this will be the user's default email address
   * @return string|null
   * @throws CiviCRM_API3_Exception
   */
  private static function getContactEmail($contactId) {
    return CRM_Contact_BAO_Contact::getPrimaryEmail($contactId);
  }

  /**
   * Adds the contact to the remote group.
   * If the contact has not been synced before it will add its Google Drive account details
   * @param $contactId the contact id of the remote contact
   * @param $remoteGroup the remote group name
   */
  public static function addContactToRemoteGroup($contactId, $remoteGroup) {
    // get the remote group
    $remoteGroup = CRM_GoogleDriveFolderSync_BAO_GoogleDriveFolder::getByOptionGroupValue($remoteGroup);


    $contactEmail = self::getContactEmail($contactId);

    $response = self::callGoogleApi(
      '/files/' . $remoteGroup->google_id . '/permissions', "POST", array(
        'role' => $remoteGroup->role,
        'type' => 'user',
        'emailAddress' => $contactEmail
      )
    );

    self::$googleDrivePermsCache[$remoteGroup->google_id][$remoteGroup->role][$contactId] =
      array($response['id'], $response);
  }

  /**
   * Removes a given contact from a remote group
   * @param int $contactId the contact to remove
   * @param string $remoteGroup the remote group to remove them from
   */
  public static function removeContactFromRemoteGroup(&$contactId, $remoteGroup) {
    $remoteGroupDAO = CRM_GoogleDriveFolderSync_BAO_GoogleDriveFolder::getByOptionGroupValue($remoteGroup);
    self::refreshLocalPermissionsCache($remoteGroupDAO->google_id);

    $response = self::callGoogleApi(
      '/files/' .
      $remoteGroupDAO->google_id .
      '/permissions/' .
      self::$googleDrivePermsCache[$remoteGroupDAO->google_id][$remoteGroupDAO->role][intval($contactId)][0],
      "DELETE"
    );
  }

  //format is [fileId][role][contactId] = array(permissionId, jsonObject)
  private static $googleDrivePermsCache = array();

  /**
   * Because we can't lookup an individual permission without getting the whole list, this
   * requests the permissions for the given google drive file id and caches them by role and contactId.
   *
   * This makes delete a lot faster.
   *
   * @param $googleId the google drive file id
   * @param bool $forceUpdate
   * @throws CRM_Extension_Exception
   */
  private static function refreshLocalPermissionsCache($googleId, $forceUpdate=false) {

    if(!$forceUpdate && key_exists($googleId, self::$googleDrivePermsCache)) {
      return;
    }

    $response = self::callGoogleApi('/files/' . $googleId . '/permissions?fields=permissions');
    // TODO: error handling and pagination
    foreach($response['permissions'] as $permission) {
      $contact = CRM_Contact_BAO_Contact::matchContactOnEmail($permission['emailAddress']);
      // drop contacts we don't match
      // (for now this only support contacts that already exist in civicrm)
      if(!key_exists($googleId, self::$googleDrivePermsCache)) {
        self::$googleDrivePermsCache[$googleId] = array();
      }
      if(!key_exists($permission['role'], self::$googleDrivePermsCache[$googleId])) {
        self::$googleDrivePermsCache[$googleId][$permission['role']] = array();
      }

      if ($contact != null) {
        self::$googleDrivePermsCache[$googleId][$permission['role']][$contact->contact_id] = array(
          $permission['id'], $permission
        );
      }
    }

  }

  /**
   * given an 'remote group' value representing a group/role return all associated contacts
   * @param $remoteGroup
   * @return array
   * @throws CRM_Extension_Exception
   */
  public static function getAllGDriveUserForRoleAndGroup($remoteGroup) {
    $contactIds = array();
    $remoteGroupDAO = CRM_GoogleDriveFolderSync_BAO_GoogleDriveFolder::getByOptionGroupValue($remoteGroup);
    self::refreshLocalPermissionsCache($remoteGroupDAO->google_id);
    foreach(self::$googleDrivePermsCache[$remoteGroupDAO->google_id][$remoteGroup->role] as $contactId => $permission) {
        $contactIds[] = $contactId;
    }
    return $contactIds;
  }
}


