<?php
/**
 * Template Name: Projectmanager Page
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
//

 /*if (isset($_POST)){
	 
	 $obj=json_decode($_POST);
	 // print_r($_POST);
	 echo $obj->project_name;
	 echo $obj->project_user;
	// 
	//echo $project_name = $_GET['project_name'];
	 //echo $project_user = $_GET['project_user'];
	 
//$projectname = json_decode($project_name);
//$projectuser = json_decode($project_user);
$response = array('1'=>$obj->project_name,'2'=>$obj->project_user);
//echo $response;

  echo json_encode($response);
 }*/

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
					<!--<a href="#">...</a>-->
				</li>
			</ul>
			<!-- END PAGE BREADCRUMB -->
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->			
			<!-- row data -->
			
			<div class="alert alert-success alert-dismissible" role="alert" style="display:none;">
	 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	 The row has been updated.</div>
			
			
		 
			
			
			<div class='row'>
				<div class='col-md-12'>
			  
					<div class="portlet light bordered">
					 <?php do_action( 'cpm_project_form', $project ); ?>
						 <div class="cpm-projects" style="left:0 !important; position:relative !important;float:right;">
							      <?php if ( $can_create_project ) { ?>
        <nav class="cpm-new-project">
            <a href="#" id="cpm-create-project"><span><?php _e( 'New Project', 'cpm' ); ?></span></a>
        </nav>
    <?php } ?>
							 </div>
						
						<div class="portlet-body">
							 <div style="margin-top:110px;"></div>
												<div class="portlet box blue-madison">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-globe"></i>Projects Dashboard
							</div>
							<div class="tools">
								<!--<a href="javascript:;" class="reload">
								</a>
								<a href="javascript:;" class="remove">
								</a>-->
							</div>
						</div>
						<?php
						

        if ( function_exists( 'cpm_project_count' ) ) {
             //$count = cpm_project_count();
        }
		 if ( function_exists( 'cpm_project_filters' ) ) {
            //cpm_project_filters();
        }
						?>
						<div class="portlet-body">
							
							<table class="table table-striped table-bordered table-hover" id="sample_3">
							<thead>
							<tr>
								<th>
									 Projects
								</th>
								<th>
									 Progress %
								</th>
								<th>
									 Project Lead (Users)
								</th>
								<th>
									 Project Categories
								</th>
								<th>
									 Info
								</th>
								 
								<th>
									 Edit
								</th>
							</tr>
							</thead>
							<tbody>
							
								<?php
								//echo bloginfo('template_url'); 
								 //
								 //echo '<pre>';print_r($projects);exit;
								 foreach ($projects as $project) {
									// echo $id = $project->ID;
									$user = $project->users;
									$key = key($project->users);
									
									
									
                if ( !$project_obj->has_permission( $project ) ) {
                    continue;
                }
								
								?>
						<form name="gridEdit" action="<?php echo get_site_url(); ?>/projects/" id="gridEdit-<?php echo $project->ID; ?>" method="POST">
						<tr id="<?php echo $project->ID; ?>"> 
								<td>
									 <input type="text" class="form-control"  disabled="disabled" id="project-name-<?php echo $project->ID; ?>" name="project-name" value="<?php echo get_the_title( $project->ID ); ?>" />
								</td>
								<td> 
								<div class="progress progress-striped">
								<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
									<span class="sr-only">60% Complete </span>
								</div>
								</div>
									<?php
										
										echo cpm_task_completeness( $progress['total'], $progress['completed'] );
									?>
								</td>
								<td>
								<input type="text" class="form-control"  disabled="disabled" id="project-user-<?php echo $project->ID; ?>" name="project-user" value="<?php echo $user[$key]['name']; ?>" />
								
									
								</td>
								<td>
									<?php echo 'Projects';//echo $project->info->todolist; ?>	
								</td>
							 <td>
									<a  class="btn btn-xs default btn-editable" href="/project/?project_id=<?php echo $project->ID; ?>"><i class="fa fa-share"></i> View</a>
							 
								 
								<td>
								
									<a class="btn blue edit-field" href="#" id="editBtn-<?php echo $project->ID; ?>" rel="<?php echo $project->ID; ?>">
									Edit </a>
									<button class="btn green-haze btn-circle" style="display:none;" name="<?php echo $project->ID; ?>" id="saverowBtn-<?php echo $project->ID; ?>"><i class="fa fa-check"></i> Save</button>
									<input type="hidden" value="1" name="editFormSubmit" />
									
								</td>
							</tr>
							 <?php } ?>
							</form>
							</tbody>
							</table>
							<?php cpm_pagination( $total_projects, $limit, $pagenum ); ?>
						</div>
					</div>

							 <?php //cpm_project_form(); ?>
							 
						<?php if ( $can_create_project ) { ?>

    <div id="cpm-project-dialog" style="display:none; z-index:999;" title="<?php _e( 'Start a new project', 'cpm' ); ?>">
        <?php cpm_project_form(); ?>
    </div>

    <div id="cpm-create-user-wrap" title="<?php _e( 'Create a new user', 'cpm' ); ?>">
        <?php cpm_user_create_form(); ?>
    </div>

    <script type="text/javascript">
	  
	 /* handle edit/save button UI display */
	 
		$('.edit-field').click(function(){
			var rel = $(this).attr('rel');
			var id = $(this).attr('id');
			var newId = 'project-name-'+id;
			var trid = $(this).closest('tr').attr('id'); // table row ID 
			   $('tr#'+trid+':has(input)').each(function() {
			var row = this;
			$('input', this).each(function() {
				$(this).prop('disabled', false);
				$('#'+id).hide();
				$('#saverowBtn-'+rel).show();
			});
			});
			e.preventDefault();
		});
			$('.btn-circle').click(function(){
			 var name = $(this).attr('name');
			  //alert(name);
				var id = $(this).attr('id');
				var newId = 'project-name-'+id;
				var trid = $(this).closest('tr').attr('id'); // table row ID 
 
				$('tr#'+trid+':has(input)').each(function() {
				var row = this;
				$('input', this).each(function() {
					
					var vals=$(this).val();
//alert(vals);

					 var project_name=$('#project-name-'+name+'').val();
 
	var project_user= $('#project-user-'+name).val();
	 //alert(project_name);
	//alert(project_user);
					
					 if(vals ==''){
						 alert('Please fill the values');
					 }else{
						 
					 }
					  /* handling Edit/save action triggering */
						  //alert(name);
		$('#gridEdit-'+name+'').submit(function(e){
 	 

	
	
	//info[0] = project_name;
//info[1] = project_user;
	
	 
	//var postData = $(this).serializeArray();

	var formURL = $(this).attr("action");	
	//alert(formURL);
	
	var d = {};
	d['project_name'] = project_name;
	d['project_user'] = project_user;
	
	$.ajax(
	{
		url : '<?php bloginfo('template_url'); ?>/page-ajax-controller.php',
		type: "POST",
		data: d,
		dataType: "json",
		success:function(response) 
		{
			//alert('Ok');
			//alert(response);
			$(".alert-dismissible").show();
			$('#saverowBtn-'+name).hide();
			$('#editBtn-'+name).show();
				$(this).prop('disabled', true);
			
		// $('.btn-circle').html(msg);
		 // var res = response.d;
                        
		},
		error: function(jqXHR, textStatus, errorThrown) 
		{
			alert('not-submited');
		}
	});
    e.preventDefault();	//STOP default action
});
	
$("#gridEdit").submit(); //SUBMIT FORM
					//$(this).prop('disabled', false);
					//$('#'+id).hide();
					$('#saverowBtn-'+rel).show();
				});
				});
				e.preventDefault();
			});
			
	
	
        jQuery(function($) {
            $( "#cpm-project-dialog" ).dialog({
                autoOpen: false,
                modal: true,
                dialogClass: 'cpm-ui-dialog',
                width: 485,
                height: 430,
                position:['middle', 100],
                zIndex: 9999,

            });
        });

        jQuery(function($) {
            $( "#cpm-create-user-wrap" ).dialog({
                autoOpen: false,
                modal: true,
                dialogClass: 'cpm-ui-dialog cpm-user-ui-dialog',
                width: 400,
                height: 'auto',
                position:['middle', 100],
            });
        });
    </script>
<?php } ?>	
							
							<?php    //do_action( 'cpmf_project_tab', $project_id, $tab, $action ); //the_content(); ?>
						</div>
					</div>
				</div>
			</div>
			<!-- end row data -->
		<!-- END PAGE CONTENT-->
	</div>
</div>
<!-- END CONTENT -->
	
	
<script>

jQuery(document).ready(function() {  
 weDevs_CPM.init();
/*   
   // initiate layout and plugins
	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	Demo.init(); // init demo features
	TableAdvanced.init();
   */
});

</script>
<?php //get_sidebar(); ?>
<?php get_footer(); ?>