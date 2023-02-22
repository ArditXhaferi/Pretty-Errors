<?php

/**
 * Fired during plugin activation
 *
 * @link       https://ardit.dev
 * @since      1.0.0
 *
 * @package    Pretty_Errors
 * @subpackage Pretty_Errors/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Pretty_Errors
 * @subpackage Pretty_Errors/includes
 * @author     Ardit Xhaferi <arditxhaferi2@gmail.com>
 */
class Pretty_Errors_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        self::create_db_error_file();
	}

    private static function create_db_error_file() {
        $content = '<?php require_once(ABSPATH . "wp-content/plugins/Pretty-Errors/error.php"); ?>';
        $file_path = WP_CONTENT_DIR . '/db-error.php';

        file_put_contents( $file_path, $content );
    }

}
