<?php
/**
 * Template Name: Productions > Details Repetoire
 *
 * The template and functionality for displaying an account
 * @package WordPress
 * @subpackage Multitool
 * @since We Cross 1.0
 */
get_header(); 
the_post();

// demo columns = on production data, this comes from livedata set for this page
$getcolumns[] = array('id' => 'col_tid', 'label'=>'Track ID');
$getcolumns[] = array('id' => 'col_name', 'label'=>'Trackname');
$getcolumns[] = array('id' => 'col_nrofmedia', 'label'=>'Nr of Media');
$getcolumns[] = array('id' => 'col_trusted', 'label'=>'Trusted');
$getcolumns[] = array('id' => 'col_totalplays', 'label'=>'Nr of Plays');
$getcolumns[] = array('id' => 'col_deltaplays', 'label'=>'Delta Plays');
$getcolumns[] = array('id' => 'col_yt', 'label'=>'<img src="http://engine.intothetune.com/img/social/youtube.png">');
$getcolumns[] = array('id' => 'col_sc', 'label'=>'<img src="http://engine.intothetune.com/img/social/soundcloud.png">');
$getcolumns[] = array('id' => 'col_vim', 'label'=>'<img src="http://engine.intothetune.com/img/social/vimeo.png">');
$getcolumns[] = array('id' => 'col_lfm', 'label'=>'<img src="http://engine.intothetune.com/img/social/lastfm.png">');

// demo dataset > to do, data set from data bigquery and main data-server
$i = 0;
while($i<100){
$data[] = array(
	'id' => 300,
	'name' => 'artistname',
	'nrofmedia' => 4,
	'trusted' => 'yes',
	'totalplays' => 10000,
	'deltaplays' => 400,
	'youtubeplays' => 1000,
	'soundcloudplays' => 200,
	'vimeoplays' => 600,
	'lastfmplays' => 2000,
);
$i++;
}

?><?php getMenuProductions(); ?>
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
					<a href="/">Productions</a>
					<i class="fa fa-circle"></i>
				</li>
				<li>
					<a href="#">...</a>
				</li>
			</ul>
			<!-- END PAGE BREADCRUMB -->
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->			
			<!-- row data -->
			
			<div class="row">
				<div class="col-md-12">
				<div class='portlet light'>
						<div class="portlet-title">
							<div class="caption font-green-haze">
								<i class="icon-settings font-green-haze"></i>
								<span class="caption-subject bold uppercase">Repetoire</span>
							</div>
							<div class="actions">
								<a class="btn btn-circle btn-icon-only blue" href="javascript:;">
								<i class="icon-cloud-upload"></i>
								</a>
								<a class="btn btn-circle btn-icon-only green" href="javascript:;">
								<i class="icon-wrench"></i>
								</a>
								<a class="btn btn-circle btn-icon-only red" href="javascript:;">
								<i class="icon-trash"></i>
								</a>
								<a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;" data-original-title="" title="">
								</a>
							</div>
						</div>
						
						<div class="portlet-body form">
							<form role="form" class="form-horizontal">
								<div class="form-body">
									<div class="form-group form-md-line-input">
										<label class="col-md-2 control-label" for="form_control_1">State</label>
										<div class="col-md-10">
											<div class="md-checkbox-inline">
												<div class="md-checkbox">
													<input type="checkbox" id="checkbox50" class="md-check">
													<label for="checkbox50">
													<span></span>
													<span class="check"></span>
													<span class="box"></span>
													new </label>
												</div>
												<div class="md-checkbox">
													<input type="checkbox" id="checkbox51" class="md-check" checked="">
													<label for="checkbox51">
													<span></span>
													<span class="check"></span>
													<span class="box"></span>
													unpromoted</label>
												</div>
												<div class="md-checkbox">
													<input type="checkbox" id="checkbox52" class="md-check">
													<label for="checkbox52">
													<span></span>
													<span class="check"></span>
													<span class="box"></span>
													promoted </label>
												</div>
												<div class="md-checkbox">
													<input type="checkbox" id="checkbox53" class="md-check">
													<label for="checkbox53">
													<span></span>
													<span class="check"></span>
													<span class="box"></span>
													prelist </label>
												</div>
												<div class="md-checkbox">
													<input type="checkbox" id="checkbox54" class="md-check">
													<label for="checkbox54">
													<span></span>
													<span class="check"></span>
													<span class="box"></span>
													inlist </label>
												</div>
												<div class="md-checkbox">
													<input type="checkbox" id="checkbox55" class="md-check">
													<label for="checkbox55">
													<span></span>
													<span class="check"></span>
													<span class="box"></span>
													postlist </label>
												</div>
												<div class="md-checkbox">
													<input type="checkbox" id="checkbox56" class="md-check">
													<label for="checkbox56">
													<span></span>
													<span class="check"></span>
													<span class="box"></span>
													no media </label>
												</div>
											</div>
										</div>
									</div>
									
									<div class="form-group form-md-line-input">
										<label class="col-md-2 control-label" for="form_control_1">Checked</label>
										<div class="col-md-10">
											<div class="md-checkbox-inline">
												<div class="md-checkbox">
													<input type="checkbox" id="checkbox60" class="md-check" checked="">
													<label for="checkbox60">
													<span></span>
													<span class="check"></span>
													<span class="box"></span>
													yes </label>
												</div>
												<div class="md-checkbox">
													<input type="checkbox" id="checkbox61" class="md-check">
													<label for="checkbox61">
													<span></span>
													<span class="check"></span>
													<span class="box"></span>
													no</label>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group form-md-line-input">
										<label class="col-md-2 control-label" for="form_control_1">No Follow</label>
										<div class="col-md-10">
											<div class="md-checkbox-inline">
												<div class="md-checkbox">
													<input type="checkbox" id="checkbox70" class="md-check">
													<label for="checkbox70">
													<span></span>
													<span class="check"></span>
													<span class="box"></span>
													yes </label>
												</div>
												<div class="md-checkbox">
													<input type="checkbox" id="checkbox71" class="md-check" checked="">
													<label for="checkbox71">
													<span></span>
													<span class="check"></span>
													<span class="box"></span>
													no</label>
												</div>
											</div>
										</div>
									</div>
									<div class="form-actions">
									<div class="row">
										<div class="col-md-offset-2 col-md-10">
											
											<button type="button" class="btn blue">Filter</button>
											<button type="button" class="btn default">Reset</button>
										</div>
									</div>
									</div>
									<div class="portlet light bordered">
						
						<div class="portlet-body">
							
							<table class="table table-striped table-bordered table-hover" id="browserlist">
								<div class="actions">
								<div class="btn-group">
									<a class="btn default" href="javascript:;" data-toggle="dropdown">
									Columns <i class="fa fa-angle-down"></i>
									</a>
									<div id="browserlist_column_toggler" class="dropdown-menu hold-on-click dropdown-checkboxes pull-right">
										<?php foreach($getcolumns as $select ){ ?>
										<label><input type="checkbox" checked data-column="<?php echo $select['id'] ?>"><?php echo $select['label']; ?></label>
										<?php } ?>
									</div>
								</div>
							</div>
							<thead>
							<tr>
								<?php foreach($getcolumns as $select ){ ?>
								<th class='<?php echo $select['id'] ?>'>
									 <?php echo $select['label']; ?>
								</th>
								<?php } ?>
								
							</tr>
							</thead>
							<tbody>
								<?php foreach($data as $row){ ?>
								<tr class='browse-row' id='row<?php echo $i; ?>' data-id='<?php echo $row['id']; ?>'>
								<?php foreach($row as $column => $columndata ){ ?>
								<td class='<?php echo $column ?>'>
									 <?php echo $columndata; ?>
								</td>
								<?php } ?>
							</tr>
							<?php $i++; }  ?>
							</tbody>
							</table>
						</div>
					</div>
								</div>
							</form>
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
	<script src="<?php bloginfo('template_url'); ?>/assets/admin/pages/scripts/components-pickers.js"></script>
<script>
jQuery(document).ready(function() {       
   // initiate layout and plugins
   Metronic.init(); // init metronic core components
   Layout.init(); // init current layout
   Demo.init(); // init demo features
   
   // browser code = enable the browser functionality > column ordering and column-selection, search functions and nr.of items   
   	var table = $('#browserlist');
    var oTable = table.dataTable({
        "language": {
            "aria": {
                "sortAscending": ": activate to sort column ascending",
                "sortDescending": ": activate to sort column descending"
            },
            "emptyTable": "No data available in table",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty": "No entries found",
            "infoFiltered": "(filtered1 from _MAX_ total entries)",
            "lengthMenu": "Show _MENU_ entries",
            "search": "Search:",
            "zeroRecords": "No matching records found"
        },
        "order": [
            [0, 'asc']
        ],
        "lengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "pageLength": 10, // set the initial value,
        "columnDefs": [{  // set default column settings
            'orderable': false,
            'targets': [0]
        }, {
            "searchable": false,
            "targets": [0]
        }],
        "order": [
            [1, "asc"]
        ]           
    });

    var oTableColReorder = new $.fn.dataTable.ColReorder( oTable );
	var tableColumnToggler = $('#browserlist_column_toggler');
    var tableWrapper = $('#browserlist_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
    tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown   
 
	$('input[type="checkbox"]', tableColumnToggler).change(function () {
        /* Get the DataTables object again - this is not a recreation, just a get of the object */
        var iCol = $(this).attr("data-column");
        $("."+iCol).toggle();
    });
    
    // onclick function on row-click on table
    $('.browse-row').click(function(){
	     window.location = 'detail?id='+$(this).attr('data-id');
    });
        
       
});

</script>
<?php //get_sidebar(); ?>
<?php get_footer(); ?>