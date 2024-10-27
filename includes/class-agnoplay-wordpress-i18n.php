<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.agnoplay.com
 * @since      1.0.0
 *
 * @package    Agnoplay_Wordpress
 * @subpackage Agnoplay_Wordpress/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Agnoplay_Wordpress
 * @subpackage Agnoplay_Wordpress/includes
 * @author     Agnoplay <info@agnoplay.com>
 */
class Agnoplay_Wordpress_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'agnoplay-wordpress',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
