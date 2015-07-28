<?php
/**
 * Template Name: Project > Backlog
 *
 * The template and functionality for displaying an account
 * @package WordPress
 * @subpackage Multitool
 * @since We Cross 1.0
 */
get_header(); 
the_post();
?><?php getMenuAgileMain(); ?>
	<!-- BEGIN CONTENT -->

	<div class="page-content-wrapper has-leftmenu">
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
			<!-- BEGIN PAGE HEADER-->
			<!-- BEGIN PAGE HEAD -->
			<div class="page-head">
				<!-- BEGIN PAGE TITLE -->
				<div class="page-title">
					<h1><?php the_title(); ?></h1>
				</div>
				<!-- END PAGE TITLE -->
			</div>
			<!-- END PAGE HEAD -->
			<?php 
				getJiraLink();
			?>
			<!-- BEGIN PAGE BREADCRUMB -->
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<a href="/">Agile</a>
					<i class="fa fa-circle"></i>
				</li>
				<li>
					<a href="#">Backlog</a>
				</li>
			</ul>
			<!-- END PAGE BREADCRUMB -->
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->			
			<!-- row data -->
			<div class='row'>
				<div class='col-md-12'>
					<div class="portlet light bordered">
						<div class="portlet-body">	
							<div class='row'>
								<div class='col-md-12'>
									<div class="portlet light">
										<div class="portlet-body">
											<div class="row ui-sortable" id="sortable_portlets">
												<div class="col-md-12 column sortable">
													<h2>Milestones tot aan verkoop</h2>
													<hr>
													<div class="portlet portlet-sortable light bordered">
														<div class="portlet-title ui-sortable-handle">
															<div class="caption font-green-sharp">
																<i class="icon-speech font-green-sharp"></i>
																<span class="caption-subject bold uppercase"> Een taak</span>
																<span class="caption-helper">Meer details over deze taak</span>
															</div>
														</div>
													</div>
													<div class="portlet portlet-sortable light bordered">
														<div class="portlet-title ui-sortable-handle">
															<div class="caption font-green-sharp">
																<i class="icon-speech font-green-sharp"></i>
																<span class="caption-subject bold uppercase"> Een taak</span>
																<span class="caption-helper">Meer details over deze taak</span>
															</div>
														</div>
														
													</div>
													<div class="portlet portlet-sortable light bordered">
														<div class="portlet-title ui-sortable-handle">
															<div class="caption font-green-sharp">
																<i class="icon-speech font-green-sharp"></i>
																<span class="caption-subject bold uppercase"> Een taak</span>
																<span class="caption-helper">Meer details over deze taak</span>
															</div>
														</div>
														
													</div>
													
													<!-- empty sortable porlet required for each columns! -->
													<div class="portlet portlet-sortable-empty">
													</div>
													<h2>Milestones na start verkoop</h2>
													<hr>
													<!-- empty sortable porlet required for each columns! -->
													<div class="portlet portlet-sortable-empty">
													</div>
													
													<h2>Start Event</h2>
													<hr>
													<!-- empty sortable porlet required for each columns! -->
													<div class="portlet portlet-sortable-empty">
													</div>
													
													<h2>Backlog</h2>
													<hr>
													<div class="portlet portlet-sortable light bordered">
														<div class="portlet-title ui-sortable-handle">
															<div class="caption font-green-sharp">
																<i class="icon-speech font-green-sharp"></i>
																<span class="caption-subject bold uppercase"> Een taak</span>
																<span class="caption-helper">Meer details over deze taak</span>
															</div>
														</div>
														
													</div>
													<div class="portlet portlet-sortable light bordered">
														<div class="portlet-title ui-sortable-handle">
															<div class="caption font-green-sharp">
																<i class="icon-speech font-green-sharp"></i>
																<span class="caption-subject bold uppercase"> Een taak</span>
																<span class="caption-helper">Meer details over deze taak</span>
															</div>
														</div>
														
													</div>
													<div class="portlet portlet-sortable light bordered">
														<div class="portlet-title ui-sortable-handle">
															<div class="caption font-green-sharp">
																<i class="icon-speech font-green-sharp"></i>
																<span class="caption-subject bold uppercase"> Een taak</span>
																<span class="caption-helper">Meer details over deze taak</span>
															</div>
														</div>
														
													</div>
													<!-- empty sortable porlet required for each columns! -->
													<div class="portlet portlet-sortable-empty">
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			
			<!-- end row data -->
		<!-- END PAGE CONTENT-->
	</div>
</div>
<!-- END CONTENT -->
<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/flot/jquery.flot.min.js"></script>
	<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/flot/jquery.flot.resize.min.js"></script>
	<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/flot/jquery.flot.pie.min.js"></script>
	<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/flot/jquery.flot.stack.min.js"></script>
	<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/flot/jquery.flot.crosshair.min.js"></script>
	<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/flot/jquery.flot.categories.min.js"></script>
	
	<!-- BEGIN PAGE LEVEL PLUGINS -->
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/assets/global/plugins/clockface/js/clockface.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/assets/global/plugins/bootstrap-daterangepicker/moment.min.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
	<!-- END PAGE LEVEL PLUGINS -->
	<script src="<?php bloginfo('template_url'); ?>/assets/admin/pages/scripts/components-pickers.js"></script>
	<script src="<?php bloginfo('template_url'); ?>/assets/admin/pages/scripts/portlet-draggable.js"></script>
<script>
jQuery(document).ready(function() {       
   // initiate layout and plugins
   Metronic.init(); // init metronic core components
Layout.init(); // init current layout
Demo.init(); // init demo features
   PortletDraggable.init();
});
</script>
<?php //get_sidebar(); ?>
<?php get_footer(); ?>