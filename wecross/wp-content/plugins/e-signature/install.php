<?php
global $wpdb;

$table_prefix = $wpdb->prefix . "esign_";

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

// Documents Table
$sql = "CREATE TABLE IF NOT EXISTS `" . $table_prefix . "documents`(
				`document_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				`user_id` int(11) NOT NULL,
				`post_id` int(11) NOT NULL,
				`document_title` varchar(200) NOT NULL,
				`document_content` longtext NOT NULL,
				`notify` tinyint(1) NOT NULL DEFAULT 0,
				`add_signature` tinyint(1) NOT NULL DEFAULT 0,
				`document_type` enum('stand_alone','normal','esig_template','esig-gravity') NOT NULL DEFAULT 'normal',
				`document_status` varchar(24) NOT NULL,
				`document_checksum` text NOT NULL,
				`document_uri` text NULL,
				`ip_address` varchar(20) NOT NULL DEFAULT '0.0.0.0',
				`date_created` datetime NOT NULL,
				`last_modified` datetime NOT NULL) ENGINE = INNODB";

dbDelta($sql);


// Generic Settings Table
$sql = "CREATE TABLE IF NOT EXISTS `" . $table_prefix . "settings`(
			  `setting_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			  `user_id` int(11) NOT NULL,
			  `setting_name` varchar(55) NOT NULL,
			  `setting_value` longtext NOT NULL) ENGINE = INNODB";
dbDelta($sql);


// Set initialized to 'false'
//$sql = "INSERT INTO " . $table_prefix . "settings VALUES(null, 1, 'initialized', 'false')";
//dbDelta($sql);


// Signatures Table
$sql = "CREATE TABLE IF NOT EXISTS `" . $table_prefix . "signatures`(
			  `signature_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			  `user_id` int(11) NOT NULL,
			  `signature_type` varchar(20) NOT NULL DEFAULT 'full',
			  `signature_hash` char(64) NOT NULL,
			  `signature_salt` char(40) NOT NULL,
			  `signature_data` longtext NOT NULL,
			  `signature_added` datetime NOT NULL) ENGINE = INNODB";
dbDelta($sql);	


// Documents / Signatures Join Table
$sql = "CREATE TABLE IF NOT EXISTS `" . $table_prefix . "documents_signatures`(
			  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			  `document_id` int(11) NOT NULL,
			  `signature_id` int(11) NOT NULL,
			  `ip_address` varchar(20) NOT NULL,
			  `sign_date` datetime NOT NULL) ENGINE = INNODB";
dbDelta($sql);


// Documents Events Join Table
$sql = "CREATE TABLE IF NOT EXISTS `" . $table_prefix . "documents_events`(
			  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			  `document_id` int(11) NOT NULL,
			  `event` varchar(20) NOT NULL,
			  `event_data` varchar(256) NOT NULL,
			  `date` datetime NOT NULL) ENGINE = INNODB";
dbDelta($sql);


// Users Table
$sql = "CREATE TABLE IF NOT EXISTS `" . $table_prefix . "users`(
			  `user_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			  `wp_user_id` int(11) NULL,
			  `uuid` char(36) NOT NULL,
			  `user_email` varchar(100) NOT NULL,
			  `user_title` varchar(55) NOT NULL DEFAULT '',
			  `first_name` varchar(45) NOT NULL,
			  `last_name` varchar(65) NOT NULL) ENGINE = INNODB";
dbDelta($sql);


// Invitation table
$sql = "CREATE TABLE IF NOT EXISTS `" . $table_prefix . "invitations`(
			  `invitation_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			  `user_id` int(11) NOT NULL,
			  `document_id` int(11) NOT NULL,
			  `invite_hash` text NOT NULL,
			  `invite_message` longtext NOT NULL,
			  `invite_sent` tinyint(1) NOT NULL DEFAULT 0,
			  `sender_ip` varchar(20) NOT NULL DEFAULT '0.0.0.0',
			  `invite_sent_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00') ENGINE = INNODB";
dbDelta($sql);

