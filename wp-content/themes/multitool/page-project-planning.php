<?php
/**
 * Template Name: Planning Page
 *
 * The template and functionality for displaying an account
 * @package WordPress
 * @subpackage Multitool
 * @since We Cross 1.0
 */
get_header(); 
the_post();
/**
* Get the init for the CPM projects
* @param : CPM_project::getInstance()
* @return : List of projects
* @Usage : Pulling projects to the drop down list 
*/
$project_obj = CPM_Project::getInstance();
$projects = $project_obj->get_projects();
$project_id=$_REQUEST['project_id']; 
?>
<!-- Get the left side bar menu -->
<?php getMenuAgileProjects(); ?> 
<!-- END HERE -->
	<!-- BEGIN CONTENT -->
<link rel='stylesheet' id='dhtml_style-css'  href='http://wecross.dev.wecross.nl/wp-content/plugins/cpm-gantt-chart/assets/css/dhtmlxgantt.css?ver=4.2.2' type='text/css' media='' />
<link href="http://wecross.dev.wecross.nl/wp-content/plugins/cpm-gantt-chart/assets/css/gant.css?ver=4.2.2"/>
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
		<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					 
						<div class="portlet light">
							 
							<div class="portlet-body">
								<div class="tabbable">
									<ul class="nav nav-tabs">
										<li class="active">
											<a href="#tab_calendar" data-toggle="tab">
											Calendar  </a>
										</li>
										<li>
											<a href="#tab_gantt" data-toggle="tab">
											Gantt Chart </a>
										</li> 
									</ul>
									<div class="tab-content no-space">
										<div class="tab-pane active" id="tab_calendar">
											  
											<div class="row">
				<div class="col-lg-12">
					<div class="portlet box green-meadow calendar">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-gift"></i>Calendar
							</div>
						</div>
						<div class="portlet-body">
							<div>
								 
								 
								 <div class="icon32" id="icon-themes"><br></div>
<h2><?php //_e( 'Calendar', 'cpm'); ?></h2>

<?php
if ( cpm_get_option( 'task_start_field' ) == 'on' ) {
    $eventDurationEditable = 'true';
} else {
    $eventDurationEditable = 'false';
}

if ( !is_admin() ) {
    $fornt_instant = 'cpmf_url:' . json_encode( get_permalink() );
} else {
    $fornt_instant = 'url:' . json_encode( admin_url() );
}
?>
<?php
    $porjects = CPM_Project::getInstance()->get_projects();
    unset($porjects['total_projects']);
?>
<?php
    if ( isset($_POST['calender-project']) ) {
        $project_id = isset( $_POST['project_id'] ) ? absint( $_POST['project_id'] ) : '0';
    } else {
        $project_id = '0';
    }
?>
<!--
<form action="" method="post">
    <?php
       // $get_values = isset( $_GET ) ? $_GET : array();

       /* foreach ( $get_values as $name => $get_value ) {
            ?>
            <!-- <input type="hidden" name="<?php echo $name; ?>" value="<?php echo $get_value; ?>"> -->
            <?php
        }*/
    ?>
    <select name="project_id">
        <option value="-1"><?php //_e( '-Select Project' ); ?></option>
        <?php
            //foreach ( $porjects as $key => $project ) {
                ?>
                <option <?php // selected( $project->ID, $project_id ); ?> value="<?php //echo $project->ID; ?>" ><?php //echo $project->post_title; ?></option>
                <?php
         //   }

        ?>
    <select>
    <input type="submit" name="calender-project" value="<?php //_e( 'Filter', 'cpm' ); ?>" class="btn button-primary">
</form>
-->

</form>
<div id='calendar' class="cpm-calendar">
    <div class="cpm-calender-loading"></div>
</div>

<script>
    jQuery(document).ready(function($) {
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();


        var calendar = $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            editable: true,
            eventStartEditable: true,
            eventDurationEditable: <?php echo $eventDurationEditable; ?>,

            events: {
                url: CPM_Vars.ajaxurl,
                type: 'POST',
                data: {
                    action: 'cpm_get_events',
                    _wpnonce: CPM_Vars.nonce,
                    <?php echo $fornt_instant; ?>,
                    project_id: <?php echo $project_id; ?>


                },
                beforeSend: function(e) {
                    $('#calendar .cpm-calender-loading').addClass('active');
                },
                success: function(res) {
                   $('#calendar .cpm-calender-loading').removeClass('active');
                },
                error: function() {
                    alert('There was an error while fetching events!');
                }
            },

            eventRender: function(event, element, calEvent) {

                if(element.hasClass('cpm-calender-todo')) {

                    var current = new Date(),
                        currentYear = current.getFullYear(),
                        currentMonth = current.getMonth(),
                        currentDay = current.getDate(),
                        currentTime = new Date( currentYear,currentMonth,currentDay );

                    var end = null;
                    if( event.end === null) {
                        end = new Date( event.start );
                    } else {
                        end = new Date( event.end );
                    }

                    var endYear = end.getFullYear(),
                        endMonth = end.getMonth(),
                        endDay = end.getDate(),
                        endTime = new Date( endYear, endMonth, endDay );

                    if( currentTime.getTime() <= endTime.getTime() ) {
                        // console.log('current time choto');
                       element.removeClass('cpm-expire-task');
                       element.addClass('cpm-task-running');

                    } else {
                        // console.log('current time boro');
                        element.removeClass('cpm-task-running');
                        element.addClass('cpm-expire-task');
                    }

                    if(event.complete_status == 'yes') {
                        element.removeClass('cpm-task-running');
                        element.removeClass('cpm-expire-task');
                        element.addClass('cpm-complete-task');
                    }
                }

                if( event.img != 'undefined' && element.hasClass('cpm-calender-todo') ) {
                    element.find('.fc-event-title').before( $("<span class=\"fc-event-icons\">"+event.img+"</span>") );
                }
            },

            eventDrop: function( event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view ) {
                CpmUpdateStartEndMeata(event);
            },
            eventResize: function(event,dayDelta,minuteDelta,revertFunc) {
                CpmUpdateStartEndMeata(event);
            },
        });

        function CpmUpdateStartEndMeata(event) {
            if(event.start != null) {
                var start_date = new Date(event.start),
                    start_date = $.datepicker.formatDate('dd M yy', start_date);
            } else {
                start_date = '';
            }

            if(event.end != null) {
                var end_date = new Date( event.end ),
                end_date = $.datepicker.formatDate('dd M yy', end_date);
            } else {
                var end_date = '';
            }

            var data = {
                action: 'cpm_calender_update_duetime',
                _wpnonce: CPM_Vars.nonce,
                task_id: event.id,
                start_date: start_date,
                end_date : end_date,
                project_id: <?php echo $project_id; ?>

            };

            $.post(CPM_Vars.ajaxurl, data );
        }

    });
</script>
				
								 
								 
								 
								 
							</div>
							<!-- END CALENDAR PORTLET-->
						</div>
					</div>
				</div>
			</div> 
											  
											  
										</div>
										<div class="tab-pane" id="tab_gantt">
											 
											 
											 <?php
											 $project_id=$_REQUEST['project_id']; 
											 
$task_obj = CPM_Task::getInstance();
/*
if ( cpm_user_can_access( $project_id, 'tdolist_view_private' ) ) {
    $lists = $task_obj->get_task_lists( $project_id, true );
} else {
    $lists = $task_obj->get_task_lists( $project_id );
}*/
 $lists = $task_obj->get_task_lists( $project_id );
 //cpm_get_header( __( 'Gantt Chart', 'cpm-gantt' ), $project_id );

if ( $lists ) {

    foreach ($lists as $list) {
        $list_url = cpm_url_single_tasklist( $project_id, $list->ID );
        $start_date = get_post_meta( $list->ID, '_start', true );

        $end_date = get_post_meta( $list->ID, '_due', true );
        $start_date = ( empty( $start_date ) && empty( $end_date ) ) ? $list->post_date : $start_date;
        $start_date = empty( $start_date ) ? $end_date : $start_date;

        $list_links = get_post_meta( $list->ID, '_link', true );

        if ( is_array( $list_links ) ) {
            foreach ( $list_links as $list_link_id ) {
                if ( 'publish' == get_post_status ( $list_link_id ) ) {
                    $link_chart[] = array(
                        'source' => $list->ID,
                        'target' => $list_link_id,
                        'type' => 1
                    );
                }
            }
        }

        $tasks = $task_obj->get_tasks_by_access_role( $list->ID , $project_id );

        $tasks = cpm_tasks_filter( $tasks );

        if ( count( $tasks['pending'] ) ) {
            $lists_chart[] = array(
                'id'        => $list->ID,
                'text'      => '<a href="'.$list_url.'">'.$list->post_title.'</a>',
                'open'      => true,
                'task_list' => true
            );
        } else {
            $list_duration = gant_date_duration( $start_date, $end_date );
            $list_duration = ( $list_duration < 0 ) ? 1 : $list_duration;
            $lists_chart[] = array(
                'id'         => $list->ID,
                'text'       => '<a href="'.$list_url.'">'.$list->post_title.'</a>',
                'open'       => true,
                'task_list'  => true,
                'start_date' => date( 'd-m-Y', strtotime( $start_date ) ),
                'duration'   => $list_duration,
            );
        }

        if ( count( $tasks['pending'] ) ) {
            foreach ($tasks['pending'] as $task) {
                $task_url        = cpm_url_single_task( $project_id, $list->ID, $task->ID );
                $task_start_date = get_post_meta( $task->ID, '_start', true );
                $end_date        = get_post_meta( $task->ID, '_due', true );
                $task_start_date = ( empty( $task_start_date ) && empty( $end_date ) ) ? $task->post_date : $task_start_date;
                $task_start_date = empty( $task_start_date ) ? $end_date : $task_start_date;


                $task_links = get_post_meta( $task->ID, '_link', true );

                if ( empty( $end_date ) ) {
                    $duration = 1;
                } else {
                    $duration = gant_date_duration( $task_start_date, $end_date );
                    $duration = ( $duration < 0 ) ? 1 : $duration;
                }

                $assigend = get_post_meta( $task->ID, '_assigned', true );
                $avatar[] =  array( 'task_id' => $task->ID, 'avatar' => get_avatar($assigend, 16) );
                $tasks_chart[] = array(
                    'id'         => $task->ID,
                    'text'       => '<a href="'.$task_url.'">'.$task->post_title.'</a>',
                    'start_date' => date( 'd-m-Y', strtotime( $task_start_date ) ),
                    'duration'   => $duration,
                    'parent'     => $list->ID,
                    'progress'   => round(get_post_meta( $task->ID, '_completed', true ), 2 ),
                    'owner'      => $list->ID,
                );

                $link_chart[] = array(
                    'source' => $list->ID,
                    'target' => $task->ID,
                    'type' => 2
                );

                if ( is_array( $task_links ) ) {
                    foreach ( $task_links as $task_link_id ) {
                        if ( 'publish' == get_post_status ( $task_link_id ) ) {
                            $link_chart[] = array(
                                'source' => $task->ID,
                                'target' => $task_link_id,
                                'type'   => 1
                            );
                        }
                    }
                }
            }
        }
    }
}
//$users = CPM_Project::getInstance()->get_users( $project_id );

$link_chart = isset( $link_chart ) ? $link_chart : array();
$tasks_chart = isset( $tasks_chart ) ? $tasks_chart : array();
$lists_chart = isset( $lists_chart ) ? $lists_chart : array();
$avatar = isset( $avatar ) ? $avatar : array();

foreach ( $link_chart as $key => $link_chart_val ) {
    $link_chart[$key]['id'] = $key;
}
$assign[] = array(
    'key' => '-1',
    'label' => __( '--Select--', 'cpm-gantt' )
);

/*foreach ( $users as $key => $user ) {
    $assign[] = array(
        'key' => $user['id'],
        'label' => $user['name']
    );
}*/

$link = json_encode( $link_chart );
$todo = json_encode( $lists_chart );
$task = json_encode( $tasks_chart );
$assigned_user = json_encode( array_values( $assign ) );
$avatars = json_encode( $avatar );

?>
<style>
.gantt_task_progress{
    text-align:left;
    padding-left:10px;
    box-sizing: border-box;
    color:white;
    font-weight: bold;
}
.gantt-wrap .gantt_task a {
    color: white;
}
.gantt_add {
    content: ' ';
    height: 20px;
    width: 20px;
}
.gantt-wrap .avatar {
    margin: 0 10px 0 0  ;
}
.fc-header-title{
	position:absolute;
    left: 10px;
	top:5px;
}
.fc-header{
	  margin-top: -22px;
}
.fc-header-left{
	text-align: right;
    width: 100%;
}
.portlet.calendar .fc-button{
	top: -35px;
}
.portlet.calendar .fc-header {
    margin-bottom: -14px;
}
</style>
<div class="gantt-wrap" style="width:100%; height:400px; margin-top: 10px;"></div>

<script>
    window.gantt_link = <?php echo $link; ?>;
    window.gantt_todo = <?php echo $todo; ?>;
    window.gantt_task = <?php echo $task; ?>;
    window.assigned_user = <?php echo $assigned_user; ?>;
    window.avatars = <?php echo $avatars; ?>;

</script>

											 
											 
										</div>
										 
									</div>
								</div>
							</div>
						</div>
					 
				</div>
			</div>
			<!-- END PAGE CONTENT-->
 
	</div>
</div>

<script type="text/javascript" src="http://wecross.dev.wecross.nl/wp-content/plugins/cpm-gantt-chart/assets/js/gantt-library.js?ver=4.2.2"></script>
<script type="text/javascript" src="http://wecross.dev.wecross.nl/wp-content/plugins/cpm-gantt-chart/assets/js/gant.js?ver=4.2.2"></script>
 
<!-- END CONTENT -->
	<!-- BEGIN PAGE LEVEL PLUGINS -->
<!-- IMPORTANT! fullcalendar depends on jquery-ui.min.js for drag & drop support -->
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/assets/global/plugins/moment.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/assets/global/plugins/fullcalendar/fullcalendar.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/assets/admin/pages/scripts/calendar.js"></script>

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
 
    
});

</script> 
 
<style>
.col-md-9{
	width:100% !important;
}
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
.fc-header tr td{
	border-width:0;
	border-style:none;
}
    
</style>
<?php //get_sidebar(); ?>
<?php get_footer(); ?>