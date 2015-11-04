<?php
    global $redux_demo;
    $options = $redux_demo;
?>
<footer class="content-info" role="contentinfo">
    <div class="inner">
      <div class="container">
        <div class="holder"><?php dynamic_sidebar('sidebar-footer'); ?></div>
        <?php if($options['show-scrolltop-link']) echo '<a href="#" id="scrollTopLink"><span class="glyphicon glyphicon-circle-arrow-up"></span></a>'; ?>
      </div>
    </div>
</footer>
