<?php
/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB2 directory)
 *
 * Be sure to replace all instances of 'sage_' with your project's prefix.
 * http://nacin.com/2010/05/11/in-wordpress-prefix-everything/
 *
 * @category YourThemeOrPlugin
 * @package  Demo_CMB2
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/WebDevStudios/CMB2
 */

/**
 * Get the bootstrap! If using the plugin from wordpress.org, REMOVE THIS!
 */

if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
    require_once dirname( __FILE__ ) . '/cmb2/init.php';
} elseif ( file_exists( dirname( __FILE__ ) . '/CMB2/init.php' ) ) {
    require_once dirname( __FILE__ ) . '/CMB2/init.php';
}

/**
 * Conditionally displays a metabox when used as a callback in the 'show_on_cb' cmb2_box parameter
 *
 * @param  CMB2 object $cmb CMB2 object
 *
 * @return bool             True if metabox should show
 */
function sage_show_if_front_page( $cmb ) {
    // Don't show this metabox if it's not the front page template
    if ( $cmb->object_id !== get_option( 'page_on_front' ) ) {
        return false;
    }
    return true;
}

add_action( 'cmb2_init', __NAMESPACE__ . '\\sage_register_general_options' );
/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_init' hook.
 */
function sage_register_general_options() {

    // Start with an underscore to hide fields from custom fields list
    $prefix = 'sage_page_options_';

    /**
     * Sample metabox to demonstrate each field type included
     */
    $cmb_demo = new_cmb2_box( array(
        'id'            => $prefix . 'metabox',
        'title'         => __( 'General', 'cmb2' ),
        'object_types'  => array( 'page', ),
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true,
        // 'show_on_cb' => 'sage_show_if_front_page', // function should return a bool value
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // true to keep the metabox closed by default
    ) );

    $cmb_demo->add_field( array(
        'name' => __( 'Hide Title', 'cmb2' ),
        'desc' => __( 'Check to hide page title', 'cmb2' ),
        'id'   => $prefix . 'hide_title',
        'type' => 'checkbox',
        // 'repeatable' => true,
    ) );

    $cmb_demo->add_field( array(
        'name' => __( 'Background Image', 'cmb2' ),
        'desc' => __( 'Upload an image or enter an URL', 'cmb2' ),
        'id'   => $prefix . 'bg_image',
        'type' => 'file',
        // 'repeatable' => true,
    ) );

    $cmb_demo->add_field( array(
        'name'          => __( 'Background opacity', 'cmb2' ),
        'desc'          => __( 'Enter value from 0 to 1 eg: 0.1', 'cmb2' ),
        'id'            => $prefix . 'bg_opacity',
        'type'          => 'text_small',
        'default'       => '0.3'
        // 'repeatable' => true,
    ) );

    $cmb_demo->add_field( array(
        'name' => __( 'Page specific CSS', 'cmb2' ),
        'desc' => __( 'Type here your custom styles', 'cmb2' ),
        'id'   => $prefix . 'css',
        'type' => 'textarea_code',
        // 'repeatable' => true,
    ) );

}

add_action( 'cmb2_init', __NAMESPACE__ . '\\sage_register_slider_options' );
/**
 * Hook in and add a metabox to demonstrate repeatable grouped fields
 */
function sage_register_slider_options() {

    $animations = array(
        'bounce' => __('Bounce', 'sage'),
        'flash' => __('Flash'),
        'pulse' => __('Pulse'),
        'rubberBand' => __('RubberBand', 'sage'),
        'shake' => __('Shake', 'sage'),
        'swing' => __('Swing', 'sage'),
        'tada' => __('Tada', 'sage'),
        'wobble' => __('Wobble', 'sage'),
        'bounceIn' => __('bounceIn', 'sage'),
        'bounceInDown' => __('BounceInDown', 'sage'),
        'bounceInLeft' => __('BounceInLeft', 'sage'),
        'bounceInRight' => __('BounceInRight', 'sage'),
        'bounceInUp' => __('BounceInUp', 'sage'),
        'bounceOut' => __('BounceOut', 'sage'),
        'bounceOutDown' => __('BounceOutDown', 'sage'),
        'bounceOutLeft' => __('BounceOutLeft', 'sage'),
        'bounceOutRight' => __('BounceOutRight', 'sage'),
        'bounceOutUp' => __('BounceOutUp', 'sage'),
        'fadeIn' => __('FadeIn', 'sage'),
        'fadeInDown' => __('FadeInDown', 'sage'),
        'fadeInDownBig' => __('FadeInDownBig', 'sage'),
        'fadeInLeft' => __('FadeInLeft', 'sage'),
        'fadeInLeftBig' => __('FadeInLeftBig', 'sage'),
        'fadeInRight' => __('FadeInRight', 'sage'),
        'fadeInRightBig' => __('FadeInRightBig', 'sage'),
        'fadeInUp' => __('FadeInUp', 'sage'),
        'fadeInUpBig' => __('FadeInUpBig', 'sage'),
        'fadeOut' => __('FadeOut', 'sage'),
        'fadeOutDown' => __('FadeOutDown', 'sage'),
        'fadeOutDownBig' => __('FadeOutDownBig', 'sage'),
        'fadeOutLeft' => __('FadeOutLeft', 'sage'),
        'fadeOutLeftBig' => __('FadeOutLeftBig', 'sage'),
        'fadeOutRight' => __('FadeOutRight', 'sage'),
        'fadeOutRightBig' => __('FadeOutRightBig', 'sage'),
        'fadeOutUp' => __('FadeOutUp', 'sage'),
        'fadeOutUpBig' => __('FadeOutUpBig', 'sage'),
        'flip' => __('Flip', 'sage'),
        'flipInX' => __('FlipInX', 'sage'),
        'flipInY' => __('FlipInY', 'sage'),
        'flipOutX' => __('FlipOutX', 'sage'),
        'flipOutY' => __('FlipOutY', 'sage'),
        'lightSpeedIn' => __('LightSpeedIn', 'sage'),
        'lightSpeedOut' => __('LightSpeedOut', 'sage'),
        'rotateIn' => __('RotateIn', 'sage'),
        'rotateInDownLeft' => __('RotateInDownLeft', 'sage'),
        'rotateInDownRight' => __('RotateInDownRight', 'sage'),
        'rotateInUpLeft' => __('RotateInUpLeft', 'sage'),
        'rotateInUpRight' => __('RotateInUpRight', 'sage'),
        'rotateOut' => __('RotateOut', 'sage'),
        'rotateOutDownLeft' => __('RotateOutDownLeft', 'sage'),
        'rotateOutDownRight' => __('RotateOutDownRight', 'sage'),
        'rotateOutUpLeft' => __('RotateOutUpLeft', 'sage'),
        'rotateOutUpRight' => __('rotateOutUpRight', 'sage'),
        'slideInUp' => __('slideInUp', 'sage'),
        'slideInDown' => __('slideInDown', 'sage'),
        'slideInLeft' => __('slideInLeft', 'sage'),
        'slideInRight' => __('slideInRight', 'sage'),
        'slideOutUp' => __('slideOutUp', 'sage'),
        'slideOutDown' => __('slideOutDown', 'sage'),
        'slideOutLeft' => __('slideOutLeft', 'sage'),
        'slideOutRight' => __('slideOutRight', 'sage'),
        'zoomIn' => __('zoomIn', 'sage'),
        'zoomInDown' => __('zoomInDown', 'sage'),
        'zoomInLeft' => __('zoomInLeft', 'sage'),
        'zoomInRight' => __('zoomInRight', 'sage'),
        'zoomInUp' => __('zoomInUp', 'sage'),
        'zoomOut' => __('zoomOut', 'sage'),
        'zoomOutDown' => __('zoomOutDown', 'sage'),
        'zoomOutLeft' => __('zoomOutLeft', 'sage'),
        'zoomOutRight' => __('zoomOutRight', 'sage'),
        'zoomOutUp' => __('zoomOutUp', 'sage'),
        'hinge' => __('hinge', 'sage'),
        'rollIn' => __('rollIn', 'sage'),
        'rollOut' => __('rollOut', 'sage'),
    );

    // Start with an underscore to hide fields from custom fields list
    $prefix = 'sage_slider_';

    /**
     * Repeatable Field Groups
     */
    $cmb_group = new_cmb2_box( array(
        'id'           => $prefix . 'metabox',
        'title'        => __( 'Slider Options', 'cmb2' ),
        'object_types' => array( 'page', ),
    ) );

    $cmb_group->add_field( array(
        'name'    => __( 'Animation', 'cmb2' ),
        'desc'    => __( 'Select slider animation', 'cmb2' ),
        'id'      => $prefix . 'animation',
        'type'    => 'select',
        'options' => array(
            'fade'   => __( 'Fade', 'cmb2' ),
            'slide'  => __( 'Slide', 'cmb2' ),
        ),
    ) );

    $cmb_group->add_field( array(
        'name'    => __( 'Interval', 'cmb2' ),
        'desc' => __( 'Enter numeric value in seconds greater than zero eg: 5', 'cmb2' ),
        'id'      => $prefix . 'interval',
        'type'       => 'text_small',
        'default' => '5'
    ) );

    $cmb_group->add_field( array(
        'name'    => __( 'Height', 'cmb2' ),
        'desc' => __( 'Enter heigth value(px,%,auto)', 'cmb2' ),
        'id'      => $prefix . 'height',
        'type'       => 'text_small',
        'default' => ''
    ) );

    $cmb_group->add_field( array(
        'name' => __( 'Parallax', 'cmb2' ),
        'desc' => __( 'Turn on vertical parallax', 'cmb2' ),
        'id'   => $prefix . 'parallax',
        'type' => 'checkbox',
        'default' => '',
    ) );

    $cmb_group->add_field( array(
        'name' => __( 'Pause', 'cmb2' ),
        'desc' => __( 'Turn on pause on hover', 'cmb2' ),
        'id'   => $prefix . 'hover',
        'type' => 'checkbox',
        'default' => '',
    ) );

    $cmb_group->add_field( array(
        'name' => __( 'Wrap', 'cmb2' ),
        'desc' => __( 'Turn on rotation wrapping', 'cmb2' ),
        'id'   => $prefix . 'wrap',
        'type' => 'checkbox',
        'default' => '',
    ) );

    $cmb_group->add_field( array(
        'name' => __( 'Keyboard', 'cmb2' ),
        'desc' => __( 'Turn on keyboard navigation', 'cmb2' ),
        'id'   => $prefix . 'keyboard',
        'type' => 'checkbox',
        'default' => '',
    ) );

    $cmb_group->add_field( array(
        'name' => __( 'Arrows', 'cmb2' ),
        'desc' => __( 'Turn on prev/next buttons', 'cmb2' ),
        'id'   => $prefix . 'arrows',
        'type' => 'checkbox',
        'default' => '',
    ) );

    $cmb_group->add_field( array(
        'name' => __( 'Bullets', 'cmb2' ),
        'desc' => __( 'Turn on slider bullets', 'cmb2' ),
        'id'   => $prefix . 'bullets',
        'type' => 'checkbox',
        'default' => '',
    ) );

    $cmb_group->add_field( array(
        'name' => __( 'Fullscreen', 'cmb2' ),
        'desc' => __( 'Turn on fullscreen mode(beta)', 'cmb2' ),
        'id'   => $prefix . 'fullscreen',
        'type' => 'checkbox',
        'default' => '',
    ) );

    // $group_field_id is the field id string, so in this case: $prefix . 'demo'
    $group_field_id = $cmb_group->add_field( array(
        'id'          => $prefix . 'group',
        'type'        => 'group',
        'description' => __( 'Here you can add slides to the slider. Please take [slider] shortcode and insert it wherever you want.', 'cmb2' ),
        'options'     => array(
            'group_title'   => __( 'Slide {#}', 'cmb2' ), // {#} gets replaced by row number
            'add_button'    => __( 'Add Another Slide', 'cmb2' ),
            'remove_button' => __( 'Remove Slide', 'cmb2' ),
            'sortable'      => true, // beta
            'closed'     => true, // true to have the groups closed by default
        ),
    ) );

    /**
     * Group fields works the same, except ids only need
     * to be unique to the group. Prefix is not needed.
     *
     * The parent field's id needs to be passed as the first argument.
     */

    $cmb_group->add_group_field( $group_field_id, array(
        'name' => __('Image', 'sage'),
        'id'   => 'image',
        'type' => 'file',
    ) );

    $cmb_group->add_group_field( $group_field_id, array(
        'name' => __('Link URL', 'sage'),
        'id'   => 'link_url',
        'type' => 'text_url',
    ) );

    $cmb_group->add_group_field( $group_field_id, array(
        'name' => __('Open in new tab', 'sage'),
        'id'   => 'new_tab',
        'type' => 'checkbox',
        'default' => '',
    ) );

    $cmb_group->add_group_field( $group_field_id, array(
        'name'    => __( 'Display caption', 'cmb2' ),
        'id'      => 'show_caption',
        'type'    => 'checkbox',
        'default' => '',
    ) );

    $cmb_group->add_group_field( $group_field_id, array(
        'name' => __('Title text', 'sage'),
        'id'   => 'title_text',
        'type' => 'text',
    ) );

    $cmb_group->add_group_field( $group_field_id, array(
        'name' => __('Caption text', 'sage'),
        'description' => __('Write a short description for this slide', 'sage'),
        'id'   => 'caption_text',
        'type' => 'textarea_small',
    ) );

    $cmb_group->add_group_field( $group_field_id, array(
        'name'    => __( 'Text Width', 'cmb2' ),
        'id'      => 'text_width',
        'type'    => 'text_small',
        'default' => '60%',
    ) );

    $cmb_group->add_group_field( $group_field_id, array(
        'name'    => __( 'Align', 'cmb2' ),
        'id'      => 'align',
        'type'    => 'select',
        'options' => array(
            'left'   => __( 'Left', 'cmb2' ),
            'center' => __( 'Center', 'cmb2' ),
            'right'  => __( 'Right', 'cmb2' ),
        ),
    ) );

    $cmb_group->add_group_field( $group_field_id, array(
        'name'    => __( 'Vertical align', 'cmb2' ),
        'id'      => 'valign',
        'type'    => 'select',
        'options' => array(
            'top'   => __( 'Top', 'cmb2' ),
            'middle' => __( 'Middle', 'cmb2' ),
            'bottom'  => __( 'Bottom', 'cmb2' ),
        ),
    ) );

    $cmb_group->add_group_field( $group_field_id, array(
        'name' => __('Title color', 'sage'),
        'id'   => 'title_color',
        'type' => 'colorpicker',
        'default' => '#ffffff',
    ) );

    $cmb_group->add_group_field( $group_field_id, array(
        'name'    => __( 'Title animation', 'cmb2' ),
        'id'      => 'title_anim',
        'type'    => 'select',
        'options' => $animations,
        'default' => 'fadeInRight',
    ) );

    $cmb_group->add_group_field( $group_field_id, array(
        'name' => __('Title animation delay', 'sage'),
        'description' => __('Delay value in seconds eg 0.5', 'sage'),
        'id'   => 'title_anim_delay',
        'type' => 'text_small',
        'default' => 1,
    ) );

    $cmb_group->add_group_field( $group_field_id, array(
        'name' => __('Title animation duration', 'sage'),
        'description' => __('Duration value in seconds eg 1', 'sage'),
        'id'   => 'title_anim_duration',
        'type' => 'text_small',
        'default' => 1,
    ) );

    $cmb_group->add_group_field( $group_field_id, array(
        'name' => __('Caption color', 'sage'),
        'id'   => 'caption_color',
        'type' => 'colorpicker',
        'default' => '#ffffff',
    ) );

    $cmb_group->add_group_field( $group_field_id, array(
        'name'    => __( 'Caption animation', 'cmb2' ),
        'id'      => 'caption_anim',
        'type'    => 'select',
        'options' => $animations,
        'default' => 'fadeInUp',
    ) );

    $cmb_group->add_group_field( $group_field_id, array(
        'name' => __('Caption animation delay', 'sage'),
        'description' => __('Delay value in seconds eg 0.5', 'sage'),
        'id'   => 'caption_anim_delay',
        'type' => 'text_small',
        'default' => 1.5,
    ) );

    $cmb_group->add_group_field( $group_field_id, array(
        'name' => __('Caption animation duration', 'sage'),
        'description' => __('Duration value in seconds eg 1', 'sage'),
        'id'   => 'caption_anim_duration',
        'type' => 'text_small',
        'default' => 1.5,
    ) );

    $cmb_group->add_group_field( $group_field_id, array(
        'name' => __('Overlay color', 'sage'),
        'id'   => 'overlay_color',
        'type' => 'colorpicker',
        'default' => '#000000',
    ) );

    $cmb_group->add_group_field( $group_field_id, array(
        'name' => __('Overlay opacity', 'sage'),
        'desc' => __( 'Enter value from 0 to 1 eg: 0.1', 'cmb2' ),
        'id'   => 'overlay_opacity',
        'type' => 'text_small',
        'default' => 0,
    ) );

    $cmb_group->add_group_field( $group_field_id, array(
        'name'    => __( 'Overlay animation', 'cmb2' ),
        'id'      => 'overlay_anim',
        'type'    => 'select',
        'options' => $animations,
        'default' => 'zoomIn',
    ) );

    $cmb_group->add_group_field( $group_field_id, array(
        'name' => __('Overlay animation delay', 'sage'),
        'description' => __('Delay value in seconds eg 0.5', 'sage'),
        'id'   => 'overlay_anim_delay',
        'type' => 'text_small',
        'default' => 0.5,
    ) );

    $cmb_group->add_group_field( $group_field_id, array(
        'name' => __('Overlay animation duration', 'sage'),
        'description' => __('Delay value in seconds eg 0.5', 'sage'),
        'id'   => 'overlay_anim_duration',
        'type' => 'text_small',
        'default' => 1,
    ) );

}

