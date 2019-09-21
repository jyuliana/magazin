<?php
/**
 * magazin hooks
 *
 * @package magazin
 */

/**
 * General
 *
 * @see  magazin_header_widget_region()
 * @see  magazin_get_sidebar()
 */
add_action( 'magazin_before_content', 'magazin_header_widget_region', 10 );
add_action( 'magazin_sidebar', 'magazin_get_sidebar', 10 );

/**
 * Header
 *
 * @see  magazin_skip_links()
 * @see  magazin_secondary_navigation()
 * @see  magazin_site_branding()
 * @see  magazin_primary_navigation()
 */
add_action( 'magazin_header', 'magazin_header_container', 0 );
add_action( 'magazin_header', 'magazin_skip_links', 5 );
add_action( 'magazin_header', 'magazin_site_branding', 20 );
add_action( 'magazin_header', 'magazin_secondary_navigation', 30 );
add_action( 'magazin_header', 'magazin_header_container_close', 41 );
add_action( 'magazin_header', 'magazin_primary_navigation_wrapper', 42 );
add_action( 'magazin_header', 'magazin_primary_navigation', 50 );
add_action( 'magazin_header', 'magazin_primary_navigation_wrapper_close', 68 );

/**
 * Footer
 *
 * @see  magazin_footer_widgets()
 * @see  magazin_credit()
 */
add_action( 'magazin_footer', 'magazin_footer_widgets', 10 );
add_action( 'magazin_footer', 'magazin_credit', 20 );

/**
 * Homepage
 *
 * @see  magazin_homepage_content()
 */
add_action( 'homepage', 'magazin_homepage_content', 10 );

/**
 * Posts
 *
 * @see  magazin_post_header()
 * @see  magazin_post_meta()
 * @see  magazin_post_content()
 * @see  magazin_paging_nav()
 * @see  magazin_single_post_header()
 * @see  magazin_post_nav()
 * @see  magazin_display_comments()
 */
add_action( 'magazin_loop_post', 'magazin_post_header', 10 );
add_action( 'magazin_loop_post', 'magazin_post_content', 30 );
add_action( 'magazin_loop_post', 'magazin_post_taxonomy', 40 );
add_action( 'magazin_loop_after', 'magazin_paging_nav', 10 );
add_action( 'magazin_single_post', 'magazin_post_header', 10 );
add_action( 'magazin_single_post', 'magazin_post_content', 30 );
add_action( 'magazin_single_post_bottom', 'magazin_edit_post_link', 5 );
add_action( 'magazin_single_post_bottom', 'magazin_post_taxonomy', 5 );
add_action( 'magazin_single_post_bottom', 'magazin_post_nav', 10 );
add_action( 'magazin_single_post_bottom', 'magazin_display_comments', 20 );
add_action( 'magazin_post_header_before', 'magazin_post_meta', 10 );
add_action( 'magazin_post_content_before', 'magazin_post_thumbnail', 10 );

/**
 * Pages
 *
 * @see  magazin_page_header()
 * @see  magazin_page_content()
 * @see  magazin_display_comments()
 */
add_action( 'magazin_page', 'magazin_page_header', 10 );
add_action( 'magazin_page', 'magazin_page_content', 20 );
add_action( 'magazin_page', 'magazin_edit_post_link', 30 );
add_action( 'magazin_page_after', 'magazin_display_comments', 10 );

/**
 * Homepage Page Template
 *
 * @see  magazin_homepage_header()
 * @see  magazin_page_content()
 */
add_action( 'magazin_homepage', 'magazin_homepage_header', 10 );
add_action( 'magazin_homepage', 'magazin_page_content', 20 );
