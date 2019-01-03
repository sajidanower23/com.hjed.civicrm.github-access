<?php

require_once 'google_drive_folder_sync.civix.php';
use CRM_GoogleDriveFolderSync_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function google_drive_folder_sync_civicrm_config(&$config) {
  _google_drive_folder_sync_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function google_drive_folder_sync_civicrm_xmlMenu(&$files) {
  _google_drive_folder_sync_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function google_drive_folder_sync_civicrm_install() {
  _google_drive_folder_sync_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function google_drive_folder_sync_civicrm_postInstall() {
  _google_drive_folder_sync_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function google_drive_folder_sync_civicrm_uninstall() {
  _google_drive_folder_sync_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function google_drive_folder_sync_civicrm_enable() {
  _google_drive_folder_sync_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function google_drive_folder_sync_civicrm_disable() {
  _google_drive_folder_sync_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function google_drive_folder_sync_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _google_drive_folder_sync_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function google_drive_folder_sync_civicrm_managed(&$entities) {
  _google_drive_folder_sync_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function google_drive_folder_sync_civicrm_caseTypes(&$caseTypes) {
  _google_drive_folder_sync_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function google_drive_folder_sync_civicrm_angularModules(&$angularModules) {
  _google_drive_folder_sync_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function google_drive_folder_sync_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _google_drive_folder_sync_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function google_drive_folder_sync_civicrm_entityTypes(&$entityTypes) {
  _google_drive_folder_sync_civix_civicrm_entityTypes($entityTypes);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_pageRun().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 */
function google_drive_folder_sync_civicrm_pageRun(&$run) {
}


/**
 * Implements hook_civicrm_oauthsync_consent_success().
 *
 * Used to get the connection id
 */
function google_drive_folder_sync_civicrm_oauthsync_consent_success(&$prefix) {
  // we don't need to do anything here
}

/**
 * Implements hook_civicrm_oauthsync_google_drive_folder_sync_groups_list().
 *
 * Used to get the connection id
 */
function google_drive_folder_sync_civicrm_oauthsync_google_drive_folder_sync_sync_groups_list(&$groups) {
  // query, searches for folders in the root
  $groups_json = CRM_GoogleDriveFolderSync_GoogleDriveHelper::getFolderList();
  print_r($groups_json);

  foreach ($groups_json as $group) {
    $groups[] = $group;
  }
}

/**
 * Implements hook_civicrm_oauthsync_google_drive_folder_sync_get_remote_user_list().
 *
 * Used to sync the members of a remote group
 */
function google_drive_folder_sync_civicrm_oauthsync_google_drive_folder_sync_get_remote_user_list(&$remoteGroupName, &$members) {
  // query, searches for folders in the root
  $query = urlencode("mimeType='application/vnd.google-apps.folder' and 'root' in parents");
  $contactIds = CRM_GoogleDriveFolderSync_GoogleDriveHelper::getAllGDriveUserForRoleAndGroup($remoteGroupName);
  // TODO: handle the above being an error

  foreach ($contactIds as $contactId) {
    $members[] = $contactId;
  }

  print_r($members);
}

/**
 *
 * Implements hook_civicrm_oauthsync_google_drive_folder_sync_update_remote_users().
 *
 * Used to sync the members of a remote group
 */
function google_drive_folder_sync_civicrm_oauthsync_google_drive_folder_sync_update_remote_users(&$remoteGroupName, &$toRemove, &$toAdd) {

  foreach ($toAdd as $contactId) {
    CRM_GoogleDriveFolderSync_GoogleDriveHelper::addContactToRemoteGroup($contactId, $remoteGroupName);
  }
  // TODO: handle the above being an error
  print("starting on remove\n");
  foreach($toRemove as $contactId) {
    CRM_GoogleDriveFolderSync_GoogleDriveHelper::removeContactFromRemoteGroup($contactId, $remoteGroupName);
  }
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
 */
function google_drive_folder_sync_civicrm_navigationMenu(&$menu) {
  _google_drive_folder_sync_civix_insert_navigation_menu($menu, 'Administer', array(
    'label' => E::ts('Google Drive Settings'),
    'name' => 'Google Drive SYnc',
    'permission' => 'administer CiviCRM',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _google_drive_folder_sync_civix_insert_navigation_menu($menu, 'Administer/GoogleDriveSync', array(
    'label' => E::ts('Google Drive API Settings'),
    'name' => 'google_drive_folder_sync_settings',
    'url' => 'civicrm/google-drive-folder-sync/config',
    'permission' => 'administer CiviCRM',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _google_drive_folder_sync_civix_insert_navigation_menu($menu, 'Administer/GoogleDriveSync', array(
    'label' => E::ts('Google Drive Connection'),
    'name' => 'google_drive_folder_syncion',
    'url' => 'civicrm/google-drive-folder-sync/connection',
    'permission' => 'administer CiviCRM',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _google_drive_folder_sync_civix_navigationMenu($menu);
}

require_once "CRM/GoogleDriveFolderSync/CRM_GoogleDriveFolderSync_GoogleDriveHelper.php";
require_once CRM_Extension_System::singleton()->getMapper()->keyToPath('com.hjed.civicrm.oauth-sync');
CRM_GoogleDriveFolderSync_GoogleDriveHelper::oauthHelper();
