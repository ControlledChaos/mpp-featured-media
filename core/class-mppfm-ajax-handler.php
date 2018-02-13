<?php
/**
 * Plugin ajax request handler class
 *
 * @package mpp-featured-media
 */

// Exit if file access directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class MPPFM_Ajax_Handler
 */
class MPPFM_Ajax_Handler {

	/**
	 * The constructor.
	 */
	public function __construct() {
		add_action( 'wp_ajax_mppfm_mark_featured_unfeatured', array( $this, 'process' ) );
	}

	/**
	 * Process request to mark media as featured or remove featured
	 */
	public function process() {

		check_ajax_referer( 'mppfm-mark-featured-unfeatured', '_nonce' );

		$media_id = absint( $_POST['media_id'] );

		if ( ! mpp_is_valid_media( $media_id ) || ! mppfm_can_user_mark_media_featured( $media_id ) ) {
			wp_send_json_error( __( 'Unable to process', 'mpp-featured-media' ) );
		}

		$label = '';
		// Removing media as featured media
		if ( mppfm_is_featured( $media_id ) ) {
			delete_post_meta( $media_id, '_mppfm_featured' );
			$label = __( 'Mark as Featured', 'mpp-featured-media' );
		} else {
			add_post_meta( $media_id, '_mppfm_featured', 1, 1 );
			$label = __( 'Remove as Featured', 'mpp-featured-media' );
		}

		wp_send_json_success( array(
			'label'    => $label,
			'media_id' => $media_id
		) );

		exit;
	}
}
new MPPFM_Ajax_Handler();