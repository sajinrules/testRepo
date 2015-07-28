<?php
/**
 * Template Name: Login
 *
 * The template and functionality for displaying an account
 * @package WordPress
 * @subpackage Multitool
 * @since We Cross 1.0
 */
get_header(); 
the_post();
?>
<div id='bg-meter-grad' class=''></div>


	<!-- BEGIN CONTENT -->

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
			
			<!-- BEGIN PAGE CONTENT-->			
			<!-- row data -->
			<div class="row">
				<div class="col-md-3 ">
					<!-- BEGIN PORTLET-->
					&nbsp;
					<!-- END PORTLET-->
				</div>
				<div class="col-md-6 ">
					<!-- BEGIN PORTLET-->
					<div class="portlet light">
						<div class="portlet-title ">
							<div class="caption caption-md">
								<i class="icon-bar-chart theme-font-color hide"></i>
								<span class="caption-subject theme-font-color bold uppercase"></span>
							</div>
							<div class="caption caption-md login-logo">
								<i class="icon-bar-chart theme-font-color hide"></i>
								<img src="http://gips.dev.wecross.nl/wp-content/themes/gips/images/logo.png" alt="logo" class="logo-default">
							</div>
						</div>
						<div class="portlet-body">
							<?php the_content(); ?>
						</div>
					</div>
					<!-- END PORTLET-->
				</div>
				<div class="col-md-3 ">
					<!-- BEGIN PORTLET-->
					&nbsp;
					<!-- END PORTLET-->
				</div>
				
			</div>
			<!-- end row data -->
		<!-- END PAGE CONTENT-->
	</div>
</div>
<!-- END CONTENT -->
<script>
jQuery(document).ready(function() {       
   // initiate layout and plugins
   Metronic.init(); // init metronic core components
   Layout.init(); // init current layout
   //Demo.init(); // init demo features
   //ChartsFlotcharts.init();
   //ChartsFlotcharts.initCharts();
   //ChartsFlotcharts.initPieCharts();
   //ChartsFlotcharts.initBarCharts();
   //ComponentsPickers.init();
   
   
});

</script>
<?php //get_sidebar(); ?>
			<?php 
				getJiraLink();
			?>
<?php get_footer(); ?>