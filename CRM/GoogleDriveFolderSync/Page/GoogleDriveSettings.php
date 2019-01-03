<?php
use CRM_GoogleDriveFolderSync_ExtensionUtil as E;
require_once __DIR__ . "/../CRM_GoogleDriveFolderSync_GoogleDriveHelper.php";

class CRM_GoogleDriveFolderSync_Page_GoogleDriveSettings extends CRM_Core_Page {

  public function run() {
    // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
    CRM_Utils_System::setTitle(E::ts('Your Google Drive Connection'));


    $connected = civicrm_api3('Setting', 'get', array('group' => 'google_drive_folder_sync_token'))["values"][1]['google_drive_folder_synced'];
    $client_id = civicrm_api3('Setting', 'get', array('group' => 'google_drive_folder_sync'))["values"][1]['google_drive_folder_sync_client_id'];
    print_r(civicrm_api3('Setting', 'get', array('group' => 'google_drive_folder_sync'))['values'][1]['google_drive_folder_sync_key']);
    $this->assign('connected', $connected);
//    if($connected) {
//    } else {
      $state = CRM_GoogleDriveFolderSync_GoogleDriveHelper::oauthHelper()->newStateKey();
      $redirect_url= CRM_OauthSync_OAuthHelper::generateRedirectUrlEncoded();
      CRM_GoogleDriveFolderSync_GoogleDriveHelper::oauthHelper()->setOauthCallbackReturnPath(
        join('/', $this->urlPath)
      );
      $scope = urlencode("https://www.googleapis.com/auth/drive");
      $this->assign(
        'oauth_url',
        'https://accounts.google.com/o/oauth2/v2/auth' .
        '?client_id=' . $client_id .
        '&access_type=offline' .
        '&scope=' . $scope .
        '&redirect_uri=' . $redirect_url .
        '&state=' . $state .
        '&response_type=code&prompt=consent'
      );
//    }
    // Example: Assign a variable for use in a template
    $this->assign('currentTime', date('Y-m-d H:i:s'));

    parent::run();
  }

}
