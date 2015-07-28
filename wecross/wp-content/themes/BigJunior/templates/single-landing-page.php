<?php
	global $post;
	//echo $post->ID; 
	$getsidebar = types_render_field( "show-sidebars", array("separator" => "," ) );
	$lptitle = get_post_meta($post->ID, 'wpcf-h1-titel', true);
	
	$hidetoptitle = get_post_meta($post->ID, 'wpcf-hide-insights-top-title', true);
	$hidetopmenu = get_post_meta($post->ID, 'wpcf-hide-insights-menu', true);
	
	$lptitle = get_post_meta($post->ID, 'wpcf-h1-titel', true);
	// main,info,whitepaper,publicatie 
	//echo $getsidebar;
	if(strlen($getsidebar)>0) {
		$showsidebar = true;
		$span = "span8";
	}
	else
	{
		$showsidebar = false;
		$span = "span12";
	}
	/*
<a onclick="__gaTracker('send', 'event', 'download', 'https://db52e019574845bfb04ae9b9c0da57e0.objectstore.eu/insights/music/74.pdf');" data-cta-variation="0" data-event-id="6952" href="https://www.wecross.nl?wp_cta_redirect_6952=https://db52e019574845bfb04ae9b9c0da57e0.objectstore.eu/insights/music%2F74.pdf&wp-cta-v=0&wpl_id=HVKxZ9qLtX13HwusWaIbUCPGS4zLin3qJao&l_type=wpluid&wp-cta-v=0&wpl_id=HVKxZ9qLtX13HwusWaIbUCPGS4zLin3qJao&l_type=wpluid" target="_blank">Download PDF</a>
	*/

// 6995 = frontpage
if($post->ID != 6995){
	?>
<div class='insights'>	
<?php
	if($lptitle !=''){	
?>
<?php if($hidetoptitle == 0){ ?>
<div class="row">
	<div class="span12">
		<span class='lptitle' style="text-align: center;"><?php echo $lptitle; ?></span>
	</div>
</div>
<?php } ?>

<?php if($hidetopmenu == 0){ ?>
<div class="row">
	<div class="span12">
		 <?php wp_nav_menu( array('menu' => 'Insights Menu' ) ); ?> 
	</div>
</div>
<?php } ?>

<?php } // end lptitle
} // end if frontpage
?>
<div class="row">
	<div class="span12">
		<h1 class="lp-h1" style="text-align: left;"><?php the_title(); ?></h1>
	</div>
</div>
<div class="row">
    <div class="<?php echo $span; ?>">
        <?php 
	        echo apply_filters('the_content', get_post_field('post_content', $post->ID));
        ?>
    </div>

<?php if($post->ID != 6937){ ?>
<!-- sidebar -->
<div class="span4">
	<?php 
		if(strlen(stristr($getsidebar, 'whitepaper'))>0){
			?>
			<div class="sidebar widget-area whitepaper-sidebar"><?php dynamic_sidebar( 'wecross-whitepaper-sidebar' ); ?></div>
			<?php
		}
		if(strlen(stristr($getsidebar, 'publicatie'))>0){
			?>
			<div class="sidebar widget-area publicatie-sidebar"><?php dynamic_sidebar( 'wecross-publicatie-sidebar' ); ?></div>
			<?php
		}
		if(strlen(stristr($getsidebar, 'info'))>0){
			?>
			<div class="sidebar widget-area info-sidebar"><?php dynamic_sidebar( 'wecross-info-sidebar' ); ?></div>
			<?php
		}
		if(strlen(stristr($getsidebar, 'main'))>0){
			?>
			<div class="sidebar widget-area"><?php dynamic_sidebar( 'wecross-main-sidebar' ); ?></div>
			<?php
		}
		if(strlen(stristr($getsidebar, 'formulier'))>0){
			?>
			<div class="sidebar widget-area formulier-sidebar"><?php dynamic_sidebar( 'wecross-formulier-sidebar' ); ?></div>
			<?php
		}
	?>
</div>
<!-- end sidebar -->
<?php } // end if not publicaties ?>

</div>
<?php
	
if($post->ID == 6937){
	
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
<div class='archive'>	
<div class="row">
	<?php 
	if(isset($_REQUEST['dev'])){
	?>
	<div class="<?php echo $span; ?>">
	<div class='posts'>
	<div id="publicaties">
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
						<span class="post-author"><?php _e('Auteur: ', TEXTDOMAIN); the_author(); ?></span>
						<?php 
							$posttags = get_the_tags();
							if($posttags){
						?><br/>
						<span class="post-categories"><?php _e(' Tags: ', TEXTDOMAIN); the_tags(', '); ?></span>
						<?php } ?>
					</div>
				</div>
			</div>
			            
	        <?php endwhile; ?>
	</div>
	</div>
	</div>
<?php	} else {
	?>
	<!-- column posts -->
	<div class="<?php echo $span; ?>">
		<div class='posts'>
			<?php  while ($my_query->have_posts()) : $my_query->the_post();  ?>
			<div <?php post_class('clearfix'); ?> >
            <div class="post-meta">
			    <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			    
			    <div class="post-info-container clearfix">
			        <div class="post-comments"><?php if(comments_open()) comments_popup_link( '0', '1', '%', 'comments-link', ''); ?></div>
			        <div class="post-info">
			            <span class="post-date"><?php the_time(get_option('date_format')); ?></span>
			            <span class="post-info-separator">/</span>
			            <!--<span class="post-categories"><?php _e('in ', TEXTDOMAIN); the_category(', '); ?></span>
			            <span class="post-info-separator">/</span>
			            <span class="post-author"><?php _e('door ', TEXTDOMAIN); the_author(); ?></span>-->
			        </div>
			    </div>
			    <?php /* if(has_tag()){ ?>
			    <div class="tagcloud"><?php the_tags('', '', ''); ?></div>
			    <?php }  */
			    ?>
			</div>
            <div class="post-content">
			<?php
			    //Post thumbnail
			    if ( function_exists('has_post_thumbnail') && has_post_thumbnail() ) { ?>
			    <div class="post-media">
			        <a class="post-image" title="<?php echo esc_attr(get_the_title()); ?>" href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
			    </div>
			    <?php
			    }
			    $excerpt = get_the_excerpt();
			    echo string_limit_words($excerpt,25);
			?>
			</div>
    		</div>
			<?php endwhile; // end while loop posts ?>
		</div>
	</div>
	<?php } ?>
	<!-- end column posts -->
	<!-- sidebar -->
<div class="span4">
	<?php 
		
		if(strlen(stristr($getsidebar, 'whitepaper'))>0){
			?>
			<div class="sidebar widget-area whitepaper-sidebar"><?php dynamic_sidebar( 'wecross-whitepaper-sidebar' ); ?></div>
			<?php
		}
		if(strlen(stristr($getsidebar, 'publicatie'))>0){
			?>
			<div class="sidebar widget-area publicatie-sidebar"><?php dynamic_sidebar( 'wecross-publicatie-sidebar' ); ?></div>
			<?php
		}
		if(strlen(stristr($getsidebar, 'info'))>0){
			?>
			<div class="sidebar widget-area info-sidebar"><?php dynamic_sidebar( 'wecross-info-sidebar' ); ?></div>
			<?php
		}
		if(strlen(stristr($getsidebar, 'main'))>0){
			?>
			<div class="sidebar widget-area"><?php dynamic_sidebar( 'wecross-main-sidebar' ); ?></div>
			<?php
		}
		if(strlen(stristr($getsidebar, 'formulier'))>0){
			?>
			<div class="sidebar widget-area formulier-sidebar"><?php dynamic_sidebar( 'wecross-formulier-sidebar' ); ?></div>
			<?php
		}
		
	?>
    
</div>
<!-- end sidebar -->	
</div>
</div>
<?php endif; // end if haveposts ?>

<?php } // end if publicaties ?>

</div> <!-- end class div insights page -->
<?php 
	if(isset($_REQUEST['dev'])){
		?>
		<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/assets/js/isotope.pkgd.min.js"></script>
		<script>
		jQuery(document).ready(function(){
			
			jQuery('#publicaties').isotope({
			  itemSelector: '.isotope-item',
			  layoutMode: 'fitRows'
			});
			
			setTimeout(function(){ 
				jQuery('#publicaties').isotope(); 
			}, 500);
		
			jQuery('.isotope-item').hover(
				
					function(){
						jQuery(this).find('img').animate({
							opacity: 0.5
						});
						jQuery(this).find('.item-meta').animate({
							opacity: 0.5
						});
					}
					,
					function(){
						jQuery(this).find('img').animate({
							opacity: 1
						});
						jQuery(this).find('.item-meta').animate({
							opacity: 1
							
						});
					}
				
			);
			
		});
		</script>
		<?php
	}
?>