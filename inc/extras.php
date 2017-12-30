<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package siteorigin-corp
 * @license GPL 2.0
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function siteorigin_corp_body_classes( $classes ) {

	// Blog settings.
	if ( siteorigin_setting( 'blog_archive_content' ) == 'full' ) {
		$classes[] = 'blog-full';
	}

	// Header margin.
	if ( is_home() && siteorigin_corp_has_featured_posts() ) {
		$classes[] = 'no-header-margin';
	}	

	// Mobile compatibility classes.
	$classes[] = 'css3-animations';
	$classes[] = 'no-js';
	$classes[] = 'no-touch';

	// Non-singlar pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}	

	// Page settings.
	$page_settings = siteorigin_page_setting();

	if ( ! empty( $page_settings ) ) {
		if ( ! empty( $page_settings['layout'] ) ) $classes[] = 'page-layout-' . $page_settings['layout'];
		if ( ! empty( $page_settings['overlap'] ) && ( $page_settings['overlap'] != 'disabled' ) ) $classes[] = 'overlap-' . $page_settings['overlap'];
		if ( empty( $page_settings['header_margin'] ) ) $classes[] = 'no-header-margin';
		if ( empty( $page_settings['footer_margin'] ) ) $classes[] = 'no-footer-margin';
	}

	// Responsive layout.
	$classes[] = 'responsive';

	// Sidebar.
	if ( is_active_sidebar( 'sidebar-main' ) ) {
		 $classes[] = 'sidebar';
	}

	if ( siteorigin_setting( 'sidebar_position' ) == 'left' ) {
		 $classes[] = 'sidebar-left';
	}		

	// WooCommerce top bar.
	if ( class_exists( 'Woocommerce' ) && ! is_store_notice_showing() ) {
		$classes[] = 'no-topbar';
	} elseif ( ! class_exists( 'Woocommerce' ) ) {
		$classes[] = 'no-topbar';
	}

	// WooCommerce columns.
	if ( function_exists( 'is_woocommerce' ) ) {
		$classes[] = 'columns-' . siteorigin_setting( 'woocommerce_archive_columns', 3 );
	}
	
	// WooCommerce sidebar.
	if ( is_active_sidebar( 'sidebar-shop' ) && ( function_exists( 'is_woocommerce' ) && ! is_product() ) ) {
		 $classes[] = 'woocommerce-sidebar';
	}

	if ( siteorigin_setting( 'woocommerce_sidebar_position' ) == 'right' ) {
		 $classes[] = 'woocommerce-sidebar-right';
	}				

	return $classes;
}
add_filter( 'body_class', 'siteorigin_corp_body_classes' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function siteorigin_corp_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'siteorigin_corp_pingback_header' );

if ( ! function_exists( 'siteorigin_corp_post_class_filter' ) ) :
/**
* Filter post classes as required.
* @link https://codex.wordpress.org/Function_Reference/post_class.
*/
function siteorigin_corp_post_class_filter( $classes ) {
	$classes[] = 'post';

	// Resolves structured data issue in core. See https://core.trac.wordpress.org/ticket/28482.
	if ( is_page() ) {
		$class_key = array_search( 'hentry', $classes );

		if ( $class_key !== false) {
			unset( $classes[ $class_key ] );
		}
	}

	$classes = array_unique( $classes );
	return $classes;
}
endif;
add_filter( 'post_class', 'siteorigin_corp_post_class_filter' );
