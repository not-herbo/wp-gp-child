<?php
/**
 * Author: Adam Butterworth
 * URL: figjam.com | @figjam
 * Custom functions, support, custom post types and more.
 */

// Useful global constants
define( 'WPGPC_VERSION', '0.1.0' );
define( 'WPGPC_URL', get_stylesheet_directory_uri() );
define( 'WPGPC_TEMPLATE_URL', get_template_directory_uri() );
define( 'WPGPC_PATH', get_stylesheet_directory() . '/' );
define( 'WPGPC_INC', WPGPC_PATH . 'includes/' );

/**
 * Load standard areas of the theme-side framework
 * These should be loaded at all times.
 */
require_once( WPGPC_INC . 'theme_menus_widgets.php' );
require_once( WPGPC_INC . 'theme_functions.php' );
require_once( WPGPC_INC . 'theme_scripts.php' );
require_once( WPGPC_INC . 'theme_hooks.php' );
require_once( WPGPC_INC . 'theme_support.php' );
require_once( WPGPC_INC . 'theme_options.php' );
