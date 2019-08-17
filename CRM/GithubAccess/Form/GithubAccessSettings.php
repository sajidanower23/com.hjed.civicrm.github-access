<?php

use CRM_GithubAccess_ExtensionUtil as E;

/**
 * Form controller class
 * Lots of inspiration drawn from https://github.com/eileenmcnaughton/nz.co.fuzion.civixero/blob/master/CRM/Civixero/Form/XeroSettings.php
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */
class CRM_GithubAccess_Form_GithubAccessSettings extends CRM_OauthSync_Form_ConnectionSettings {

  protected function getConnectionSettingsPrefix() {
    return 'github_access';
  }

  protected function getHumanReadableConnectionName() {
    return "Github Sync";
  }

}
