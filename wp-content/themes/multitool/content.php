<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Multitool
 * @since Multitool 1.0
 */
?>
<!-- BEGIN PAGE HEADER-->
<div class="page-bar">
	<ul class="page-breadcrumb hide">
		<li>
			<i class="fa fa-home"></i>
			<a href="/">Home</a>
			<i class="fa fa-angle-right"></i>
		</li>
		<li>
			<a href="#">Account</a>
		</li>
	</ul>
	<div class="page-toolbar">
		
	</div>
</div>
<!-- END PAGE HEADER-->

<div class="clearfix"></div>
<div class='row'>
<div class="col-md-12 col-sm-12">
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
			if ( is_single() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( sprintf( '<h2 class="entry-title">', '</h2>' ));
			endif;
		?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
			the_content();
		?>
	</div><!-- .entry-content -->

</article><!-- #post-## -->
</div>
</div>