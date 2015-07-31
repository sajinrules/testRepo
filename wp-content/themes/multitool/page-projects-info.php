<?php
/**
 * Template Name: Projectinfo Page
 *
 * The template and functionality for displaying an account
 * @package WordPress
 * @subpackage Multitool
 * @since We Cross 1.0
 */
get_header(); 
the_post();
$task_obj = CPM_Task::getInstance();

$project_id=$_REQUEST['project_id']; 

//$pro_obj = CPM_Project::getInstance();
//$activities = $pro_obj->get_activity( $project_id, array() );

  $activities = CPM_project::getInstance()->get_activity( $project_id, array() );
     $comment_count = get_comment_count( $project_id );
 //echo '<pre>';print_r($comment_count);
 //echo $comment_count->total_comments;

 
	
	

if ( cpm_user_can_access( $project_id, 'tdolist_view_private' ) ) {
    $lists = $task_obj->get_task_lists( $project_id, true );
} else {
    $lists = $task_obj->get_task_lists( $project_id );
}

//cpm_get_header( __( 'Kanboard', 'cpm' ), $project_id );
//$sections = kbc_get_sections( $project_id );



?><?php getMenuAgileProjects(); ?>
	<!-- BEGIN CONTENT -->

	<div class="page-content-wrapper has-leftmenu">
	<!-- BEGIN PAGE HEADER-->
			<!-- BEGIN PAGE HEAD -->
			<div class="page-head">
				<!-- BEGIN PAGE TITLE -->
				<div class="page-title">
					<h1><?php echo get_the_title( $project_id ); //the_title(); ?></h1>
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
				<!--<li>
					<a href="#">...</a>
				</li>-->
			</ul>
			<!-- END PAGE BREADCRUMB -->
			<!-- END PAGE HEADER-->
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
			<div class='row'>
				<div class='col-md-12'>
					<div class="portlet light bordered">
						<div class="portlet-body">	
							 
							 
							 <h2><?php echo strtoupper(get_the_title( $project_id ));  ?></h2> 
							 
							 
							 <div class="project-summary">Project Summary : </div>
							 
							 <div class="comments-count">
							 
							 Comments : <?php foreach($comment_count as $comments){ echo $comments->total_comments; } ?>
							 
							 </div>
							 
							 <?php 
							  echo cpm_activity_html( $activities );
							 ?>
<?php


if ( $lists ) {

    foreach ($lists as $list) {
        $lists_dropdown[$list->ID] = $list->post_title;
        $tasks = $task_obj->get_tasks_by_access_role( $list->ID , $project_id );

        $tasks = cpm_tasks_filter( $tasks );

        if ( count( $tasks['pending'] ) ) {
            foreach ($tasks['pending'] as $task) {
                $pending_tasks[$task->ID] = $task->ID;
            }
        }

        if ( count( $tasks['completed'] ) ) {
            foreach ($tasks['completed'] as $task) {
                $completed_tasks[$task->ID] = $task->ID;
            }
        }

    }
}

$completed_tasks = isset( $completed_tasks ) ? $completed_tasks : array();
$pending_tasks = isset( $pending_tasks ) ? $pending_tasks : array();

?>


 		 
							 
							 
						</div>
					</div>
				</div>
			</div>
			<!-- end row data -->
		<!-- END PAGE CONTENT-->
	</div>
</div>
<!-- END CONTENT -->
	
	<!-- BEGIN PAGE LEVEL PLUGINS -->
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/assets/global/plugins/clockface/js/clockface.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/assets/global/plugins/bootstrap-daterangepicker/moment.min.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
	<!-- END PAGE LEVEL PLUGINS -->
	
<script>
jQuery(document).ready(function() {       
   // initiate layout and plugins
   Metronic.init(); // init metronic core components
   Layout.init(); // init current layout
   Demo.init(); // init demo features
    
});

</script>
<style>
.cpm-project-head, .nav-tab-wrapper{
	display:none !important;
}
.projects{
	margin-top: 79px;
}
.project-summary{
	margin:50px 0px 30px 0px;
}
</style>
<?php //get_sidebar(); ?>
<?php get_footer(); ?>