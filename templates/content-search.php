<article <?php post_class(); ?>>
  <?php
      $prefix = 'sage_page_options_';
      $videoURL = get_post_meta( get_the_ID(), $prefix .'lightbox_video_url', true );

      if($videoURL){
          echo sprintf('<a href="%s" class="%s">%s</a>',
              $videoURL,
              'video-lightbox',
              get_the_post_thumbnail(
                  get_the_ID(),
                  'thumbnail',
                  array('class' => "attachment-$size alignleft")
              )
          );
      }else{
          the_post_thumbnail(
              'thumbnail',
              array('class' => "attachment-$size alignleft"
          ));
      }
  ?>
  <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
  <?php the_excerpt(); ?>
</article>
