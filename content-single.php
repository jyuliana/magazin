<?php
/**
 * Template used to display post content on single pages.
 *
 * @package magazin
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	do_action( 'magazin_single_post_top' );

	/**
	 * Functions hooked into magazin_single_post add_action
	 *
	 * @hooked magazin_post_header          - 10
	 * @hooked magazin_post_content         - 30
	 */
	do_action( 'magazin_single_post' );

	/**
	 * Functions hooked in to magazin_single_post_bottom action
	 *
	 * @hooked magazin_post_nav         - 10
	 * @hooked magazin_display_comments - 20
	 */
	do_action( 'magazin_single_post_bottom' );
	?>

</article><!-- #post-## -->
