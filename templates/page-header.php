<?php
    use Roots\Sage\Titles;

    $object = $wp_query->queried_object;
    $curr_ID = !empty($object) ? $object->ID : '';
    $prefix = 'sage_page_options_';
    $hide_header = get_post_meta( $curr_ID, $prefix .'hide_title', true );
?>

<?php if(!$hide_header): ?>
<div class="page-header">
    <h1> <?php echo Titles\title(); ?> </h1>
</div>
<?php endif; ?>

