<?php
/**
 * Plugin admin settings helper class
 *
 * @package mpp-featured-media
 */

// Exit if file access directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class MPPFM_Admin_Settings_Helper
 */
class MPPFM_Admin_Settings_Helper {

	/**
	 * The constructor.
	 */
	public function __construct() {
		add_action( 'mpp_admin_register_settings', array( $this, 'register_settings' ) );
	}

	/**
	 * Register settings
	 *
	 * @param MPP_Admin_Settings_Page $page Page object.
	 */
	public function register_settings( $page ) {

		$panel = $page->get_panel( 'addons' );

		$fields = array(
			array(
				'name'    => 'mppfm_enabled_components',
				'label'   => __( 'Enable for component', 'mpp-featured-media' ),
				'type'    => 'multicheck',
				'options' => mppfm_get_components()
			),
			array(
				'name'    => 'mppfm_enabled_types',
				'label'   => __( 'Enable for types', 'mpp-featured-media' ),
				'type'    => 'multicheck',
				'options' => mppfm_get_types()
			),
			array(
				'name'    => 'mppfm_button_ui_places',
				'label'   => __( 'Where to show mark featured button', 'mpp-featured-media' ),
				'type'    => 'multicheck',
				'options' => array(
					'single_media'   => __( 'Single Media Page', 'mpp-featured-media' ),
					'light_box'      => __( 'LightBox', 'mpp-featured-media' ),
					'single_gallery' => __( 'Single Gallery', 'mpp-featured-media' )
				)
			),
			array(
				'name'    => 'mppfm_media_in_header',
				'label'   => __( 'Show media in header', 'mpp-featured-media' ),
				'type'    => 'checkbox',
			),
			array(
				'name'    => 'mppfm_header_media_limit',
				'label'   => __( 'Limit', 'mpp-featured-media' ),
				'type'    => 'text',
			),
		);

		$panel->add_section( 'mppfm-settings', __( 'MediaPress Featured Media Setting', 'mpp-featured-media' ) )->add_fields( $fields );
	}
}

new MPPFM_Admin_Settings_Helper();
