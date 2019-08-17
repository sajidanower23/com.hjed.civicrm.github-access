<?php

/**
 * Handles Githuboauth callback
 */
use CRM_GithubAccess_ExtensionUtil as E;

class CRM_GithubAccess_Page_OAuthCallback extends CRM_Core_Page {

  public function run() {
    // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
    CRM_Utils_System::setTitle(E::ts('OAuthCallback'));

    //verify the callback
    if(CRM_GithubAccess_GithubHelper::verifyState($_GET['state'])) {
      CRM_GithubAccess_GithubHelper::doOAuthCodeExchange($_GET['code']);
      echo "success";
    } else {
      echo "error";
    }


    parent::run();
  }

}
