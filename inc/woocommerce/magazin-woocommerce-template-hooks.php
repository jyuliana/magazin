<?php
/**
 * magazin WooCommerce hooks
 *
 * @package magazin
 */

/**
 * Homepage
 *
 * @see  magazin_product_categories()
 * @see  magazin_recent_products()
 * @see  magazin_featured_products()
 * @see  magazin_popular_products()
 * @see  magazin_on_sale_products()
 * @see  magazin_best_selling_products()
 */
add_action( 'homepage', 'magazin_product_categories', 20 );
add_action( 'homepage', 'magazin_recent_products', 30 );
add_action( 'homepage', 'magazin_featured_products', 40 );
add_action( 'homepage', 'magazin_popular_products', 50 );
add_action( 'homepage', 'magazin_on_sale_products', 60 );
add_action( 'homepage', 'magazin_best_selling_products', 70 );

/**
 * Layout
 *
 * @see  magazin_before_content()
 * @see  magazin_after_content()
 * @see  woocommerce_breadcrumb()
 * @see  magazin_shop_messages()
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
add_action( 'woocommerce_before_main_content', 'magazin_before_content', 10 );
add_action( 'woocommerce_after_main_content', 'magazin_after_content', 10 );
add_action( 'magazin_content_top', 'magazin_shop_messages', 15 );
add_action( 'magazin_before_content', 'woocommerce_breadcrumb', 10 );

add_action( 'woocommerce_after_shop_loop', 'magazin_sorting_wrapper', 9 );
add_action( 'woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 10 );
add_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 20 );
add_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 30 );
add_action( 'woocommerce_after_shop_loop', 'magazin_sorting_wrapper_close', 31 );

add_action( 'woocommerce_before_shop_loop', 'magazin_sorting_wrapper', 9 );
add_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 10 );
add_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
add_action( 'woocommerce_before_shop_loop', 'magazin_woocommerce_pagination', 30 );
add_action( 'woocommerce_before_shop_loop', 'magazin_sorting_wrapper_close', 31 );

add_action( 'magazin_footer', 'magazin_handheld_footer_bar', 999 );

// Legacy WooCommerce columns filter.
if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.3', '<' ) ) {
	add_filter( 'loop_shop_columns', 'magazin_loop_columns' );
	add_action( 'woocommerce_before_shop_loop', 'magazin_product_columns_wrapper', 40 );
	add_action( 'woocommerce_after_shop_loop', 'magazin_product_columns_wrapper_close', 40 );
}

/**
 * Products
 *
 * @see magazin_edit_post_link()
 * @see magazin_upsell_display()
 * @see magazin_single_product_pagination()
 * @see magazin_sticky_single_add_to_cart()
 */
add_action( 'woocommerce_single_product_summary', 'magazin_edit_post_link', 60 );

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action( 'woocommerce_after_single_product_summary', 'magazin_upsell_display', 15 );

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 6 );

add_action( 'woocommerce_after_single_product_summary', 'magazin_single_product_pagination', 30 );
add_action( 'magazin_after_footer', 'magazin_sticky_single_add_to_cart', 999 );

/**
 * Header
 *
 * @see magazin_product_search()
 * @see magazin_header_cart()
 */
add_action( 'magazin_header', 'magazin_product_search', 40 );
add_action( 'magazin_header', 'magazin_header_cart', 60 );

/**
 * Cart fragment
 *
 * @see magazin_cart_link_fragment()
 */
if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '2.3', '>=' ) ) {
	add_filter( 'woocommerce_add_to_cart_fragments', 'magazin_cart_link_fragment' );
} else {
	add_filter( 'add_to_cart_fragments', 'magazin_cart_link_fragment' );
}

/**
 * Integrations
 *
 * @see magazin_woocommerce_brands_archive()
 * @see magazin_woocommerce_brands_single()
 * @see magazin_woocommerce_brands_homepage_section()
 */
if ( class_exists( 'WC_Brands' ) ) {
	add_action( 'woocommerce_archive_description', 'magazin_woocommerce_brands_archive', 5 );
	add_action( 'woocommerce_single_product_summary', 'magazin_woocommerce_brands_single', 4 );
	add_action( 'homepage', 'magazin_woocommerce_brands_homepage_section', 80 );
}
