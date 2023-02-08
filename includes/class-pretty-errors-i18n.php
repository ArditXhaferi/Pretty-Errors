<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://ardit.dev
 * @since      1.0.0
 *
 * @package    Pretty_Errors
 * @subpackage Pretty_Errors/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Pretty_Errors
 * @subpackage Pretty_Errors/includes
 * @author     Ardit Xhaferi <arditxhaferi2@gmail.com>
 */
class Pretty_Errors_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'pretty-errors',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
