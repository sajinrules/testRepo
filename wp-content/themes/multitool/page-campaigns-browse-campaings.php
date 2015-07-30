<?php
/**
 * Template Name: Campaigns > Browse > Campaigns 
 *
 * The template and functionality for displaying an account
 * @package WordPress
 * @subpackage Multitool
 * @since We Cross 1.0
 */
//echo getcwd();

//$file = fopen("../../home/sajin/demodata/campaigns-facebook.csv","r");
$file = fopen("/var/demodata/campaigns-facebook.csv","r");
$a=[];
while(!feof($file)){
	if($i>10)
		break;
	array_push($a,fgetcsv($file));
	$i++;
}

//exit();
$file = fopen("/var/demodata/campaigns-facebook.csv","r");
$arry = fgetcsv($file);
function get_th($arry){
	foreach ($arry as $key => $item) {
		if($item !='Start Date' && $item !='End Date'){
			echo "<th class='align-top'><input id='col-select' type='checkbox' value=".$key."><div>".$item."</div></th>";
		}
	}
	
}

//fclose($file);
//get_th($arry);
//exit();
get_header(); 
the_post();
?>

<?php getMenuCampaigns(); ?>
<style type="text/css">
div.checker, div.checker span, div.checker input
{
	width: 20px !important;
}
.align-top{
	vertical-align: top !important;
}
</style>

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
					<h1>Browse Campaigns <small>...</small></h1>
				</div>
				<!-- END PAGE TITLE -->
			</div>
			<!-- END PAGE HEAD -->
			<!-- BEGIN PAGE BREADCRUMB -->
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<a href="/">Browse</a>
					<i class="fa fa-circle"></i>
				</li>
				<li>
					<a href="#">Campaigns</a>
				</li>
			</ul>
			<!-- END PAGE BREADCRUMB -->
			<?php 
				getJiraLink();
			?>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class='row'>
				<div class='col-md-12'>
					<div class='portlet light'>
						<div class='portlet-body'>
								<!-- row filters -->
						
									<div class="portlet light">
										<div class="portlet-title">
											<div class="caption font-red-sunglo">
												
											</div>
											<div class="actions">
												<div class="btn-group">
														<button type="button" class="btn btn-default"><i class="fa  fa-file-text-o"></i> Export Report</button>
														<button type="button" class="btn btn-default"><i class="fa  fa-calendar"></i> July 2015</button>
													</div>
											</div>
											
										</div>
									</div>
							<!-- end row filters -->
							
							<!-- row visualisation -->
							<div class='row'>
								<div class='col-md-12'>
									<div class="portlet light bordered">
										<div class="portlet-title">
											<div class="caption font-red-sunglo">
												
											</div>
											<div class="actions">
												<div class="btn-group btn-group-devided" data-toggle="buttons">
													<label class="btn btn-circle btn-transparent grey-salsa btn-sm active">
													<input type="radio" name="options" class="toggle" id="option1">Today</label>
													<label class="btn btn-circle btn-transparent grey-salsa btn-sm">
													<input type="radio" name="options" class="toggle" id="option2">Week</label>
													<label class="btn btn-circle btn-transparent grey-salsa btn-sm">
													<input type="radio" name="options" class="toggle" id="option2">Month</label>
												</div>
											</div>
										</div>
										<div class="portlet-body">
									
											<!-- BEGIN INTERACTIVE CHART PORTLET-->
											<div class="portlet-body">
												<!-- <img src="<?php bloginfo('template_url'); ?>/images/chart1.png" width="100%"> -->
												<button onclick="generate()"> Generate</button>
												<svg id="visualisation" width="1000" height="500"></svg>
											</div>											
											</div>
											<!-- END INTERACTIVE CHART PORTLET-->
									</div>
									</div>
								</div>
							<!-- end row visualisation -->
							
							<!-- row data -->
							<div class='row'>
								<div class='col-md-12'>
									<div class="portlet light bordered">
										<div class="portlet-title">
											<div class="caption font-red-sunglo">
												
											</div>
											<div class="actions">
												<div class="btn-group btn-group-devided" data-toggle="buttons">
													<label class="btn btn-circle btn-transparent grey-salsa btn-sm active">
													<input type="radio" name="options" class="toggle" id="option1">Edit Metrics</label>
													<label class="btn btn-circle btn-transparent grey-salsa btn-sm">
												</div>
											</div>
										</div>
										<div class="portlet-body">											
											<!-- BEGIN table portlet-->
											<div class="portlet-body">
												<div class="table-responsive">
													<table class="table table-striped table-bordered table-hover">
													<thead>
													<tr>
														<th>#</th>
														<?php
															get_th($arry);
														?>
													</tr>
													</thead>
													<tbody>
														<?php
														$count = 0;
														while(!feof($file))
												  		{
													  		if($count > 10) {
													  			break;
													  		}

													  		$arry = fgetcsv($file);
													  		?>
													  			<tr>

													  				<td><input class="campaign-checker" type="checkbox"></td>
													  				<?php foreach ($arry as $key => $item) {
																		if($key !=0 && $key !=1){
																			echo "<td>".$item."</td>";
																		}
																	} ?>
													  			</tr>


													  		<?php
													  		$count++;
												  		} ?>

													</tbody>
													</table>

												</div>
											</div>										
										</div>
										<!-- END table portlet-->
									</div>
									</div>
								</div>
							<!-- end row data -->
							
						</div>
					</div>
				</div>
			</div>
		</div>			
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
	<script src="http://d3js.org/d3.v3.min.js"></script>
<script>

$(document).ready(function(){
	$n=0;
	var datas=[];
	var parseDate = d3.time.format("%Y-%m-%d").parse; //2015-04-18
	var array = <?php echo json_encode($a);?>;
	
	$('input').change(function(e){
		var values =[],
		datas=[];
			$n=0; 
		if(!this.checked)
			return;
		
		var index = parseInt($(this).val());
		
		if(index<5 || index >15)
			return;

		array.forEach(function(item){
			if($n>0){
				console.log(item[0]);
				datas.push({"sale":item[index],"year":parseDate(item[0])})
			}
			$n++;
		})
		generate(datas);
	})
});
	var data=[];
      
	function generate(data){
		//updateData(randomData());
		updateData(data);
	}

	var parseDate = d3.time.format("%d-%m-%Y").parse;
      
    function randomData(){
        data = [{
            "sale": Math.floor((Math.random() * 100) + 1),
            "year": parseDate("31-05-1989")
        }, {
            "sale": Math.floor((Math.random() * 100) + 1),
            "year": parseDate("25-05-1989")
        }, {
            "sale": Math.floor((Math.random() * 100) + 1),
            "year": parseDate("20-05-1989")
        }, {
            "sale": Math.floor((Math.random() * 100) + 1),
            "year": parseDate("15-05-1989")
        }, {
            "sale": Math.floor((Math.random() * 100) + 1),
            "year": parseDate("10-05-1989")
        }, {
            "sale": Math.floor((Math.random() * 100) + 1),
            "year": parseDate("05-05-1989")
        }];
        return data;
    } 
      
    //randomData();
	
	var vis = d3.select("#visualisation"),
		WIDTH = 1000,
		HEIGHT = 500,
		MARGINS = {
			top: 20,
			right: 20,
			bottom: 20,
			left: 50
		}
		var color= "red";
      
    xScale = d3.time.scale().range([MARGINS.left, WIDTH - MARGINS.right])
        .domain([d3.min(data, function(d) {
          return d.year;
        }), d3.max(data, function(d) {
          return d.year;
        })]);
      
    yScale = d3.scale.linear().range([HEIGHT - MARGINS.top, MARGINS.bottom])
        .domain([d3.min(data, function(d) {
          return d.sale;
        }), d3.max(data, function(d) {
          return d.sale;
        })]);
      
	xAxis = d3.svg.axis()
		.scale(xScale)
		.ticks(10)

	yAxis = d3.svg.axis()
		.scale(yScale)
		.ticks(10)
		.orient("left");

	vis.append("svg:g")
		.attr("class","axis")
		.attr("transform", "translate(0," + (HEIGHT - MARGINS.bottom) + ")")
		.call(xAxis); 

	vis.append("svg:g")
		.attr("class","y axis")
		.call(yAxis)
		.attr("transform", "translate(" + (MARGINS.left) + ",0)")

	var lineGen = d3.svg.line()
		.x(function(d) {
		  return xScale(d.year);
		})
		.y(function(d) {
		  return yScale(d.sale);
		})
		.interpolate("basis");

	vis.append('svg:path')
		.attr("class", "line")
		.attr('d', lineGen(data))
		.attr('stroke',color)
		.attr('stroke-width', 2)
		.attr('fill', 'none');  

	function updateData(data) {
        // Scale the range of the data again 
        console.log(data);
        //return;
        xScale.domain([d3.min(data,function(d){
                return d.year;
              }), d3.max(data, function(d) {
                return d.year;
              })]);
        yScale.domain([d3.min(data, function(d) {
                  return d.sale;
                }), d3.max(data, function(d) {
                  return d.sale;
                })]);


        // Make the changes
          vis.select('.line')
            .attr('d', lineGen(data))
            .attr('stroke',color)
            .attr('stroke-width', 2)
            .attr('fill', 'none');
          
          vis.append("svg:g")
            .attr("class","axis")
            .attr("transform", "translate(0," + (HEIGHT - MARGINS.bottom) + ")")
            .call(xAxis); 
          
          vis.select(".y.axis") // change the y axis
            .call(yAxis);
    }	



   // initiate layout and plugins
   //Metronic.init(); // init metronic core components
   //Layout.init(); // init current layout
   //Demo.init(); // init demo features
   //ChartsFlotcharts.init();
   //ChartsFlotcharts.initCharts();
   //ChartsFlotcharts.initPieCharts();
   //ChartsFlotcharts.initBarCharts();
   //ComponentsPickers.init();
   
	/*function chart2() {
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
	        
	        //chart2();

	});*/

</script>
<?php //get_sidebar(); ?>
<?php get_footer(); ?>
