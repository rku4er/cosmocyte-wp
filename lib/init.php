<?php

namespace Roots\Sage\Init;

use Roots\Sage\Assets;

/**
 * Theme setup
 */
function setup() {
  global $redux_demo;
  $options = $redux_demo;

  // Make theme available for translation
  // Community translations can be found at https://github.com/roots/sage-translations
  load_theme_textdomain('sage', get_template_directory() . '/lang');

  // Enable plugins to manage the document title
  // http://codex.wordpress.org/Function_Reference/add_theme_support#Title_Tag
  add_theme_support('title-tag');

  // Register wp_nav_menu() menus
  // http://codex.wordpress.org/Function_Reference/register_nav_menus
  register_nav_menus([
    'primary_navigation' => __('Primary Navigation', 'sage')
  ]);

  // Add post thumbnails
  // http://codex.wordpress.org/Post_Thumbnails
  // http://codex.wordpress.org/Function_Reference/set_post_thumbnail_size
  // http://codex.wordpress.org/Function_Reference/add_image_size
  add_theme_support('post-thumbnails');
  add_image_size('slider', 1200, 600, true);
  //update_option( 'medium_crop', 1 ); //Turn on image crop at medium size

  // Add post formats
  // http://codex.wordpress.org/Post_Formats
  //add_theme_support('post-formats', ['aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio']);

  // Add HTML5 markup for captions
  // http://codex.wordpress.org/Function_Reference/add_theme_support#HTML5
  add_theme_support('html5', ['caption', 'comment-form', 'comment-list']);

  // Tell the TinyMCE editor to use a custom stylesheet
  add_editor_style(Assets\asset_path('styles/editor-style.css'));

  // Allow shortcode execution in widgets
  add_filter('widget_text', 'do_shortcode');

}
add_action('after_setup_theme', __NAMESPACE__ . '\\setup');

/**
 * Register sidebars
 */
add_action('widgets_init', __NAMESPACE__ . '\\widgets_init');
function widgets_init() {
  register_sidebar([
    'name'          => __('Primary', 'sage'),
    'id'            => 'sidebar-primary',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ]);

  register_sidebar([
    'name'          => __('Footer', 'sage'),
    'id'            => 'sidebar-footer',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ]);
}

/**
 * Work Post Type
 */
 //add_action( 'init', __NAMESPACE__ . '\\create_post_type_product' );
function create_post_type_product() {

  register_post_type( 'product',
    array(
      'labels' => array(
        'name' => __( 'Products' ),
        'singular_name' => __( 'Product' ),
        'add_new' => __( 'Add Product' ),
        'add_new_item' => __( 'Add New Product' ),
      ),
      'rewrite' => array('slug' => __( 'product' )),
      'public' => true,
      'exclude_from_search' => false,
      'has_archive' => true,
      'hierarchical' => true,
      'menu_position' => 5,
      'capability_type' => 'post',
      'can_export' => true,
      'supports' => array(
        'title',
        'editor',
        'thumbnail'
      )
    )
  );

}


// hook into the init action and call create_book_taxonomies when it fires
//add_action( 'init', __NAMESPACE__ . '\\create_product_tax' );
function create_product_tax() {
    register_taxonomy(
        'product_category',
        'product',
        array(
            'label' => __( 'Category' ),
            'hierarchical' => true,
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'product-category',
                'with_front' => false
            )
        )
    );
}

