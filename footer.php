<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package magazin
 */

?>

		</div><!-- .col-full -->
	</div><!-- #content -->

	<?php do_action( 'magazin_before_footer' ); ?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="col-full">

			<?php
			/**
			 * Functions hooked in to magazin_footer action
			 *
			 * @hooked magazin_footer_widgets - 10
			 * @hooked magazin_credit         - 20
			 */
			do_action( 'magazin_footer' );
			?>

		</div><!-- .col-full -->
	</footer><!-- #colophon -->

	<?php do_action( 'magazin_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
