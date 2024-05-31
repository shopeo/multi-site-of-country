<?php
/**
 * Plugin Name: Multi Site Of Country
 * Plugin URI: https://wordpress.org/plugins/multi-site-of-country
 * Description: Multi Site Of Country
 * Author: SHOPEO
 * Version: 0.0.1
 * Author URI: https://shopeo.cn
 * License: GPL3+
 * Text Domain: multi-site-of-country
 * Domain Path: /languages
 * Requires at least: 5.9
 * Requires PHP: 5.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once 'vendor/autoload.php';
$countries = require __DIR__ . '/vendor/umpirsky/country-list/data/en/country.php';

if ( ! defined( 'MULTI_SITE_OF_COUNTRY_FILE' ) ) {
	define( 'MULTI_SITE_OF_COUNTRY_FILE', __FILE__ );
}

if ( ! function_exists( 'multi_site_of_country_activation' ) ) {
	/**
	 * Plugin activation
	 */
	function multi_site_of_country_activation() {
	}
}

register_activation_hook( MULTI_SITE_OF_COUNTRY_FILE, 'multi_site_of_country_activation' );

if ( ! function_exists( 'multi_site_of_country_deactivation' ) ) {
	/**
	 * Plugin deactivation
	 */
	function multi_site_of_country_deactivation() {
	}
}

register_deactivation_hook( MULTI_SITE_OF_COUNTRY_FILE, 'multi_site_of_country_deactivation' );

if ( ! function_exists( 'multi_site_of_country_init' ) ) {
	/**
	 * Plugin init
	 */
	function multi_site_of_country_init() {

		// load text domain.
		load_plugin_textdomain( 'multi-site-of-country', false, dirname( plugin_basename( MULTI_SITE_OF_COUNTRY_FILE ) ) . '/languages' );
	}
}

add_action( 'init', 'multi_site_of_country_init' );

add_action(
	'admin_enqueue_scripts',
	function () {
		$plugin_version = get_plugin_data( MULTI_SITE_OF_COUNTRY_FILE )['Version'];
		// enqueue style.

		// enqueue script.
		wp_enqueue_script( 'multi-site-of-country-admin-script', plugins_url( '/assets/js/admin.js', MULTI_SITE_OF_COUNTRY_FILE ), array( 'jquery' ), $plugin_version );
		wp_localize_script(
			'multi-site-of-country-admin-script',
			'multi_site_of_country',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			)
		);
	}
);

add_action(
	'wp_enqueue_scripts',
	function () {
		$plugin_version = get_plugin_data( MULTI_SITE_OF_COUNTRY_FILE )['Version'];
		// enqueue style.
		wp_enqueue_style( 'multi-site-of-country-style', plugins_url( '/assets/css/style.css', MULTI_SITE_OF_COUNTRY_FILE ), array(), $plugin_version );
		wp_style_add_data( 'multi-site-of-country-style', 'rtl', 'replace' );

		// enqueue script.
		wp_enqueue_script( 'multi-site-of-country-script', plugins_url( '/assets/js/app.js', MULTI_SITE_OF_COUNTRY_FILE ), array( 'jquery' ), $plugin_version );
		wp_localize_script( 'multi-site-of-country-script', 'multi_site_of_country', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	}
);

if ( ! function_exists( 'multi_site_of_country_register_blocks' ) ) {
	/**
	 * Register blocks
	 */
	function multi_site_of_country_register_blocks() {
		$blocks = array(
			'site_switch_of_country' => 'site_switch_of_country_dynamic_block_test',
		);
		foreach ( $blocks as $dir => $render_callback ) {
			$args = array();
			if ( ! empty( $render_callback ) ) {
				$args['render_callback'] = $render_callback;
			}
			register_block_type( __DIR__ . '/blocks/dist/' . $dir, $args );
		}
	}
}

add_action( 'init', 'multi_site_of_country_register_blocks' );

if ( ! function_exists( 'site_switch_of_country_dynamic_block_test' ) ) {
	/**
	 * Site switch of country dynamic block test
	 *
	 * @param array $attributes Attributes.
	 */
	function site_switch_of_country_dynamic_block_test( $attributes ) {
	}
}

if ( ! function_exists( 'is_multisite_enabled' ) ) {
	/**
	 * Check if multisite is enabled
	 *
	 * @return bool
	 */
	function is_multisite_enabled() {
		if ( defined( 'MULTISITE' ) && MULTISITE ) {
			return true;
		} else {
			return false;
		}
	}
}

if ( ! function_exists( 'add_country_field_to_site_creation_form' ) ) {
	/**
	 *  Add country field to site creation form
	 */
	function add_country_field_to_site_creation_form() {
		global $countries;
		?>
		<table class="form-table">
			<tr class="form-field form-required">
				<th scope="row"><label
						for="country"><?php esc_html_e( 'Country', 'multi-site-of-country' ); ?> <span
							class="required">*</span></label></th>
				<td>
					<select name="country" id="country" required>
						<option value=""><?php esc_html_e( 'Select Country', 'multi-site-of-country' ); ?></option>
						<?php
						foreach ( $countries as $code => $name ) {
							echo '<option value="' . esc_attr( $code ) . '">' . esc_html( $name ) . '</option>';
						}
						?>
					</select>
				</td>
			</tr>
		</table>
		<?php
	}
}

add_action( 'network_site_new_form', 'add_country_field_to_site_creation_form' );

if ( ! function_exists( 'multi_site_validate_site_data' ) ) {
	function multi_site_validate_site_data( $errors, $data, $old_site ) {
		error_log( print_r( $data, true ) );
		error_log( print_r( $old_site, true ) );
		if ( $_POST['country'] && $_POST['country'] != '' ) {
			$country_code = sanitize_text_field( $_POST['country'] );
			$sites        = get_sites( array( 'number' => 0 ) );
			foreach ( $sites as $site ) {
				if ( get_blog_option( $site->blog_id, 'country' ) == $country_code ) {
					$errors->add( 'site_country_already', __( 'This country is already assigned to another site. Please choose a different country.', 'multi-site-of-country' ) );
				}
			}
		} else {
			$errors->add( 'site_empty_country', __( 'Country must not be empty.', 'multi-site-of-country' ) );
		}
	}
}

add_action( 'wp_validate_site_data', 'multi_site_validate_site_data', 10, 3 );

if ( ! function_exists( 'save_site_country_on_creation' ) ) {
	function save_site_country_on_creation( $new_site ) {
		if ( isset( $_POST['country'] ) ) {
			update_blog_option( $new_site->blog_id, 'country', $_POST['country'] );
		}
	}
}
add_action( 'wp_initialize_site', 'save_site_country_on_creation' );
