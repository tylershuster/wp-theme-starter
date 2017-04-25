<?php
/**
 * Theme Functions &
 * Functionality
 *
 */


/* =========================================
		ACTION HOOKS & FILTERS
	 ========================================= */

/**--- Actions ---**/

add_action( 'after_setup_theme',  'theme_setup' );

add_action( 'wp_enqueue_scripts', 'theme_styles' );

add_action( 'wp_enqueue_scripts', 'theme_scripts' );

// expose php variables to js. just uncomment line
// below and see function theme_scripts_localize
// add_action( 'wp_enqueue_scripts', 'theme_scripts_localize', 20 );

/**--- Filters ---**/



/* =========================================
		HOOKED Functions
	 ========================================= */

/**--- Actions ---**/


/**
 * Setup the theme
 *
 * @since 1.0
 */
if ( ! function_exists( 'theme_setup' ) ) {
	function theme_setup() {

		// Let wp know we want to use html5 for content
		add_theme_support( 'html5', array(
			'comment-list',
			'comment-form',
			'search-form',
			'gallery',
			'caption'
		) );


		add_theme_support( 'post-thumbnails' );


		// Register navigation menus for theme

		$main_menu_name = 'Main Menu';
		register_nav_menus( array(
			'primary' => $main_menu_name
		) );

		if(!wp_get_nav_Menu_object($main_menu_name)) {
			$menu_id = wp_create_nav_menu($main_menu_name);
			wp_update_nav_menu_item($menu_id, 0, array(
				'menu-item-title' => __('Home'),
				'menu-item-classes' => 'home',
				'menu-item-url' => home_url('/'),
				'menu-item-status' => 'publish'
			));

			// http://stackoverflow.com/questions/19401556/automatically-setting-a-menu-on-location-primary-menu-on-theme-activation
			$locations = get_theme_mod('nav_menu_locations');
			$locations['primary'] = $menu_id;
			set_theme_mod('nav_menu_locations', $locations);
		}




		// Let wp know we are going to handle styling galleries
		/*
		add_filter( 'use_default_gallery_style', '__return_false' );
		*/


		// Stop WP from printing emoji service on the front
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );


		// Remove toolbar for all users in front end
		show_admin_bar( false );


		// Add Custom Image Sizes
		/*
		add_image_size( 'ExampleImageSize', 1200, 450, true ); // Example Image Size
		...
		*/


		// WPML configuration
		// disable plugin from printing styles and js
		// we are going to handle all that ourselves.
		define( 'ICL_DONT_LOAD_NAVIGATION_CSS', true );
		define( 'ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true );
		define( 'ICL_DONT_LOAD_LANGUAGES_JS', true );


		// Contact Form 7 Configuration needs to be done
		// in wp-config.php. add the following snippet
		// under the line:
		// define( 'WP_DEBUG', false );
		/*
		//Contact Form 7 Plugin Configuration
		define ( 'WPCF7_LOAD_JS',  false ); // Added to disable JS loading
		define ( 'WPCF7_LOAD_CSS', false ); // Added to disable CSS loading
		define ( 'WPCF7_AUTOP',    false ); // Added to disable adding <p> & <br> in form output
		*/


		// Register Autoloaders Loader
		$theme_dir = get_template_directory();
		include "$theme_dir/library/library-loader.php";
		include "$theme_dir/includes/includes-loader.php";
		include "$theme_dir/components/components-loader.php";

		if ( function_exists('register_sidebar') ) {
			register_sidebar(array(
				'name' => "Footer Widgets",
				'id' => 'footer__widgets',
				'before_widget' => '<div class="footer__widget">',
				'after_widget' => '</div>',
				'before_title' => '<h2 class="widget__title">',
				'after_title' => '</h2>',
			));
		}
	}
}


/**
 * Register and/or Enqueue
 * Styles for the theme
 *
 * @since 1.0
 */
if ( ! function_exists( 'theme_styles' ) ) {
	function theme_styles() {
		$theme_dir = get_stylesheet_directory_uri();

		wp_enqueue_style( 'main', "$theme_dir/assets/css/main.css", array(), null, 'all' );
	}
}


/**
 * Register and/or Enqueue
 * Scripts for the theme
 *
 * @since 1.0
 */
if ( ! function_exists( 'theme_scripts' ) ) {
	function theme_scripts() {
		$theme_dir = get_stylesheet_directory_uri();

		wp_enqueue_script( 'main', "$theme_dir/assets/js/main.js", array( 'jquery' ), null, true );
	}
}


/**
 * Attach variables we want
 * to expose to our JS
 *
 * @since 3.12.0
 */
if ( ! function_exists( 'theme_scripts_localize' ) ) {
	function theme_scripts_localize() {
		$ajax_url_params = array();

		// You can remove this block if you don't use WPML
		if ( function_exists( 'wpml_object_id' ) ) {
			/** @var $sitepress SitePress */
			global $sitepress;

			$current_lang = $sitepress->get_current_language();
			wp_localize_script( 'main', 'i18n', array(
				'lang' => $current_lang
			) );

			$ajax_url_params['lang'] = $current_lang;
		}

		wp_localize_script( 'main', 'urls', array(
			'home'  => home_url(),
			'theme' => get_stylesheet_directory_uri(),
			'ajax'  => add_query_arg( $ajax_url_params, admin_url( 'admin-ajax.php' ) )
		) );
	}
}

if(!function_exists('detect_browser')) {
	function detect_browser() {
	if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE)
		return 'ie';
	elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) //For Supporting IE 11
		return 'ie';
	elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Edge') !== FALSE) //For Supporting IE 11
		return 'edge';
	elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE)
		return 'firefox';
	elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE)
		return 'chrome';
	elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== FALSE)
		return "opera";
	elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== FALSE)
		return "opera";
	elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE)
		return "safari";
	else
		return 'unknown-browser';
	}
}

function wpcf7_ajax_loader () {
	return  get_stylesheet_directory_uri() . '/assets/svg/loader.svg';
}
add_filter('wpcf7_ajax_loader', 'wpcf7_ajax_loader');


function responsive_image($path) {
	$sizes = array(
		'mobile' => 480,
		'tablet' => 768,
		'laptop' => 960,
		'desktop' => 1200
	);
	preg_match('/(.+)\.(\w+)$/', $path, $parts);
	$base_path = get_stylesheet_directory() . '/assets/img/' . $parts[1];
	$base_uri = get_stylesheet_directory_uri() . '/assets/img/' . $parts[1];
	$extension = $parts[2];
	$return_str = '';
	foreach($sizes as $size => $dimension) {
		if($size === 'mobile') {
			$size = '';
		} else {
			$size = "--{$size}";
		}
		if(file_exists($base_path . $size . '.' . $extension)) {
			$return_str .= "{$base_uri}{$size}.{$extension} {$dimension}w, ";
		}
	}
	return $return_str;
}

function PS_SVG ($path) {
	// Helper function to replace responsive images
	$svg = MOZ_SVG::get_svg($path);
	$svg = str_replace('ROOT_URL', get_bloginfo('home'), $svg);
	preg_match('/data-responsive="(.+)"/', $svg, $php);
	if($php[1]) {
		$image = responsive_image($php[1]);
		$svg = str_replace(
			$php[0],
			"data-srcset=\"{$image}\" data-sizes=\"auto\"",
			$svg
		);
	}
	echo $svg;
}
