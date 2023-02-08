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

    $plugin = new Pretty_Errors();
    $plugin->run();

}

run_pretty_errors();

function custom_default_wp_die_handler( $message, $title = '', $args = array() ) {
    global $wp_version;
        $error_array = error_get_last();
    clearstatcache();
        $file = file_get_contents($error_array['file']);
        $file_content = htmlspecialchars($file)
    ?>

    <html class="bg-gray-300 w-full py-12">
        <head>
            <script src="https://cdn.tailwindcss.com"></script>
        </head>
        <body class="w-full flex justify-center items-start">
        </body>
        <header>

        </header>
    </html>
    <script>
        //*Script*
        let phpVersion = "<?= phpversion() ?>";
        let wpVersion = "<?= $wp_version ?>";
        let file_content = `<?= $file_content ?>`;
        let content = document.body.textContent;
        let dom = "";
        let wpImageUrl = "<img class='mr-2' width='16px' src='https://static-00.iconduck.com/assets.00/wordpress-icon-512x512-38lz8224.png' />";
        content = content.split("//*Script*")[0]
        errorType = content.split(":")[0]
        errorTitle = content.split(":")[1]
        first_line = content.split(':').slice(1).join(':').split("Stack trace:")[0].trim()
        first_line = first_line.split("in")
        const regex = /^#\d+.*$/gm;
        const lines = content.match(regex);
        let line = "<div class='flex flex-col'>";
        line += "<div class='px-6 py-4 border-b border-gray-200 bg-blue-400 text-white'>" + first_line[1] + "): <br>" + "<b>" + first_line[0] + "</b></div>";
        lines.forEach((singleline, index) => {
            let errorline = singleline.split("):")[0];
            let errorlinetype = singleline.split("):")[1];
            line += "<div class='px-6 py-4 border-b border-gray-200 hover:bg-blue-400 hover:text-white'>" + errorline.substring(3) + "): <br>" + "<b>" + errorlinetype + "</b></div>";
        })
        line += "</div>"
        header = "<div class='bg-white p-8 w-[90%] shadow-lg mb-10'>"
        type = `<span class="py-1 text-lg px-4 items-center flex gap-3 rounded-sm bg-gray-100 w-fit capitalize">`+ errorType +`</span>`
        php = `<span class='text-sm text-gray-500 mr-4'>PHP `+phpVersion+`</span>`
        wp = `<span class='text-sm text-gray-500 flex'>`+ wpImageUrl +wpVersion+`</span>`
        typeRow = `<div class="w-full flex justify-between items-center">` + type + "<div class='flex'>" + php + wp + "</div>" + `</div>`
        title = "<h1 class='font-semibold text-xl leading-slug mt-6 mb-4'>" + errorTitle + "</h1>"
        dom += header + typeRow + title + "</div>";

        body = "<div class='bg-white flex w-[90%] shadow-lg'>"+line + "<div class='w-full overflow-scroll'><pre class='py-8 px-4 w-full'>" + file_content  + "</pre></div></div>"
        document.body.innerHTML = "<div class='flex items-center w-full flex-col'>" + dom + body + "</div>";
    </script>
    <?php
    die();
}


remove_filter('wp_die_handler', '_default_wp_die_handler');
add_filter('wp_die_handler', 'custom_default_wp_die_handler');
