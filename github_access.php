<?php

require_once 'github_access.civix.php';
use CRM_GithubAccess_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function github_access_civicrm_config(&$config) {
  _github_access_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function github_access_civicrm_xmlMenu(&$files) {
  _github_access_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function github_access_civicrm_install() {
  _github_access_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function github_access_civicrm_postInstall() {
  _github_access_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function github_access_civicrm_uninstall() {
  _github_access_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function github_access_civicrm_enable() {
  _github_access_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function github_access_civicrm_disable() {
  _github_access_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function github_access_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _github_access_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function github_access_civicrm_managed(&$entities) {
  _github_access_civix_civicrm_managed($entities);
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
function github_access_civicrm_caseTypes(&$caseTypes) {
  _github_access_civix_civicrm_caseTypes($caseTypes);
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
function github_access_civicrm_angularModules(&$angularModules) {
  _github_access_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function github_access_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _github_access_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function github_access_civicrm_entityTypes(&$entityTypes) {
  _github_access_civix_civicrm_entityTypes($entityTypes);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_pageRun().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 */
function github_access_civicrm_pageRun(&$run) {
}


/**
 * Implements hook_civicrm_oauthsync_consent_success().
 *
 * Used to get the connection id
 */
function github_access_civicrm_oauthsync_consent_success(&$prefix) {
  // we don't need to do anything here
}

/**
 * Implements hook_civicrm_oauthsync_github_access_groups_list().
 *
 * Used to get the connection id
 */
function github_access_civicrm_oauthsync_github_access_sync_groups_list(&$groups) {
  // query, searches for folders in the root
  $groups_json = CRM_GithubAccess_GithubHelper::getCalendarList();

  foreach ($groups_json as $group) {
    $groups[] = $group;
  }
}

/**
 * Implements hook_civicrm_oauthsync_github_access_get_remote_user_list().
 *
 * Used to sync the members of a remote group
 */
function github_access_civicrm_oauthsync_github_access_get_remote_user_list(&$remoteGroupName, &$members) {
  // query, searches for folders in the root
  $query = urlencode("mimeType='application/vnd.google-apps.folder' and 'root' in parents");
  $contactIds = CRM_GithubAccess_GithubHelper::getAllGCalendarUserForRoleAndGroup($remoteGroupName);
  // TODO: handle the above being an error

  foreach ($contactIds as $contactId) {
    $members[] = $contactId;
  }

}

/**
 *
 * Implements hook_civicrm_oauthsync_github_access_update_remote_users().
 *
 * Used to sync the members of a remote group
 */
function github_access_civicrm_oauthsync_github_access_update_remote_users(&$remoteGroupName, &$toRemove, &$toAdd) {

  foreach ($toAdd as $contactId) {
    CRM_GithubAccess_GithubHelper::addContactToRemoteGroup($contactId, $remoteGroupName);
  }
  // TODO: handle the above being an error
  foreach($toRemove as $contactId) {
    CRM_GithubAccess_GithubHelper::removeContactFromRemoteGroup($contactId, $remoteGroupName);
  }
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
 */
function github_access_civicrm_navigationMenu(&$menu) {
  _github_access_civix_insert_navigation_menu($menu, 'Administer', array(
    'label' => E::ts('Github Settings'),
    'name' => 'GithubSync',
    'permission' => 'administer CiviCRM',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _github_access_civix_insert_navigation_menu($menu, 'Administer/GithubSync', array(
    'label' => E::ts('Github API Settings'),
    'name' => 'github_access_settings',
    'url' => 'civicrm/github-folder-sync/config',
    'permission' => 'administer CiviCRM',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _github_access_civix_insert_navigation_menu($menu, 'Administer/GithubSync', array(
    'label' => E::ts('Github Connection'),
    'name' => 'github_access_connection',
    'url' => 'civicrm/github-folder-sync/connection',
    'permission' => 'administer CiviCRM',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _github_access_civix_navigationMenu($menu);
}

require_once "CRM/GithubAccess/CRM_GithubAccess_GithubHelper.php";
require_once CRM_Extension_System::singleton()->getMapper()->keyToPath('com.hjed.civicrm.oauth-sync');
CRM_GithubAccess_GithubHelper::oauthHelper();
