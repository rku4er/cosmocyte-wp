<?php

namespace Roots\Sage\Extras;

use Roots\Sage\Config;

/**
 * Add <body> classes
 */
function body_class($classes) {
  // Add page slug if it doesn't exist
  if (is_single() || is_page() && !is_front_page()) {
    if (!in_array(basename(get_permalink()), $classes)) {
      $classes[] = basename(get_permalink());
    }
  }

  // Add class if sidebar is active
  if (Config\display_sidebar()) {
    $classes[] = 'sidebar-primary';
  }

  return $classes;
}
add_filter('body_class', __NAMESPACE__ . '\\body_class');

/**
 * Clean up the_excerpt()
 */
function excerpt_more() {
  return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'sage') . '</a>';
}
add_filter('excerpt_more', __NAMESPACE__ . '\\excerpt_more');

/**
 * Filtering the Wrapper: Custom Post Types
 */
add_filter('sage/wrap_base', __NAMESPACE__ . '\\sage_wrap_base_cpts');

function sage_wrap_base_cpts($templates) {
    $cpt = get_post_type();
    if ($cpt) {
       array_unshift($templates, __NAMESPACE__ . 'base-' . $cpt . '.php');
    }
    return $templates;
  }

/**
 * Search Filter
 */
function search_filter($query) {
  if ( !is_admin() && $query->is_main_query() ) {
    if ($query->is_search) {
      $query->set('post_type', array('post'));
    }
  }
}

add_action('pre_get_posts', __NAMESPACE__ . '\\search_filter');

/**
 * Login Image
 */
function my_login_logo() { ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/dist/images/backend-logo.png);
            background-size: contain;
        }
        .login h1 a {
            height: 25px !important;
            width: 320px !important;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', __NAMESPACE__ . '\\my_login_logo' );


/**
 * Expand wp query
 */
add_filter('pre_get_posts', __NAMESPACE__ . '\\query_post_type');
function query_post_type($query) {
    if(is_category() || is_tag()) {
        $post_type = get_query_var('post_type');
        if($post_type)
            $post_type = $post_type;
        else
            $post_type = array('post', 'property', 'nav_menu_item');
        $query->set('post_type',$post_type);
        return $query;
    }
}

/**
 * Set default term on publish
 */
add_action( 'publish_property', __NAMESPACE__ . '\\set_prop_tax' );
function set_prop_tax($post_ID){
    $type = 'property_category';
    if(!has_term('',$type,$post_ID)){
        $term = get_term_by('slug', 'uncategorized', $type);
        wp_set_object_terms($post_ID, $term->term_id, $type);
    }
}

/**
 * Dequeue bootstrap 3 shortcodes scripts
 */
add_action( 'the_post', __NAMESPACE__ . '\\dequeue_bootstrap_scripts', 9999 );
function dequeue_bootstrap_scripts($post_ID){
    wp_dequeue_script( 'bootstrap-shortcodes-tooltip' );
    wp_dequeue_script( 'bootstrap-shortcodes-popover' );
}


/**
 * Gravity Forms Field Choice Markup Pre-render
 */

add_filter( 'gform_field_choice_markup_pre_render', __NAMESPACE__ . '\\choice_render', 10, 4 );
function choice_render($choice_markup, $choice, $field, $value){
    if ( $field->get_input_type() == 'radio' || 'checkbox' ) {
        $choice_markup = preg_replace("/(<li[^>]*>)\s*(<input[^>]*>)\s*(<label[^>]*>)\s*([\w\s]*<\/label>\s*<\/li>)/", '$1$3$2$4', $choice_markup);
        return $choice_markup;
    }
    return $choice_markup;
}

/**
 * Video background
 */

add_action( 'get_visual', __NAMESPACE__ . '\\page_video_bg', 9999 );
function page_video_bg(){
    global $wp_query;
    $page_ID = $wp_query->queried_object->ID;
    $prefix = 'sage_page_options_';
    $data_prefix = 'data-youtube_video_';
    $bg_image = get_post_meta( $page_ID, $prefix .'bg_image', true );

    $shield_color = get_post_meta( $page_ID, $prefix .'bg_shield_color', true );
    $shield_opacity = get_post_meta( $page_ID, $prefix .'bg_shield_opacity', true );
    $video_height = get_post_meta( $page_ID, $prefix .'bg_video_height', true );
    $video_ratio = get_post_meta( $page_ID, $prefix .'bg_video_ratio', true );
    $video_fitbg = get_post_meta( $page_ID, $prefix .'bg_video_fitbg', true );
    $video_repeat = get_post_meta( $page_ID, $prefix .'bg_video_repeat', true );
    $video_mute = get_post_meta( $page_ID, $prefix .'bg_video_mute', true );
    $video_pause = get_post_meta( $page_ID, $prefix .'bg_video_pause', true );
    $video_start = get_post_meta( $page_ID, $prefix .'bg_video_start', true );
    $video_id = get_post_meta( $page_ID, $prefix .'bg_video_id', true );

    if($video_id) echo sprintf('<div class="background-video %s" %s style="%s"></div>%s',
        $video_fitbg ? esc_attr('fit-background') : 'fit-container',
        sprintf('%s %s %s %s %s %s %s',
            $data_prefix .'id="'. esc_attr($video_id) .'"',
            $data_prefix .'fitbg="'. esc_attr($video_fitbg) .'"',
            $data_prefix .'ratio="'. esc_attr($video_ratio) .'"',
            $data_prefix .'start="'. esc_attr($video_start) .'"',
            $data_prefix .'pause="'. esc_attr($video_pause) .'"',
            $data_prefix .'repeat="'. esc_attr($video_repeat) .'"',
            $data_prefix .'mute="'. esc_attr($video_mute) .'"'
        ),
        sprintf('%s %s',
            'background-image: url('. esc_attr($bg_image) .');',
            'padding-bottom: '. esc_attr($video_height) .';'
        ),
        sprintf('<style>.ytplayer-shield{%s}</style>',
            sprintf('%s %s',
                'background-color: '. esc_attr($shield_color) .';',
                'opacity: '. esc_attr($shield_opacity) .';'
            )
        )
    );
}

/**
 * Add page specific CSS
 */
add_action( 'get_footer', __NAMESPACE__ . '\\page_specific_css', 9999 );
function page_specific_css(){
    global $wp_query;
    $page_ID = $wp_query->queried_object->ID;
    $prefix = 'sage_page_options_';
    $page_css = get_post_meta( $page_ID, $prefix .'css', true );
    echo $page_css ? '<style>' . $page_css . '</style>' : '';
}


/**
 * Custom HTML
 */
add_action( 'get_header', __NAMESPACE__ . '\\custom_html', 999 );
function custom_html(){
    global $redux_demo;
    $editor_content = $redux_demo['custom-html-editor'];
    echo $editor_content ? $editor_content : '';
}
/**
 * Custom CSS
 */
add_action( 'get_header', __NAMESPACE__ . '\\custom_css', 999 );
function custom_css(){
    global $redux_demo;
    $editor_content = $redux_demo['custom-css-editor'];
    echo $editor_content ? '<style>'. $editor_content .'</style>' : '';
}

/**
 * Custom JS
 */
add_action( 'get_footer', __NAMESPACE__ . '\\custom_js', 999 );
function custom_js(){
    global $redux_demo;
    $editor_content = $redux_demo['custom-js-editor'];
    echo $editor_content ? '<script>'. $editor_content .'</script>' : '';
}


/**
 *  Favicon
 */
add_action('wp_head', __NAMESPACE__ . '\\site_favicon');
function site_favicon() {
    global $redux_demo;
    $options = $redux_demo;
    $favicon = $options['favicon']['url'] ? $options['favicon']['url'] : get_template_directory_uri().'/dist/images/favicon.ico';
    echo '<link rel="shortcut icon" href="'. $favicon .'">';
}

