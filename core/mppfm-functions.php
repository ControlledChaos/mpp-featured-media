<?php

// exit if file access directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @param $media_id
 *
 * function will return true if media is featured
 *
 * @return bool
 */
function mppfm_is_featured( $media_id ) {

	if ( empty( $media_id ) ) {
		return false;
	}

	if ( get_post_meta( $media_id, '_mppfm_featured', true ) ) {
		return true;
	}

	return false;
}

/**
 * @param $media_id
 *
 * function will check weather current user can mark media as featured or not
 *
 * @return bool|mixed|void
 */
function mppfm_user_can_mark_featured( $media_id ) {

	if ( empty( $media_id ) || ! is_user_logged_in() ) {
		return false;
	}

	$can_mark = false;
	$user_id  = get_current_user_id();

	if ( $user_id == mpp_get_media_creator_id( $media_id ) ) {
		$can_mark = true;
	}

	return $can_mark;
}

function mppfm_show_media_of() {

	return array(
		'loggedin'  => __( 'Logged In User', 'mpp-set-featured' ),
		'displayed' => __( 'Displayed User', 'mpp-set-featured' ),
	);
}

function mppfm_get_active_components() {

	$active_component = array();

	$components = mpp_get_active_components();

	if ( empty( $components ) ) {
		return $active_component;
	}

	foreach ( $components as $key => $component ) {
		$active_component[ $key ] = $component->label;
	}

	return $active_component;
}

/**
 * @return array of active media types
 */
function mppfm_get_active_types() {

	$active_types = array();

	$types = mpp_get_active_types();

	if ( empty( $types ) ) {
		return $active_types;
	}

	foreach ( $types as $key => $type ) {
		$active_types[ $key ] = $type->label;
	}

	return $active_types;
}

/**
 * @param $media_id
 *
 * Function checks if media can be featured
 *
 * @return bool
 */
function mppfm_media_can_mark_featured( $media_id ) {

	if ( empty( $media_id ) ) {
		return false;
	}

	$media = mpp_get_media( $media_id );

	$enabled_component = mpp_get_option( 'mppfm-components', array() );
	$enabled_type      = mpp_get_option( 'mppfm-types' );

	if ( empty( $enabled_component ) || empty( $enabled_type ) ) {
		return false;
	}

	if ( ! in_array( $media->component, $enabled_component ) || ! in_array( $media->type, $enabled_type ) ) {
		return false;
	}

	return true;
}

/**
 * @param $media_id
 *
 * Function checks if current screen is valid from where user can mark media as featured
 *
 * @return bool
 */
function mppfm_is_valid_screen( $media_id ) {

	if ( empty( $media_id ) ) {
		return false;
	}

	$screens = mpp_get_option( 'mppfm-screens', array() );

	if ( mpp_is_single_media() && array_key_exists( 'single_media', $screens ) ) {
		return true;
	} elseif ( ! mpp_is_single_media() && mpp_is_single_gallery() && array_key_exists( 'single_gallery', $screens ) ) {
		return true;
	}

	return false;
}