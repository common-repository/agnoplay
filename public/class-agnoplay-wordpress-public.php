<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.agnoplay.com
 * @since      1.0.0
 *
 * @package    Agnoplay_Wordpress
 * @subpackage Agnoplay_Wordpress/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Agnoplay_Wordpress
 * @subpackage Agnoplay_Wordpress/public
 * @author     Agnoplay <info@agnoplay.com>
 */
class Agnoplay_Wordpress_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/agnoplay-wordpress-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$options = get_option( 'agno_settings' );
		switch( $options['agno_environment'] ) {
			case 'acceptance':
				$agnoplay_src = 'https://player-acc.agnoplay.com/static/agnoplay/js/agnoplay.js';
			break;
			case 'production':
			default:
			$agnoplay_src = 'https://player.agnoplay.com/static/agnoplay/js/agnoplay.js';
				break;
		}

		wp_enqueue_script( 'agnoplay', $agnoplay_src, null, $this->version );
		wp_script_add_data( 'agnoplay', 'crossorigin', 'anonymous' );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/agnoplay-wordpress-public.js', array( 'jquery', 'agnoplay' ), $this->version, false );
	}

	public function add_shortcode() {
		add_shortcode( 'agnoplay', [$this, 'agnoplayer_shortcode'] );
	}

	public function agnoplayer_shortcode( $atts ) {
		$options = get_option( 'agno_settings' );
		$input_type = $atts['inputtype'];
		$blockId = $atts['blockid'];
		$title = $atts['title'];
		$variant = $atts['variant'];
		$id = $atts['id'];
		$src = $atts['src'];
		$thumbnail = $atts['thumbnail'];

		$brand = $options['agno_' . $variant . '_brand'];
		$licenseKey = $options['agno_' . $variant . '_license'];

		$title_config = '';
		if ( $variant !== 'live' && !empty($title) ) {
			$title_config = 'show_title: "true", poster_title: "' . $title . '",';
		}

		$input_config = '';
		if ( $input_type === 'custom' ) {
			$input_config = 'stream_source: "custom", custom_source: { source: "' . $src . '", title: "' . $title . '"';
			if ( $thumbnail && $thumbnail !== 'null' ) $input_config .= ',  thumbnail: "' . $thumbnail . '"';
			$input_config .= '},';
		} else if ( $variant !== 'live' ) {
			$input_config = 'videoId: "' . $id . '"';
			 if ( $thumbnail && $thumbnail !== 'null' ) $input_config .= ',  poster: "' . $thumbnail . '"';
		}

		// Check if block configuration is valid
		if (
			(
				( $input_type !== 'custom' && ( $variant === 'live' || !empty( $id ) ) )
				|| ( $input_type === 'custom' && !empty( $src ) && ( !empty( $thumbnail ) && $thumbnail !== 'null' ) )
			)
			&& !empty( $brand )
		) {
			return
				'<div id="agnoplayer-' . $blockId . '"></div>
				<script>
					var element = document.getElementById("agnoplayer-' . $blockId . '"),
						config = {
						brand: "' . $brand . '",
						license_key: "' . $licenseKey . '",
						url: window.location.href,
						' . $title_config . '
						' . $input_config . '
					};
					var player = window.AGNO.insertPlayer(config, element);
				</script>';
		}

		return '<p><strong>' . __( 'This Agnoplay block is configured incorrectly. Ensure that all required fields and an active license are set.', 'agnoplay-wordpress' ) . '</p></strong>';
	}
}
