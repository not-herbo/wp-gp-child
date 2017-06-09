<?php
/**
 * Enqueue scripts and styles
 */
add_action( 'wp_enqueue_scripts', 'generate_scripts' );
function generate_scripts()
{
	// Get our options.
	$generate_settings = wp_parse_args(
		get_option( 'generate_settings', array() ),
		generate_get_defaults()
	);

	// Get the minified suffix.
	$suffix = generate_get_min_suffix();

	// Enqueue our CSS.
	//wp_enqueue_style( 'generate-style-grid', get_template_directory_uri() . "/css/unsemantic-grid{$suffix}.css", false, GENERATE_VERSION, 'all' );
	wp_enqueue_style( 'generate-style', get_template_directory_uri() . '/style.css', false, GENERATE_VERSION, 'all' );
	//wp_enqueue_style( 'generate-mobile-style', get_template_directory_uri() . "/css/mobile{$suffix}.css", array( 'generate-style' ), GENERATE_VERSION, 'all' );

	// Add the child theme CSS if child theme is active.
	if ( is_child_theme() ) {
		wp_enqueue_style( 'generate-child', get_stylesheet_uri(), array( 'generate-style' ), filemtime( get_stylesheet_directory() . '/style.css' ), 'all' );
	}

	// Font Awesome
	$icon_essentials = apply_filters( 'generate_fontawesome_essentials', false );
	$icon_essentials = ( $icon_essentials ) ? '-essentials' : false;
	wp_enqueue_style( "fontawesome{$icon_essentials}", get_template_directory_uri() . "/css/font-awesome{$icon_essentials}{$suffix}.css", false, '4.7', 'all' );

	// IE 8
	wp_enqueue_style( 'generate-ie', get_template_directory_uri() . "/css/ie{$suffix}.css", false, GENERATE_VERSION, 'all' );
	wp_style_add_data( 'generate-ie', 'conditional', 'lt IE 9' );

	// Add jQuery
	wp_enqueue_script( 'jquery' );

	// Add our mobile navigation
	wp_enqueue_script( 'generate-navigation', get_template_directory_uri() . "/js/navigation{$suffix}.js", array( 'jquery' ), GENERATE_VERSION, true );

	// Add our hover or click dropdown menu scripts
	$click = ( 'click' == $generate_settings[ 'nav_dropdown_type' ] || 'click-arrow' == $generate_settings[ 'nav_dropdown_type' ] ) ? '-click' : '';
	wp_enqueue_script( 'generate-dropdown', get_template_directory_uri() . "/js/dropdown{$click}{$suffix}.js", array( 'jquery' ), GENERATE_VERSION, true );

	// Add our navigation search if it's enabled
	if ( 'enable' == $generate_settings['nav_search'] ) {
		wp_enqueue_script( 'generate-navigation-search', get_template_directory_uri() . "/js/navigation-search{$suffix}.js", array( 'jquery' ), GENERATE_VERSION, true );
	}

	// Add the back to top script if it's enabled
	if ( 'enable' == $generate_settings['back_to_top'] ) {
		wp_enqueue_script( 'generate-back-to-top', get_template_directory_uri() . "/js/back-to-top{$suffix}.js", array( 'jquery' ), GENERATE_VERSION, true );
	}

	// Move the navigation from below the content on mobile to below the header if it's in a sidebar
	if ( 'nav-left-sidebar' == generate_get_navigation_location() || 'nav-right-sidebar' == generate_get_navigation_location() ) {
		wp_enqueue_script( 'generate-move-navigation', get_template_directory_uri() . "/js/move-navigation{$suffix}.js", array( 'jquery' ), GENERATE_VERSION, true );
	}

	// IE 8
	if ( function_exists( 'wp_script_add_data' ) ) {
		wp_enqueue_script( 'generate-html5', get_template_directory_uri() . "/js/html5shiv{$suffix}.js", array( 'jquery' ), GENERATE_VERSION, true );
		wp_script_add_data( 'generate-html5', 'conditional', 'lt IE 9' );
	}

	// Add the threaded comments script
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

//REMOVE THOSE FUCKING ANNOYING INLINE STYLES
add_action( 'wp_print_styles', function()
{
    // Remove previous inline style
    wp_styles()->add_data( 'generate-style', 'after', '' );

} );

//Set Pages to Full Width
function generate_add_body_class( $classes ) {
  $classes[] = 'full-width-content';
  return $classes;
}
add_filter( 'body_class','generate_add_body_class' );

//Add Placeholder text to search...
add_action( 'after_setup_theme','tu_change_nav_placeholder' );
function tu_change_nav_placeholder()
{
	remove_action( 'generate_inside_navigation','generate_navigation_search');
	add_action( 'generate_inside_navigation','tu_navigation_search', 5 );
}

function tu_navigation_search()
{
	if ( function_exists( 'generate_get_setting' ) && 'enable' !== generate_get_setting( 'nav_search' ) )
		return;

	?>
	<form method="get" class="search-form navigation-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<input type="search" placeholder="YOUR PLACEHOLDER HERE" class="search-field" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" title="<?php _ex( 'Search', 'label', 'generatepress' ); ?>">
	</form>
	<?php
}

// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
if (!(function_exists('figjam_add_slug_to_body_class'))) {
    function figjam_add_slug_to_body_class($classes)
    {
        global $post;
        if (is_home()) {
            $key = array_search('blog', $classes);
            if ($key > -1) {
                unset($classes[$key]);
            }
        } elseif (is_page()) {
            $classes[] = sanitize_html_class($post->post_name);
        } elseif (is_singular()) {
            $classes[] = sanitize_html_class($post->post_name);
        }
        return $classes;
    }
}

// Add Post thumbnail to admin
if (!(function_exists('figjam_add_post_thumbnail_column'))) {
    function figjam_add_post_thumbnail_column($cols)
    {
        $cols['figjam_post_thumb'] = __('Featured Image', 'figjam');
        return $cols;
    }
}
add_filter('manage_posts_columns', 'figjam_add_post_thumbnail_column', 5);
add_filter('manage_pages_columns', 'figjam_add_post_thumbnail_column', 5);

// Display Post Thumbnail in Admin
if (!(function_exists('figjam_display_post_thumbnail_column'))) {
    function figjam_display_post_thumbnail_column($col, $id)
    {
        switch ($col) {
            case 'figjam_post_thumb':
                if (function_exists('the_post_thumbnail')) {
                    echo the_post_thumbnail('figjam-admin-list-thumb');
                } else {
                    echo 'Not supported in theme';
                }
                break;
        }
    }
}
add_action('manage_posts_custom_column', 'figjam_display_post_thumbnail_column', 5, 2);
add_action('manage_pages_custom_column', 'figjam_display_post_thumbnail_column', 5, 2);

// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var)
{
    return is_array($var) ? array() : '';
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist)
{
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

// Remove the width and height attributes from inserted images
function remove_width_attribute($html)
{
    $html = preg_replace('/(width|height)="\d*"\s/', "", $html);
    return $html;
}

// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style()
{
    global $wp_widget_factory;

    if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
        remove_action('wp_head', array(
            $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
            'recent_comments_style'
        ));
    }
}

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function figjamwp_pagination()
{
    global $wp_query;
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages
    ));
}

// Custom Excerpts
// Create 20 Word Callback for Index page Excerpts, call using figjamwp_excerpt('figjamwp_index');
function figjamwp_index($length)
{
    return 20;
}

// Create 40 Word Callback for Custom Post Excerpts, call using figjamwp_excerpt('figjamwp_custom_post');
function figjamwp_custom_post($length)
{
    return 40;
}

// Create the Custom Excerpts callback
function figjamwp_excerpt($length_callback = '', $more_callback = '')
{
    global $post;
    if (function_exists($length_callback)) {
        add_filter('excerpt_length', $length_callback);
    }
    if (function_exists($more_callback)) {
        add_filter('excerpt_more', $more_callback);
    }
    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = '<p>' . $output . '</p>';
    echo $output;
}

// Custom View Article link to Post
function figjam_blank_view_article($more)
{
    global $post;
    return '... <a class="view-article" href="' . get_permalink($post->ID) . '">' . __('View Article', 'figjam') . '</a>';
}

// Remove Admin bar
function remove_admin_bar()
{
    return false;
}

// Remove 'text/css' from our enqueued stylesheet
function figjam_style_remove($tag)
{
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions($html)
{
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}

// Custom Gravatar in Settings > Discussion
function figjamgravatar($avatar_defaults)
{
    $myavatar = get_template_directory_uri() . '/img/gravatar.jpg';
    $avatar_defaults[$myavatar] = "Custom Gravatar";
    return $avatar_defaults;
}

// Threaded Comments
function enable_threaded_comments()
{
    if (!is_admin()) {
        if (is_singular() and comments_open() and (get_option('thread_comments') == 1)) {
            wp_enqueue_script('comment-reply');
        }
    }
}

// Custom Comments Callback
function figjamcomments($comment, $args, $depth)
{
    $GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);

    if ('div' == $args['style']) {
        $tag = 'div';
        $add_below = 'comment';
    } else {
        $tag = 'li';
        $add_below = 'div-comment';
    } ?>
    <!-- heads up: starting < for the html tag (li or div) in the next line: -->
    <<?php echo $tag ?> <?php comment_class(empty($args['has_children']) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
    <?php if ('div' != $args['style']) : ?>
    <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
    <?php endif; ?>
    <div class="comment-author vcard">
    <?php if ($args['avatar_size'] != 0) {
        echo get_avatar($comment, $args['avatar_size']);
    }?>
    <?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
    </div>
<?php if ($comment->comment_approved == '0') : ?>
    <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
    <br />
<?php endif; ?>

    <div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)) ?>">
        <?php
            printf(__('%1$s at %2$s'), get_comment_date(), get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'), '  ', ''); ?>
    </div>

    <?php comment_text() ?>

    <div class="reply">
    <?php comment_reply_link(array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
    </div>
    <?php if ('div' != $args['style']) : ?>
    </div>
    <?php endif; ?>
<?php
}


function disable_wp_emojicons()
{

  // all actions related to emojis
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');


  // filter to remove TinyMCE emojis
    add_filter('tiny_mce_plugins', 'disable_emojicons_tinymce');
  //remove DNS prefetch for Emoji
    add_filter('emoji_svg_url', '__return_false');
}

function disable_emojicons_tinymce($plugins)
{
    if (is_array($plugins)) {
        return array_diff($plugins, array( 'wpemoji' ));
    } else {
        return array();
    }
}
