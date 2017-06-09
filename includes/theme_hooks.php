<?php
/*------------------------------------*\
    Actions + Filters + ShortCodes
\*------------------------------------*/

// Add Actions

//add_action('wp_print_scripts', 'figjam_conditional_scripts'); // Add Conditional Page Scripts

add_action('get_header', 'enable_threaded_comments'); // Enable Threaded Comments

//add_action('init', 'create_post_type_figjam'); // Add our figjam Blank Custom Post Type

add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()

add_action('init', 'figjamwp_pagination'); // Add our figjam Pagination
add_action( 'init', 'disable_wp_emojicons' ); //Remove all that Emoji rubbish

// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds

remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed

remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link

remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.

remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version

remove_action('wp_head', 'rel_canonical');

remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Add Filters
add_filter('avatar_defaults', 'figjamgravatar'); // Custom Gravatar in Settings > Discussion

add_filter('body_class', 'figjam_add_slug_to_body_class'); // Add slug to body class (Starkers build)

add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)

//add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected classes (Commented out by default)
add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected ID (Commented out by default)
add_filter('page_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> Page ID's (Commented out by default)

add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute

add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)

add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)

add_filter('excerpt_more', 'figjam_blank_view_article'); // Add 'View Article' button instead of [...] for Excerpts
//add_filter('style_loader_tag', 'figjam_style_remove'); // Remove 'text/css' from enqueued stylesheet

add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails

add_filter('post_thumbnail_html', 'remove_width_attribute', 10 ); // Remove width and height dynamic attributes to post images

add_filter('image_send_to_editor', 'remove_width_attribute', 10 ); // Remove width and height dynamic attributes to post images

// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether

// Shortcodes
add_shortcode('figjam_shortcode_demo', 'figjam_shortcode_demo'); // You can place [figjam_shortcode_demo] in Pages, Posts now.
add_shortcode('figjam_shortcode_demo_2', 'figjam_shortcode_demo_2'); // Place [figjam_shortcode_demo_2] in Pages, Posts now.

// Shortcodes above would be nested like this -
// [figjam_shortcode_demo] [figjam_shortcode_demo_2] Here's the page title! [/figjam_shortcode_demo_2] [/figjam_shortcode_demo]

/*------------------------------------*\
    Custom Post Types
\*------------------------------------*/

// Create 1 Custom Post type for a Demo, called figjam-Blank
function create_post_type_figjam()
{
    register_taxonomy_for_object_type('category', 'figjam-blank'); // Register Taxonomies for Category
    register_taxonomy_for_object_type('post_tag', 'figjam-blank');
    register_post_type('figjam-blank', // Register Custom Post Type
        array(
        'labels' => array(
            'name' => __('figjam Blank Custom Post', 'figjam'), // Rename these to suit
            'singular_name' => __('figjam Blank Custom Post', 'figjam'),
            'add_new' => __('Add New', 'figjam'),
            'add_new_item' => __('Add New figjam Blank Custom Post', 'figjam'),
            'edit' => __('Edit', 'figjam'),
            'edit_item' => __('Edit figjam Blank Custom Post', 'figjam'),
            'new_item' => __('New figjam Blank Custom Post', 'figjam'),
            'view' => __('View figjam Blank Custom Post', 'figjam'),
            'view_item' => __('View figjam Blank Custom Post', 'figjam'),
            'search_items' => __('Search figjam Blank Custom Post', 'figjam'),
            'not_found' => __('No figjam Blank Custom Posts found', 'figjam'),
            'not_found_in_trash' => __('No figjam Blank Custom Posts found in Trash', 'figjam')
        ),
        'public' => true,
        'hierarchical' => true, // Allows your posts to behave like Hierarchy Pages
        'has_archive' => true,
        'supports' => array(
            'title',
            'editor',
            'excerpt',
            'thumbnail'
        ), // Go to Dashboard Custom figjam Blank post for supports
        'can_export' => true, // Allows export in Tools > Export
        'taxonomies' => array(
            'post_tag',
            'category'
        ) // Add Category and Post Tags support
    ));
}

/*------------------------------------*\
    ShortCode Functions
\*------------------------------------*/

// Shortcode Demo with Nested Capability
function figjam_shortcode_demo($atts, $content = null)
{
    return '<div class="shortcode-demo">' . do_shortcode($content) . '</div>'; // do_shortcode allows for nested Shortcodes
}

// Shortcode Demo with simple <h2> tag
function figjam_shortcode_demo_2($atts, $content = null) // Demo Heading H2 shortcode, allows for nesting within above element. Fully expandable.
{
    return '<h2>' . $content . '</h2>';
}
