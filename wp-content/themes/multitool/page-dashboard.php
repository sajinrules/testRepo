<?php
/**
 * Template Name: Dashboard
 *
 * The template and functionality for displaying an account
 * @package WordPress
 * @subpackage Multitool
 * @since We Cross 1.0
 */
get_header(); 
the_post();
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
					<h1><?php the_title(); ?></h1>
				</div>
				<!-- END PAGE TITLE -->
			</div>
			<!-- END PAGE HEAD -->
			<?php 
				getJiraLink();
			?>
			<div class='row'>
				<div class='col-md-12'>
					<div class="portlet light bordered">
					
						<div class="portlet-body">
							<div class="tabbable-line">
								<ul class="nav nav-tabs ">
									<li class="active">
										<a href="#tab_15_1" data-toggle="tab" aria-expanded="true">
										Section 1 </a>
									</li>
									<li class="">
										<a href="#tab_15_2" data-toggle="tab" aria-expanded="false">
										Section 2 </a>
									</li>
									<li class="">
										<a href="#tab_15_3" data-toggle="tab" aria-expanded="false">
										Section 3 </a>
									</li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="tab_15_1">
										<div class='row'>
											<div class='col-md-12'>
												<div class="portlet light">
													<div class="portlet-body">
														<div class="row ui-sortable" id="sortable_portlets">
															<div class="col-md-4 column sortable">
																<div class="portlet portlet-sortable light bordered">
																	<div class="portlet-title ui-sortable-handle">
																		<div class="caption font-green-sharp">
																			<i class="icon-speech font-green-sharp"></i>
																			<span class="caption-subject bold uppercase"> Portlet</span>
																			<span class="caption-helper">details...</span>
																		</div>
																		<div class="actions">
																			<a href="javascript:;" class="btn btn-circle btn-default btn-sm">
																			<i class="fa fa-plus"></i> Add </a>
																		</div>
																	</div>
																	<div class="portlet-body">
																		<div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 200px;"><div class="scroller" style="height: 200px; overflow: hidden; width: auto;" data-rail-visible="1" data-rail-color="yellow" data-handle-color="#a1b2bd" data-initialized="1">
																			<h4>Heading Text</h4>
																			<p>
																				 Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.
																			</p>
																			<p>
																				 nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.
																			</p>
																		</div><div class="slimScrollBar" style="background-color: rgb(161, 178, 189); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-top-left-radius: 7px; border-top-right-radius: 7px; border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; z-index: 99; right: 1px; height: 84.7457627118644px; background-position: initial initial; background-repeat: initial initial;"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; border-top-left-radius: 7px; border-top-right-radius: 7px; border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; background-color: yellow; opacity: 0.2; z-index: 90; right: 1px; display: none; background-position: initial initial; background-repeat: initial initial;"></div></div>
																	</div>
																</div>
																<div class="portlet portlet-sortable light bg-inverse">
																	<div class="portlet-title ui-sortable-handle">
																		<div class="caption">
																			<i class="icon-paper-plane font-green-haze"></i>
																			<span class="caption-subject bold font-green-haze uppercase">
																			Input </span>
																			<span class="caption-helper"></span>
																		</div>
																		<div class="actions">
																			<div class="portlet-input input-inline input-small">
																				<div class="input-icon right">
																					<i class="icon-magnifier"></i>
																					<input type="text" class="form-control input-circle" placeholder="search...">
																				</div>
																			</div>
																		</div>
																	</div>
																	<div class="portlet-body">
																		<h4>Heading text goes here...</h4>
																		<p>
																			 Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis.
																		</p>
																	</div>
																</div>
																<div class="portlet portlet-sortable box green-haze">
																	<div class="portlet-title ui-sortable-handle">
																		<div class="caption">
																			<i class="fa fa-gift"></i>Portlet
																		</div>
																		<div class="actions">
																			<a href="javascript:;" class="btn btn-default btn-sm">
																			<i class="fa fa-pencil"></i> Edit </a>
																			<a href="javascript:;" class="btn btn-default btn-sm">
																			<i class="fa fa-plus"></i> Add </a>
																		</div>
																	</div>
																	<div class="portlet-body">
																		<h4>Heading Text</h4>
																		<p>
																			 Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur
																		</p>
																	</div>
																</div>
																<!-- empty sortable porlet required for each columns! -->
																<div class="portlet portlet-sortable-empty">
																</div>
															</div>
															<div class="col-md-4 column sortable">
																<div class="portlet portlet-sortable light bg-inverse">
																	<div class="portlet-title ui-sortable-handle">
																		<div class="caption font-red-sunglo">
																			<i class="icon-share font-red-sunglo"></i>
																			<span class="caption-subject bold uppercase"> Portlet</span>
																			<span class="caption-helper"></span>
																		</div>
																		<div class="actions">
																			<div class="btn-group btn-group-devided" data-toggle="buttons">
																				<label class="btn btn-circle btn-transparent grey-salsa btn-sm active">
																				<input type="radio" name="options" class="toggle" id="option2">Week</label>
																				<label class="btn btn-circle btn-transparent grey-salsa btn-sm">
																				<input type="radio" name="options" class="toggle" id="option2">Month</label>
																			</div>
																		</div>
																	</div>
																	<div class="portlet-body">
																		<div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 200px;"><div class="scroller" style="height: 200px; overflow: hidden; width: auto;" data-always-visible="1" data-rail-visible="1" data-rail-color="red" data-handle-color="green" data-initialized="1">
																			<h4>Heading Text</h4>
																			<p>
																				 Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.
																			</p>
																			<p>
																				 nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.
																			</p>
																		</div><div class="slimScrollBar" style="background-color: green; width: 7px; position: absolute; top: 115px; opacity: 0.4; display: block; border-top-left-radius: 7px; border-top-right-radius: 7px; border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; z-index: 99; right: 1px; height: 84.7457627118644px; background-position: initial initial; background-repeat: initial initial;"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: block; border-top-left-radius: 7px; border-top-right-radius: 7px; border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; background-color: red; opacity: 0.2; z-index: 90; right: 1px; background-position: initial initial; background-repeat: initial initial;"></div></div>
																	</div>
																</div>
																<div class="portlet portlet-sortable box red-sunglo">
																	<div class="portlet-title ui-sortable-handle">
																		<div class="caption">
																			<i class="fa fa-gift"></i>Portlet
																		</div>
																		<div class="actions">
																			<a href="javascript:;" class="btn btn-default btn-sm">
																			<i class="fa fa-pencil"></i> Edit </a>
																			<a href="javascript:;" class="btn btn-default btn-sm">
																			<i class="fa fa-plus"></i> Add </a>
																		</div>
																	</div>
																	<div class="portlet-body">
																		<p>
																			 Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis.
																		</p>
																		<p>
																			 Nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus.
																		</p>
																	</div>
																</div>
																<div class="portlet portlet-sortable light bordered">
																	<div class="portlet-title ui-sortable-handle">
																		<div class="caption font-yellow-crusta">
																			<i class="icon-share font-yellow-crusta"></i>
																			<span class="caption-subject bold uppercase"> Portlet</span>
																			<span class="caption-helper">stats...</span>
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
																	<div class="portlet-body">
																		 Nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.
																	</div>
																</div>
																<!-- empty sortable porlet required for each columns! -->
																<div class="portlet portlet-sortable-empty">
																</div>
															</div>
															<div class="col-md-4 column sortable">
																<div class="portlet portlet-sortable light bordered">
																	<div class="portlet-title tabbable-line ui-sortable-handle">
																		<div class="caption">
																			<i class="icon-pin font-yellow-lemon"></i>
																			<span class="caption-subject bold font-yellow-lemon uppercase">
																			Tabs </span>
																		</div>
																		<ul class="nav nav-tabs">
																			<li>
																				<a href="#portlet_tab2" data-toggle="tab">
																				Tab 2 </a>
																			</li>
																			<li class="active">
																				<a href="#portlet_tab1" data-toggle="tab">
																				Tab 1 </a>
																			</li>
																		</ul>
																	</div>
																	<div class="portlet-body">
																		<div class="tab-content">
																			<div class="tab-pane active" id="portlet_tab1">
																				<h4>Tab 1 Content</h4>
																				<p>
																					 Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.ut laoreet dolore magna ut laoreet dolore magna. ut laoreet dolore magna. ut laoreet dolore magna.
																				</p>
																			</div>
																			<div class="tab-pane" id="portlet_tab2">
																				<h4>Tab 2 Content</h4>
																				<p>
																					 Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo.
																				</p>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="portlet portlet-sortable box blue-hoki">
																	<div class="portlet-title ui-sortable-handle">
																		<div class="caption">
																			<i class="fa fa-gift"></i>Portlet
																		</div>
																		<div class="actions">
																			<a href="javascript:;" class="btn btn-default btn-sm">
																			<i class="fa fa-pencil"></i> Edit </a>
																			<div class="btn-group">
																				<a class="btn btn-sm btn-default" href="javascript:;" data-toggle="dropdown">
																				<i class="fa fa-user"></i> User <i class="fa fa-angle-down"></i>
																				</a>
																				<ul class="dropdown-menu pull-right">
																					<li>
																						<a href="javascript:;">
																						<i class="fa fa-pencil"></i> Edit </a>
																					</li>
																					<li>
																						<a href="javascript:;">
																						<i class="fa fa-trash-o"></i> Delete </a>
																					</li>
																					<li>
																						<a href="javascript:;">
																						<i class="fa fa-ban"></i> Ban </a>
																					</li>
																					<li class="divider">
																					</li>
																					<li>
																						<a href="javascript:;">
																						<i class="i"></i> Make admin </a>
																					</li>
																				</ul>
																			</div>
																		</div>
																	</div>
																	<div class="portlet-body">
																		 Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis. eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis.
																	</div>
																</div>
																<div class="portlet portlet-sortable light bg-inverse">
																	<div class="portlet-title ui-sortable-handle">
																		<div class="caption">
																			<i class="icon-puzzle font-red-flamingo"></i>
																			<span class="caption-subject bold font-red-flamingo uppercase">
																			Tools </span>
																			<span class="caption-helper">actions...</span>
																		</div>
																		<div class="tools">
																			<a href="" class="collapse" data-original-title="" title="">
																			</a>
																			<a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title="">
																			</a>
																			<a href="" class="reload" data-original-title="" title="">
																			</a>
																			<a href="" class="fullscreen" data-original-title="" title="">
																			</a>
																			<a href="" class="remove" data-original-title="" title="">
																			</a>
																		</div>
																	</div>
																	<div class="portlet-body">
																		<h4>Heading text goes here...</h4>
																		<p>
																			 Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur.
																		</p>
																	</div>
																</div>
																<!-- empty sortable porlet required for each columns! -->
																<div class="portlet portlet-sortable-empty">
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane active" id="tab_15_2">
										<div class='row'>
											<div class='col-md-12'>
												<div class="portlet light">
													<div class="portlet-body">
														<div class="row ui-sortable" id="sortable_portlets">
															<div class="col-md-4 column sortable">
																<div class="portlet portlet-sortable light bordered">
																	<div class="portlet-title ui-sortable-handle">
																		<div class="caption font-green-sharp">
																			<i class="icon-speech font-green-sharp"></i>
																			<span class="caption-subject bold uppercase"> Portlet</span>
																			<span class="caption-helper">details...</span>
																		</div>
																		<div class="actions">
																			<a href="javascript:;" class="btn btn-circle btn-default btn-sm">
																			<i class="fa fa-plus"></i> Add </a>
																		</div>
																	</div>
																	<div class="portlet-body">
																		<div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 200px;"><div class="scroller" style="height: 200px; overflow: hidden; width: auto;" data-rail-visible="1" data-rail-color="yellow" data-handle-color="#a1b2bd" data-initialized="1">
																			<h4>Heading Text</h4>
																			<p>
																				 Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.
																			</p>
																			<p>
																				 nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.
																			</p>
																		</div><div class="slimScrollBar" style="background-color: rgb(161, 178, 189); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-top-left-radius: 7px; border-top-right-radius: 7px; border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; z-index: 99; right: 1px; height: 84.7457627118644px; background-position: initial initial; background-repeat: initial initial;"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; border-top-left-radius: 7px; border-top-right-radius: 7px; border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; background-color: yellow; opacity: 0.2; z-index: 90; right: 1px; display: none; background-position: initial initial; background-repeat: initial initial;"></div></div>
																	</div>
																</div>
																<div class="portlet portlet-sortable light bg-inverse">
																	<div class="portlet-title ui-sortable-handle">
																		<div class="caption">
																			<i class="icon-paper-plane font-green-haze"></i>
																			<span class="caption-subject bold font-green-haze uppercase">
																			Input </span>
																			<span class="caption-helper"></span>
																		</div>
																		<div class="actions">
																			<div class="portlet-input input-inline input-small">
																				<div class="input-icon right">
																					<i class="icon-magnifier"></i>
																					<input type="text" class="form-control input-circle" placeholder="search...">
																				</div>
																			</div>
																		</div>
																	</div>
																	<div class="portlet-body">
																		<h4>Heading text goes here...</h4>
																		<p>
																			 Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis.
																		</p>
																	</div>
																</div>
																<div class="portlet portlet-sortable box green-haze">
																	<div class="portlet-title ui-sortable-handle">
																		<div class="caption">
																			<i class="fa fa-gift"></i>Portlet
																		</div>
																		<div class="actions">
																			<a href="javascript:;" class="btn btn-default btn-sm">
																			<i class="fa fa-pencil"></i> Edit </a>
																			<a href="javascript:;" class="btn btn-default btn-sm">
																			<i class="fa fa-plus"></i> Add </a>
																		</div>
																	</div>
																	<div class="portlet-body">
																		<h4>Heading Text</h4>
																		<p>
																			 Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur
																		</p>
																	</div>
																</div>
																<!-- empty sortable porlet required for each columns! -->
																<div class="portlet portlet-sortable-empty">
																</div>
															</div>
															<div class="col-md-4 column sortable">
																<div class="portlet portlet-sortable light bg-inverse">
																	<div class="portlet-title ui-sortable-handle">
																		<div class="caption font-red-sunglo">
																			<i class="icon-share font-red-sunglo"></i>
																			<span class="caption-subject bold uppercase"> Portlet</span>
																			<span class="caption-helper"></span>
																		</div>
																		<div class="actions">
																			<div class="btn-group btn-group-devided" data-toggle="buttons">
																				<label class="btn btn-circle btn-transparent grey-salsa btn-sm active">
																				<input type="radio" name="options" class="toggle" id="option2">Week</label>
																				<label class="btn btn-circle btn-transparent grey-salsa btn-sm">
																				<input type="radio" name="options" class="toggle" id="option2">Month</label>
																			</div>
																		</div>
																	</div>
																	<div class="portlet-body">
																		<div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 200px;"><div class="scroller" style="height: 200px; overflow: hidden; width: auto;" data-always-visible="1" data-rail-visible="1" data-rail-color="red" data-handle-color="green" data-initialized="1">
																			<h4>Heading Text</h4>
																			<p>
																				 Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.
																			</p>
																			<p>
																				 nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.
																			</p>
																		</div><div class="slimScrollBar" style="background-color: green; width: 7px; position: absolute; top: 115px; opacity: 0.4; display: block; border-top-left-radius: 7px; border-top-right-radius: 7px; border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; z-index: 99; right: 1px; height: 84.7457627118644px; background-position: initial initial; background-repeat: initial initial;"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: block; border-top-left-radius: 7px; border-top-right-radius: 7px; border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; background-color: red; opacity: 0.2; z-index: 90; right: 1px; background-position: initial initial; background-repeat: initial initial;"></div></div>
																	</div>
																</div>
																<div class="portlet portlet-sortable box red-sunglo">
																	<div class="portlet-title ui-sortable-handle">
																		<div class="caption">
																			<i class="fa fa-gift"></i>Portlet
																		</div>
																		<div class="actions">
																			<a href="javascript:;" class="btn btn-default btn-sm">
																			<i class="fa fa-pencil"></i> Edit </a>
																			<a href="javascript:;" class="btn btn-default btn-sm">
																			<i class="fa fa-plus"></i> Add </a>
																		</div>
																	</div>
																	<div class="portlet-body">
																		<p>
																			 Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis.
																		</p>
																		<p>
																			 Nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus.
																		</p>
																	</div>
																</div>
																<div class="portlet portlet-sortable light bordered">
																	<div class="portlet-title ui-sortable-handle">
																		<div class="caption font-yellow-crusta">
																			<i class="icon-share font-yellow-crusta"></i>
																			<span class="caption-subject bold uppercase"> Portlet</span>
																			<span class="caption-helper">stats...</span>
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
																	<div class="portlet-body">
																		 Nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.
																	</div>
																</div>
																<!-- empty sortable porlet required for each columns! -->
																<div class="portlet portlet-sortable-empty">
																</div>
															</div>
															<div class="col-md-4 column sortable">
																<div class="portlet portlet-sortable light bordered">
																	<div class="portlet-title tabbable-line ui-sortable-handle">
																		<div class="caption">
																			<i class="icon-pin font-yellow-lemon"></i>
																			<span class="caption-subject bold font-yellow-lemon uppercase">
																			Tabs </span>
																		</div>
																		<ul class="nav nav-tabs">
																			<li>
																				<a href="#portlet_tab2" data-toggle="tab">
																				Tab 2 </a>
																			</li>
																			<li class="active">
																				<a href="#portlet_tab1" data-toggle="tab">
																				Tab 1 </a>
																			</li>
																		</ul>
																	</div>
																	<div class="portlet-body">
																		<div class="tab-content">
																			<div class="tab-pane active" id="portlet_tab1">
																				<h4>Tab 1 Content</h4>
																				<p>
																					 Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.ut laoreet dolore magna ut laoreet dolore magna. ut laoreet dolore magna. ut laoreet dolore magna.
																				</p>
																			</div>
																			<div class="tab-pane" id="portlet_tab2">
																				<h4>Tab 2 Content</h4>
																				<p>
																					 Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo.
																				</p>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="portlet portlet-sortable box blue-hoki">
																	<div class="portlet-title ui-sortable-handle">
																		<div class="caption">
																			<i class="fa fa-gift"></i>Portlet
																		</div>
																		<div class="actions">
																			<a href="javascript:;" class="btn btn-default btn-sm">
																			<i class="fa fa-pencil"></i> Edit </a>
																			<div class="btn-group">
																				<a class="btn btn-sm btn-default" href="javascript:;" data-toggle="dropdown">
																				<i class="fa fa-user"></i> User <i class="fa fa-angle-down"></i>
																				</a>
																				<ul class="dropdown-menu pull-right">
																					<li>
																						<a href="javascript:;">
																						<i class="fa fa-pencil"></i> Edit </a>
																					</li>
																					<li>
																						<a href="javascript:;">
																						<i class="fa fa-trash-o"></i> Delete </a>
																					</li>
																					<li>
																						<a href="javascript:;">
																						<i class="fa fa-ban"></i> Ban </a>
																					</li>
																					<li class="divider">
																					</li>
																					<li>
																						<a href="javascript:;">
																						<i class="i"></i> Make admin </a>
																					</li>
																				</ul>
																			</div>
																		</div>
																	</div>
																	<div class="portlet-body">
																		 Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis. eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis.
																	</div>
																</div>
																<div class="portlet portlet-sortable light bg-inverse">
																	<div class="portlet-title ui-sortable-handle">
																		<div class="caption">
																			<i class="icon-puzzle font-red-flamingo"></i>
																			<span class="caption-subject bold font-red-flamingo uppercase">
																			Tools </span>
																			<span class="caption-helper">actions...</span>
																		</div>
																		<div class="tools">
																			<a href="" class="collapse" data-original-title="" title="">
																			</a>
																			<a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title="">
																			</a>
																			<a href="" class="reload" data-original-title="" title="">
																			</a>
																			<a href="" class="fullscreen" data-original-title="" title="">
																			</a>
																			<a href="" class="remove" data-original-title="" title="">
																			</a>
																		</div>
																	</div>
																	<div class="portlet-body">
																		<h4>Heading text goes here...</h4>
																		<p>
																			 Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur.
																		</p>
																	</div>
																</div>
																<!-- empty sortable porlet required for each columns! -->
																<div class="portlet portlet-sortable-empty">
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane" id="tab_15_3">
										<div class='row'>
											<div class='col-md-12'>
												<div class="portlet light">
													<div class="portlet-body">
														<div class="row ui-sortable" id="sortable_portlets">
															<div class="col-md-4 column sortable">
																<div class="portlet portlet-sortable light bordered">
																	<div class="portlet-title ui-sortable-handle">
																		<div class="caption font-green-sharp">
																			<i class="icon-speech font-green-sharp"></i>
																			<span class="caption-subject bold uppercase"> Portlet</span>
																			<span class="caption-helper">details...</span>
																		</div>
																		<div class="actions">
																			<a href="javascript:;" class="btn btn-circle btn-default btn-sm">
																			<i class="fa fa-plus"></i> Add </a>
																		</div>
																	</div>
																	<div class="portlet-body">
																		<div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 200px;"><div class="scroller" style="height: 200px; overflow: hidden; width: auto;" data-rail-visible="1" data-rail-color="yellow" data-handle-color="#a1b2bd" data-initialized="1">
																			<h4>Heading Text</h4>
																			<p>
																				 Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.
																			</p>
																			<p>
																				 nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.
																			</p>
																		</div><div class="slimScrollBar" style="background-color: rgb(161, 178, 189); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-top-left-radius: 7px; border-top-right-radius: 7px; border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; z-index: 99; right: 1px; height: 84.7457627118644px; background-position: initial initial; background-repeat: initial initial;"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; border-top-left-radius: 7px; border-top-right-radius: 7px; border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; background-color: yellow; opacity: 0.2; z-index: 90; right: 1px; display: none; background-position: initial initial; background-repeat: initial initial;"></div></div>
																	</div>
																</div>
																<div class="portlet portlet-sortable light bg-inverse">
																	<div class="portlet-title ui-sortable-handle">
																		<div class="caption">
																			<i class="icon-paper-plane font-green-haze"></i>
																			<span class="caption-subject bold font-green-haze uppercase">
																			Input </span>
																			<span class="caption-helper"></span>
																		</div>
																		<div class="actions">
																			<div class="portlet-input input-inline input-small">
																				<div class="input-icon right">
																					<i class="icon-magnifier"></i>
																					<input type="text" class="form-control input-circle" placeholder="search...">
																				</div>
																			</div>
																		</div>
																	</div>
																	<div class="portlet-body">
																		<h4>Heading text goes here...</h4>
																		<p>
																			 Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis.
																		</p>
																	</div>
																</div>
																<div class="portlet portlet-sortable box green-haze">
																	<div class="portlet-title ui-sortable-handle">
																		<div class="caption">
																			<i class="fa fa-gift"></i>Portlet
																		</div>
																		<div class="actions">
																			<a href="javascript:;" class="btn btn-default btn-sm">
																			<i class="fa fa-pencil"></i> Edit </a>
																			<a href="javascript:;" class="btn btn-default btn-sm">
																			<i class="fa fa-plus"></i> Add </a>
																		</div>
																	</div>
																	<div class="portlet-body">
																		<h4>Heading Text</h4>
																		<p>
																			 Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur
																		</p>
																	</div>
																</div>
																<!-- empty sortable porlet required for each columns! -->
																<div class="portlet portlet-sortable-empty">
																</div>
															</div>
															<div class="col-md-4 column sortable">
																<div class="portlet portlet-sortable light bg-inverse">
																	<div class="portlet-title ui-sortable-handle">
																		<div class="caption font-red-sunglo">
																			<i class="icon-share font-red-sunglo"></i>
																			<span class="caption-subject bold uppercase"> Portlet</span>
																			<span class="caption-helper"></span>
																		</div>
																		<div class="actions">
																			<div class="btn-group btn-group-devided" data-toggle="buttons">
																				<label class="btn btn-circle btn-transparent grey-salsa btn-sm active">
																				<input type="radio" name="options" class="toggle" id="option2">Week</label>
																				<label class="btn btn-circle btn-transparent grey-salsa btn-sm">
																				<input type="radio" name="options" class="toggle" id="option2">Month</label>
																			</div>
																		</div>
																	</div>
																	<div class="portlet-body">
																		<div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 200px;"><div class="scroller" style="height: 200px; overflow: hidden; width: auto;" data-always-visible="1" data-rail-visible="1" data-rail-color="red" data-handle-color="green" data-initialized="1">
																			<h4>Heading Text</h4>
																			<p>
																				 Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.
																			</p>
																			<p>
																				 nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.
																			</p>
																		</div><div class="slimScrollBar" style="background-color: green; width: 7px; position: absolute; top: 115px; opacity: 0.4; display: block; border-top-left-radius: 7px; border-top-right-radius: 7px; border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; z-index: 99; right: 1px; height: 84.7457627118644px; background-position: initial initial; background-repeat: initial initial;"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: block; border-top-left-radius: 7px; border-top-right-radius: 7px; border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; background-color: red; opacity: 0.2; z-index: 90; right: 1px; background-position: initial initial; background-repeat: initial initial;"></div></div>
																	</div>
																</div>
																<div class="portlet portlet-sortable box red-sunglo">
																	<div class="portlet-title ui-sortable-handle">
																		<div class="caption">
																			<i class="fa fa-gift"></i>Portlet
																		</div>
																		<div class="actions">
																			<a href="javascript:;" class="btn btn-default btn-sm">
																			<i class="fa fa-pencil"></i> Edit </a>
																			<a href="javascript:;" class="btn btn-default btn-sm">
																			<i class="fa fa-plus"></i> Add </a>
																		</div>
																	</div>
																	<div class="portlet-body">
																		<p>
																			 Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis.
																		</p>
																		<p>
																			 Nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus.
																		</p>
																	</div>
																</div>
																<div class="portlet portlet-sortable light bordered">
																	<div class="portlet-title ui-sortable-handle">
																		<div class="caption font-yellow-crusta">
																			<i class="icon-share font-yellow-crusta"></i>
																			<span class="caption-subject bold uppercase"> Portlet</span>
																			<span class="caption-helper">stats...</span>
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
																	<div class="portlet-body">
																		 Nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.
																	</div>
																</div>
																<!-- empty sortable porlet required for each columns! -->
																<div class="portlet portlet-sortable-empty">
																</div>
															</div>
															<div class="col-md-4 column sortable">
																<div class="portlet portlet-sortable light bordered">
																	<div class="portlet-title tabbable-line ui-sortable-handle">
																		<div class="caption">
																			<i class="icon-pin font-yellow-lemon"></i>
																			<span class="caption-subject bold font-yellow-lemon uppercase">
																			Tabs </span>
																		</div>
																		<ul class="nav nav-tabs">
																			<li>
																				<a href="#portlet_tab2" data-toggle="tab">
																				Tab 2 </a>
																			</li>
																			<li class="active">
																				<a href="#portlet_tab1" data-toggle="tab">
																				Tab 1 </a>
																			</li>
																		</ul>
																	</div>
																	<div class="portlet-body">
																		<div class="tab-content">
																			<div class="tab-pane active" id="portlet_tab1">
																				<h4>Tab 1 Content</h4>
																				<p>
																					 Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.ut laoreet dolore magna ut laoreet dolore magna. ut laoreet dolore magna. ut laoreet dolore magna.
																				</p>
																			</div>
																			<div class="tab-pane" id="portlet_tab2">
																				<h4>Tab 2 Content</h4>
																				<p>
																					 Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo.
																				</p>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="portlet portlet-sortable box blue-hoki">
																	<div class="portlet-title ui-sortable-handle">
																		<div class="caption">
																			<i class="fa fa-gift"></i>Portlet
																		</div>
																		<div class="actions">
																			<a href="javascript:;" class="btn btn-default btn-sm">
																			<i class="fa fa-pencil"></i> Edit </a>
																			<div class="btn-group">
																				<a class="btn btn-sm btn-default" href="javascript:;" data-toggle="dropdown">
																				<i class="fa fa-user"></i> User <i class="fa fa-angle-down"></i>
																				</a>
																				<ul class="dropdown-menu pull-right">
																					<li>
																						<a href="javascript:;">
																						<i class="fa fa-pencil"></i> Edit </a>
																					</li>
																					<li>
																						<a href="javascript:;">
																						<i class="fa fa-trash-o"></i> Delete </a>
																					</li>
																					<li>
																						<a href="javascript:;">
																						<i class="fa fa-ban"></i> Ban </a>
																					</li>
																					<li class="divider">
																					</li>
																					<li>
																						<a href="javascript:;">
																						<i class="i"></i> Make admin </a>
																					</li>
																				</ul>
																			</div>
																		</div>
																	</div>
																	<div class="portlet-body">
																		 Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis. eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis.
																	</div>
																</div>
																<div class="portlet portlet-sortable light bg-inverse">
																	<div class="portlet-title ui-sortable-handle">
																		<div class="caption">
																			<i class="icon-puzzle font-red-flamingo"></i>
																			<span class="caption-subject bold font-red-flamingo uppercase">
																			Tools </span>
																			<span class="caption-helper">actions...</span>
																		</div>
																		<div class="tools">
																			<a href="" class="collapse" data-original-title="" title="">
																			</a>
																			<a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title="">
																			</a>
																			<a href="" class="reload" data-original-title="" title="">
																			</a>
																			<a href="" class="fullscreen" data-original-title="" title="">
																			</a>
																			<a href="" class="remove" data-original-title="" title="">
																			</a>
																		</div>
																	</div>
																	<div class="portlet-body">
																		<h4>Heading text goes here...</h4>
																		<p>
																			 Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur.
																		</p>
																	</div>
																</div>
																<!-- empty sortable porlet required for each columns! -->
																<div class="portlet portlet-sortable-empty">
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
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
	<script src="<?php bloginfo('template_url'); ?>/assets/admin/pages/scripts/portlet-draggable.js"></script>
<script>
jQuery(document).ready(function() {       
   // initiate layout and plugins
   Metronic.init(); // init metronic core components
Layout.init(); // init current layout
Demo.init(); // init demo features
   PortletDraggable.init();
});
</script>
<?php //get_sidebar(); ?>
<?php get_footer(); ?>