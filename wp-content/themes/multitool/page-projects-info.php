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
$project_obj = CPM_Project::getInstance();
$projects = $project_obj->get_projects();
 //echo '<pre>';print_r($projects);
$project_id=$_REQUEST['project_id']; 

//$pro_obj = CPM_Project::getInstance();
//$activities = $pro_obj->get_activity( $project_id, array() );

	$info = CPM_project::getInstance()->get_info( $project_id, array() );
	$comment_count = get_comment_count( $project_id );
	//echo '<pre>';print_r($info);
	
//echo cpm_project_summary( $project->info );  
 
	
	

if ( cpm_user_can_access( $project_id, 'tdolist_view_private' ) ) {
    $lists = $task_obj->get_task_lists( $project_id, true );
} else {
    $lists = $task_obj->get_task_lists( $project_id );
}

//cpm_get_header( __( 'Kanboard', 'cpm' ), $project_id );
//$sections = kbc_get_sections( $project_id );



?><?php getMenuAgileProjects(); ?>
	<!-- BEGIN CONTENT -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="<?php get_bloginfo('url'); ?>/wp-content/themes/multitool/assets/global/plugins/bootstrap-select/bootstrap-select.min.css"/>
<link href="<?php get_bloginfo('url'); ?>/wp-content/themes/multitool/assets/global/plugins/jquery-multi-select/css/multi-select.css"/>

	<div class="page-content-wrapper has-leftmenu">
	<!-- BEGIN PAGE HEADER-->
			<!-- BEGIN PAGE HEAD -->
			<div class="page-head">
				<!-- BEGIN PAGE TITLE -->
				<div class="page-title">
					<h1 id="page-title-cl"><?php echo get_the_title( $project_id ); ?></h1> 
				</div>
				 <div class="clear"></div>
				<!-- For replacement of current project - title to the drop down list -->
				<div id="switchProject" style="display:none; float:left;" class="form-group">		
				<div class="col-md-4">				
				<form name="switchProject">
					<select class="form-control input-medium select2me" data-placeholder="Select..." name="menu" onChange="window.document.location.href=this.options[this.selectedIndex].value;" value="GO">
						<option selected="selected"><?php echo get_the_title( $project_id ); ?></option>
						<?php foreach($projects as $proj){ //$key = key($proj);?>
							<option value="<?php  bloginfo('url');?>/project-info/?project_id=<?php echo $proj->ID; ?>"> <?php echo $proj->post_title; ?></option>
						<?php } ?>
					</select>
				</form>	 
				</div>
				</div>
				<!-- Replacement ends here -->
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
							 
							 
							 

							 
							<!-- <div class="project-summary">
							 Project Summary : 
							 
							 </div>
							 -->
							 <div class="project-comments">
							 
							 Comments : <a href="#"><?php echo $info->comments; ?></a>
							 
							 </div>
							 <div class="project-todolist">
							 To-do lists : <a href="#"><?php echo $info->todolist; ?></a>
							 </div>
							 <div class="project-todos">
							 Tasks : <a href="#"><?php echo $info->todos; ?></a>
							 </div>
							  <div class="project-files">
							 Files : <a href="#"><?php echo $info->files; ?></a>
							 </div>
							  <div class="project-milestone">
							 Milestones : <a href="#"><?php echo $info->milestone; ?></a>
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
	
<script type="text/javascript">
function dropdown(mySel)
{
var myWin, myVal;
myVal = mySel.options[mySel.selectedIndex].value;
if(myVal)
 {
 if(mySel.form.target)myWin = parent[mySel.form.target];
 else myWin = window;
 if (! myWin) return true;
 myWin.location = myVal;
 }
return false;
}


jQuery(document).ready(function() {   


$( "#page-title-cl" ).click(function() {
	
  $( "#switchProject" ).toggle( "fast", function() {
    // Animation complete.
  });
});

$(".page-title h1").hover(function (){
        $(this).css("text-decoration", "underline");
    },function(){
        $(this).css("text-decoration", "none");
    }
);

 
 

 
    
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
	margin:30px 0px 30px 0px;
}
.project-comments, .project-todolist, .project-todos, .project-files, .project-milestone{
	margin:20px 0px 30px 0px;
}
.page-title{
	 
}
#page-title-cl{
	cursor: pointer; cursor: hand; 
}
#switchProject{
	position:absolute;
	z-index: 2;
	top: 50px;
}
.input-medium {
    width: 235px !important;
}
#switchProject .col-md-4{
	padding-left:0;
}
</style>
<?php //get_sidebar(); ?>
<?php get_footer(); ?>