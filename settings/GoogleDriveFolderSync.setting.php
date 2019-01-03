<?php
/**
 * Created by IntelliJ IDEA.
 * User: hjed
 * Date: 9/09/18
 * Time: 6:04 PM
 */

return array(
    'google_drive_folder_sync_cloud_id' => array(
      'group_name' => 'Google DriveSettings',
      'group' => 'google_drive_folder_sync_settings',
      'name' => 'google_drive_folder_sync_cloud_id',
      'type' => 'String',
      'add' => '4.4',
      'is_domain' => 1,
      'is_contact' => 0,
      'description' => 'Google Drive"cloudid" for the domain we are connected to',
      'title' => 'Google DriveCloudID',
      'help_text' => '',
      'default' => false,
    ),
  ) +
  CRM_OAuthSync_Settings::generateSettings('google_drive_folder_sync', 'Google Drive Sync');

?>