<?php

/**
 * Handles Google Calendaroauth callback
 */
use CRM_GoogleCalendarAccess_ExtensionUtil as E;

class CRM_GoogleCalendarAccess_Page_OAuthCallback extends CRM_Core_Page {

  public function run() {
    // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
    CRM_Utils_System::setTitle(E::ts('OAuthCallback'));

    //verify the callback
    if(CRM_GoogleCalendarAccess_GoogleCalendarHelper::verifyState($_GET['state'])) {
      CRM_GoogleCalendarAccess_GoogleCalendarHelper::doOAuthCodeExchange($_GET['code']);
      echo "success";
    } else {
      echo "error";
    }


    parent::run();
  }

}
