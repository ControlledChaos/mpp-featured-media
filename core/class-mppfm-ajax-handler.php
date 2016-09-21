<?php

// exit if file access directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MPPFM_Ajax_Handler {

	public function __construct() {
		add_action( 'wp_ajax_mppfm_process_req', array( $this, 'process_req' ) );
	}

	public function process_req() {

		if ( ! wp_verify_nonce( $_POST['_nonce'], 'mppfm-process-req' ) ) {
			wp_send_json_error( __( 'Unable to process', 'mpp-featured-media' ) );
		}

		$media_id = absint( $_POST['media_id'] );

		if ( ! mpp_is_valid_media( $media_id ) || ! mppfm_user_can_mark_featured( $media_id ) ) {
			wp_send_json_error( __( 'Unable to process', 'mpp-featured-media' ) );
		}

		// removing media as featured media
		if ( get_post_meta( $media_id, '_mppfm_featured', true ) ) {

			if ( delete_post_meta( $media_id, '_mppfm_featured' ) ) {
				wp_send_json_success( array(
					'label'    => __( 'Mark as Featured', 'mpp-featured-media' ),
					'media_id' => $media_id
				) );
			} else {
				wp_send_json_error( __( 'Something went wrong', 'mpp-featured-media' ) );
			};

		} else {
			// will set media as featured media
			if ( ! add_post_meta( $media_id, '_mppfm_featured', 1, 1 ) ) {
				wp_send_json_error( __( 'Something went wrong', 'mpp-featured-media' ) );
			};

			wp_send_json_success( array(
				'label'    => __( 'Remove as Featured', 'mpp-featured-media' ),
				'media_id' => $media_id
			) );
		}

		exit;

	}

}
new MPPFM_Ajax_Handler();