<?php
/*
	Template Name: Publicaties Overview Page
*/
get_header();
get_template_part( 'templates/title' );
get_template_part('templates/head');
//Get the sidebar option
$sidebar = get_meta('sidebar');
$sidebarPos = opt('sidebar-position');

?>
<!--Content-->


<div id="main" class="container container-vspace">
<div class='insights'>	
<div class="row">
	<div class="span12">
		<span class='lptitle' style="text-align: center;">WE CROSS INSIGHTS PUBLICEERT MET REGELMAAT<br/>
ONDERZOEKSRAPPORTEN & WHITEPAPERS, DOWNLOAD DEZE GRATIS.</span>
	</div>
</div>
<div class="row">
	<div class="span12">
		 <?php wp_nav_menu( array('menu' => 'Insights Menu' ) ); ?> 
	</div>
</div>
    <?php
        if($sidebar == 'no-sidebar' )
            get_template_part('templates/loop-page-insights');
        else{
            $contentClass = 'span8';
            $sidebarClass = 'span3';

            if(1 == $sidebarPos)
                $contentClass .= ' offset1 float-right';
            else
                $sidebarClass .= ' offset1';
    ?>
    	<div class="publicaties">
        
            <div class="<?php echo $contentClass; ?>">
	        <?php
            	$args = array( 
	            'post_type'=>'post', 
	            'paged' => $paged,
	            'category_name'=>'publicaties',
	            'posts_per_page' => 12,
	            'monthnum'=>$monthnum
	        	);
	    
	            $my_query = new WP_Query();
	            $my_query->query($args);
	            $post_counter=($paged-1)*$posts_per_page;
	            if ($my_query->have_posts()) : 
			?>
		    <?php  while ($my_query->have_posts()) : $my_query->the_post();  ?>
			<div class="item isotope-item">
				<div class="item-wrap">
				    <div class="item-image"> 
					    <a href="<?php the_permalink(); ?>" class="item-image-link">
					  <?php the_post_thumbnail(); ?>
					    <div class="item-image-overlay"></div> 
						</a>
					</div>
					<div class="item-meta">
						<h3 class="item-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>
						<span class="item-category"></span>
					</div>
				</div>
			</div>
			            
	        <?php 
		        endwhile;
		        endif;    ?>
            </div>
	        
        </div>
    <?php } ?>

<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/assets/js/isotope.pkgd.min.js"></script>
<script>
jQuery(document).ready(function(){
	jQuery('.publicaties').isotope({
	  // options
	  itemSelector: '.isotope-item',
	  masonry: {
	    columnWidth: 110,
	    gutterWidth: 10
	  }
	  
	});
});
</script>
<?php get_footer(); ?>