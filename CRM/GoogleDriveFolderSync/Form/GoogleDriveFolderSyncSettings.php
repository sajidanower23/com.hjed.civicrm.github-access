<?php

use CRM_GoogleDriveFolderSync_ExtensionUtil as E;

/**
 * Form controller class
 * Lots of inspiration drawn from https://github.com/eileenmcnaughton/nz.co.fuzion.civixero/blob/master/CRM/Civixero/Form/XeroSettings.php
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */
class CRM_GoogleDriveFolderSync_Form_GoogleDriveFolderSyncSettings extends CRM_OauthSync_Form_ConnectionSettings {

  protected function getConnectionSettingsPrefix() {
    return 'google_drive_folder_sync';
  }

  protected function getHumanReadableConnectionName() {
    return "JIRA";
  }

}
