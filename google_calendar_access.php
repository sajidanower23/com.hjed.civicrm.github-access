<?php

require_once 'google_calendar_access.civix.php';
use CRM_GoogleCalendarAccess_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function google_calendar_access_civicrm_config(&$config) {
  _google_calendar_access_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function google_calendar_access_civicrm_xmlMenu(&$files) {
  _google_calendar_access_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function google_calendar_access_civicrm_install() {
  _google_calendar_access_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function google_calendar_access_civicrm_postInstall() {
  _google_calendar_access_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function google_calendar_access_civicrm_uninstall() {
  _google_calendar_access_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function google_calendar_access_civicrm_enable() {
  _google_calendar_access_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function google_calendar_access_civicrm_disable() {
  _google_calendar_access_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function google_calendar_access_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _google_calendar_access_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function google_calendar_access_civicrm_managed(&$entities) {
  _google_calendar_access_civix_civicrm_managed($entities);
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
function google_calendar_access_civicrm_caseTypes(&$caseTypes) {
  _google_calendar_access_civix_civicrm_caseTypes($caseTypes);
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
function google_calendar_access_civicrm_angularModules(&$angularModules) {
  _google_calendar_access_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function google_calendar_access_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _google_calendar_access_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function google_calendar_access_civicrm_entityTypes(&$entityTypes) {
  _google_calendar_access_civix_civicrm_entityTypes($entityTypes);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_pageRun().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 */
function google_calendar_access_civicrm_pageRun(&$run) {
}


/**
 * Implements hook_civicrm_oauthsync_consent_success().
 *
 * Used to get the connection id
 */
function google_calendar_access_civicrm_oauthsync_consent_success(&$prefix) {
  // we don't need to do anything here
}

/**
 * Implements hook_civicrm_oauthsync_google_calendar_access_groups_list().
 *
 * Used to get the connection id
 */
function google_calendar_access_civicrm_oauthsync_google_calendar_access_sync_groups_list(&$groups) {
  // query, searches for folders in the root
  $groups_json = CRM_GoogleCalendarAccess_GoogleCalendarHelper::getCalendarList();

  foreach ($groups_json as $group) {
    $groups[] = $group;
  }
}

/**
 * Implements hook_civicrm_oauthsync_google_calendar_access_get_remote_user_list().
 *
 * Used to sync the members of a remote group
 */
function google_calendar_access_civicrm_oauthsync_google_calendar_access_get_remote_user_list(&$remoteGroupName, &$members) {
  // query, searches for folders in the root
  $query = urlencode("mimeType='application/vnd.google-apps.folder' and 'root' in parents");
  $contactIds = CRM_GoogleCalendarAccess_GoogleCalendarHelper::getAllGCalendarUserForRoleAndGroup($remoteGroupName);
  // TODO: handle the above being an error

  foreach ($contactIds as $contactId) {
    $members[] = $contactId;
  }

}

/**
 *
 * Implements hook_civicrm_oauthsync_google_calendar_access_update_remote_users().
 *
 * Used to sync the members of a remote group
 */
function google_calendar_access_civicrm_oauthsync_google_calendar_access_update_remote_users(&$remoteGroupName, &$toRemove, &$toAdd) {

  foreach ($toAdd as $contactId) {
    CRM_GoogleCalendarAccess_GoogleCalendarHelper::addContactToRemoteGroup($contactId, $remoteGroupName);
  }
  // TODO: handle the above being an error
  foreach($toRemove as $contactId) {
    CRM_GoogleCalendarAccess_GoogleCalendarHelper::removeContactFromRemoteGroup($contactId, $remoteGroupName);
  }
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
 */
function google_calendar_access_civicrm_navigationMenu(&$menu) {
  _google_calendar_access_civix_insert_navigation_menu($menu, 'Administer', array(
    'label' => E::ts('Google Calendar Settings'),
    'name' => 'GoogleCalendarSync',
    'permission' => 'administer CiviCRM',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _google_calendar_access_civix_insert_navigation_menu($menu, 'Administer/GoogleCalendarSync', array(
    'label' => E::ts('Google Calendar API Settings'),
    'name' => 'google_calendar_access_settings',
    'url' => 'civicrm/google-calendar-folder-sync/config',
    'permission' => 'administer CiviCRM',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _google_calendar_access_civix_insert_navigation_menu($menu, 'Administer/GoogleCalendarSync', array(
    'label' => E::ts('Google Calendar Connection'),
    'name' => 'google_calendar_access_connection',
    'url' => 'civicrm/google-calendar-folder-sync/connection',
    'permission' => 'administer CiviCRM',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _google_calendar_access_civix_navigationMenu($menu);
}

require_once "CRM/GoogleCalendarAccess/CRM_GoogleCalendarAccess_GoogleCalendarHelper.php";
require_once CRM_Extension_System::singleton()->getMapper()->keyToPath('com.hjed.civicrm.oauth-sync');
CRM_GoogleCalendarAccess_GoogleCalendarHelper::oauthHelper();
