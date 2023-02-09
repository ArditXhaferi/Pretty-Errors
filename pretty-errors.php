<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://ardit.dev
 * @since             1.0.0
 * @package           Pretty_Errors
 *
 * @wordpress-plugin
 * Plugin Name:       Pretty Errors
 * Plugin URI:        https://ardit.dev
 * Description:       This is a description of the plugin.
 * Version:           1.0.0
 * Author:            Ardit Xhaferi
 * Author URI:        https://ardit.dev
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pretty-errors
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('PRETTY_ERRORS_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pretty-errors-activator.php
 */
function activate_pretty_errors()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-pretty-errors-activator.php';
    Pretty_Errors_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pretty-errors-deactivator.php
 */
function deactivate_pretty_errors()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-pretty-errors-deactivator.php';
    Pretty_Errors_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_pretty_errors');
register_deactivation_hook(__FILE__, 'deactivate_pretty_errors');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-pretty-errors.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_pretty_errors()
{

    ini_set("error_reporting", E_ALL);
    ini_set("display_errors", 'off');

    $plugin = new Pretty_Errors();
    $plugin->run();

}

run_pretty_errors();

function custom_default_wp_die_handler( $message, $title = '', $args = array() )
{
    require_once("error.php");
    die();
}


remove_filter('wp_die_handler', '_default_wp_die_handler');
add_filter('wp_die_handler', 'custom_default_wp_die_handler');
