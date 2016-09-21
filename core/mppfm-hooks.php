<?php

// exit if file access directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function mppfm_add_interface( $media_id = null ) {

	$media_id = ( is_null( $media_id ) ) ? mpp_get_current_media_id() : $media_id;

	if ( empty( $media_id ) || ! mppfm_media_can_mark_featured( $media_id ) || ! mppfm_user_can_mark_featured( $media_id ) ) {
		return '';
	}

	$label = ( mppfm_is_featured( $media_id ) ) ? 'Remove as Featured' : 'Mark as Featured';

	?>

	<button class="mppfm-interface-btn" data-media-id="<?php echo $media_id ?>">
		<?php _e( $label, 'mpp-featured-media' ); ?>
	</button>

	<?php

}
add_action( 'mpp_before_bulk_edit_media_item_thumbnail', 'mppfm_add_interface' );

function mppfm_media_add_interface() {

	$media_id = mpp_get_current_media_id();
	
	if ( empty( $media_id ) || ! mppfm_is_valid_screen( $media_id ) ) {
		return '';
	}

	mppfm_add_interface( $media_id );
}
add_action( 'mpp_media_meta', 'mppfm_media_add_interface' );    

function mppfm_light_box_add_interface() {

	$media_id = mpp_get_current_media_id();

	$screens = mpp_get_option( 'mppfm-screens', array() );

	if ( empty( $media_id ) || ! in_array( 'light_box', $screens ) ) {
		return '';
	}

	mppfm_add_interface( $media_id );
}
add_action( 'mpp_lightbox_media_meta', 'mppfm_light_box_add_interface' );