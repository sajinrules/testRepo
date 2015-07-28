<?php
/**
 * Template Name: We Cross Account Page 
 *
 * The template and functionality for displaying an account
 * @package WordPress
 * @subpackage Multitool
 * @since We Cross 1.0
 */

get_header(); ?>
<div class="page-content-wrapper">
	<div class="page-content">
		<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">Modal title</h4>
					</div>
					<div class="modal-body">
						 Widget settings form goes here
					</div>
					<div class="modal-footer">
						<button type="button" class="btn blue">Save changes</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
		<!-- /.modal -->
		<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
		<?php // include('menu-options.php'); ?>
		<div class="page-head">
			
			<!-- BEGIN PAGE TITLE -->
			<div class="page-title">
				<h1><?php the_title(); ?></h1>
			</div>
			<!-- END PAGE TITLE -->
		</div>
		<div class="page-breadcrumb breadcrumb">
		<?php
			if ( function_exists( 'yoast_breadcrumb' ) ) {
				yoast_breadcrumb();
			}
		?>
		</div>
		<div class="row">
			<div class="col-md-12">	
			<?php the_content(); ?>
			</div>
		</div>
		
		
	</div>
</div>
<?php //get_sidebar(); ?>
<?php get_footer(); ?>