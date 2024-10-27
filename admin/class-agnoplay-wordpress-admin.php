<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.agnoplay.com
 * @since      1.0.0
 *
 * @package    Agnoplay_Wordpress
 * @subpackage Agnoplay_Wordpress/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Agnoplay_Wordpress
 * @subpackage Agnoplay_Wordpress/admin
 * @author     Agnoplay <info@agnoplay.com>
 */
class Agnoplay_Wordpress_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = filemtime( __FILE__ );

	}

	/**
	 * Register the custom gutenberg block assets
	 */
	function enqueue_block_editor_assets() {
		// Enqueue block configuration
		wp_enqueue_script(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . '/js/ap-register-block.js',
			['wp-blocks','wp-editor'],
			$this->version
		);

		wp_localize_script( $this->plugin_name, 'imagePath', array( esc_url( plugins_url( '/images/agnoplay.png', __FILE__ ) ) ) );

		// Enqueue admin styling
		wp_enqueue_style(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . '/css/ap-block-admin-styling.css',
			array(),
			$this->version
		);
	}

}
