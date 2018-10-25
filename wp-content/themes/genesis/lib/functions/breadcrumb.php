<?php
/**
 * Genesis Framework.
 *
 * WARNING: This file is part of the core Genesis Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Genesis\Breadcrumbs
 * @author  StudioPress
 * @license GPL-2.0+
 * @link    http://my.studiopress.com/themes/genesis/
 */

/**
 * Helper function for the Genesis Breadcrumb Class.
 *
 * @since 0.1.6
 *
 * @global Genesis_Breadcrumb $_genesis_breadcrumb
 *
 * @param array $args Breadcrumb arguments.
 */
function genesis_breadcrumb( $args = array() ) {

	global $_genesis_breadcrumb;

	if ( ! $_genesis_breadcrumb ) {
		$_genesis_breadcrumb = new Genesis_Breadcrumb;
	}

	$_genesis_breadcrumb->output( $args );

}

add_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
/**
 * Display Breadcrumbs above the Loop. Concedes priority to popular breadcrumb
 * plugins.
 *
 * @since 0.1.6
 *
 * @return void Return early if Genesis settings dictate that no breadcrumbs should show in current context.
 */
function genesis_do_breadcrumbs() {

	if (
		( is_single() && ! genesis_get_option( 'breadcrumb_single' ) ) ||
		( is_page() && ! genesis_get_option( 'breadcrumb_page' ) ) ||
		( is_404() && ! genesis_get_option( 'breadcrumb_404' ) ) ||
		( is_attachment() && ! genesis_get_option( 'breadcrumb_attachment' ) ) ||
		( ( 'posts' === get_option( 'show_on_front' ) && is_home() ) && ! genesis_get_option( 'breadcrumb_home' ) ) ||
		( ( 'page' === get_option( 'show_on_front' ) && is_front_page() ) && ! genesis_get_option( 'breadcrumb_front_page' ) ) ||
		( ( 'page' === get_option( 'show_on_front' ) && is_home() ) && ! genesis_get_option( 'breadcrumb_posts_page' ) ) ||
		( ( is_archive() || is_search() ) && ! genesis_get_option( 'breadcrumb_archive' ) )
	) {
		return;
	}

	$breadcrumb_markup_open = sprintf( '<div %s>', genesis_attr( 'breadcrumb' ) );

	if ( function_exists( 'bcn_display' ) ) {
		echo $breadcrumb_markup_open;
		bcn_display();
		echo '</div>';
	}
	elseif ( function_exists( 'breadcrumbs' ) ) {
		breadcrumbs();
	}
	elseif ( function_exists( 'crumbs' ) ) {
		crumbs();
	}
	elseif ( class_exists( 'WPSEO_Breadcrumbs' ) && genesis_get_option( 'breadcrumbs-enable', 'wpseo_internallinks' ) ) {
		yoast_breadcrumb( $breadcrumb_markup_open, '</div>' );
	}
	elseif( function_exists( 'yoast_breadcrumb' ) && ! class_exists( 'WPSEO_Breadcrumbs' ) ) {
		yoast_breadcrumb( $breadcrumb_markup_open, '</div>' );
	}
	else {
		genesis_breadcrumb();
	}

}
