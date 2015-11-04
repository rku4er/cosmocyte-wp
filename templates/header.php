<?php
    use Roots\Sage\Nav\NavWalker;

    global $redux_demo;
    $options = $redux_demo;

    $navbar_logo = $options['navbar-logo'];
    $logo_image = $navbar_logo['url'] ? '<img src="' . esc_url($navbar_logo['url']) . '" alt="' . get_bloginfo('name') . '">' : '';
    $navbar_brand = sprintf(
        '<a class="%s" href="%s">%s</a>',
        esc_attr('navbar-brand withoutripple'),
        esc_url(home_url('/')),
        ($options['navbar-logo-text'] === '1') ? $logo_image. '<strong>'.get_bloginfo('name').'</strong><span>'.get_bloginfo('description') . '</span>' : $logo_image
    );

    if($options['navbar-position'] === '1'){
        $navbar_class = 'navbar-static-top';
    }else if($options['navbar-position'] === '2'){
        $navbar_class = 'navbar-fixed-top';
    }else if($options['navbar-position'] === '3'){
        $navbar_class = 'navbar_fixed_bottom';
    }

    if($options['header-layout'] === '1'){
        $container_class = 'container';
    }else if($options['header-layout'] === '2'){
        $container_class = 'container-fluid';
    }

    $socials = ($options['socials-in-header'] != 0) ? do_shortcode('[socials]') : '';
?>

<header class="banner navbar navbar-default <?php echo $navbar_class; ?>" role="banner">
    <div class="inner">
        <div class="<?php echo $container_class; ?>">
            <div class="navbar-header">

              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only"><?php echo __('Toggle navigation', 'sage'); ?></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <?php echo $navbar_brand; ?>

            </div>

            <nav class="collapse navbar-collapse" role="navigation">
              <?php if (has_nav_menu('primary_navigation')) wp_nav_menu(['theme_location' => 'primary_navigation', 'walker' => new NavWalker(), 'menu_class' => 'nav navbar-nav']);  ?>
              <?php echo $socials; ?>
            </nav>

        </div>
    </div>
</header>
