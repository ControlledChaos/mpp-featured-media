<?php
/**
 * Plugin hooks file
 *
 * @package mpp-featured-media
 */

// exit if file access directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add ui to mark media as featured
 *
 * @return string
 */
function mppfm_add_ui() {

    $media = mpp_get_media();

    if ( ! mppfm_is_valid_screen() || ! mppfm_can_mark_media_featured( $media ) || ! mppfm_can_user_mark_media_featured( $media ) ) {
        return '';
    }

    mppfm_featured_button( $media->id );
}
add_action( 'mpp_media_meta', 'mppfm_add_ui' );

/**
 * Add
 *
 * @return string
 */
function mppfm_add_lightbox_ui() {

	$media = mpp_get_media();
	$screens = mpp_get_option( 'mppfm_button_ui_places', array() );

	if ( ! array_key_exists( 'light_box', $screens ) || ! mppfm_can_mark_media_featured( $media ) || ! mppfm_can_user_mark_media_featured( $media ) ) {
		return '';
	}

	mppfm_featured_button( $media->id );
}
add_action( 'mpp_lightbox_media_meta', 'mppfm_add_lightbox_ui' );

/**
 * Show featured media in user profile
 *
 * @return string
 */
function mppfm_show_user_header_featured_media() {

    if ( ! mpp_get_option( 'mppfm_media_in_header' ) ) {
        return '';
    }

	mppfm_featured_media( array(
		'component'    => 'members',
		'component_id' => bp_displayed_user_id(),
		'per_page'     => mppfm_get_header_media_limit(),
	) );
}
add_action( 'bp_profile_header_meta', 'mppfm_show_user_header_featured_media' );

/**
 * Show featured media in user profile
 *
 * @return string
 */
function mppfm_show_group_header_featured_media() {

    if ( ! mpp_get_option( 'mppfm_media_in_header' ) ) {
        return '';
    }

	mppfm_featured_media( array(
		'component'    => 'groups',
		'component_id' => bp_get_current_group_id(),
		'per_page'     => mppfm_get_header_media_limit(),
	) );
}
add_action( 'bp_group_header_meta', 'mppfm_show_group_header_featured_media' );