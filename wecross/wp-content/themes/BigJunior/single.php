<?php
/**
 * Template for displaying all single posts.
 */

get_header();
// 6995 is home landingpage
if($post->ID != 6995){
	get_template_part( 'templates/title' );
	$class = "container container-vspace";
}
else
{
	$class = "home-container";
}
?>

<!--Content-->
    <div id="main" class="<?php echo $class; ?>">
		
        <?php while ( have_posts() ) { the_post(); ?>
				 
			<?php
			$cats = wp_get_post_categories( get_the_ID() );
				foreach($cats as $s_cat)
			   	{
			   		$primarycat = get_category($s_cat);	
			   	}
		    	if($primarycat->slug == 'publicaties'){
			    	?> 
					<div class="row">
						<div class="span12">
						<span class='lptitle' style="text-align: center;">We Cross Insights publiceert met regelmaat<br/>onderzoeksrapporten & whitepapers, download deze gratis.</span>
						</div>
					</div>
					<div class="row">
						<div class="span12">
							 <?php wp_nav_menu( array('menu' => 'Insights Menu' ) ); ?> 
						</div>
					</div>
			    	<?php
		    	}
	            get_template_part( 'templates/single', get_post_type() ); ?>
           <!-- Go to www.addthis.com/dashboard to customize your tools --> 
						 <div class="addthis_sharing_toolbox"></div>
        <?php 
	        } // end of the loop. ?>
		
    </div>

<?php get_footer(); ?>