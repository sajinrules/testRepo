<?php
/**
 * Template Name: Boards Page
 *
 * The template and functionality for displaying an account
 * @package WordPress
 * @subpackage Multitool
 * @since We Cross 1.0
 */
get_header(); 
the_post();
$project_obj = CPM_Project::getInstance();
$projects = $project_obj->get_projects();
 //echo '<pre>';print_r($projects);
$project_id=$_REQUEST['project_id']; 

//$pro_obj = CPM_Project::getInstance();
//$activities = $pro_obj->get_activity( $project_id, array() );

	$info = CPM_project::getInstance()->get_info( $project_id, array() );
	$comment_count = get_comment_count( $project_id );

?><?php getMenuAgileProjects(); ?>
	<!-- BEGIN CONTENT -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="<?php get_bloginfo('url'); ?>/wp-content/themes/multitool/assets/global/plugins/bootstrap-select/bootstrap-select.min.css"/>
<link href="<?php get_bloginfo('url'); ?>/wp-content/themes/multitool/assets/global/plugins/jquery-multi-select/css/multi-select.css"/>
<link href="<?php get_bloginfo('url'); ?>/wp-content/themes/multitool/assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css"/>
<link href="<?php get_bloginfo('url'); ?>/wp-content/themes/multitool/assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>

	<div class="page-content-wrapper has-leftmenu">
	<!-- BEGIN PAGE HEADER-->
			<!-- BEGIN PAGE HEAD -->
			<div class="page-head">
				<!-- BEGIN PAGE TITLE -->
				<div class="page-title">
					<h1 id="page-title-cl"><?php echo get_the_title( $project_id ); //the_title(); ?></h1> 
				</div>
				 <div class="clear"></div>
				<!-- For replacement of current project - title to the drop down list -->

				
				<div id="switchProject" style="display:none; float:left;" class="form-group">		
<div class="col-md-4">				
				<form name="switchProject">
					<select class="form-control input-medium select2me" data-placeholder="Select..." name="menu" onChange="window.document.location.href=this.options[this.selectedIndex].value;" value="GO">
						<option selected="selected">Select project</option>
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
		<a class="btn default" id="ajax-demo" data-toggle="modal">
									View Demo </a>
		
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
							 
							 
							 
<?php
$task_obj = CPM_Task::getInstance();

$project_id=$_REQUEST['project_id']; 

if ( cpm_user_can_access( $project_id, 'tdolist_view_private' ) ) {
    $lists = $task_obj->get_task_lists( $project_id, true );
} else {
    $lists = $task_obj->get_task_lists( $project_id );
}

//cpm_get_header( __( 'Kanboard', 'cpm' ), $project_id );
$sections = kbc_get_sections( $project_id );


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



<?php
foreach ( $sections as $key => $section ) {
    if( $section->menu_order == 0 || $section->menu_order == 3  ) {
        continue;
    }

    $tasks_id = get_post_meta( $section->ID, '_tasks_id', true );
    $tasks_id = empty( $tasks_id ) ? array() : $tasks_id;

    foreach ( $tasks_id as $key => $task_id ) {
        if( in_array( $task_id, $pending_tasks ) ) {
            unset( $pending_tasks[$task_id] );
        }
    }
}
?>
<div class="kbc-body-wrap">
<?php
foreach ( $sections as $key => $section ) {
    $tasks_id = get_post_meta( $section->ID, '_tasks_id', true );
    $tasks_id = empty( $tasks_id ) ? array() : $tasks_id;
    $tasks_id = ( $section->menu_order == 0 ) ? $pending_tasks : $tasks_id; //array_unique( array_merge( $tasks_id, $pending_tasks ) ) : $tasks_id;
    $tasks_id = ( $section->menu_order == 3 ) ? $completed_tasks : $tasks_id;//array_unique( array_merge( $tasks_id, $completed_tasks ) ) : $tasks_id;

    $add_icon = ( $section->menu_order != 3 ) ? '+' : '';
    $class = ( $section->menu_order != 3 ) ? 'kbc-new-task' : '';
    $section_cross = ( $section->menu_order > 3 ) ? 'x' : '';
    $section_cross_class = ( $section->menu_order > 3 ) ? 'kbc-delete-section' : '';
    $last_menu_order = $section->menu_order;
    $add_more_class = ( $section->menu_order > 3 ) ? 'kbc-add-more-left' : 'kbc-add-more';

    ?>
    <div class="kbc-col-wrap">
        <h3 class="kbc-section-title">
            <?php echo $section->post_title; ?>
        </h3>

        <ul class="kbc-sortable connectedSortable" data-menu_order="<?php echo $section->menu_order; ?>" data-section_id="<?php echo $section->ID; ?>">

            <?php

            foreach ( $tasks_id as $key => $task_id ) {

                if ( 'publish' != get_post_status ( $task_id ) ) {
                    continue;
                }
                if ( $section->menu_order != 3 && in_array( $task_id, $completed_tasks ) ) {
                    continue;
                }

                $tasks = CPM_Task::getInstance()->get_task( $task_id );
                $url = cpm_url_single_task( $project_id, $tasks->post_parent, $task_id );
                ?>
                <li class="kbc-li-text" data-task_id="<?php echo $task_id; ?>"><a href="<?php echo $url; ?>"><?php echo $tasks->post_title; ?></a></li>
                <?php
            }
            ?>

        </ul>
        <?php
                    if ( $section->menu_order != 3 ) {
                        ?>
						

                        <div class="<?php echo $class .' '. $add_more_class; ?>" data-section_id="<?php echo $section->ID; ?>"><?php _e('+ Add More Task', 'kbc' ); ?></div>
                        <?php
                        if ( $section->menu_order > 3 ) {
                            ?>
                            <span class="kbc-close <?php echo $section_cross_class; ?>" data-section_id="<?php echo $section->ID; ?>"><?php _e('Delete', 'kbc' ); ?></span>
                            <?php
                        }
                    }
                ?>

    </div>

    <div class="kbc-task-dialog-init kbc-task-dialog-<?php echo $section->ID; ?>" style="display:none; z-index:999;" title="<?php _e( 'Start a new task', 'kbc' ); ?>">

        <form action="" method="post" class="cpm-task-form">
            <select name="list_id">
            <?php $lists_dropdown = isset( $lists_dropdown ) ? $lists_dropdown : array(); ?>
            <?php
            foreach ( $lists_dropdown as $id => $todo_title ) {
                ?>
                <option value="<?php echo $id; ?>"><?php echo $todo_title; ?> </option>
                <?php
            }
            ?>
            <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
            <input type="hidden" name="section_id" value="<?php echo $section->ID; ?>">
            <input type="hidden" name="action" value="cpm_task_add">
            <input type="hidden" name="single" value="0">
            <?php wp_nonce_field( 'kbc_task_add' ); ?>


            <div class="item content">
                <textarea name="task_text" class="todo_content" cols="40" placeholder="<?php esc_attr_e( 'Add a new to-do', 'kbc' ) ?>" rows="1"></textarea>
            </div>

            <div class="item date">
                <?php if(cpm_get_option( 'task_start_field' ) == 'on') { ?>
                    <div class="cpm-task-start-field">
                        <label><?php _e('Start date', 'kbc'); ?></label>
                        <input  type="text" autocomplete="off" class="datepicker" placeholder="<?php esc_attr_e( 'Start date', 'kbc' ); ?>" value="" name="task_start" />
                    </div>
                <?php } ?>

                <div class="cpm-task-due-field">
                    <label><?php _e('Due date', 'kbc'); ?></label>
                    <input type="text" autocomplete="off" class="datepicker" placeholder="<?php esc_attr_e( 'Due date', 'kbc' ); ?>" value="" name="task_due" />
                </div>
            </div>

            <div class="item user">
                <?php cpm_task_assign_dropdown( $project_id, '-1' ); ?>
            </div>

            <?php if( cpm_user_can_access( $project_id, 'todo_view_private' ) ) { ?>
                <div class="cpm-make-privacy">
                    <label>
                        <input type="checkbox"  value="yes" name="task_privacy">
                        <?php _e( 'Private', 'kbc' ); ?>
                    </label>
                </div>
            <?php } ?>

            <div class="item submit">
                <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
                <span class="cpm-new-task-spinner"></span>
                <input type="submit" class="button-primary" name="submit_kbc_task" value="<?php _e( 'Add this to-do', 'kbc' ) ?>">
            </div>
        </form>
    </div>

    <?php
}
?>
    <div class="kbc-clear"></div>
</div>
<div><a class="button-primary kbc-add-section-btn" href="#"><?php _e( 'Add new section', 'kbc' ); ?></a></div>


<div class="kbc-section" style="display:none; z-index:999;" title="<?php _e( 'Start a section', 'kbd' ); ?>">
    <form action="" method="post" class="cpm-task-form">
        <?php wp_nonce_field( 'kbc_task_add' ); ?>
        <input type="text" name="post_title" placeholder="<?php _e( 'Section Name', 'kbc' ); ?>">
        <input type="hidden" value="<?php echo $last_menu_order+1; ?>" name="menu_order">
        <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
        <input type="submit" class="button-primary" value="Submit" name="kbc_new_section">
    </form>

</div>


<script type="text/javascript">
    jQuery(function($) {
        $( ".kbc-task-dialog-init, .kbc-section" ).dialog({
            autoOpen: false,
            modal: true,
            dialogClass: 'kbc-ui-dialog',
            width: 485,
            height: 425,
            position:['middle', 100],

            zIndex: 999,

        });
        $( ".kbc-section" ).dialog({
            autoOpen: false,
            modal: true,
            dialogClass: 'kbc-ui-dialog',
            width: 485,
            height: 200,
            position:['middle', 100],

            zIndex: 999,

        });
    });

</script>
							 
							 
							 
							 
							 
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
	
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js" type="text/javascript"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js" type="text/javascript"></script>

	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/assets/admin/pages/scripts/ui-extended-modals.js"></script>

	<!-- END PAGE LEVEL PLUGINS -->
	
<script>
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