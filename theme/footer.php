<?php
/**
 * Footer file common to all
 * templates
 *
 */
?>


<footer class="site__footer" id="footer">
	<section id="footer__widgets">
		<div class="wrapper wrapper--large"><?php dynamic_sidebar( 'footer__widgets' ); ?></div>
	</section>
	<section id="colophon">
		<img src="https://pacificsky.co/logo" alt="Pacific Sky">
		<span class="copyright">&copy;<?php echo date('Y'); ?></span>
	</section>
</footer>



<?php wp_footer(); ?>

<script async defer src="<?php echo get_template_directory_uri(); ?>/assets/js/core.js"></script>
</body>
</html>
