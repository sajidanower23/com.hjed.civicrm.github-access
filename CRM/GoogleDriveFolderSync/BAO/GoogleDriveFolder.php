<?php
use CRM_GoogleDriveFolderSync_ExtensionUtil as E;

class CRM_GoogleDriveFolderSync_BAO_GoogleDriveFolder extends CRM_GoogleDriveFolderSync_DAO_GoogleDriveFolder {

  const G_DRIVE_ROLES = array(
    'owner', 'organizer', 'fileOrganizer', 'writer', 'commenter', 'reader'
  );
  /**
   * Create a new GoogleDriveFolder based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_GoogleDriveFolderSync_DAO_GoogleDriveFolder|NULL
   */
  public static function create($params) {
    $className = 'CRM_GoogleDriveFolderSync_DAO_GoogleDriveFolder';
    $entityName = 'GoogleDriveFolder';
    $hook = 'create';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  }

  /**
   * Create a set of new GoogleDriveFolder based on a Google Drive file object
   * and its possible roles
   *
   * @param array $params key-value pairs representing a file in the google drive rest api
   * @return array of role based GoogleDriveFolder's
   */
  public static function createFromGoogFile($params) {
    $folderName = $params['name'];
    $names = array();
    foreach (self::G_DRIVE_ROLES as $role) {
      $nameAndRole = $role . ":" . $folderName;
      $dbParams = array(
        'google_id' => $params['id'],
        'folder_name_and_role' => $nameAndRole,
        'role' => $role
      );

      self::create($dbParams);
      $names[] = $nameAndRole;
    }
    return $names;
  }

  /**
   * Lookup a folder mapping
   * @param $oGroupValue the option group value to search for
   * @return CRM_Core_DAO|object the dao containing the google_id and the role
   */
  public static function getByOptionGroupValue($oGroupValue) {
    $dao = CRM_Core_DAO::executeQuery(
      "SELECT google_id, role FROM civicrm_google_drive_folder WHERE folder_name_and_role = (%1)",
      array(1 => array($oGroupValue, 'String'))
    );
    $dao->fetch();
    return $dao;
  }

}
