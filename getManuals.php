<?php
/*
Plugin Name: Get Manuals
Plugin URI: https://example.com/my-serial-number-plugin
Description: A plugin that allows users to search for manuals using serial numbers.
Version: 1.0
Authors: George P & Juan C
Author URI:
License: GPL2
*/
//include all the classes in src/
require_once plugin_dir_path(__FILE__) . 'src/tables.php';
require_once plugin_dir_path(__FILE__) . 'src/manuals.php';
require_once plugin_dir_path(__FILE__) . 'src/display-manuals.php';
require_once plugin_dir_path(__FILE__) . 'src/database.php';

// Shortcode to display the search form
add_shortcode('serial_number_search_form', 'display_serial_number_search_form');
/// Function to display the search form and manual details
function display_serial_number_search_form()
{
    $display_manuals = new DisplayManuals();
    $display_manuals->display_serial_number_search_form();
}

//Function to search the DB for the serial number and return the manual

function get_manual_by_serial_number_and_product_code($serial_number, $product_code)
{
    //use the get manual by serial number function from the database class
    $manual = Database::get_manual_by_serial_number_and_product_code($serial_number, $product_code);
    return $manual;
}

// activation hook
register_activation_hook(__FILE__, 'my_plugin_activate');
function my_plugin_activate()
{
  // Code to be executed on plugin activation
  Database::create_tables();
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'my_plugin_deactivate');
function my_plugin_deactivate()
{
    // Code to be executed on plugin deactivation
}
// Uninstall hook
register_uninstall_hook(__FILE__, 'my_plugin_uninstall');
function my_plugin_uninstall()
{
    // Code to be executed on plugin uninstallation
    Database::drop_tables();
}
?>