<?php
/**
 * Header file common to all
 * templates
 *
 */
?>
<!doctype html>
<html class="site no-js" <?php language_attributes(); ?>>
<head>
	<!--[if lt IE 9]>
		<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
	<![endif]-->

	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	
	<title><?php wp_title(); ?></title>

	<?php // replace the no-js class to js on the html element ?>
	<script>document.documentElement.className=document.documentElement.className.replace(/\sno-js\s/,'js')</script>

	<?php // load the core js polyfills ?>
	<script async defer src="<?php echo get_template_directory_uri(); ?>/assets/js/core.js"></script>

	<?php wp_head(); ?>
</head>
<body <?php body_class( 'site__body' ); ?>">

	<header class="site__header"></header>
