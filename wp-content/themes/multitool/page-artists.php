<?php
/**
 * Template Name: Artists > Browse
 *
 * The template and functionality for displaying an account
 * @package WordPress
 * @subpackage Multitool
 * @since We Cross 1.0
 */
get_header(); 
the_post();
$i=0;
$servername = "213.187.242.145";
$username = "wecrossdata";
$password = "Rpr5VCSmte3K99ZK";
$dbname = "dataviews";

// Create connection
$conn = new mysqli($servername, $username, $password,$dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$sql = "SELECT * FROM D028";
$result = $conn->query($sql);

$getcolumns[] = array('id' => 'col_name', 'label'=>'Name');
$getcolumns[] = array('id' => 'col_totaltracks', 'label'=>'Total in Repetoire');
$getcolumns[] = array('id' => 'col_totalplays', 'label'=>'Nr of Plays');
$getcolumns[] = array('id' => 'col_totalchannels', 'label'=>'Nr of Facebook Fans');
$getcolumns[] = array('id' => 'col_gigs', 'label'=>'Nr of Gigs');
$getcolumns[] = array('id' => 'col_totalnews', 'label'=>'Nr of newsitems');

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = array(
        	'id' => $row['sq1_aid'],
			'name' => $row['sq1_name'],
			'totaltracks' => $row['sq1_total_in_repetoire'],
			'deltaplays' => $row['sq1_nr_of_plays'],
			'totalchannels' => $row['dff_maxFacebookFans'],
			'nrofgigs' => $row['nr_of_gigs'],
			'totalnews' => $row['nr_of_gigs']
		);
    }
} else {
    echo "0 results";
}

/*$i = 0;

while($i<100){
	$data[] = array(
		'name' => 'artistname',
		'totaltracks' => 20,
		'deltaplays' => 400,
		'totalchannels' => 5,
		'nrofgigs' => 10,
		'totalnews' => 40
	);
$i++;
}*/
?>
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
			<!-- BEGIN PAGE HEADER-->
			<!-- BEGIN PAGE HEAD -->
			<div class="page-head">
				<!-- BEGIN PAGE TITLE -->
				<div class="page-title">
					<h1><?php the_title(); ?><small></h1>
				</div>
				<!-- END PAGE TITLE -->
			</div>
			<!-- END PAGE HEAD -->
			<!-- BEGIN PAGE BREADCRUMB -->
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<a href="/">Artists</a>
					<i class="fa fa-circle"></i>
				</li>
				<li>
					<a href="#">Browse</a>
				</li>
			</ul>
			<!-- END PAGE BREADCRUMB -->
			<!-- END PAGE HEADER-->
			<?php 
				getJiraLink();
			?>
			<!-- BEGIN PAGE CONTENT-->			
			<!-- row data -->
			<div class='row'>
				<div class='col-md-12'>
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
								<?php 
									foreach($data as $row){ 
								?>
								<tr class='browse-row' id='row_<?php echo $row['id']; ?>' data-id='<?php echo $row['id']; ?>'>
									<?php 
										foreach($row as $column => $columndata){
											if($column!='id'){
									?>			
												<td class='<?php echo $column ?>'>
													 <?php echo $columndata; ?>
												</td>
									<?php			
											} 
									?>
									
									<?php
										} 
									?>
							</tr>
								<?php
										$i++;
								 	}
								?>
							</tbody>
							</table>
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
    	console.log($(this).attr('data-id'));
	     //window.location = 'detail?id='+$(this).attr('data-id');
	     window.location = 'detail';
    });
        
       
});

</script>
<?php //get_sidebar(); ?>
<?php get_footer(); ?>