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
require('./wp-db-init.php');


$sql = "SELECT * FROM D032";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
    	$data[] = array(
        	'date' 			=> $row['day'],
			'trackPlays'	=> $row['trackPlays'],
			'fans'			=> $row['fans'],
			'buzz'			=> $row['buzz']

		);
        /*$trackPlays[] = array(
        	'date' 	=> $row['day'],
			'value' => $row['trackPlays']
		);
		$fans[] = array(
        	'date' 	=> $row['day'],
			'value' => $row['fans']
		);
		
		$buzz[] = array(
        	'date' 	=> $row['day'],
			'value' => $row['buzz']
		);*/
    }
} else {
    echo "0 results";
}

if (isset($_GET['row'])){
 	$row = $_GET['row'];
}else {
	die("url format error");
}
//exit();
?><?php getMenuArtists(); ?>
	<style type="text/css">
		#chartdiv {
			width	: 100%;
			height	: 500px;
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
			
			<div class="row">
				<div class="col-md-12">
					
					<!-- BEGIN SAMPLE FORM PORTLET-->
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption font-green-haze">
								<i class="icon-settings font-green-haze"></i>
								<span class="caption-subject bold uppercase">Artist Detail</span>
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
						<div id="chartdiv"></div>
						<div class="portlet-body form">
							<form role="form" class="form-horizontal">
								<div class="form-body">
									<div class="form-group form-md-line-input">
										<label class="col-md-2 control-label" for="form_control_1">artistname</label>
										<div class="col-md-10">
											<input type="text" class="form-control" id="form_control_1" placeholder="Artistname">
											<div class="form-control-focus">
											</div>
										</div>
									</div>
									<div class="form-group form-md-line-input">
										<label class="col-md-2 control-label" for="form_control_1">artist ID</label>
										<div class="col-md-10">
											<input type="text" class="form-control" id="form_control_1" placeholder="AID" disabled>
											<div class="form-control-focus">
											</div>
										</div>
									</div>
									<div class="form-group form-md-line-input">
										<label class="col-md-2 control-label" for="form_control_1">input date in engine</label>
										<div class="col-md-10">
											<input type="text" class="form-control" id="form_control_1" placeholder="DD-MM-YYYY" disabled>
											<div class="form-control-focus">
											</div>
										</div>
									</div>
									<div class="form-group form-md-line-input">
										<label class="col-md-2 control-label" for="form_control_1">E-mailaddress</label>
										<div class="col-md-10">
											<div class="input-group has-success">
												<span class="input-group-addon">
												<i class="fa fa-envelope"></i>
												</span>
												<input type="text" class="form-control" placeholder="Email Address">
												<div class="form-control-focus">
												</div>
											</div>
										</div>
									</div>
									<div class="form-group form-md-line-input">
										<label class="col-md-2 control-label" for="form_control_1">Genre Channels</label>
										<div class="col-md-10">
											<div class="md-checkbox-list">
												<div class="md-checkbox">
													<input type="checkbox" id="checkbox30" class="md-check">
													<label for="checkbox30">
													<span></span>
													<span class="check"></span>
													<span class="box"></span>
													pop </label>
												</div>
												<div class="md-checkbox">
													<input type="checkbox" id="checkbox31" class="md-check" checked="">
													<label for="checkbox31">
													<span></span>
													<span class="check"></span>
													<span class="box"></span>
													urban </label>
												</div>
												<div class="md-checkbox">
													<input type="checkbox" id="checkbox32" class="md-check">
													<label for="checkbox32">
													<span></span>
													<span class="check"></span>
													<span class="box"></span>
													alternative </label>
												</div>
												<div class="md-checkbox">
													<input type="checkbox" id="checkbox33" class="md-check">
													<label for="checkbox33">
													<span></span>
													<span class="check"></span>
													<span class="box"></span>
													electronics </label>
												</div>
												<div class="md-checkbox">
													<input type="checkbox" id="checkbox34" class="md-check">
													<label for="checkbox34">
													<span></span>
													<span class="check"></span>
													<span class="box"></span>
													dance </label>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group form-md-line-input">
										<label class="col-md-2 control-label" for="form_control_1">engine reputation</label>
										<div class="col-md-10">
											<select class="form-control" id="form_control_1">
												<option value="">unpromoted</option>
												<option value="">promoted</option>
												<option value="">prelist</option>
												<option value="">inlist</option>
												<option value="">postlist</option>
											</select>
											<div class="form-control-focus">
											</div>
										</div>
									</div>
									<div class="form-group form-md-line-input">
										<label class="col-md-2 control-label" for="form_control_1">Checked</label>
										<div class="col-md-10">
											<div class="md-checkbox-list">
												<div class="md-checkbox">
													<input type="checkbox" id="checkbox40" class="md-check" checked="">
													<label for="checkbox40">
													<span></span>
													<span class="check"></span>
													<span class="box"></span>
													dd-mm-yyyy by username </label>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group form-md-line-input">
										<label class="col-md-2 control-label" for="form_control_1">Biography</label>
										<div class="col-md-10">
											<textarea class="form-control" rows="3" placeholder="Enter more text"></textarea>
											<div class="form-control-focus">
											</div>
										</div>
									</div>
									<div class="form-group form-md-line-input">
										<label class="col-md-2 control-label">Inputsources</label>
										<div class="col-md-10">
											<select multiple="" class="form-control">
												<option>Noorderslag 2012</option>
												<option>Noorderslag 2013</option>
												<option>Paaspop 2014</option>
												<option>Paaspop 2015</option>
												<option>Best Kept Secret 2015</option>
											</select>
										</div>
									</div>
									<div class="form-group form-md-line-input">
										<label class="col-md-2 control-label" for="form_control_1">Add source</label>
										<div class="col-md-10">
											<div class="input-group input-group-sm">
												<div class="input-group-control">
													<input type="text" class="form-control input-sm" placeholder="source name">
													<div class="form-control-focus">
													</div>
												</div>
												<span class="input-group-btn btn-right">
												<button class="btn green-haze" type="button">Add</button>
												</span>
											</div>
										</div>
									</div>
									<div class="form-actions">
									<div class="row">
										<div class="col-md-offset-2 col-md-10">
											<button type="button" class="btn default">Cancel</button>
											<button type="button" class="btn blue">Save</button>
										</div>
									</div>
								</div>
								</div>
							</form>
						</div>
					</div>
					<!-- END SAMPLE FORM PORTLET-->
					
					<div class='portlet light'>
						<div class="portlet-body">
							<div class="tabbable-custom nav-justified">
								<ul class="nav nav-tabs nav-justified">
									<li class="active">
										<a href="#tab_1_1_1" data-toggle="tab" aria-expanded="true">
										Channels </a>
									</li>
									<li class="">
										<a href="#tab_1_1_2" data-toggle="tab" aria-expanded="false">
										Image </a>
									</li>
									<li class="">
										<a href="#tab_1_1_3" data-toggle="tab" aria-expanded="false">
										Gigs </a>
									</li>
									<li class="">
										<a href="#tab_1_1_4" data-toggle="tab" aria-expanded="false">
										News </a>
									</li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="tab_1_1_1">
												<!-- BEGIN SAMPLE FORM PORTLET-->
												<div class="portlet light">
													<div class="portlet-title">
														<div class="caption font-green-haze">
															<i class="icon-settings font-green-haze"></i>
															<span class="caption-subject bold uppercase">Channels</span>
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
																	<label class="col-md-2 control-label" for="form_control_1">Add channel URL</label>
																	<div class="col-md-10">
																		<div class="input-group input-group-sm">
																			<div class="input-group-control">
																				<input type="text" class="form-control input-sm" placeholder="http://">
																				<div class="form-control-focus">
																				</div>
																			</div>
																			<span class="input-group-btn btn-right">
																			<button class="btn green-haze" type="button">Add</button>
																			</span>
																		</div>
																	</div>
																</div>
															</div>
															<div class="table-responsive">
															<table class="table table-striped table-bordered table-hover">
															<thead>
															<tr>
																<th>
																	 Channel ID
																</th>
																<th>
																	 
																</th>
																<th>
																	 Type
																</th>
																<th>
																	 Channelname
																</th>
																<th>
																	 ChannelID
																</th>
																<th>
																	 URI
																</th>
																<th>
																	 Include name
																</th>
															</tr>
															</thead>
															<tbody>
															<tr>
																<td>
																	 ID
																</td>
																<td>
																	 <img src="http://engine.intothetune.com/img/social/youtube.png">
																</td>
																<td>
																	 Media
																</td>
																<td>
																	 Youtube
																</td>
																<td>
																	 PetjeAf
																</td>
																<td>
																	 <a href='https://youtube.com/user/PetjeAf' target="_blank">https://youtube.com/user/PetjeAf</a>
																</td>
																<td>
																	 1
																</td>
															</tr>
															<tr>
																<td>
																	 ID
																</td>
																<td>
																	 <img src="http://engine.intothetune.com/img/social/soundcloud.png">
																</td>
																<td>
																	 Media
																</td>
																<td>
																	 Soundcloud
																</td>
																<td>
																	 spec-entertainment
																</td>
																<td>
																	 <a href='https://soundcloud.com/spec-entertainment' target='_blank'>https://soundcloud.com/spec-entertainment</a>
																</td>
																<td>
																	 1
																</td>
															</tr>
															<tr>
																<td>
																	 ID
																</td>
																<td>
																	 <img src="http://engine.intothetune.com/img/social/facebook.png">
																</td>
																<td>
																	 Social
																</td>
																<td>
																	 Facebook
																</td>
																<td>
																	 109334142425928
																</td>
																<td>
																	 <a href='https://www.facebook.com/AliB' target='_blank'>https://www.facebook.com/AliB</a>
																</td>
																<td>
																	 
																</td>
															</tr>
															</tbody>
															</table>
														</div>
															
														</form>
													</div>
												</div>
												<!-- END SAMPLE FORM PORTLET-->
									</div>
									<div class="tab-pane" id="tab_1_1_2">
										<!-- image -->
										
					<!-- BEGIN SAMPLE FORM PORTLET-->
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption font-green-haze">
								<i class="icon-settings font-green-haze"></i>
								<span class="caption-subject bold uppercase"> Image</span>
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
									<div class="row">
								<div class="col-sm-12 col-md-12">
									<div class="form-group last">
										<div class="col-md-12">
											<div class="fileinput fileinput-new" data-provides="fileinput">
												<div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
													<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt="">
												</div>
												<div>
													<span class="btn default btn-file">
													<input type="file" name="...">
													</span>
													<a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput">
													Remove </a>
												</div>
											</div>
											<div class="clearfix margin-top-10">
												<span class="label label-danger">
												NOTE! </span>&nbsp;&nbsp;Image preview only works in IE10+, FF3.6+, Safari6.0+, Chrome6.0+ and Opera11.1+. In older browsers the filename is shown instead.
											</div>
										</div>
									</div>
								</div>
							</div>
								</div>
								
							</form>
						</div>
					</div>
					<!-- END SAMPLE FORM PORTLET-->
										<!-- end image -->
									</div>
									<div class="tab-pane" id="tab_1_1_3">
										
										<!-- BEGIN SAMPLE FORM PORTLET-->
										<div class="portlet light">
											<div class="portlet-title">
												<div class="caption font-green-haze">
													<i class="icon-settings font-green-haze"></i>
													<span class="caption-subject bold uppercase"> Gigs/Events</span>
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
												<div class="table-responsive">
													<table class="table table-striped table-bordered table-hover">
													<thead>
													<tr>
														<th>
															 Venue
														</th>
														<th>
															 Date
														</th>
														<th>
															 Location
														</th>
														<th>
															 SongkickID
														</th>
														<th>
															 URI
														</th>
													</tr>
													</thead>
													<tbody>
													<tr>
														<td>
															 Patronaat, Grote Zaal
														</td>
														<td>
															 12-06-2015 18:00
														</td>
														<td>
															 Haarlem
														</td>
														<td>
															24330344
														</td>
														<td>
															 <a href='http://www.songkick.com/concerts/24330344-attila-at-patronaat'>http://www.songkick.com/concerts/24330344-attila-at-patronaat</a>
														</td>
													</tr>
													
													</tbody>
													</table>
												</div>
											</div>
										</div>
										<!-- END SAMPLE FORM PORTLET-->
					
									</div>
									<div class="tab-pane" id="tab_1_1_4">
										
									<!-- BEGIN SAMPLE FORM PORTLET-->
									<div class="portlet light">
										<div class="portlet-title">
											<div class="caption font-green-haze">
												<i class="icon-settings font-green-haze"></i>
												<span class="caption-subject bold uppercase"> News/Blogs</span>
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
											<div class="table-responsive">
												<table class="table table-striped table-bordered table-hover">
												<thead>
												<tr>
													<th>
														 Title
													</th>
													<th>
														 Date
													</th>
													<th>
														 Source
													</th>
													<th>
														 URI
													</th>
												</tr>
												</thead>
												<tbody>
												<tr>
													<td>
														OKÃ‰ EDM, HET IS AFGELOPEN MET JE KINDERFEESTJE
													</td>
													<td>
														 12-06-2015 18:00
													</td>
													<td>
														 Noisy
													</td>
													<td>
														 <a href='http://noisey.vice.com/nl/blog/ok-edm-es-reicht-204'>http://noisey.vice.com/nl/blog/ok-edm-es-reicht-204</a>
													</td>
												</tr>
												
												</tbody>
												</table>
											</div>
										</div>
									</div>
									<!-- END SAMPLE FORM PORTLET-->
					
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class='portlet light'>
						<div class="portlet-title">
							<div class="caption font-green-haze">
								<i class="icon-settings font-green-haze"></i>
								<span class="caption-subject bold uppercase">Tracks</span>
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
									<form role="form" class="form-horizontal" data-form-processed="true">
										
										<div class="table-responsive">
										<table class="table table-striped table-bordered table-hover">
										<thead>
										<tr>
											<th>
												 Track ID
											</th>
											<th>
												 Trackname
											</th>
											<th>
												 Nr of attached media
											</th>
											<th>
												 Trusted
											</th>
											<th>
												 Total Plays
											</th>
											<th>
												 Delta plays
											</th>
											<th>
												 first entry
											</th>
											<th>
												 <img src="http://engine.intothetune.com/img/social/youtube.png">
											</th>
											<th>
												 <img src="http://engine.intothetune.com/img/social/soundcloud.png">
											</th>
											<th>
												 <img src="http://engine.intothetune.com/img/social/vimeo.png">
											</th>
											<th>
												 <img src="http://engine.intothetune.com/img/social/lastfm.png">
											</th>
										</tr>
										</thead>
										<tbody>
										<tr>
											<td>
												 ID
											</td>
											<td>
												 trackname
											</td>
											<td>
												 ###
											</td>
											<td>
												 <i class="fa fa-check-circle"></i>
											</td>
											<td>
												 24.000
											</td>
											<td>
												 4.000
											</td>
											<td>
												 12-06-2014
											</td>
											<td>
												 20.000
											</td>
											<td>
												 4.000
											</td>
											<td>
												 -
											</td>
											<td>
												 -
											</td>
										</tr>
										<tr>
											<td>
												 ID
											</td>
											<td>
												 trackname
											</td>
											<td>
												 ###
											</td>
											<td>
												 <i class="fa fa-check-circle"></i>
											</td>
											<td>
												 24.000
											</td>
											<td>
												 4.000
											</td>
											<td>
												 12-06-2014
											</td>
											<td>
												 20.000
											</td>
											<td>
												 4.000
											</td>
											<td>
												 -
											</td>
											<td>
												 -
											</td>
										</tr>
										<tr>
											<td>
												 ID
											</td>
											<td>
												 trackname
											</td>
											<td>
												 ###
											</td>
											<td>
												 <i class="fa fa-check-circle"></i>
											</td>
											<td>
												 24.000
											</td>
											<td>
												 4.000
											</td>
											<td>
												 12-06-2014
											</td>
											<td>
												 20.000
											</td>
											<td>
												 4.000
											</td>
											<td>
												 -
											</td>
											<td>
												 -
											</td>
										</tr>
										<tr>
											<td>
												 ID
											</td>
											<td>
												 trackname
											</td>
											<td>
												 ###
											</td>
											<td>
												 <i class="fa fa-check-circle"></i>
											</td>
											<td>
												 24.000
											</td>
											<td>
												 4.000
											</td>
											<td>
												 12-06-2014
											</td>
											<td>
												 20.000
											</td>
											<td>
												 4.000
											</td>
											<td>
												 -
											</td>
											<td>
												 -
											</td>
										</tr>
										<tr>
											<td>
												 ID
											</td>
											<td>
												 trackname
											</td>
											<td>
												 ###
											</td>
											<td>
												 <i class="fa fa-check-circle"></i>
											</td>
											<td>
												 24.000
											</td>
											<td>
												 4.000
											</td>
											<td>
												 12-06-2014
											</td>
											<td>
												 20.000
											</td>
											<td>
												 4.000
											</td>
											<td>
												 -
											</td>
											<td>
												 -
											</td>
										</tr>
										<tr>
											<td>
												 ID
											</td>
											<td>
												 trackname
											</td>
											<td>
												 ###
											</td>
											<td>
												 <i class="fa fa-check-circle"></i>
											</td>
											<td>
												 24.000
											</td>
											<td>
												 4.000
											</td>
											<td>
												 12-06-2014
											</td>
											<td>
												 20.000
											</td>
											<td>
												 4.000
											</td>
											<td>
												 -
											</td>
											<td>
												 -
											</td>
										</tr>
										<tr>
											<td>
												 ID
											</td>
											<td>
												 trackname
											</td>
											<td>
												 ###
											</td>
											<td>
												 <i class="fa fa-check-circle"></i>
											</td>
											<td>
												 24.000
											</td>
											<td>
												 4.000
											</td>
											<td>
												 12-06-2014
											</td>
											<td>
												 20.000
											</td>
											<td>
												 4.000
											</td>
											<td>
												 -
											</td>
											<td>
												 -
											</td>
										</tr>
										
										</tbody>
										</table>
									</div>
										
									</form>
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
	<script src="http://www.amcharts.com/lib/3/amcharts.js"></script>
		<script src="http://www.amcharts.com/lib/3/serial.js"></script>
		<script src="http://www.amcharts.com/lib/3/themes/light.js"></script>
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
    
});
	var data  = [],
	dataProvider = [];

	data = <?php echo json_encode($data);?>;

	/*var chartData = [];
	function generateChartData() {
	    var firstDate = new Date();
	    firstDate.setTime(firstDate.getTime() - 10 * 24 * 60 * 60 * 1000);

	    for (var i = firstDate.getTime(); i < (firstDate.getTime() + 10 * 24 * 60 * 60 * 1000); i += 60 * 60 * 1000) {
	        var newDate = new Date(i);

	        if (i == firstDate.getTime()) {
	            var value1 = Math.round(Math.random() * 10) + 1;
	        } else {
	            var value1 = Math.round(chartData[chartData.length - 1].value1 / 100 * (90 + Math.round(Math.random() * 20)) * 100) / 100;
	        }

	        if (newDate.getHours() == 12) {
	            // we set daily data on 12th hour only
	            var value2 = Math.round(Math.random() * 12) + 1;
	            chartData.push({
	                date: newDate,
	                value1: value1,
	                value2: value2
	            });
	        } else {
	            chartData.push({
	                date: newDate,
	                value1: value1
	            });
	        }
	    }
	}
	generateChartData();*/
	
	//console.log(data);
	data.forEach(function(obj){
		dataProvider.push({'buzz':obj.buzz,'fans':obj.fans,'trackPlays':obj.trackPlays,'date':toDate(obj.date)});
	});
	
	function toDate(string){
		return new Date(string);
	}
	
		//console.log(dataProvider);
	var chart = AmCharts.makeChart("chartdiv", {
	    "type": "serial",
	    "theme": "light",
	    "marginRight": 80,
	    "dataProvider": dataProvider,
	    "valueAxes": [{
	        "axisAlpha": 0.1
	    }],

	    "graphs": [{
	        "balloonText": "[[title]]: [[value]]",
	        "columnWidth": 20,
	        "fillAlphas": 1,
	        "title": "daily",
	        "type": "column",
	        "valueField": "fans"
	    },{
	        "balloonText": "[[title]]: [[value]]",
	        "lineThickness": 2,
	        "title": "intra-day",
	        "valueField": "buzz"
	    },{
	        "balloonText": "[[title]]: [[value]]",
	        "lineThickness": 2,
	        "title": "intra-day",
	        "valueField": "trackPlays"
	    }],
	    "zoomOutButtonRollOverAlpha": 0.15,
	    "chartCursor": {
	        "categoryBalloonDateFormat": "MMM DD JJ:NN",
	        "cursorPosition": "mouse",
	        "showNextAvailable": true
	    },
	    "autoMarginOffset": 5,
	    "columnWidth": 1,
	    "categoryField": "date",
	    "categoryAxis": {
	        "minPeriod": "hh",
	        "parseDates": true
	    },
	    "export": {
	        "enabled": true
	    }
	});


</script>
<?php //get_sidebar(); ?>
<?php get_footer(); ?>