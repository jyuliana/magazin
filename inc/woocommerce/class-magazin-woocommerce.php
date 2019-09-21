<?php
/**
 * magazin WooCommerce Class
 *
 * @package  magazin
 * @since    2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'magazin_WooCommerce' ) ) :

	/**
	 * The magazin WooCommerce Integration class
	 */
	class magazin_WooCommerce {

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'after_setup_theme', array( $this, 'setup' ) );
			add_filter( 'body_class', array( $this, 'woocommerce_body_class' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'woocommerce_scripts' ), 20 );
			add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
			add_filter( 'woocommerce_output_related_products_args', array( $this, 'related_products_args' ) );
			add_filter( 'woocommerce_product_thumbnails_columns', array( $this, 'thumbnail_columns' ) );
			add_filter( 'woocommerce_breadcrumb_defaults', array( $this, 'change_breadcrumb_delimiter' ) );

			if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '2.5', '<' ) ) {
				add_action( 'wp_footer', array( $this, 'star_rating_script' ) );
			}

			if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.3', '<' ) ) {
				add_filter( 'loop_shop_per_page', array( $this, 'products_per_page' ) );
			}

			// Integrations.
			add_action( 'magazin_woocommerce_setup', array( $this, 'setup_integrations' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'woocommerce_integrations_scripts' ), 99 );
			add_action( 'wp_enqueue_scripts', array( $this, 'add_customizer_css' ), 140 );
		}

		/**
		 * Sets up theme defaults and registers support for various WooCommerce features.
		 *
		 * Note that this function is hooked into the after_setup_theme hook, which
		 * runs before the init hook. The init hook is too late for some features, such
		 * as indicating support for post thumbnails.
		 *
		 * @since 2.4.0
		 * @return void
		 */
		public function setup() {
			add_theme_support(
				'woocommerce', apply_filters(
					'magazin_woocommerce_args', array(
						'single_image_width'    => 416,
						'thumbnail_image_width' => 324,
						'product_grid'          => array(
							'default_columns' => 3,
							'default_rows'    => 4,
							'min_columns'     => 1,
							'max_columns'     => 6,
							'min_rows'        => 1,
						),
					)
				)
			);

			add_theme_support( 'wc-product-gallery-zoom' );
			add_theme_support( 'wc-product-gallery-lightbox' );
			add_theme_support( 'wc-product-gallery-slider' );

			/**
			 * Add 'magazin_woocommerce_setup' action.
			 *
			 * @since  2.4.0
			 */
			do_action( 'magazin_woocommerce_setup' );
		}

		/**
		 * Add CSS in <head> for styles handled by the theme customizer
		 * If the Customizer is active pull in the raw css. Otherwise pull in the prepared theme_mods if they exist.
		 *
		 * @since 2.1.0
		 * @return void
		 */
		public function add_customizer_css() {
			wp_add_inline_style( 'magazin-woocommerce-style', $this->get_woocommerce_extension_css() );
		}

		/**
		 * Assign styles to individual theme mod.
		 *
		 * @deprecated 2.3.1
		 * @since 2.1.0
		 * @return void
		 */
		public function set_magazin_style_theme_mods() {
			if ( function_exists( 'wc_deprecated_function' ) ) {
				wc_deprecated_function( __FUNCTION__, '2.3.1' );
			} else {
				_deprecated_function( __FUNCTION__, '2.3.1' );
			}
		}

		/**
		 * Add WooCommerce specific classes to the body tag
		 *
		 * @param  array $classes css classes applied to the body tag.
		 * @return array $classes modified to include 'woocommerce-active' class
		 */
		public function woocommerce_body_class( $classes ) {
			$classes[] = 'woocommerce-active';

			// Remove `no-wc-breadcrumb` body class.
			$key = array_search( 'no-wc-breadcrumb', $classes, true );

			if ( false !== $key ) {
				unset( $classes[ $key ] );
			}

			return $classes;
		}

		/**
		 * WooCommerce specific scripts & stylesheets
		 *
		 * @since 1.0.0
		 */
		public function woocommerce_scripts() {
			global $magazin_version;

			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

			wp_enqueue_style( 'magazin-woocommerce-style', get_template_directory_uri() . '/assets/css/woocommerce/woocommerce.css', array(), $magazin_version );
			wp_style_add_data( 'magazin-woocommerce-style', 'rtl', 'replace' );

			wp_register_script( 'magazin-header-cart', get_template_directory_uri() . '/assets/js/woocommerce/header-cart' . $suffix . '.js', array(), $magazin_version, true );
			wp_enqueue_script( 'magazin-header-cart' );

			wp_enqueue_script( 'magazin-handheld-footer-bar', get_template_directory_uri() . '/assets/js/footer' . $suffix . '.js', array(), $magazin_version, true );

			if ( ! class_exists( 'magazin_Sticky_Add_to_Cart' ) && is_product() ) {
				wp_register_script( 'magazin-sticky-add-to-cart', get_template_directory_uri() . '/assets/js/sticky-add-to-cart' . $suffix . '.js', array(), $magazin_version, true );
			}

			if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.3', '<' ) ) {
				wp_enqueue_style( 'magazin-woocommerce-legacy', get_template_directory_uri() . '/assets/css/woocommerce/woocommerce-legacy.css', array(), $magazin_version );
				wp_style_add_data( 'magazin-woocommerce-legacy', 'rtl', 'replace' );
			}
		}

		/**
		 * Star rating backwards compatibility script (WooCommerce <2.5).
		 *
		 * @since 1.6.0
		 */
		public function star_rating_script() {
			if ( is_product() ) {
				?>
			<script type="text/javascript">
				var starsEl = document.querySelector( '#respond p.stars' );
				if ( starsEl ) {
					starsEl.addEventListener( 'click', function( event ) {
						if ( event.target.tagName === 'A' ) {
							starsEl.classList.add( 'selected' );
						}
					} );
				}
			</script>
				<?php
			}
		}

		/**
		 * Related Products Args
		 *
		 * @param  array $args related products args.
		 * @since 1.0.0
		 * @return  array $args related products args
		 */
		public function related_products_args( $args ) {
			$args = apply_filters(
				'magazin_related_products_args', array(
					'posts_per_page' => 3,
					'columns'        => 3,
				)
			);

			return $args;
		}

		/**
		 * Product gallery thumbnail columns
		 *
		 * @return integer number of columns
		 * @since  1.0.0
		 */
		public function thumbnail_columns() {
			$columns = 4;

			if ( ! is_active_sidebar( 'sidebar-1' ) ) {
				$columns = 5;
			}

			return intval( apply_filters( 'magazin_product_thumbnail_columns', $columns ) );
		}

		/**
		 * Products per page
		 *
		 * @return integer number of products
		 * @since  1.0.0
		 */
		public function products_per_page() {
			return intval( apply_filters( 'magazin_products_per_page', 12 ) );
		}

		/**
		 * Query WooCommerce Extension Activation.
		 *
		 * @param string $extension Extension class name.
		 * @return boolean
		 */
		public function is_woocommerce_extension_activated( $extension = 'WC_Bookings' ) {
			return class_exists( $extension ) ? true : false;
		}

		/**
		 * Remove the breadcrumb delimiter
		 *
		 * @param  array $defaults The breadcrumb defaults.
		 * @return array           The breadcrumb defaults.
		 * @since 2.2.0
		 */
		public function change_breadcrumb_delimiter( $defaults ) {
			$defaults['delimiter']   = '<span class="breadcrumb-separator"> / </span>';
			$defaults['wrap_before'] = '<div class="magazin-breadcrumb"><div class="col-full"><nav class="woocommerce-breadcrumb">';
			$defaults['wrap_after']  = '</nav></div></div>';
			return $defaults;
		}

		/**
		 * Integration Styles & Scripts
		 *
		 * @return void
		 */
		public function woocommerce_integrations_scripts() {
			global $magazin_version;

			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

			/**
			 * Bookings
			 */
			if ( $this->is_woocommerce_extension_activated( 'WC_Bookings' ) ) {
				wp_enqueue_style( 'magazin-woocommerce-bookings-style', get_template_directory_uri() . '/assets/css/woocommerce/extensions/bookings.css', 'magazin-woocommerce-style', $magazin_version );
				wp_style_add_data( 'magazin-woocommerce-bookings-style', 'rtl', 'replace' );
			}

			/**
			 * Brands
			 */
			if ( $this->is_woocommerce_extension_activated( 'WC_Brands' ) ) {
				wp_enqueue_style( 'magazin-woocommerce-brands-style', get_template_directory_uri() . '/assets/css/woocommerce/extensions/brands.css', 'magazin-woocommerce-style', $magazin_version );
				wp_style_add_data( 'magazin-woocommerce-brands-style', 'rtl', 'replace' );

				wp_enqueue_script( 'magazin-woocommerce-brands', get_template_directory_uri() . '/assets/js/woocommerce/extensions/brands' . $suffix . '.js', array(), $magazin_version, true );
			}

			/**
			 * Wishlists
			 */
			if ( $this->is_woocommerce_extension_activated( 'WC_Wishlists_Wishlist' ) ) {
				wp_enqueue_style( 'magazin-woocommerce-wishlists-style', get_template_directory_uri() . '/assets/css/woocommerce/extensions/wishlists.css', 'magazin-woocommerce-style', $magazin_version );
				wp_style_add_data( 'magazin-woocommerce-wishlists-style', 'rtl', 'replace' );
			}

			/**
			 * AJAX Layered Nav
			 */
			if ( $this->is_woocommerce_extension_activated( 'SOD_Widget_Ajax_Layered_Nav' ) ) {
				wp_enqueue_style( 'magazin-woocommerce-ajax-layered-nav-style', get_template_directory_uri() . '/assets/css/woocommerce/extensions/ajax-layered-nav.css', 'magazin-woocommerce-style', $magazin_version );
				wp_style_add_data( 'magazin-woocommerce-ajax-layered-nav-style', 'rtl', 'replace' );
			}

			/**
			 * Variation Swatches
			 */
			if ( $this->is_woocommerce_extension_activated( 'WC_SwatchesPlugin' ) ) {
				wp_enqueue_style( 'magazin-woocommerce-variation-swatches-style', get_template_directory_uri() . '/assets/css/woocommerce/extensions/variation-swatches.css', 'magazin-woocommerce-style', $magazin_version );
				wp_style_add_data( 'magazin-woocommerce-variation-swatches-style', 'rtl', 'replace' );
			}

			/**
			 * Composite Products
			 */
			if ( $this->is_woocommerce_extension_activated( 'WC_Composite_Products' ) ) {
				wp_enqueue_style( 'magazin-woocommerce-composite-products-style', get_template_directory_uri() . '/assets/css/woocommerce/extensions/composite-products.css', 'magazin-woocommerce-style', $magazin_version );
				wp_style_add_data( 'magazin-woocommerce-composite-products-style', 'rtl', 'replace' );
			}

			/**
			 * WooCommerce Photography
			 */
			if ( $this->is_woocommerce_extension_activated( 'WC_Photography' ) ) {
				wp_enqueue_style( 'magazin-woocommerce-photography-style', get_template_directory_uri() . '/assets/css/woocommerce/extensions/photography.css', 'magazin-woocommerce-style', $magazin_version );
				wp_style_add_data( 'magazin-woocommerce-photography-style', 'rtl', 'replace' );
			}

			/**
			 * Product Reviews Pro
			 */
			if ( $this->is_woocommerce_extension_activated( 'WC_Product_Reviews_Pro' ) ) {
				wp_enqueue_style( 'magazin-woocommerce-product-reviews-pro-style', get_template_directory_uri() . '/assets/css/woocommerce/extensions/product-reviews-pro.css', 'magazin-woocommerce-style', $magazin_version );
				wp_style_add_data( 'magazin-woocommerce-product-reviews-pro-style', 'rtl', 'replace' );
			}

			/**
			 * WooCommerce Smart Coupons
			 */
			if ( $this->is_woocommerce_extension_activated( 'WC_Smart_Coupons' ) ) {
				wp_enqueue_style( 'magazin-woocommerce-smart-coupons-style', get_template_directory_uri() . '/assets/css/woocommerce/extensions/smart-coupons.css', 'magazin-woocommerce-style', $magazin_version );
				wp_style_add_data( 'magazin-woocommerce-smart-coupons-style', 'rtl', 'replace' );
			}

			/**
			 * WooCommerce Deposits
			 */
			if ( $this->is_woocommerce_extension_activated( 'WC_Deposits' ) ) {
				wp_enqueue_style( 'magazin-woocommerce-deposits-style', get_template_directory_uri() . '/assets/css/woocommerce/extensions/deposits.css', 'magazin-woocommerce-style', $magazin_version );
				wp_style_add_data( 'magazin-woocommerce-deposits-style', 'rtl', 'replace' );
			}

			/**
			 * WooCommerce Product Bundles
			 */
			if ( $this->is_woocommerce_extension_activated( 'WC_Bundles' ) ) {
				wp_enqueue_style( 'magazin-woocommerce-bundles-style', get_template_directory_uri() . '/assets/css/woocommerce/extensions/bundles.css', 'magazin-woocommerce-style', $magazin_version );
				wp_style_add_data( 'magazin-woocommerce-bundles-style', 'rtl', 'replace' );
			}

			/**
			 * WooCommerce Multiple Shipping Addresses
			 */
			if ( $this->is_woocommerce_extension_activated( 'WC_Ship_Multiple' ) ) {
				wp_enqueue_style( 'magazin-woocommerce-sma-style', get_template_directory_uri() . '/assets/css/woocommerce/extensions/ship-multiple-addresses.css', 'magazin-woocommerce-style', $magazin_version );
				wp_style_add_data( 'magazin-woocommerce-sma-style', 'rtl', 'replace' );
			}

			/**
			 * WooCommerce Advanced Product Labels
			 */
			if ( $this->is_woocommerce_extension_activated( 'Woocommerce_Advanced_Product_Labels' ) ) {
				wp_enqueue_style( 'magazin-woocommerce-apl-style', get_template_directory_uri() . '/assets/css/woocommerce/extensions/advanced-product-labels.css', 'magazin-woocommerce-style', $magazin_version );
				wp_style_add_data( 'magazin-woocommerce-apl-style', 'rtl', 'replace' );
			}

			/**
			 * WooCommerce Mix and Match
			 */
			if ( $this->is_woocommerce_extension_activated( 'WC_Mix_and_Match' ) ) {
				wp_enqueue_style( 'magazin-woocommerce-mix-and-match-style', get_template_directory_uri() . '/assets/css/woocommerce/extensions/mix-and-match.css', 'magazin-woocommerce-style', $magazin_version );
				wp_style_add_data( 'magazin-woocommerce-mix-and-match-style', 'rtl', 'replace' );
			}

			/**
			 * WooCommerce Memberships
			 */
			if ( $this->is_woocommerce_extension_activated( 'WC_Memberships' ) ) {
				wp_enqueue_style( 'magazin-woocommerce-memberships-style', get_template_directory_uri() . '/assets/css/woocommerce/extensions/memberships.css', 'magazin-woocommerce-style', $magazin_version );
				wp_style_add_data( 'magazin-woocommerce-memberships-style', 'rtl', 'replace' );
			}

			/**
			 * WooCommerce Quick View
			 */
			if ( $this->is_woocommerce_extension_activated( 'WC_Quick_View' ) ) {
				wp_enqueue_style( 'magazin-woocommerce-quick-view-style', get_template_directory_uri() . '/assets/css/woocommerce/extensions/quick-view.css', 'magazin-woocommerce-style', $magazin_version );
				wp_style_add_data( 'magazin-woocommerce-quick-view-style', 'rtl', 'replace' );
			}

			/**
			 * Checkout Add Ons
			 */
			if ( $this->is_woocommerce_extension_activated( 'WC_Checkout_Add_Ons' ) ) {
				add_filter( 'magazin_sticky_order_review', '__return_false' );
			}

			/**
			 * WooCommerce Product Recommendations
			 */
			if ( $this->is_woocommerce_extension_activated( 'WC_Product_Recommendations' ) ) {
				wp_enqueue_style( 'magazin-woocommerce-product-recommendations-style', get_template_directory_uri() . '/assets/css/woocommerce/extensions/product-recommendations.css', 'magazin-woocommerce-style', $magazin_version );
				wp_style_add_data( 'magazin-woocommerce-product-recommendations-style', 'rtl', 'replace' );
			}
		}

		/**
		 * Get extension css.
		 *
		 * @see get_magazin_theme_mods()
		 * @return array $styles the css
		 */
		public function get_woocommerce_extension_css() {
			global $magazin;

			if ( ! is_object( $magazin ) ||
				 ! property_exists( $magazin, 'customizer' ) ||
				 ! is_a( $magazin->customizer, 'magazin_Customizer' ) ||
				 ! method_exists( $magazin->customizer, 'get_magazin_theme_mods' ) ) {
				return apply_filters( 'magazin_customizer_woocommerce_extension_css', '' );
			}

			$magazin_theme_mods = $magazin->customizer->get_magazin_theme_mods();

			$woocommerce_extension_style = '';

			if ( $this->is_woocommerce_extension_activated( 'WC_Bookings' ) ) {
				$woocommerce_extension_style .= '
				.wc-bookings-date-picker .ui-datepicker td.bookable a {
					background-color: ' . $magazin_theme_mods['accent_color'] . ' !important;
				}

				.wc-bookings-date-picker .ui-datepicker td.bookable a.ui-state-default {
					background-color: ' . magazin_adjust_color_brightness( $magazin_theme_mods['accent_color'], -10 ) . ' !important;
				}

				.wc-bookings-date-picker .ui-datepicker td.bookable a.ui-state-active {
					background-color: ' . magazin_adjust_color_brightness( $magazin_theme_mods['accent_color'], -50 ) . ' !important;
				}
				';
			}

			if ( $this->is_woocommerce_extension_activated( 'WC_Product_Reviews_Pro' ) ) {
				$woocommerce_extension_style .= '
				.woocommerce #reviews .product-rating .product-rating-details table td.rating-graph .bar,
				.woocommerce-page #reviews .product-rating .product-rating-details table td.rating-graph .bar {
					background-color: ' . $magazin_theme_mods['text_color'] . ' !important;
				}

				.woocommerce #reviews .contribution-actions .feedback,
				.woocommerce-page #reviews .contribution-actions .feedback,
				.star-rating-selector:not(:checked) label.checkbox {
					color: ' . $magazin_theme_mods['text_color'] . ';
				}

				.woocommerce #reviews #comments ol.commentlist li .contribution-actions a,
				.woocommerce-page #reviews #comments ol.commentlist li .contribution-actions a,
				.star-rating-selector:not(:checked) input:checked ~ label.checkbox,
				.star-rating-selector:not(:checked) label.checkbox:hover ~ label.checkbox,
				.star-rating-selector:not(:checked) label.checkbox:hover,
				.woocommerce #reviews #comments ol.commentlist li .contribution-actions a,
				.woocommerce-page #reviews #comments ol.commentlist li .contribution-actions a,
				.woocommerce #reviews .form-contribution .attachment-type:not(:checked) label.checkbox:before,
				.woocommerce-page #reviews .form-contribution .attachment-type:not(:checked) label.checkbox:before {
					color: ' . $magazin_theme_mods['accent_color'] . ' !important;
				}';
			}

			if ( $this->is_woocommerce_extension_activated( 'WC_Smart_Coupons' ) ) {
				$woocommerce_extension_style .= '
				.coupon-container {
					background-color: ' . $magazin_theme_mods['button_background_color'] . ' !important;
				}

				.coupon-content {
					border-color: ' . $magazin_theme_mods['button_text_color'] . ' !important;
					color: ' . $magazin_theme_mods['button_text_color'] . ';
				}

				.sd-buttons-transparent.woocommerce .coupon-content,
				.sd-buttons-transparent.woocommerce-page .coupon-content {
					border-color: ' . $magazin_theme_mods['button_background_color'] . ' !important;
				}';
			}

			return apply_filters( 'magazin_customizer_woocommerce_extension_css', $woocommerce_extension_style );
		}

		/*
		|--------------------------------------------------------------------------
		| Integrations.
		|--------------------------------------------------------------------------
		*/

		/**
		 * Sets up integrations.
		 *
		 * @since  2.3.4
		 *
		 * @return void
		 */
		public function setup_integrations() {

			if ( $this->is_woocommerce_extension_activated( 'WC_Bundles' ) ) {
				add_filter( 'woocommerce_bundled_table_item_js_enqueued', '__return_true' );
				add_filter( 'woocommerce_bundles_group_mode_options_data', array( $this, 'bundles_group_mode_options_data' ) );
			}

			if ( $this->is_woocommerce_extension_activated( 'WC_Composite_Products' ) ) {
				add_filter( 'woocommerce_composited_table_item_js_enqueued', '__return_true' );
				add_filter( 'woocommerce_display_composite_container_cart_item_data', '__return_true' );
			}
		}

		/**
		 * Add "Includes" meta to parent cart items.
		 * Displayed only on handheld/mobile screens.
		 *
		 * @since  2.3.4
		 *
		 * @param  array $group_mode_data Group mode data.
		 * @return array
		 */
		public function bundles_group_mode_options_data( $group_mode_data ) {
			$group_mode_data['parent']['features'][] = 'parent_cart_item_meta';

			return $group_mode_data;
		}
	}

endif;

return new magazin_WooCommerce();
