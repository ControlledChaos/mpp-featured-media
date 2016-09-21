<?php

// exit if file access directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MPPFM_Admin_Settings {

	public function __construct() {
		//setup hooks
		add_action( 'mpp_admin_register_settings', array( $this, 'register_settings' ) );
	}

	/**
	 *
	 * @param MPPFM_Admin_Settings $page
	 */

	public function register_settings( $page ) {

		$panel = $page->get_panel( 'addons' );

		$fields = array(
			array(
				'name'    => 'mppfm-components',
				'label'   => __( 'Enabled for Components', 'mpp-set-featured' ),
				'type'    => 'multicheck',
				'options' => mppfm_get_active_components()
			),
			array(
				'name'    => 'mppfm-types',
				'label'   => __( 'Enabled for Types', 'mpp-set-featured' ),
				'type'    => 'multicheck',
				'options' => mppfm_get_active_types()
			),
			array(
				'name'    => 'mppfm-screens',
				'label'   => __( 'Appearance', 'mpp-set-featured' ),
				'type'    => 'multicheck',
				'options' => array(
					'single_media'   => __( 'Single Media Page', 'mpp-set-featured' ),
					'light_box'      => __( 'LightBox', 'mpp-set-featured' ),
					'single_gallery' => __( 'Single Gallery', 'mpp-set-featured' )
				)
			)

		);

		$panel->add_section( 'mppfm-settings', __( 'MediaPress Set Featured Setting', 'mpp-set-featured' ) )->add_fields( $fields );

	}

}

new MPPFM_Admin_Settings();