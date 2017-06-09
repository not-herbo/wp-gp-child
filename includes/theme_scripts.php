<?php
/**
 * Figjam Load Scripts
 * Properly Enqueues Scripts & Styles for the theme
 */
if (!(function_exists('figjam_load_scripts'))) {
    function figjam_load_scripts($debug = true)
    {
        if ($debug || defined('SCRIPT_DEBUG') && true === SCRIPT_DEBUG) {
            // DEVELOPMENT STYLES
      wp_enqueue_style(
      'figjam-style',
      WPGPC_URL . "/assets/css/project.css",
      array(),
      WPGPC_VERSION
      );

      // DEVELOPMENT SCRIPTS
      // jQuery
      wp_deregister_script('jquery');
            wp_enqueue_script(
        'jquery',
        WPGPC_URL . "/bower_components/jquery/dist/jquery.js",
        array(),
        '3.2.1',
        true
      );

      wp_enqueue_script(
        'figjam-script',
        WPGPC_URL . "/assets/js/project.js",
        array(),
        WPGPC_VERSION,
        true
      );
        } else {

    // PRODUCTION STYLES - MINIFIED

      wp_enqueue_style(
      'figjam-style-min',
      WPGPC_URL . "/assets/css/project.min.css",
      array(),
      WPGPC_VERSION
      );

    //PRODUCTION SCRIPTS - MINIFIED ALL
    // jQuery
    wp_deregister_script('jquery');
        wp_enqueue_script(
            'jquery',
            "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js",
            array(),
            '3.2.1',
            true
        );

      wp_enqueue_script(
        'figjam-script-min',
        WPGPC_URL . "/assets/js/project.min.js",
        array(),
        WPGPC_VERSION,
        true
      );
        }

    //Conditionally Enqueue < IE 9 Scripts
    wp_enqueue_script(
      'figjam_selectivizr',
      "https://cdnjs.cloudflare.com/ajax/libs/selectivizr/1.0.2/selectivizr-min.js",
      array(),
      '1.0.2',
      false
    );
        wp_script_add_data('figjam_selectivizr', 'conditional', 'lt IE 9');

        wp_enqueue_script(
      'figjam_html5shiv',
      "https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js",
      array(),
      '3.7.3',
      false
    );
        wp_script_add_data('figjam_html5shiv', 'conditional', 'lt IE 9');

        wp_enqueue_script(
      'figjam_respond',
      "https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js",
      array(),
      '1.4.2',
      false
    );
        wp_script_add_data('figjam_respond', 'conditional', 'lt IE 9');
    }
    add_action('wp_enqueue_scripts', 'figjam_load_scripts', 110);
}

// Load figjam Blank conditional scripts
// function figjam_conditional_scripts()
// {
//     if (is_page('pagenamehere')) {
//         // Conditional script(s)
//         wp_register_script('scriptname', get_template_directory_uri() . '/js/scriptname.js', array('jquery'), '1.0.0');
//         wp_enqueue_script('scriptname');
//     }
// }
