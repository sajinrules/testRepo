<?php
/**
 * Archive template
 */

get_header();
//Page title
px_title_bar();
?>
<!--Content-->
<div id="main" class="container container-vspace">
	<div class='insights'>
		<div class="row">
			<div class="span12">
				<span class='lptitle' style="text-align: center;">We Cross Insights publiceert met regelmaat<br/>onderzoeksrapporten & whitepapers, download deze gratis.</span>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="span12">
			 <?php wp_nav_menu( array('menu' => 'Insights Menu' ) ); ?> 
		</div>
	</div>
	<div class="row">
		<div class="span12">
			<h1 style="text-align: left;">BLOG</h1>
		</div>
	</div>
	<div class="row">
	    <div class="span8">
	        <?php 
	            get_template_part( 'templates/loop', 'blog' );
	            
				get_pagination();
	        ?>
	    </div>
	    <div class="span3 offset1">
	        <div class="sidebar widget-area"><?php dynamic_sidebar( 'Main Sidebar' ); ?></div>
	    </div>
	</div>
</div>
<?php get_footer(); ?>