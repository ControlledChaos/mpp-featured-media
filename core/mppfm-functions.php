<?php
/**
 * Plugin core functions file
 *
 * @package mpp-featured-media
 */

// Exit if file access directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if media is featured or not
 *
 * @param int|MPP_Media $media Media object or media id.
 *
 * @return bool
 */
function mppfm_is_featured( $media ) {

	$media = mpp_get_media( $media );

	if ( get_post_meta( $media->id, '_mppfm_featured', true ) ) {
		return true;
	}

	return false;
}

/**
 * Check weather current user can mark media as featured or not
 *
 * @param int|MPP_Media $media Media object or media id.
 *
 * @return bool
 */
function mppfm_can_user_mark_media_featured( $media ) {

	$can_mark = false;

	if ( ! is_user_logged_in() ) {
		return false;
	}

	$media = mpp_get_media( $media );
	$user_id  = get_current_user_id();

	if ( $media->user_id == $user_id ) {
		$can_mark = true;
	} elseif ( 'groups' == $media->component && groups_is_user_admin( $user_id, $media->component_id ) ) {
		$can_mark = true;
	}

	return $can_mark;
}

/**
 * Checks if media can be featured
 *
 * @param int|MPP_Media $media Media id or Media object.
 *
 * @return bool
 */
function mppfm_can_mark_media_featured( $media ) {

	$media = mpp_get_media( $media );

	$enabled_component = mpp_get_option( 'mppfm_enabled_components', array() );
	$enabled_type      = mpp_get_option( 'mppfm_enabled_types' );

	if ( empty( $enabled_component ) || empty( $enabled_type ) ) {
		return false;
	}

	if ( ! in_array( $media->component, $enabled_component ) || ! in_array( $media->type, $enabled_type ) ) {
		return false;
	}

	return true;
}

/**
 * Show media of user types
 *
 * @return array
 */
function mppfm_show_media_of() {

	return array(
		'loggedin'  => __( 'Logged In', 'mpp-featured-media' ),
		'displayed' => __( 'Displayed', 'mpp-featured-media' ),
	);
}

/**
 * Get components
 *
 * @return array
 */
function mppfm_get_components() {

	$components = array();

	$active_components = mpp_get_active_components();

	if ( empty( $active_components ) ) {
		return $components;
	}

	foreach ( $active_components as $key => $component ) {

	    if ( 'sitewide' == $key ) {
	        continue;
        } elseif ( 'members' == $key ) {
		    $label = __( 'Users', 'mpp-featured-media' );
	    } elseif ( 'groups' == $key ) {
		    $label = __( 'Groups', 'mpp-featured-media' );
	    }

        $components[ $key ] = $label;
	}

	return $components;
}

/**
 * Get active types
 *
 * @return array
 */
function mppfm_get_types() {

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
 * Function checks if current screen is valid from where user can mark media as featured
 *
 * @return bool
 */
function mppfm_is_valid_screen() {

	$screens = mpp_get_option( 'mppfm_button_ui_places', array() );

	if ( mpp_is_single_media() && array_key_exists( 'single_media', $screens ) ) {
		return true;
	} elseif ( ! mpp_is_single_media() && mpp_is_single_gallery() && array_key_exists( 'single_gallery', $screens ) ) {
		return true;
	}

	return false;
}

/**
 * Render featured media button
 *
 * @param int $media_id Media id.
 */
function mppfm_featured_button( $media_id ) {
	echo mppfm_get_featured_button( $media_id );
}

/**
 * Get mark as featured button
 *
 * @param int $media_id Media id.
 *
 * @return string
 */
function mppfm_get_featured_button( $media_id ) {

	$label = __( 'Mark Featured', 'mpp-featured-media' );

	if ( mppfm_is_featured( $media_id ) ) {
		$label = __( 'Remove Featured', 'mpp-featured-media' );
	}

	return sprintf( '<div class="generic-button"><a href="#" class="mppfm-featured-btn" data-media-id="%s" data-nonce="%s">%s</a></div>', $media_id, wp_create_nonce( 'mppfm-mark-featured-unfeatured' ), $label );
}

/**
 * Get featured items
 *
 * @param array $args
 *
 * @return array
 */
function mppfm_get_featured_media( $args = array() ) {

	$default = array(
		'component'    => 'members',
		'component_id' => get_current_user_id(),
		'per_page'     => 5,
	);

	$args = wp_parse_args( $args, $default );
	$args['meta_key'] = '_mppfm_featured';

	$query = new MPP_Media_Query( $args );

	return $query->posts;
}

/**
 * Get header media limit
 *
 * @return mixed
 */
function mppfm_get_header_media_limit() {
	return mpp_get_option( 'mppfm_header_media_limit', 5 );
}

/**
 * Render featured media
 *
 * @return string
 */
function mppfm_featured_media( $args = array() ) {

	$media_items = mppfm_get_featured_media( $args );

	if ( empty( $media_items ) ) {
		return '';
	}

	?>

	<div class="mppfm-featured-media">
		<ul class="mppfm-featured-media-list">
		<?php foreach ( $media_items as $item ) : ?>
			<li>
				<a href="<?php mpp_media_permalink( $item->ID )?>">
					<img width="50" src="<?php mpp_media_src( 'thumbnail', $item->ID ); ?>" title="<?php echo mpp_get_media_title( $item->ID )?>" />
				</a>
			</li>
		<?php endforeach; ?>
		</ul>
	</div>

	<?php
}