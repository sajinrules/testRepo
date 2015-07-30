<?php
/**
 * Template Name: To Do Page
 *
 * The template and functionality for displaying an account
 * @package WordPress
 * @subpackage Multitool
 * @since We Cross 1.0
 */
get_header(); 
the_post();
	$project_obj        = CPM_Project::getInstance();
$projects           = $project_obj->get_projects();
$total_projects     = $projects['total_projects'];
$pagenum            = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
$db_limit           = intval( cpm_get_option( 'pagination' ) );
$limit              = $db_limit ? $db_limit : 10;
$status_class       = isset( $_GET['status'] ) ? $_GET['status'] : 'active';
$count              = cpm_project_count();
$can_create_project = cpm_manage_capability( 'project_create_role' );
$class              = $can_create_project ? '' : ' cpm-no-nav';

$task_obj = CPM_Task::getInstance();

if ( cpm_user_can_access( $project_id, 'tdolist_view_private' ) ) {
    $lists = $task_obj->get_task_lists( $project_id, true );
} else {
    $lists = $task_obj->get_task_lists( $project_id );
}

/*
Task list
*/

 $tasks['pending']   = array();
    $tasks['completed'] = array();
    $private            = ( $list->private == 'yes' ) ? 'cpm-lock' : 'cpm-unlock';
    ob_start();
	


?><?php getMenuAgileMain(); ?>
	<!-- BEGIN CONTENT -->

	<div class="page-content-wrapper has-leftmenu">
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
					<!--<a href="#">...</a>-->
				</li>
			</ul>
			<!-- END PAGE BREADCRUMB -->
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
			
			<div class='row'>
				<div class='col-md-12'>
					<div class="portlet light bordered">
					
						<div class="portlet-body">
							
							<div class="portlet-body">
					<div class="row inbox">
						<div class="col-md-2">
							<ul class="inbox-nav margin-bottom-10">
								<!--<li class="compose-btn">
									<a href="javascript:;" data-title="Compose" class="btn green">
									<i class="fa fa-edit"></i> Create Task </a>
								</li>-->
								<li class="inbox active">
									<a href="javascript:;" class="btn" data-title="Inbox">
									My Tasks (<?php echo count($lists); ?>) </a>
									<b></b>
								</li>
								<!--<li class="sent">
									<a class="btn" href="javascript:;" data-title="Sent">
									Archive </a>
									<b></b>
								</li>
								<li class="draft">
									<a class="btn" href="javascript:;" data-title="Draft">
									Draft </a>
									<b></b>
								</li>
								<li class="trash">
									<a class="btn" href="javascript:;" data-title="Trash">
									Trash </a>
									<b></b>
								</li>-->
							</ul>
						</div>
	 
						<div class="col-md-10">
							<div class="inbox-header">
								<h1 class="pull-left">Tasks</h1>
								<!--<form class="form-inline pull-right" action="index.html">
									<div class="input-group input-medium">
										<input type="text" class="form-control" placeholder="Search">
										<span class="input-group-btn">
										<button type="submit" class="btn green"><i class="fa fa-search"></i></button>
										</span>
									</div>
								</form>-->
							</div>
							<div class="inbox-loading" style="display: none;">
								 Loading...
							</div>
							<div class="inbox-content"><table class="table table-striped table-advance table-hover">
<thead>
<tr>
	<th colspan="3">
		<div class="checker"><span><input type="checkbox" class="mail-checkbox mail-group-checkbox"></span></div>
		<div class="btn-group">
			<a class="btn btn-sm blue dropdown-toggle" href="javascript:;" data-toggle="dropdown">
			More <i class="fa fa-angle-down"></i>
			</a>
			<ul class="dropdown-menu">
				<li>
					<a href="javascript:;">
					<i class="fa fa-pencil"></i> Mark as Read </a>
				</li>
				<li>
					<a href="javascript:;">
					<i class="fa fa-ban"></i> Spam </a>
				</li>
				<li class="divider">
				</li>
				<li>
					<a href="javascript:;">
					<i class="fa fa-trash-o"></i> Delete </a>
				</li>
			</ul>
		</div>

	</th>
	<th class="pagination-control" colspan="3">
		<span class="pagination-info">
		1-30 of <?php echo count($lists)?> </span>
		<a class="btn btn-sm blue">
		<i class="fa fa-angle-left"></i>
		</a>
		<a class="btn btn-sm blue">
		<i class="fa fa-angle-right"></i>
		</a>
	</th>
</tr>
</thead>
<tbody>
<?php
    if ( $lists ) {

        foreach ($lists as $list) {
            ?>
			<?php //echo cpm_task_list_html( $list, $project_id ); ?>
			<?php 
			 if ( $task->completed == '1' && $task->completed_by ) {
			?>
	<tr data-messageid="1">
			 <?php }else{?>
			 <tr class="unread" data-messageid="1">
			 <?php } ?>

	<td class="inbox-small-cells">
		<div class="checker"><span><input type="checkbox" class="mail-checkbox"></span></div>
	</td>
	<td class="inbox-small-cells">
		<i class="fa fa-star"></i>
	</td>
	<td class="view-message hidden-xs">
		<?php echo get_the_title( $list->ID ); ?>
	</td>
	<td class="view-message ">
		 <?php echo cpm_get_content( $list->post_content ); ?>
	</td>
	<td class="view-message inbox-small-cells">
		<i class="fa fa-paperclip"></i>
	</td>
	<td class="view-message text-right">
	   <?php
	      $postDate=$list->post_date;
		
		$pieces = explode(" ", $postDate);
		//echo $pieces[0];
	   $mydate = strtotime($pieces[0]);
	$newTime=$pieces[1];
	  
	  //echo date("Y-m-d H:i:s");  
	   if($pieces[0] == date('Y-m-d')){
		   echo $newTime;
	   }else{
		   echo date('F j', $mydate);
		  //echo strtotime($postDate);
		    // echo $list->post_date;
	   }
	   
	 
                   // $complete = $task_obj->get_completeness( $list->ID, $project_id );
                   // echo cpm_task_completeness( $complete['total'], $complete['completed'] );
				//	 $percentage = (100 * $complete['completed']) / $complete['total'];
                    ?>
				<!--	 <div class="cpm-progress cpm-progress-info">
					 <div class="progress progress-striped">
	<div style="width:<?php //echo $percentage; ?>%" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>	
				 </div>
				 </div>
<div class="text"><?php //printf( '%s: %d%% (%d of %d)', __( 'Completed', 'cpm' ), $percentage, $complete['completed'] , $complete['total'] ); ?></div>					
	-->
	</td>
</tr>
            <?php
        }
    }
    ?>
  
</tbody>
</table></div>
						</div>
					</div>
				</div>
							
						</div>
					</div>
				</div>
			</div>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->			
			
			
		<!-- END PAGE CONTENT-->
	</div>
</div>
<!-- END CONTENT -->
	
<!-- BEGIN: Page level plugins -->
<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/fancybox/source/jquery.fancybox.pack.js" type="text/javascript"></script>
<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js" type="text/javascript"></script>
<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js" type="text/javascript"></script>
<!-- BEGIN:File Upload Plugin JS files-->
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/jquery-file-upload/js/vendor/jquery.ui.widget.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/jquery-file-upload/js/vendor/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/jquery-file-upload/js/vendor/load-image.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/jquery-file-upload/js/vendor/canvas-to-blob.min.js"></script>
<!-- blueimp Gallery script -->
<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/jquery-file-upload/blueimp-gallery/jquery.blueimp-gallery.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/jquery-file-upload/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/jquery-file-upload/js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/jquery-file-upload/js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/jquery-file-upload/js/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/jquery-file-upload/js/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/jquery-file-upload/js/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin -->
<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/jquery-file-upload/js/jquery.fileupload-validate.js"></script>
<!-- The File Upload user interface plugin -->
<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/jquery-file-upload/js/jquery.fileupload-ui.js"></script>
<!-- The main application script -->
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
<!--[if (gte IE 8)&(lt IE 10)]>
    <script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/jquery-file-upload/js/cors/jquery.xdr-transport.js"></script>
    <![endif]-->
<!-- END:File Upload Plugin JS files-->
<!-- END: Page level plugins -->
	<script src="<?php bloginfo('template_url'); ?>/assets/admin/pages/scripts/inbox.js" type="text/javascript"></script>
<script>

jQuery(document).ready(function() {       
   // initiate layout and plugins
   Metronic.init(); // init metronic core components
Layout.init(); // init current layout
Demo.init(); // init demo features
   //Inbox.init();
});

</script>
<style>
.inbox td.text-right{
	width:175px !important;
}
div.checker span, div.checker{
	width:20px !important;
}
.projects{
	margin-top: 79px;
	
}
</style>
<?php //get_sidebar(); ?>
<?php get_footer(); ?>