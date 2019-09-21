<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package magazin
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php do_action( 'magazin_before_site' ); ?>

<div id="page" class="hfeed site">
	<?php do_action( 'magazin_before_header' ); ?>

	<header id="masthead" class="site-header" role="banner" style="<?php magazin_header_styles(); ?>">

		<?php
		/**
		 * Functions hooked into magazin_header action
		 *
		 * @hooked magazin_header_container                 - 0
		 * @hooked magazin_skip_links                       - 5
		 * @hooked magazin_social_icons                     - 10
		 * @hooked magazin_site_branding                    - 20
		 * @hooked magazin_secondary_navigation             - 30
		 * @hooked magazin_product_search                   - 40
		 * @hooked magazin_header_container_close           - 41
		 * @hooked magazin_primary_navigation_wrapper       - 42
		 * @hooked magazin_primary_navigation               - 50
		 * @hooked magazin_header_cart                      - 60
		 * @hooked magazin_primary_navigation_wrapper_close - 68
		 */
		do_action( 'magazin_header' );
		?>
		<?php echo do_shortcode('[metaslider id="536"]'); ?>

	</header><!-- #masthead -->

	<?php
	/**
	 * Functions hooked in to magazin_before_content
	 *
	 * @hooked magazin_header_widget_region - 10
	 * @hooked woocommerce_breadcrumb - 10
	 */
	do_action( 'magazin_before_content' );
	?>

	<div id="content" class="site-content" tabindex="-1">
		<div class="col-full">

		<?php
		do_action( 'magazin_content_top' );
