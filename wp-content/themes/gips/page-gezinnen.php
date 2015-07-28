<?php
/**
 * Template Name: Gezinnen > Browser
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

			<!-- BEGIN PAGE BREADCRUMB -->
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<a href="/">Home</a>
					<i class="fa fa-circle"></i>
				</li>
				<li>
					<a href="/Professionals">Gezinnen</a>
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
			<div class='row'>
				<div class='col-md-12'>
					<div class="portlet light bordered">
					
						<div class="portlet-body">											
							<!-- BEGIN table portlet-->
							
							<div class="col-md-12 col-sm-12 search-table">
								<div id="sample_2_filter" class="dataTables_filter">
									<label>Zoeken:<input type="search" class="form-control input-small input-inline" placeholder="" aria-controls="sample_2"></label>
								</div>
							</div>
						</div>
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover dataTable no-footer" id="sample_2" role="grid" aria-describedby="sample_2_info">
							<thead>
							<tr role="row"><th class="sorting_asc" tabindex="0" aria-controls="sample_2" rowspan="1" colspan="1" style="width: 225px;" aria-sort="ascending" aria-label="
									 Rendering engine
								: activate to sort column ascending">
									 Naam Gezin
								</th><th class="sorting" tabindex="0" aria-controls="sample_2" rowspan="1" colspan="1" style="width: 191px;" aria-label="
									 Engine version
								: activate to sort column ascending">
									 Gezinsleden
								</th><th class="sorting" tabindex="0" aria-controls="sample_2" rowspan="1" colspan="1" style="width: 290px;" aria-label="
									 Browser
								: activate to sort column ascending">
									 Casusregisseur
								</th><th class="sorting" tabindex="0" aria-controls="sample_2" rowspan="1" colspan="1" style="width: 264px;" aria-label="
									 Platform(s)
								: activate to sort column ascending">
									 Professionals
								</th><th class="sorting" tabindex="0" aria-controls="sample_2" rowspan="1" colspan="1" style="width: 136px;" aria-label="
									 CSS grade
								: activate to sort column ascending">
									Adres
								</th></tr>
							</thead>
							<tbody>
							<tr role="row" class="odd">
								<td class="sorting_1"> <a href='/gezin'>Naam van gezin</a> </td>
								<td>Namen van de gezinsleden</td>
								<td>Naam van Casusregisseur</td>
								<td>Namen van de professionals</td>
								<td>Adres woonplaats</td>
							</tr>
							<tr role="row" class="even">
								<td class="sorting_1"> <a href='/gezin'>Naam van gezin</a> </td>
								<td>Namen van de gezinsleden</td>
								<td>Naam van Casusregisseur</td>
								<td>Namen van de professionals</td>
								<td>Adres woonplaats</td>
							</tr>
							<tr role="row" class="odd">
								<td class="sorting_1"> <a href='/gezin'>Naam van gezin</a> </td>
								<td>Namen van de gezinsleden</td>
								<td>Naam van Casusregisseur</td>
								<td>Namen van de professionals</td>
								<td>Adres woonplaats</td>
							</tr>
							<tr role="row" class="even">
								<td class="sorting_1"> <a href='/gezin'>Naam van gezin</a> </td>
								<td>Namen van de gezinsleden</td>
								<td>Naam van Casusregisseur</td>
								<td>Namen van de professionals</td>
								<td>Adres woonplaats</td>
							</tr>
							<tr role="row" class="odd">
								<td class="sorting_1"> <a href='/gezin'>Naam van gezin</a> </td>
								<td>Namen van de gezinsleden</td>
								<td>Naam van Casusregisseur</td>
								<td>Namen van de professionals</td>
								<td>Adres woonplaats</td>
							</tr>
							<tr role="row" class="even">
								<td class="sorting_1"> <a href='/gezin'>Naam van gezin</a> </td>
								<td>Namen van de gezinsleden</td>
								<td>Naam van Casusregisseur</td>
								<td>Namen van de professionals</td>
								<td>Adres woonplaats</td>
							</tr>
							<tr role="row" class="odd">
								<td class="sorting_1"> <a href='/gezin'>Naam van gezin</a> </td>
								<td>Namen van de gezinsleden</td>
								<td>Naam van Casusregisseur</td>
								<td>Namen van de professionals</td>
								<td>Adres woonplaats</td>
							</tr>
							<tr role="row" class="even">
								<td class="sorting_1"> <a href='/gezin'>Naam van gezin</a> </td>
								<td>Namen van de gezinsleden</td>
								<td>Naam van Casusregisseur</td>
								<td>Namen van de professionals</td>
								<td>Adres woonplaats</td>
							</tr>
							<tr role="row" class="odd">
								<td class="sorting_1"> <a href='/gezin'>Naam van gezin</a> </td>
								<td>Namen van de gezinsleden</td>
								<td>Naam van Casusregisseur</td>
								<td>Namen van de professionals</td>
								<td>Adres woonplaats</td>
							</tr>
							<tr role="row" class="even">
								<td class="sorting_1"> <a href='/gezin'>Naam van gezin</a> </td>
								<td>Namen van de gezinsleden</td>
								<td>Naam van Casusregisseur</td>
								<td>Namen van de professionals</td>
								<td>Adres woonplaats</td>
							</tr>
							<tr role="row" class="odd">
								<td class="sorting_1"> <a href='/gezin'>Naam van gezin</a> </td>
								<td>Namen van de gezinsleden</td>
								<td>Naam van Casusregisseur</td>
								<td>Namen van de professionals</td>
								<td>Adres woonplaats</td>
							</tr>
							<tr role="row" class="even">
								<td class="sorting_1"> <a href='/gezin'>Naam van gezin</a> </td>
								<td>Namen van de gezinsleden</td>
								<td>Naam van Casusregisseur</td>
								<td>Namen van de professionals</td>
								<td>Adres woonplaats</td>
							</tr>
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