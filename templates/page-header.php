<?php
    use Roots\Sage\Titles;

    $curr_ID = $wp_query->queried_object->ID;
    $prefix = 'sage_page_options_';
    $hide_header = get_post_meta( $curr_ID, $prefix .'hide_title', true );
?>

<?php if(!$hide_header && !is_search()): ?>
<div class="page-header">
    <h1> <?php echo Titles\title(); ?> </h1>
</div>
<?php endif; ?>
