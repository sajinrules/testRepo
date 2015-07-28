<?php
$title = isset($title) ? $title : get_the_title();
?>
<div id="page-title">
    <div class="container clearfix">
        
        <?php get_template_part( 'templates/breadcrumb' ); ?>
    </div>
</div>
