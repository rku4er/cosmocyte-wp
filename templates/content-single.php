<?php
    use Roots\Sage\Titles;

    $object      = $wp_query->queried_object;
    $curr_ID     = !empty($object) ? $object->ID : '';
    $prefix      = 'sage_page_options_';
    $hide_header = get_post_meta( $curr_ID, $prefix .'hide_title', true );
?>

<?php
    $prefix = 'sage_page_options_';
    $videoURL = get_post_meta( get_the_ID(), $prefix .'lightbox_video_url', true );
    $thumb_class = 'img-fluid';

    if($videoURL){
      $thumb = sprintf('<a href="%s" class="video-lightbox featured-wrapper">%s</a>',
        $videoURL,
        get_the_post_thumbnail( get_the_ID(), 'full', array('class' => $thumb_class))
      );
    } else {
      $thumb = sprintf('<span class="featured-wrapper">%s</span>',
        get_the_post_thumbnail( get_the_ID(), 'full', array('class' => $thumb_class))
      );
    }
?>

<?php echo $thumb; ?>

<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>

    <?php if(!$hide_header): ?>
      <h1 class="entry-single-title"><?php echo Titles\title(); ?></h1>
    <?php endif; ?>

    <div class="entry-content">
      <?php the_content(); ?>
    </div>

    <footer>
      <?php wp_link_pages(['before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']); ?>
    </footer>

    <?php comments_template('/templates/comments.php'); ?>

  </article>
<?php endwhile; ?>
