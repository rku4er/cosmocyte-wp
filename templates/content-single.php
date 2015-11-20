<?php
    use Roots\Sage\Titles;

    $curr_ID = $wp_query->queried_object->ID;
    $prefix = 'sage_page_options_';
    $hide_header = get_post_meta( $curr_ID, $prefix .'hide_title', true );
?>

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
