<?php
use CRM_GoogleCalendarAccess_ExtensionUtil as E;

class CRM_GoogleCalendarAccess_BAO_GoogleCalendarAccess extends CRM_GoogleCalendarAccess_DAO_GoogleCalendarAccess {

  const G_CALENDAR_ROLES = array(
    'freeBusyReader', 'reader', 'writer', 'owner'
  );
  /**
   * Create a new GoogleCalendarAccess based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_GoogleCalendarAccess_DAO_GoogleCalendarAccess|NULL
   */
  public static function create($params) {
    $className = 'CRM_GoogleCalendarAccess_DAO_GoogleCalendarAccess';
    $entityName = 'GoogleCalendarAccess';
    $hook = 'create';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  }

  /**
   * Create a set of new GoogleCalendarAccess based on a Google Calendar object
   * and its possible roles
   *
   * @param array $params key-value pairs representing a file in the google Calendar rest api
   * @return array of role based GoogleCalendarAccess's
   */
  public static function createFromGoogCalListEntry($params) {
    $calendarName = $params['summary'];
    $names = array();
    foreach (self::G_CALENDAR_ROLES as $role) {
      $nameAndRole = $role . ":" . $calendarName;
      $dbParams = array(
        'google_id' => $params['id'],
        'calendar_name_and_role' => $nameAndRole,
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
      "SELECT google_id, role FROM civicrm_google_calendar_access WHERE calendar_name_and_role = (%1)",
      array(1 => array($oGroupValue, 'String'))
    );
    $dao->fetch();
    return $dao;
  }

}
