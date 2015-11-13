<?php

namespace Roots\Sage\Shortcodes;
use Roots\Sage\Utils;
use Roots\Sage\Extras;

/**
 * Slider shortcode
 */
add_shortcode( 'slider', __NAMESPACE__.'\\slider_init' );
function slider_init( $attr ){
    $defaults = array (
        "animation"  => 'fade',
        "interval"   => false,
        "parallax"   => false,
        "pause"      => false,
        "wrap"       => false,
        "keyboard"   => false,
        "arrows"     => false,
        "bullets"    => false,
        "fullscreen" => false,
    );
    $atts = wp_parse_args( $atts, $defaults );

    if( isset($GLOBALS['carousel_count']) )
      $GLOBALS['carousel_count']++;
    else
      $GLOBALS['carousel_count'] = 0;

    global $wp_query;
    $page_ID = $wp_query->queried_object->ID;
    $prefix = 'sage_slider_';
    $slides = get_post_meta( $page_ID, $prefix .'group', true );

    if($slides){

        $animation   = get_post_meta( $page_ID, $prefix .'animation', true );
        $animation   = $animation ? $animation : $atts['animation'];
        $parallax    = get_post_meta( $page_ID, $prefix .'parallax', true );
        $parallax    = $parallax ? $parallax : $atts['parallax'];
        $pause       = get_post_meta( $page_ID, $prefix .'hover', true );
        $pause       = $pause ? $pause : $atts['pause'];
        $wrap        = get_post_meta( $page_ID, $prefix .'wrap', true );
        $wrap        = $wrap ? $wrap : $atts['wrap'];
        $keyboard    = get_post_meta( $page_ID, $prefix .'keyboard', true );
        $keyboard    = $keyboard ? $keyboard : $atts['keyboard'];
        $arrows      = get_post_meta( $page_ID, $prefix .'arrows', true );
        $arrows      = $arrows ? $arrows : $atts['arrows'];
        $bullets     = get_post_meta( $page_ID, $prefix .'bullets', true );
        $bullets     = $bullets ? $bullets : $atts['bullets'];
        $fullscreen  = get_post_meta( $page_ID, $prefix .'fullscreen', true );
        $fullscreen  = $fullscreen ? $fullscreen : $atts['fullscreen'];
        $interval    = get_post_meta( $page_ID, $prefix .'interval', true );
        $interval    = $interval ? $interval : $atts['interval'];
        $height      = get_post_meta( $page_ID, $prefix .'height', true );
        $height      = $height ? $height : $atts['height'];

        $div_class   = 'row carousel carousel-inline'
            . (($animation === 'fade') ? ' slide carousel-fade' : ' slide')
            . ($fullscreen ? ' carousel-fullscreen' : '')
            . ($progress ? ' carousel-progress' : '');
        $inner_class = 'carousel-inner';
        $id          = 'custom-carousel-'. $GLOBALS['carousel_count'];

        $indicators = array();
        $items = array();

        $i = -1;

        foreach($slides as $slide):
            $i++;

            $image_obj = wp_get_attachment_image_src($slide['image_id'], 'slider');
            $image_original = preg_replace("/-\d+x\d+/", "$2", $image_obj[0]);;
            $target = $slide['new_tab'] ? 'target="_blank"' : '';

            $image = sprintf('%s<img src="%s" alt="" >%s',
                $slide['link_url'] ? '<a href="' . $slide['link_url'] . '" '. $target .'>' : '',
                $image_obj[0],
                $slide['link_url'] ? '</a>' : ''
            );

            $item_style = sprintf('%s',
                sprintf('background-image: url(%s); background-attachment: %s;',
                    $image_obj[0],
                    $parallax ? 'fixed' : 'scroll'
                )
            );

            if($slide['title_text']){
                $title_style = '
                    color: '. $slide['title_color'] .';
                    -webkit-animation-delay: '. $slide['title_anim_delay'] .'s;
                    animation-delay: '. $slide['title_anim_delay'] .'s;
                    -webkit-animation-duration: '. $slide['title_anim_duration'] .'s;
                    animation-duration: '. $slide['title_anim_duration'] .'s;
                ';
                $title_html = '<h3 class="slide-title" data-animated="true" data-animation="'. $slide['title_anim'] .'" style="'
                    . $title_style .'">'
                    . $slide['title_text'] . '</h3>';
            }

            if($slide['caption_text']){
                $caption_style = '
                    color: '. $slide['caption_color'] .';
                    -webkit-animation-delay: '. $slide['caption_anim_delay'] .'s;
                    animation-delay: '. $slide['caption_anim_delay'] .'s;
                    -webkit-animation-duration: '. $slide['caption_anim_duration'] .'s;
                    animation-duration: '. $slide['caption_anim_duration'] .'s;
                ';
                $caption_html = '<div class="slide-caption" data-animated="true" data-animation="'. $slide['caption_anim'] .'" style="'
                    . $caption_style .'"><p>'
                    . $slide['caption_text'] . '</p></div>';
            }

            $overlay_style = '
                -webkit-animation-delay: ' . $slide['overlay_anim_delay'] . 's;
                animation-delay: ' . $slide['overlay_anim_delay'] . 's;
                -webkit-animation-duration: ' . $slide['overlay_anim_duration'] . 's;
                animation-duration: ' . $slide['overlay_anim_duration'] . 's;
            ';
            $overlay_inner_style = '
                background-color:' . $slide['overlay_color'] . ';
                opacity: ' . $slide['overlay_opacity'] . ';
            ';
            $overlay_html = '<span data-animated="true" data-animation="' . $slide['overlay_anim'] . '" style="' . $overlay_style . '"><span style="' . $overlay_inner_style . '"></span></span>';

            if($slide['show_caption']){
                $caption = sprintf(
                    '<div class="carousel-caption container %s %s">'
                        .'<div>'
                            .'<div>'
                                .'<div class="text-wrapper" style="%s">%s%s%s</div>'
                            .'</div>'
                        .'</div>'
                    .'</div>',
                    'align-'.$slide['align'],
                    'valign-'.$slide['valign'],
                    'max-width: '. $slide['text_width'],
                    $title_html,
                    $caption_html,
                    $overlay_html
                );
            }

            $active_class = ($i == 0) ? ' active' : '';

            $indicators[] = sprintf(
              '<li class="%s" data-target="%s" data-slide-to="%s"></li>',
              $active_class,
              esc_attr( '#' . $id ),
              esc_attr( $i )
            );

            $items[] = sprintf(
              '<div class="%s" style="%s">%s%s</div>',
              'item' . $active_class,
              $item_style,
              $image,
              $caption
          );

        endforeach;

        return sprintf(
          '<div class="%s" id="%s" data-ride="carousel" %s%s%s%s%s>'
              . '%s<div class="%s" style="height: %s;">%s</div>%s</div>',
          esc_attr( $div_class ),
          esc_attr( $id ),
          ( $parallax )   ? ' data-type="parallax"' : ' data-type="false"',
          ( $interval )   ? ' data-interval="'. $interval * 1000 .'"' : ' data-interval="false"',
          ( $pause )      ? ' data-pause="hover"' : ' data-pause="false"',
          ( $wrap )       ? ' data-wrap="true"' : ' data-wrap="false"',
          ( $keyboard )   ? ' data-keyboard="true"' : ' data-keyboard="false"',
          ( $bullets )    ? '<ol class="carousel-indicators">' . implode( $indicators ) . '</ol>' : '',
          ( $inner_class ),
          $height,
          implode($items),
          ( $arrows ) ? sprintf( '%s%s',
              '<a class="left carousel-control"  href="' . esc_url( '#' . $id ) . '" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>',
              '<a class="right carousel-control" href="' . esc_url( '#' . $id ) . '" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>'
          ) : ''
        );
    }
}

/**
 * Socials
 */

add_shortcode( 'socials', __NAMESPACE__.'\\socials_init' );
function socials_init( $attr ){
    $defaults = array (
        'label' => false
    );
    $atts = wp_parse_args( $atts, $defaults );

    global $redux_demo;
    $options = $redux_demo;

    $socials = $options['socials'];

    if($socials){
        $buffer = '<span class="socials">';
        $buffer .= $atts['label'] ? '<span>'. $atts['label'] .'</span>' : '';

        foreach( $socials as $key => $value ){
            $buffer .= $value ? '<a href="'. $value . '" target="_blank"><i class="fa fa-'. strtolower($key) .'"></i></a>' : '';
        }
        $buffer .= '</span>';
        return $buffer;
    }
}

/**
  * Vertical Tabs
  *
  */
add_shortcode( 'tabs_vertical', __NAMESPACE__.'\\bs_tabs_vertical' );
function bs_tabs_vertical( $atts, $content = null ) {
  $defaults = array (
      "type"   => false,
      "xclass" => false,
      "data"   => false,
      "text"   => false,
  );
  $atts = wp_parse_args( $atts, $defaults );

  if( isset( $GLOBALS['tabs_count'] ) )
    $GLOBALS['tabs_count']++;
  else
    $GLOBALS['tabs_count'] = 0;

  $GLOBALS['tabs_default_count'] = 0;

  $ul_class  = 'nav';
  $ul_class .= ( $atts['type'] )     ? ' nav-' . $atts['type'] : ' nav-tabs';
  $ul_class .= ( $atts['xclass'] )   ? ' ' . $atts['xclass'] : '';
  $ul_class .= ' tabs-vertical tabs-left';

  $div_class = 'tab-content';

  $id = 'custom-tabs-'. $GLOBALS['tabs_count'];

  $data_props = $atts['data'];

  $atts_map = Utils\attribute_map( $content );

  // Extract the tab titles for use in the tab widget.
  if ( $atts_map ) {
    $tabs = array();
    $GLOBALS['tabs_default_active'] = true;
    foreach( $atts_map as $check ) {
        if( !empty($check["tab"]["active"]) ) {
            $GLOBALS['tabs_default_active'] = false;
        }
    }

    $i = 0;
    foreach( $atts_map as $tab ) {
      $i++;

      $class  ='';
      $class .= ( !empty($tab["tab"]["active"]) || ($GLOBALS['tabs_default_active'] && $i == 1) ) ? 'active' : '';
      $class .= ( !empty($tab["tab"]["xclass"]) ) ? ' ' . $tab["tab"]["xclass"] : '';

      $tabs[] = sprintf(
        '<li%s><a href="#%s" data-toggle="tab" aria-expanded="%s">%s</a></li>',
        ( !empty($class) ) ? ' class="' . $class . '"' : '',
        'custom-tab-' . $GLOBALS['tabs_count'] . '-' . md5($tab["tab"]["title"]),
        ($i == 1) ? 'true' : 'false',
        $tab["tab"]["title"]
      );
    }
  }
  return sprintf(
    '<div class="row"><div class="col-sm-4">%s<ul class="%s" id="%s"%s>%s</ul></div><div class="col-sm-8"><div class="%s">%s</div></div></div>',
    sprintf('<h4 class="sidebar-title" style="text-align: right">%s</h4>', $atts['text']),
    esc_attr( $ul_class ),
    esc_attr( $id ),
    ( $data_props ) ? ' ' . $data_props : '',
    ( $tabs )  ? implode( $tabs ) : '',
    esc_attr( $div_class ),
    do_shortcode( $content )
  );
}


/**
  * Products
  */
add_shortcode( 'products', __NAMESPACE__.'\\get_product_tabs' );
function get_product_tabs( $atts, $content = null ) {
    $defaults = array (
        'taxonomy' => 'product_category',
        'columns'  => '4',
        'size'     => 'thumbnail'
    );
    $atts = wp_parse_args( $atts, $defaults );

    $args = array(
        'post_type' => 'product',
        'numberposts' => '-1',
    );
    $products = get_posts( $args );

    if($products){
        $html = '[tabs_vertical type="tabs" text="'. __('All Products', 'sage') .'"]';
        $term_array = array();
        $i = 0;

        foreach ($products as $product): $i++;
            $terms = get_the_terms( $product->ID, $atts['taxonomy'] );

            foreach($terms as $term){
                if(empty($term_array[$term->term_id])){
                    $term_array[$term->name] = $term;
                }
            }

        endforeach;

        ksort($term_array);

        foreach ($term_array as $term){
            $args['tax_query'] = array(
                array(
                    'taxonomy' => $atts['taxonomy'],
                    'field' => 'id',
                    'terms' => $term->term_id,
                    'include_children' => false
                )
            );

            $tab_products = get_posts( $args );
            $html .= '[tab title="'. $term->name .'" fade="true"]';
            $thumb_ids_array = array();

            foreach( $tab_products as $product ){
                $thumb_ids_array[] = get_post_thumbnail_id( $product->ID );
            }

            $html .= '<h2 class="term-title">'. $term->name .'</h2>';
            $html .= do_shortcode('[gallery link="file" size="'. $atts['size'] .'" columns="'. $atts['columns'] .'" ids="'. join(',', $thumb_ids_array) .'"]');
            $html .= '[/tab]';
        }

        $html .= '[/tabs_vertical]';

        return do_shortcode($html);

    }
}

/**
  * Section
  */
add_shortcode( 'section', __NAMESPACE__.'\\get_section' );
function get_section( $atts, $content = null ) {
    $defaults = array ();
    $atts = wp_parse_args( $atts, $defaults );

    $html = '<div class="layout-section"><div class="inner">'. $content .'</div></div>';

    return do_shortcode($html);
}

/**
  * Container
  */
add_shortcode( 'container', __NAMESPACE__.'\\get_container' );
function get_container( $atts, $content = null ) {
    $defaults = array ();
    $atts = wp_parse_args( $atts, $defaults );

    $html = '<div class="container">'. $content .'</div>';

    return do_shortcode($html);
}

/**
  * Clear
  */
add_shortcode( 'clearfix', __NAMESPACE__.'\\get_clearfix' );
function get_clearfix( $atts, $content = null ) {
    $defaults = array ();
    $atts = wp_parse_args( $atts, $defaults );

    $html = '<div class="clearfix"></div>';

    return do_shortcode($html);
}

/**
  * Parallax Section
  */
add_shortcode( 'parallax', __NAMESPACE__.'\\get_parallax_section' );
function get_parallax_section( $atts, $content = null ) {
    $defaults = array (
        'section_class'   => 'parallax-section',
        'inner_class'     => 'parallax-inner',
        'layer_class'     => 'parallax-layer'
    );
    $atts = wp_parse_args( $atts, $defaults );

    global $wp_query;
    $page_ID = $wp_query->queried_object->ID;

    $prefix = 'sage_parallax_';
    $data_pref = 'data-youtube_video_';
    $parallax_height = get_post_meta( $page_ID, $prefix .'height', true );
    $layers = get_post_meta( $page_ID, $prefix .'group', true );

    function get_parallax_layers_html($layers){

        $i = 0;
        $html = '';

        foreach ($layers as $layer){

            $i++;

            $html .= sprintf('<div %s %s %s></div>',
                sprintf('class="%s %s"',
                    'parallax-layer',
                    'col-xs-12'
                ),
                sprintf('style="%s %s"',
                    'background-image: url( '. esc_attr($layer['background_image']) .' );',
                    'z-index: '. esc_attr($layer['z_index']) .';'
                ),
                sprintf('%s %s %s',
                    'data-ride="parallax"',
                    'data-direction="'. esc_attr($layer['direction']) .'"',
                    'data-speed="'. esc_attr($layer['speed']) .'"'
                )
            );

            if (count($layers) == $i) {
                 return $html;
            }
        }
    }

    if($layers){
        $parallax_layers_html = get_parallax_layers_html($layers);

        return sprintf('<div %s %s><div %s>%s</div></div>',
            sprintf('class="%s row"',
                $atts['section_class']
            ),
            sprintf('style="padding-bottom: %s;"',
                esc_attr($parallax_height)
            ),
            sprintf('class="%s"',
                $atts['inner_class']
            ),
            $parallax_layers_html
        );
    }
}

/**
  * Background Video
  */
add_shortcode( 'background_video', __NAMESPACE__.'\\get_background_video' );
function get_background_video( $atts, $content = null ) {
    $defaults = array (
        'id' => '1'
    );
    $atts = wp_parse_args( $atts, $defaults );

    global $wp_query;
    $page_ID = $wp_query->queried_object->ID;

    $prefix = 'sage_background_video_';
    $data_pref = 'data-youtube_video_';
    $videos = get_post_meta( $page_ID, $prefix .'group', true );
    $section_id = $atts['id'] -1;
    $video = $videos[$section_id];

    if($video['id']) return sprintf('<div %s %s %s %s>%s %s</div>',
        'id="background-video-'. $atts['id'] .'"',
        sprintf('class="background-video %s %s %s"',
            $video['fitbg'] ? 'fit-background' : 'fit-container',
            $video['expand'] ? 'expand' : '',
            $video['controls'] ? 'enable-controls' : ''
        ),
        sprintf('%s %s %s %s %s %s %s %s %s',
            $data_pref .'id="'. esc_attr($video['id']) .'"',
            $data_pref .'fitbg="'. esc_attr($video['fitbg']) .'"',
            $data_pref .'ratio="'. esc_attr($video['ratio']) .'"',
            $data_pref .'start="'. esc_attr($video['start']) .'"',
            $data_pref .'pause="'. esc_attr($video['pause']) .'"',
            $data_pref .'repeat="'. esc_attr($video['repeat']) .'"',
            $data_pref .'mute="'. esc_attr($video['mute']) .'"',
            $data_pref .'expand="'. esc_attr($video['expand']) .'"',
            $data_pref .'controls="'. esc_attr($video['controls']) .'"'
        ),
        sprintf('style="%s %s"',
            'background-image: url('. esc_attr($video['fallback_image']) .');',
            $video['expand'] ? '' : 'padding-bottom: '. esc_attr($video['height']) .';'
        ),
        do_shortcode($content),
        sprintf('<style>.ytplayer-shield{%s}</style>',
            sprintf('%s %s',
                'background-color: '. esc_attr($video['shield_color']) .';',
                'opacity: '. esc_attr($video['shield_opacity']) .';'
            )
        )
    );
}

/**
  * Case studies
  */
add_shortcode( 'case_studies', __NAMESPACE__.'\\get_case_studies' );
function get_case_studies( $atts, $content = null ) {
    $defaults = array ();
    $atts = wp_parse_args( $atts, $defaults );

    global $wp_query;
    $page_ID = $wp_query->queried_object->ID;

    $prefix = 'sage_case_studies_';
    $title = get_post_meta( $page_ID, $prefix .'title', true );
    $posts = get_post_meta( $page_ID, $prefix .'posts', true );
    $url = get_post_meta( $page_ID, $prefix .'url', true );

    function get_case_studies_posts_html($post_ids){

        $i = 0;
        $html = '';

        foreach ($post_ids as $id){

            $i++;
            $post = get_post($id);
            setup_postdata($post);

            $html .= sprintf('<div class="case-study"><a href="%s">%s</a>%s</div>',
                get_the_permalink($id),
                get_the_post_thumbnail($id, 'case_studies'),
                Extras\excerpt(20)
            );

            if (count($post_ids) == $i) {
                 return $html;
            }
        }
        wp_reset_postdata();

    }

    $case_studies_posts_html = get_case_studies_posts_html($posts);

    if($posts) return sprintf('<div class="case-studies">%s%s%s</div>',
        '<h3 class="title text-center">'. __($title, 'sage') .'</h3>',
        sprintf('<div class="inner">%s</div>',
            $case_studies_posts_html
        ),
        sprintf('<p class="text-center"><a href="%s" class="btn btn-default">%s</a></p>',
            $url,
            __('View All', 'sage'). ' ' . __($title, 'sage')
        )
    );

}

/**
  * Case studies page
  */
add_shortcode( 'case_studies_page', __NAMESPACE__.'\\get_case_studies_page' );
function get_case_studies_page( $atts, $content = null ) {
    $defaults = array ();
    $atts = wp_parse_args( $atts, $defaults );

    $args=array(
      'post_type' => array('animations', 'interactive'),
      'post_status' => 'publish',
      'posts_per_page' => -1
    );

    $my_query = new \WP_Query($args);
    if( $my_query->have_posts() ) {
        ob_start();
        while ($my_query->have_posts()) : $my_query->the_post();
          get_template_part('templates/content', get_post_type() != 'post' ? get_post_type() : get_post_format());
        endwhile;
    }
    wp_reset_query();
    return ob_get_clean();

}
