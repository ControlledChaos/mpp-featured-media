<?php
/**
 * Main plugin file
 *
 * @package mpp-featured-media
 */

/**
 * Plugin Name: MediaPress Featured Media
 * Description: MediaPress add-on to mark media as featured and will display featured media in sidebar using widget.
 * Version: 1.0.0
 * Author: BuddyDev
 * Author URI: https://www.buddydev.com
 *
 */

/**
 * Contributor: @raviousprime :)
 */

// exit if file access directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class MPP_Featured_Media
 */
class MPP_Featured_Media {

	/**
	 * Class Instance
	 *
	 * @var MPP_Featured_Media
	 */
	private static $instance = null;

	/**
	 * Plugin directory absolute path
	 *
	 * @var string
	 */
	private $path;

	/**
	 * Plugin directory url to be accessed over web
	 *
	 * @var string
	 */
	private $url;

	/**
	 * The constructor.
	 */
	private function __construct() {
		$this->setup();
	}

	/**
	 * Get class instance
	 *
	 * @return MPP_Featured_Media
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * function will load necessary files of plugin and initialize the functionality
	 */
	private function setup() {

		$this->path = plugin_dir_path( __FILE__ );
		$this->url  = plugin_dir_url( __FILE__ );

		add_action( 'mpp_loaded', array( $this, 'load' ) );
		add_action( 'mpp_enqueue_scripts', array( $this, 'load_assets' ) );
		add_action( 'mpp_init', array( $this, 'load_text_domain' ) );
	}


	/**
	 * function will load the plugin files
	 */
	public function load() {

		if ( ! function_exists( 'buddypress' ) ) {
			return;
		}

		$files = array(
			'core/mppfm-functions.php',
			'core/mppfm-hooks.php',
			'core/class-mppfm-widget.php',
			'core/class-mppfm-ajax-handler.php',
		);

		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			$files[] = 'admin/class-mppfm-admin-settings-helper.php';
		}

		foreach ( $files as $file ) {
			require_once $this->path . $file;
		}
	}

	/**
	 *  function will load plugin stylesheet and script
	 */
	public function load_assets() {

		wp_register_style( 'mppfm-css', $this->url . 'assets/css/mppfm-style.css' );

		wp_register_script( 'mppfm-js', $this->url . 'assets/js/mppfm-script.js', array( 'jquery' ) );

		wp_localize_script( 'mppfm-js', 'MPP_FEATURED_MEDIA', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
		) );

		wp_enqueue_style( 'mppfm-css' );
		wp_enqueue_script( 'mppfm-js' );
	}

	/**
	 *  this function will load language file of the plugin
	 */
	public function load_text_domain() {
		load_plugin_textdomain( 'mpp-featured-media', false, $this->path . '/languages' );
	}
}

MPP_Featured_Media::get_instance();

