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

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
	require_once 'vendor/autoload.php';
}

if (!defined('MULTI_SITE_OF_COUNTRY_FILE')) {
	define('MULTI_SITE_OF_COUNTRY_FILE', __FILE__);
}

if (!function_exists('multi_site_of_country_activation')) {
	function multi_site_of_country_activation()
	{

	}
}

register_activation_hook(MULTI_SITE_OF_COUNTRY_FILE, 'multi_site_of_country_activation');

if (!function_exists('multi_site_of_country_deactivation')) {
	function multi_site_of_country_deactivation()
	{

	}
}

register_deactivation_hook(MULTI_SITE_OF_COUNTRY_FILE, 'multi_site_of_country_deactivation');

if (!function_exists('multi_site_of_country_init')) {
	function multi_site_of_country_init()
	{

		//load text domain
		load_plugin_textdomain('multi-site-of-country', false, dirname(plugin_basename(MULTI_SITE_OF_COUNTRY_FILE)) . '/languages');
	}
}

add_action('init', 'multi_site_of_country_init');

add_action('admin_enqueue_scripts', function () {
	$plugin_version = get_plugin_data(MULTI_SITE_OF_COUNTRY_FILE)['Version'];
	//style

	//script
	wp_enqueue_script('multi-site-of-country-admin-script', plugins_url('/assets/js/admin.js', MULTI_SITE_OF_COUNTRY_FILE), array('jquery'), $plugin_version);
	wp_localize_script('multi-site-of-country-admin-script', 'multi_site_of_country', array(
		'ajax_url' => admin_url('admin-ajax.php')
	));
});

add_action('wp_enqueue_scripts', function () {
	$plugin_version = get_plugin_data(MULTI_SITE_OF_COUNTRY_FILE)['Version'];
	//style
	wp_enqueue_style('multi-site-of-country-style', plugins_url('/assets/css/style.css', MULTI_SITE_OF_COUNTRY_FILE), array(), $plugin_version);
	wp_style_add_data('multi-site-of-country-style', 'rtl', 'replace');

	//script
	wp_enqueue_script('multi-site-of-country-script', plugins_url('/assets/js/app.js', MULTI_SITE_OF_COUNTRY_FILE), array('jquery'), $plugin_version);
	wp_localize_script('multi-site-of-country-script', 'multi_site_of_country', array(
		'ajax_url' => admin_url('admin-ajax.php')
	));
});

if (!function_exists('multi_site_of_country_register_blocks')) {
	function multi_site_of_country_register_blocks()
	{
		$blocks = array(
			'site_switch_of_country' => 'site_switch_of_country_dynamic_block_test',
		);
		foreach ($blocks as $dir => $render_callback) {
			$args = array();
			if (!empty($render_callback)) {
				$args['render_callback'] = $render_callback;
			}
			register_block_type(__DIR__ . '/blocks/dist/' . $dir, $args);
		}
	}
}

add_action('init', 'multi_site_of_country_register_blocks');

if (!function_exists('site_switch_of_country_dynamic_block_test')) {
	function site_switch_of_country_dynamic_block_test($attributes)
	{

	}
}
