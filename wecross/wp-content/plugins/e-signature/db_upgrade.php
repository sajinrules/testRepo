<?php
global $wpdb;

$table_prefix = $wpdb->prefix . "esign_";

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

// UPgrade Documents Table
   
   $sql ="ALTER TABLE " . $table_prefix . "documents MODIFY COLUMN document_type ENUM('stand_alone','normal','esig_template','esig_gravity') NOT NULL DEFAULT 'normal';";
 
  $wpdb->query($sql);

$sql_latest = "ALTER TABLE " . $table_prefix . "documents` CHANGE `document_title` `document_title` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;";

$wpdb->query($sql);