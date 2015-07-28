<?php
	global $wpdb;
	
	$title = isset($title) ? $title : get_the_title();
	
	$results = $wpdb->get_results( "select meta_value from $wpdb->postmeta where meta_key = '_yoast_wpseo_metadesc' AND post_id = '".get_the_ID()."'" );
	//printpre($results[0]);
	$metadescr = "";
	if(isset($results[0]->meta_value)){
		$metadescr = $results[0]->meta_value;
	}
	
?>
<div id="page-title-enhanced">
    <div class="container clearfix">
        <?php get_template_part( 'templates/breadcrumb' ); ?>
        <h1 class="title"><?php echo $title; ?></h1>
        <?php 
	        if($metadescr!=""){
		       echo "<div class='page-descr'> $metadescr</div>";
	        }
        ?>
    </div>
</div>
