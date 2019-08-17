<?php
// Important logic happens here
use CRM_GithubAccess_ExtensionUtil as E;
require_once __DIR__ . "/../CRM_GithubAccess_GithubHelper.php";

class CRM_GithubAccess_Page_GithubAccessSettings extends CRM_Core_Page {

  public function run() {
    // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
    CRM_Utils_System::setTitle(E::ts('Your Github Connection'));


    $connected = civicrm_api3('Setting', 'get', array('group' => 'github_access_token'))["values"][1]['github_accessed'];
    $client_id = civicrm_api3('Setting', 'get', array('group' => 'github_access'))["values"][1]['github_access_client_id'];
    $this->assign('connected', $connected);
//    if($connected) {
//    } else {
      $state = CRM_GithubAccess_GithubHelper::oauthHelper()->newStateKey();
      $redirect_url= CRM_OauthSync_OAuthHelper::generateRedirectUrlEncoded();
      CRM_GithubAccess_GithubHelper::oauthHelper()->setOauthCallbackReturnPath(
        join('/', $this->urlPath)
      );
      $scope = urlencode("https://www.googleapis.com/auth/calendar");
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
