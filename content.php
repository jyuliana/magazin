<?php
/**
 * Template used to display post content.
 *
 * @package magazin
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	/**
	 * Functions hooked in to magazin_loop_post action.
	 *
	 * @hooked magazin_post_header          - 10
	 * @hooked magazin_post_content         - 30
	 */
	do_action( 'magazin_loop_post' );
	?>

</article><!-- #post-## -->
