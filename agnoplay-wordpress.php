<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.agnoplay.com
 * @since             1.0.0
 * @package           Agnoplay_Wordpress
 *
 * @wordpress-plugin
 * Plugin Name:       Agnoplay
 * Plugin URI:        agnoplay.com
 * Description:       Play your video, livestream or podcast seamlessly with Agnoplay
 * Version:           1.0.2
 * Author:            Agnoplay
 * Author URI:        https://www.agnoplay.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       agnoplay-wordpress
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'AGNOPLAY_WORDPRESS_VERSION', '1.0.2' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-agnoplay-wordpress-activator.php
 */
function activate_agnoplay_wordpress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-agnoplay-wordpress-activator.php';	
	Agnoplay_Wordpress_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-agnoplay-wordpress-deactivator.php
 */
function deactivate_agnoplay_wordpress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-agnoplay-wordpress-deactivator.php';
	Agnoplay_Wordpress_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_agnoplay_wordpress' );
register_deactivation_hook( __FILE__, 'deactivate_agnoplay_wordpress' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-agnoplay-wordpress.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_agnoplay_wordpress() {

	$plugin = new Agnoplay_Wordpress();
	$plugin->run();

}
run_agnoplay_wordpress();

function agno_load_my_own_textdomain( $mofile, $domain ) {
	if ( 'agnoplay-wordpress' === $domain && false !== strpos( $mofile, WP_LANG_DIR . '/plugins/' ) ) {
		$locale = apply_filters( 'plugin_locale', determine_locale(), $domain );
		$mofile = WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ) . '/languages/' . $domain . '-' . $locale . '.mo';
	}
	return $mofile;
}
add_filter( 'load_textdomain_mofile', 'agno_load_my_own_textdomain', 10, 2 );

/**
 * Settings page
 */
// Add menu item
function agno_add_admin_menu(  ) {
	add_options_page(
		__( 'Agnoplay settings', 'agnoplay-wordpress' ),
		__( 'Agnoplay settings', 'agnoplay-wordpress' ),
		'manage_options',
		'agnoplay_wordpress',
		'agno_options_page'
	);
}
add_action( 'admin_menu', 'agno_add_admin_menu' );

// Register settings
function agno_settings_init(  ) {
	register_setting(
		'pluginPage',
		'agno_settings',
		array( 'sanitize_callback' => 'agno_validate_settings' )
	);

	// Register a settings section for each player type
	add_settings_section(
		'agno_video_section',
		'Video',
		'',
		'pluginPage'
	);

	add_settings_section(
		'agno_live_section',
		'Live',
		'',
		'pluginPage'
	);

	add_settings_section(
		'agno_audio_section',
		'Audio',
		'',
		'pluginPage'
	);

	add_settings_section(
		'agno_environment_section',
		'Environment',
		'',
		'pluginPage'
	);

	// Add settings fields for brand ID and license keys to each settings section
	add_settings_field(
		'aagno_video_brand',
		__( 'Brand ID', 'agnoplay-wordpress' ),
		'agno_video_brand_render',
		'pluginPage',
		'agno_video_section'
	);

	add_settings_field(
		'agno_video_license',
		__( 'License key', 'agnoplay-wordpress' ),
		'agno_video_license_render',
		'pluginPage',
		'agno_video_section'
	);

	add_settings_field(
		'agno_live_brand',
		__( 'Brand ID', 'agnoplay-wordpress' ),
		'agno_live_brand_render',
		'pluginPage',
		'agno_live_section'
	);

	add_settings_field(
		'agno_live_license',
		__( 'License key', 'agnoplay-wordpress' ),
		'agno_live_license_render',
		'pluginPage',
		'agno_live_section'
	);

	add_settings_field(
		'agno_brand',
		__( 'Brand ID', 'agnoplay-wordpress' ),
		'agno_audio_brand_render',
		'pluginPage',
		'agno_audio_section'
	);

	add_settings_field(
		'agno_audio_license',
		__( 'License key', 'agnoplay-wordpress' ),
		'agno_audio_license_render',
		'pluginPage',
		'agno_audio_section'
	);

	add_settings_field(
		'agno_environment',
		__( 'Environment', 'agnoplay-wordpress' ),
		'agno_environment_render',
		'pluginPage',
		'agno_environment_section'
	);
}
add_action( 'admin_init', 'agno_settings_init' );

function agno_get_option($key) {
	$options = get_option( 'agno_settings' );
	if ($options) {
		if ($options[$key]) {
			return $options[$key];
		}
	}
	return '';
}

function agno_video_brand_render(  ) {
	echo '<input type="text" name="agno_settings[agno_video_brand]" value="' . esc_attr(agno_get_option('agno_video_brand')) . '">';
}

function agno_video_license_render(  ) {
	echo '<input type="text" name="agno_settings[agno_video_license]" value="' . esc_attr(agno_get_option('agno_video_license')) . '">';
}

function agno_live_brand_render(  ) {
	echo '<input type="text" name="agno_settings[agno_live_brand]" value="' . esc_attr(agno_get_option('agno_live_brand')) . '">';
}

function agno_live_license_render(  ) {
	echo '<input type="text" name="agno_settings[agno_live_license]" value="' . esc_attr(agno_get_option('agno_live_license')) . '">';
}

function agno_audio_brand_render(  ) {
	echo '<input type="text" name="agno_settings[agno_audio_brand]" value="' . esc_attr(agno_get_option('agno_audio_brand')) . '">';
}

function agno_audio_license_render(  ) {
	echo '<input type="text" name="agno_settings[agno_audio_license]" value="' . esc_attr(agno_get_option('agno_audio_license')) . '">';
}

function agno_environment_render( ) {
	$options = get_option( 'agno_settings' );
	?>
        <select name="agno_settings[agno_environment]">
          <option value="production" <?php selected($options['agno_environment'], "production"); ?>>Production</option>
          <option value="acceptance" <?php selected($options['agno_environment'], "acceptance"); ?>>Acceptance</option>
        </select>
   <?php
}

// Render options page
function agno_options_page(  ) {
	?>
	<form action='options.php' method='post'>
		<h2><?php _e( 'Agnoplay settings', 'agnoplay-wordpress' ); ?></h2>
		<p><?php _e( 'Enter the brand ID of your players, including the equivalent license keys. Please contact Agnoplay for the correct values.', 'agnoplay-wordpress' ); ?></p>
		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>
	</form>
	<?php
}

function agno_validate_license_key( $brand, $license, $environment ) {
	switch( $environment ) {
		case 'acceptance':
			$url = 'https://acc-api.agnoplay.com/acc/license-check';
		break;
		case 'production':
		default:
			$url = 'https://api.agnoplay.com/prod/license-check';
			break;
	}

	$postBody = array(
		'brand' => $brand,
		'licenseKey' => $license
	);
	$postBody = wp_json_encode($postBody);
	$args = array(
		'headers' => array(
			'Content-Type' => 'application/json',
			'x-agnoplay-platform' => 'web'
		),
		'body' => $postBody
	);
	$response = wp_remote_post($url, $args);

	if (is_wp_error($response)) {
		return "HTTP Error #:" . $response->get_error_message();
	} else {
		$body = wp_remote_retrieve_body($response);
		$result = json_decode($body);

		// If request was succesful, but the validation failed
		if ( !property_exists( $result, 'success') || !$result->success ) {
			return $result->message;
		// Validation succeeded
		} else {
			return true;
		}
	}
}

function agno_validate_settings($data) {
	$envChanged = false;
	if ($data['agno_environment'] != agno_get_option('agno_environment')) {
		$envChanged = true;
	}

	$hasError = false;
	if (!agno_validate_brand_license_settings($data, 'Video', 'agno_video_brand', 'agno_video_license')) {
		$data['agno_video_brand'] = agno_get_option('agno_video_brand');
		$data['agno_video_license'] = agno_get_option('agno_video_license');
		$data['agno_environment'] = agno_get_option('agno_environment');
		$hasError = true;
	}

	if (!agno_validate_brand_license_settings($data, 'Audio', 'agno_audio_brand', 'agno_audio_license')) {
		$data['agno_audio_brand'] = agno_get_option('agno_audio_brand');
		$data['agno_audio_license'] = agno_get_option('agno_audio_license');
		$data['agno_environment'] = agno_get_option('agno_environment');
		$hasError = true;
	}

	if (!agno_validate_brand_license_settings($data, 'Live', 'agno_live_brand', 'agno_live_license')) {
		$data['agno_live_brand'] = agno_get_option('agno_live_brand');
		$data['agno_live_license'] = agno_get_option('agno_live_license');
		$data['agno_environment'] = agno_get_option('agno_environment');
		$hasError = true;
	}

	if ($hasError && $envChanged) {
		agno_show_custom_error('Environment', 'Due to errors environment has not been updated');
	}
	
	return $data;
}

function agno_validate_brand_license_settings($data, $section, $brandOptionKey, $licenseOptionKey) {
	if (empty($data[$licenseOptionKey])) {
		// Don't validate if license is empty
		return true;
	}

	$result = agno_validate_license_key( $data[$brandOptionKey], $data[$licenseOptionKey], $data['agno_environment'] );
	if ($result !== true) {
		agno_show_custom_error($section, $result);
		return false;
	}

	return true;
}

function agno_show_custom_error( $type, $message ) {
	add_settings_error(
		'agno_settings',
		'agno_' . $type . '_error',
		$type . ': ' . $message,
		'error'
	);
}