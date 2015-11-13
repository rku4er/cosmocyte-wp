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
add_filter('excerpt_more', __NAMESPACE__ . '\\excerpt_more');
function excerpt_more() {
  return ' &hellip; <a href="' . get_permalink() . '">' . __('More', 'sage') . '</a>';
}
function excerpt($limit=40) {
  $content = explode(' ', get_the_content(), $limit);
  if (count($content)>=$limit) {
    array_pop($content);
    $content = implode(" ",$content).'&hellip; <a href="' . get_permalink() . '">' . __('More', 'sage') . '</a>';
  } else {
    $content = implode(" ",$content);
  }
  $content = preg_replace('/\[.+\]/','', $content);
  $content = apply_filters('the_content', $content);
  $content = str_replace(']]>', ']]&gt;', $content);
  return $content;
}

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
add_action('pre_get_posts', __NAMESPACE__ . '\\search_filter');
function search_filter($query) {
  if ( !is_admin() && $query->is_main_query() ) {
    if ($query->is_search) {
      $query->set('post_type', array('post'));
    }
  }
}

/**
 * Login Image
 */
add_action( 'login_enqueue_scripts', __NAMESPACE__ . '\\my_login_logo' );
function my_login_logo() { ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/dist/images/backend-logo.png);
            background-size: contain;
        }
        .login h1 a {
            height: 62px !important;
            width: 320px !important;
        }
    </style>
<?php }


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
        $query->set('post_type', $post_type);
        return $query;
    }
}

/**
 * Set default term on publish
 */
add_action( 'publish_product', __NAMESPACE__ . '\\set_prop_tax' );
function set_prop_tax($post_ID){
    $type = 'product_category';
    if(!has_term('',$type,$post_ID)){
        $term = get_term_by('slug', 'uncategorized', $type);
        wp_set_object_terms($post_ID, $term->term_id, $type);
    }
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
