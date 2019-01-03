<?php

/**
 * Handles Google Driveoauth callback
 */
use CRM_GoogleDriveFolderSync_ExtensionUtil as E;

class CRM_GoogleDriveFolderSync_Page_OAuthCallback extends CRM_Core_Page {

  public function run() {
    // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
    CRM_Utils_System::setTitle(E::ts('OAuthCallback'));

    //verify the callback
    if(CRM_GoogleDriveFolderSync_JiraApiHelper::verifyState($_GET['state'])) {
      CRM_GoogleDriveFolderSync_JiraApiHelper::doOAuthCodeExchange($_GET['code']);
      echo "success";
    } else {
      echo "error";
    }


    parent::run();
  }

}
