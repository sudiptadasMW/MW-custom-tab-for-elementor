<?php
/**
 * Plugin Name:       MW Custom Tab for Elementor
 * Plugin URI:        https://example.com/mw-custom-tab
 * Description:       A custom Elementor widget with two advanced layout styles for fabrication/service sections.
 * Version:           1.0.0
 * Author:            Mediusware
 * Author URI:        https://example.com
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mw-custom-tab
 * Domain Path:       /languages
 */

namespace MW_Custom_Tab;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MW_CUSTOM_TAB_VERSION', '1.0.0' );
define( 'MW_CUSTOM_TAB_PATH', plugin_dir_path( __FILE__ ) );
define( 'MW_CUSTOM_TAB_URL', plugin_dir_url( __FILE__ ) );
define( 'MW_CUSTOM_TAB_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Main Plugin Class (Singleton)
 */
final class Plugin {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct() {
		add_action( 'plugins_loaded', [ $this, 'init' ] );
	}

	public function init() {
		// Check if Elementor is active
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'elementor_missing_notice' ] );
			return;
		}

		// Check minimum Elementor version
		$elementor_version_required = '3.0.0';
		if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'elementor_version_notice' ] );
			return;
		}

		// Load textdomain
		load_plugin_textdomain( 'mw-custom-tab', false, dirname( MW_CUSTOM_TAB_BASENAME ) . '/languages' );

		// Register widget
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );

		// Enqueue assets
		add_action( 'elementor/frontend/after_enqueue_styles',   [ $this, 'enqueue_styles' ] );
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_scripts' ] );
		add_action( 'elementor/editor/after_enqueue_styles',     [ $this, 'enqueue_styles' ] );
		add_action( 'elementor/preview/enqueue_styles',          [ $this, 'enqueue_styles' ] );
	}

	public function register_widgets( $widgets_manager ) {
		$widgets = [
			'Fabrication_Widget',
			// To add multiple widgets, create a new file in includes/widgets/new-widget-name.php
			// and add the class name here, e.g., 'New_Widget_Name'
		];

		foreach ( $widgets as $widget ) {
			// Convert class name to file name: Fabrication_Widget -> fabrication-widget.php
			$filename = str_replace( '_', '-', strtolower( $widget ) ) . '.php';
			$filepath = MW_CUSTOM_TAB_PATH . 'includes/widgets/' . $filename;

			if ( file_exists( $filepath ) ) {
				require_once $filepath;
				$class_name = '\\MW_Custom_Tab\\Widget\\' . $widget;
				$widgets_manager->register( new $class_name() );
			}
		}
	}

	public function enqueue_styles() {
		// Our CSS bundles the critical Swiper layout rules so the carousel
		// renders correctly on the frontend even if the theme does not load
		// Elementor's full asset bundle (which normally includes Swiper CSS).
		wp_enqueue_style(
			'mw-custom-tab',
			MW_CUSTOM_TAB_URL . 'assets/css/fabrication-widget.css',
			[],
			MW_CUSTOM_TAB_VERSION
		);
	}

	public function register_scripts() {
		// Use Elementor's bundled swiper handle if available, otherwise fall
		// back to the generic 'swiper' handle (registered by some themes/plugins).
		// The JS itself checks typeof Swiper before calling new Swiper().
		$swiper_handle = wp_script_is( 'swiper', 'registered' ) ? 'swiper' : 'jquery';

		wp_register_script(
			'mw-custom-tab',
			MW_CUSTOM_TAB_URL . 'assets/js/fabrication-widget.js',
			[ 'jquery', $swiper_handle ],
			MW_CUSTOM_TAB_VERSION,
			true
		);
	}

	public function elementor_missing_notice() {
		$message = sprintf(
			/* translators: %s: Plugin name */
			esc_html__( '"%s" requires Elementor to be installed and activated.', 'mw-custom-tab' ),
			'<strong>' . esc_html__( 'MW Custom Tab for Elementor', 'mw-custom-tab' ) . '</strong>'
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%s</p></div>', wp_kses_post( $message ) );
	}

	public function elementor_version_notice() {
		$message = sprintf(
			/* translators: %s: Plugin name */
			esc_html__( '"%s" requires Elementor version 3.0 or greater.', 'mw-custom-tab' ),
			'<strong>' . esc_html__( 'MW Custom Tab for Elementor', 'mw-custom-tab' ) . '</strong>'
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%s</p></div>', wp_kses_post( $message ) );
	}
}

Plugin::instance();
