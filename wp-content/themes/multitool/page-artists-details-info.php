<?php
/**
 * Template Name: Artists > Details Info
 *
 * The template and functionality for displaying an account
 * @package WordPress
 * @subpackage Multitool
 * @since We Cross 1.0
 */
get_header(); 
the_post();
?><?php getMenuArtists(); ?>
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
					<a href="/">Artists</a>
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
			<div class="portlet light bordered form-fit">
									<div class="portlet-title">
										<div class="caption">
											<i class="icon-user font-blue-hoki"></i>
											<span class="caption-subject font-blue-hoki bold uppercase">Form Sample</span>
											<span class="caption-helper">demo form...</span>
										</div>
										<div class="actions">
											<a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
											<i class="icon-cloud-upload"></i>
											</a>
											<a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
											<i class="icon-wrench"></i>
											</a>
											<a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
											<i class="icon-trash"></i>
											</a>
										</div>
									</div>
									<div class="portlet-body form">
										<!-- BEGIN FORM-->
										<form action="#" class="form-horizontal form-bordered form-label-stripped">
											<div class="form-body">
												<div class="form-group">
													<label class="control-label col-md-3">First Name</label>
													<div class="col-md-9">
														<input type="text" placeholder="small" class="form-control">
														<span class="help-block">
														This is inline help </span>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-3">Last Name</label>
													<div class="col-md-9">
														<input type="text" placeholder="medium" class="form-control">
														<span class="help-block">
														This is inline help </span>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-3">Gender</label>
													<div class="col-md-9">
														<select class="form-control">
															<option value="">Male</option>
															<option value="">Female</option>
														</select>
														<span class="help-block">
														Select your gender. </span>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-3">Date of Birth</label>
													<div class="col-md-9">
														<input type="text" class="form-control" placeholder="dd/mm/yyyy">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-3">Category</label>
													<div class="col-md-9">
														<div class="select2-container form-control select2_category" id="s2id_autogen27"><a href="javascript:void(0)" class="select2-choice" tabindex="-1">   <span class="select2-chosen" id="select2-chosen-28">Category 1</span><abbr class="select2-search-choice-close"></abbr>   <span class="select2-arrow" role="presentation"><b role="presentation"></b></span></a><label for="s2id_autogen28" class="select2-offscreen"></label><input class="select2-focusser select2-offscreen" type="text" aria-haspopup="true" role="button" aria-labelledby="select2-chosen-28" id="s2id_autogen28"><div class="select2-drop select2-display-none select2-with-searchbox">   <div class="select2-search">       <label for="s2id_autogen28_search" class="select2-offscreen"></label>       <input type="text" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" class="select2-input" role="combobox" aria-expanded="true" aria-autocomplete="list" aria-owns="select2-results-28" id="s2id_autogen28_search" placeholder="">   </div>   <ul class="select2-results" role="listbox" id="select2-results-28">   </ul></div></div><select class="form-control select2_category select2-offscreen" tabindex="-1" title="">
															<option value="Category 1">Category 1</option>
															<option value="Category 2">Category 2</option>
															<option value="Category 3">Category 5</option>
															<option value="Category 4">Category 4</option>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-3">Multi-Value Select</label>
													<div class="col-md-9">
														<div class="select2-container select2-container-multi form-control select2_sample1" id="s2id_autogen43"><ul class="select2-choices">  <li class="select2-search-field">    <label for="s2id_autogen44" class="select2-offscreen"></label>    <input type="text" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" class="select2-input select2-default" id="s2id_autogen44" style="width: 110%;" placeholder="">  </li></ul><div class="select2-drop select2-drop-multi select2-display-none">   <ul class="select2-results">   <li class="select2-no-results">No matches found</li></ul></div></div><select class="form-control select2_sample1 select2-offscreen" multiple="" tabindex="-1">
															<optgroup label="NFC EAST">
															<option>Dallas Cowboys</option>
															<option>New York Giants</option>
															<option>Philadelphia Eagles</option>
															<option>Washington Redskins</option>
															</optgroup>
															<optgroup label="NFC NORTH">
															<option>Chicago Bears</option>
															<option>Detroit Lions</option>
															<option>Green Bay Packers</option>
															<option>Minnesota Vikings</option>
															</optgroup>
															<optgroup label="NFC SOUTH">
															<option>Atlanta Falcons</option>
															<option>Carolina Panthers</option>
															<option>New Orleans Saints</option>
															<option>Tampa Bay Buccaneers</option>
															</optgroup>
															<optgroup label="NFC WEST">
															<option>Arizona Cardinals</option>
															<option>St. Louis Rams</option>
															<option>San Francisco 49ers</option>
															<option>Seattle Seahawks</option>
															</optgroup>
															<optgroup label="AFC EAST">
															<option>Buffalo Bills</option>
															<option>Miami Dolphins</option>
															<option>New England Patriots</option>
															<option>New York Jets</option>
															</optgroup>
															<optgroup label="AFC NORTH">
															<option>Baltimore Ravens</option>
															<option>Cincinnati Bengals</option>
															<option>Cleveland Browns</option>
															<option>Pittsburgh Steelers</option>
															</optgroup>
															<optgroup label="AFC SOUTH">
															<option>Houston Texans</option>
															<option>Indianapolis Colts</option>
															<option>Jacksonville Jaguars</option>
															<option>Tennessee Titans</option>
															</optgroup>
															<optgroup label="AFC WEST">
															<option>Denver Broncos</option>
															<option>Kansas City Chiefs</option>
															<option>Oakland Raiders</option>
															<option>San Diego Chargers</option>
															</optgroup>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-3">Loading Data</label>
													<div class="col-md-9">
														<div class="select2-container form-control select2_sample2" id="s2id_autogen59"><a href="javascript:void(0)" class="select2-choice select2-default" tabindex="-1">   <span class="select2-chosen" id="select2-chosen-60">Type to select an option</span><abbr class="select2-search-choice-close"></abbr>   <span class="select2-arrow" role="presentation"><b role="presentation"></b></span></a><label for="s2id_autogen60" class="select2-offscreen"></label><input class="select2-focusser select2-offscreen" type="text" aria-haspopup="true" role="button" aria-labelledby="select2-chosen-60" id="s2id_autogen60"><div class="select2-drop select2-display-none select2-with-searchbox">   <div class="select2-search">       <label for="s2id_autogen60_search" class="select2-offscreen"></label>       <input type="text" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" class="select2-input" role="combobox" aria-expanded="true" aria-autocomplete="list" aria-owns="select2-results-60" id="s2id_autogen60_search" placeholder="">   </div>   <ul class="select2-results" role="listbox" id="select2-results-60">   </ul></div></div><input type="hidden" class="form-control select2_sample2 select2-offscreen" tabindex="-1" title="">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-3">Tags Support List</label>
													<div class="col-md-9">
														<div class="select2-container select2-container-multi form-control select2_sample3" id="s2id_autogen75"><ul class="select2-choices">  <li class="select2-search-choice">    <div>red</div>    <a href="#" class="select2-search-choice-close" tabindex="-1"></a></li><li class="select2-search-choice">    <div>blue</div>    <a href="#" class="select2-search-choice-close" tabindex="-1"></a></li><li class="select2-search-field">    <label for="s2id_autogen76" class="select2-offscreen"></label>    <input type="text" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" class="select2-input" id="s2id_autogen76" style="width: 20px;" placeholder="">  </li></ul><div class="select2-drop select2-drop-multi select2-display-none">   <ul class="select2-results">   </ul></div></div><input type="hidden" class="form-control select2_sample3 select2-offscreen" value="red,blue" tabindex="-1">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-3">Membership</label>
													<div class="col-md-9">
														<div class="radio-list">
															<label>
															<div class="radio"><span><input type="radio" name="optionsRadios2" value="option1"></span></div>
															Free </label>
															<label>
															<div class="radio"><span class="checked"><input type="radio" name="optionsRadios2" value="option2" checked=""></span></div>
															Professional </label>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-3">Street</label>
													<div class="col-md-9">
														<input type="text" class="form-control">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-3">City</label>
													<div class="col-md-9">
														<input type="text" class="form-control">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-3">State</label>
													<div class="col-md-9">
														<input type="text" class="form-control">
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-3">Post Code</label>
													<div class="col-md-9">
														<input type="text" class="form-control">
													</div>
												</div>
												<div class="form-group last">
													<label class="control-label col-md-3">Country</label>
													<div class="col-md-9">
														<select class="form-control">
														</select>
													</div>
												</div>
											</div>
											<div class="form-actions">
												<div class="row">
													<div class="col-md-offset-3 col-md-9">
														<button type="submit" class="btn green"><i class="fa fa-check"></i> Submit</button>
														<button type="button" class="btn default">Cancel</button>
													</div>
												</div>
											</div>
										</form>
										<!-- END FORM-->
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
<script>
jQuery(document).ready(function() {       
   // initiate layout and plugins
   Metronic.init(); // init metronic core components
   Layout.init(); // init current layout
   Demo.init(); // init demo features
   ChartsFlotcharts.init();
   ChartsFlotcharts.initCharts();
   ChartsFlotcharts.initPieCharts();
   ChartsFlotcharts.initBarCharts();
   ComponentsPickers.init();
   
   function chart2() {
                if ($('#chart_2').size() != 1) {
                    return;
                }

                function randValue() {
                    return (Math.floor(Math.random() * (1 + 40 - 20))) + 20;
                }
                var pageviews = [
                    [1, randValue()],
                    [2, randValue()],
                    [3, 2 + randValue()],
                    [4, 3 + randValue()],
                    [5, 5 + randValue()],
                    [6, 10 + randValue()],
                    [7, 15 + randValue()],
                    [8, 20 + randValue()],
                    [9, 25 + randValue()],
                    [10, 30 + randValue()],
                    [11, 35 + randValue()],
                    [12, 25 + randValue()],
                    [13, 15 + randValue()],
                    [14, 20 + randValue()],
                    [15, 45 + randValue()],
                    [16, 50 + randValue()],
                    [17, 65 + randValue()],
                    [18, 70 + randValue()],
                    [19, 85 + randValue()],
                    [20, 80 + randValue()],
                    [21, 75 + randValue()],
                    [22, 80 + randValue()],
                    [23, 75 + randValue()],
                    [24, 70 + randValue()],
                    [25, 65 + randValue()],
                    [26, 75 + randValue()],
                    [27, 80 + randValue()],
                    [28, 85 + randValue()],
                    [29, 90 + randValue()],
                    [30, 95 + randValue()]
                ];
                var visitors = [
                    [1, randValue() - 5],
                    [2, randValue() - 5],
                    [3, randValue() - 5],
                    [4, 6 + randValue()],
                    [5, 5 + randValue()],
                    [6, 20 + randValue()],
                    [7, 25 + randValue()],
                    [8, 36 + randValue()],
                    [9, 26 + randValue()],
                    [10, 38 + randValue()],
                    [11, 39 + randValue()],
                    [12, 50 + randValue()],
                    [13, 51 + randValue()],
                    [14, 12 + randValue()],
                    [15, 13 + randValue()],
                    [16, 14 + randValue()],
                    [17, 15 + randValue()],
                    [18, 15 + randValue()],
                    [19, 16 + randValue()],
                    [20, 17 + randValue()],
                    [21, 18 + randValue()],
                    [22, 19 + randValue()],
                    [23, 20 + randValue()],
                    [24, 21 + randValue()],
                    [25, 14 + randValue()],
                    [26, 24 + randValue()],
                    [27, 25 + randValue()],
                    [28, 26 + randValue()],
                    [29, 27 + randValue()],
                    [30, 31 + randValue()]
                ];

                var plot = $.plot($("#chart_2"), [{
                    data: pageviews,
                    label: "Unique Visits",
                    lines: {
                        lineWidth: 1,
                    },
                    shadowSize: 0

                }, {
                    data: visitors,
                    label: "Page Views",
                    lines: {
                        lineWidth: 1,
                    },
                    shadowSize: 0
                }], {
                    series: {
                        lines: {
                            show: true,
                            lineWidth: 2,
                            fill: true,
                            fillColor: {
                                colors: [{
                                    opacity: 0.05
                                }, {
                                    opacity: 0.01
                                }]
                            }
                        },
                        points: {
                            show: true,
                            radius: 3,
                            lineWidth: 1
                        },
                        shadowSize: 2
                    },
                    grid: {
                        hoverable: true,
                        clickable: true,
                        tickColor: "#eee",
                        borderColor: "#eee",
                        borderWidth: 1
                    },
                    colors: ["#d12610", "#37b7f3", "#52e136"],
                    xaxis: {
                        ticks: 11,
                        tickDecimals: 0,
                        tickColor: "#eee",
                    },
                    yaxis: {
                        ticks: 11,
                        tickDecimals: 0,
                        tickColor: "#eee",
                    }
                });


                function showTooltip(x, y, contents) {
                    $('<div id="tooltip">' + contents + '</div>').css({
                        position: 'absolute',
                        display: 'none',
                        top: y + 5,
                        left: x + 15,
                        border: '1px solid #333',
                        padding: '4px',
                        color: '#fff',
                        'border-radius': '3px',
                        'background-color': '#333',
                        opacity: 0.80
                    }).appendTo("body").fadeIn(200);
                }

                var previousPoint = null;
                $("#chart_2").bind("plothover", function(event, pos, item) {
                    $("#x").text(pos.x.toFixed(2));
                    $("#y").text(pos.y.toFixed(2));

                    if (item) {
                        if (previousPoint != item.dataIndex) {
                            previousPoint = item.dataIndex;

                            $("#tooltip").remove();
                            var x = item.datapoint[0].toFixed(2),
                                y = item.datapoint[1].toFixed(2);

                            showTooltip(item.pageX, item.pageY, item.series.label + " of " + x + " = " + y);
                        }
                    } else {
                        $("#tooltip").remove();
                        previousPoint = null;
                    }
                });
            }
            
            chart2();
   
});

</script>
<?php //get_sidebar(); ?>
<?php get_footer(); ?>