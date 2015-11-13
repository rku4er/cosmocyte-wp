<article <?php post_class('clearfix'); ?>>
  <?php the_post_thumbnail('thumbnail', array('class' => "attachment-$size alignleft")); ?>
  <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
  <?php the_excerpt(); ?>
</article>
