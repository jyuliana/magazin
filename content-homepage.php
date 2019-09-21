<?php
/**
 * The template used for displaying page content in template-homepage.php
 *
 * @package magazin
 */

?>
<?php
$featured_image = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="<?php magazin_homepage_content_styles(); ?>" data-featured-image="<?php echo esc_url( $featured_image ); ?>">
	<div class="col-full">
		<?php
		/**
		 * Functions hooked in to magazin_page add_action
		 *
		 * @hooked magazin_homepage_header      - 10
		 * @hooked magazin_page_content         - 20
		 */
		do_action( 'magazin_homepage' );
		?>
	</div>
</div><!-- #post-## -->
