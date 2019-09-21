<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package magazin
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	/**
	 * Functions hooked in to magazin_page add_action
	 *
	 * @hooked magazin_page_header          - 10
	 * @hooked magazin_page_content         - 20
	 */
	do_action( 'magazin_page' );
	?>
</article><!-- #post-## -->
