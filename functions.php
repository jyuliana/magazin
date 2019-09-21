<?php
/**
 * magazin engine room
 *
 * @package magazin
 */

/**
 * Assign the magazin version to a var
 */
$theme              = wp_get_theme( 'magazin' );
$magazin_version = $theme['Version'];

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 980; /* pixels */
}

$magazin = (object) array(
	'version'    => $magazin_version,

	/**
	 * Initialize all the things.
	 */
	'main'       => require 'inc/class-magazin.php',
	'customizer' => require 'inc/customizer/class-magazin-customizer.php',
);

require 'inc/magazin-functions.php';
require 'inc/magazin-template-hooks.php';
require 'inc/magazin-template-functions.php';

if ( class_exists( 'Jetpack' ) ) {
	$magazin->jetpack = require 'inc/jetpack/class-magazin-jetpack.php';
}

if ( magazin_is_woocommerce_activated() ) {
	$magazin->woocommerce            = require 'inc/woocommerce/class-magazin-woocommerce.php';
	$magazin->woocommerce_customizer = require 'inc/woocommerce/class-magazin-woocommerce-customizer.php';

	require 'inc/woocommerce/class-magazin-woocommerce-adjacent-products.php';

	require 'inc/woocommerce/magazin-woocommerce-template-hooks.php';
	require 'inc/woocommerce/magazin-woocommerce-template-functions.php';
	require 'inc/woocommerce/magazin-woocommerce-functions.php';
}

if ( is_admin() ) {
	$magazin->admin = require 'inc/admin/class-magazin-admin.php';

	require 'inc/admin/class-magazin-plugin-install.php';
}

/**
 * NUX
 * Only load if wp version is 4.7.3 or above because of this issue;
 * https://core.trac.wordpress.org/ticket/39610?cversion=1&cnum_hist=2
 */
if ( version_compare( get_bloginfo( 'version' ), '4.7.3', '>=' ) && ( is_admin() || is_customize_preview() ) ) {
	require 'inc/nux/class-magazin-nux-admin.php';
	require 'inc/nux/class-magazin-nux-guided-tour.php';

	if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.0.0', '>=' ) ) {
		require 'inc/nux/class-magazin-nux-starter-content.php';
	}
}

/**
 * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
 * https://github.com/woocommerce/theme-customisations
 */