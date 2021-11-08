<?php
/**
 * Plugin Name: QA Plugin
 * Plugin URI: https://singularity.is
 * Description: This is a qa plugin with checklist for object web pages
 * Version: 1.0
 * Author: Miljana Pinic
 **/

define('QA__PLUGIN_DIR', plugin_dir_path(__FILE__));

$path = $_SERVER['DOCUMENT_ROOT'];

$path .= '/plugin_test';

include_once $path . '/wp-config.php';
include_once $path . '/wp-load.php';
include_once $path . '/wp-includes/wp-db.php';
include_once $path . '/wp-includes/pluggable.php';
require_once 'qa-plugin-functionality.php';
require_once 'qa-plugin-frontend.php' ;

add_action('admin_menu', 'show_qa_plugin');
function show_qa_plugin()
{
    add_menu_page(
        __('QA plugin'),// the page title
        __('QA plugin'),//menu title
        'manage_options',//capability
        'add-edit-shops',//menu slug
        'front_qa_plugin',
        '',
        '50'

    );
}

register_activation_hook(__FILE__, 'on_qa_plugin_activate');
register_activation_hook(__FILE__, 'insert_qa_requests');

function on_qa_plugin_activate()
{
    create_qa_checkboxes();
}

function create_qa_checkboxes()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->base_prefix}qa_checkboxes` (
            id INTEGER NOT NULL AUTO_INCREMENT,
            checked TINYINT NOT NULL,
            title varchar(200) NOT NULL,
            description varchar(200),
            comment TEXT,
            PRIMARY KEY (id)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
	$sql2 = "CREATE TABLE IF NOT EXISTS `{$wpdb->base_prefix}qa_history` (
            id INTEGER NOT NULL AUTO_INCREMENT,
			checked TINYINT,
			username varchar(200),
            request_id int,
		    FOREIGN KEY (request_id) REFERENCES {$wpdb->base_prefix}qa_checkboxes(id),
            PRIMARY KEY (id)
    ) $charset_collate;";
	dbDelta($sql2);
}

function insert_qa_requests()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'qa_checkboxes';
    require_once('requests-array.php');
    foreach ($requests_array as $data) {
        $wpdb->insert(
            $table_name,
            $data
        );
    }
}

add_action('rest_api_init', function () {

    register_rest_route('qa_plugin/', 'update', array(
        'methods' => 'POST',
        'callback' => 'update_qa_requests_comment',
    ));
	 register_rest_route('qa_plugin/', 'update_checked', array(
        'methods' => 'POST',
        'callback' => 'update_checklist',
    ));
	register_rest_route('qa_plugin/', 'insert', array(
        'methods' => 'POST',
        'callback' => 'insert_request',
    ));
	register_rest_route('qa_plugin/', 'delete', array(
        'methods' => 'GET',
        'callback' => 'delete',
    ));
});





