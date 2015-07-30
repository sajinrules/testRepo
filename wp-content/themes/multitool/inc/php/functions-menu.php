<?php

 
function getMenuAudience(){
	
	?>
		<!-- BEGIN SIDEBAR -->
	<div class="page-sidebar-wrapper">
		<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
		<!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
		<div class="page-sidebar navbar-collapse collapse">
			<!-- BEGIN SIDEBAR MENU -->
			<!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
			<!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
			<!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
			<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
			<!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
			<!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
			<ul class="page-sidebar-menu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
				
				<li>
					<a href="<?php bloginfo('wpurl'); ?>/audience"></i>
					<span class="title">Browse</span></a>
				</li>
				
				<li>
					<a href="<?php bloginfo('wpurl'); ?>/audience/insights"><i class='fa fa-bar-chart-o'></i>
					<span class="title">Insights</span>
					</a>
				</li>
				<li>
					<a href="<?php bloginfo('wpurl'); ?>/audience/segmentation"><i class='fa fa-bullhorn'></i>
					<span class="title">Segmentation</span>
					</a>
					
				</li>
			</ul>
			<!-- END SIDEBAR MENU -->
			<?php getMenuAgile(); ?>
		</div>
	</div>
	<!-- END SIDEBAR -->
	
	<?php
	
}
	
function getMenuArtists(){
	
	?>
		<!-- BEGIN SIDEBAR -->
	<div class="page-sidebar-wrapper">
		<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
		<!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
		<div class="page-sidebar navbar-collapse collapse">
			<!-- BEGIN SIDEBAR MENU -->
			<!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
			<!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
			<!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
			<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
			<!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
			<!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
			<ul class="page-sidebar-menu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
				
				<li>
					<a href="<?php bloginfo('wpurl'); ?>/artists"></i>
					<span class="title">Browse</span></a>
				</li>
				<li class="start open">
					<a href="javascript:;">
					<span class="title">Details</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/artists/contacts">
							Contacts</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/artists/channels">
							Channels</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/artists/repetoire">
							Repetoire</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/artists/content">
							Trending Content</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/artists/agenda">
							Agenda</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/artists/partners">
							Partners</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="javascript:;"><i class='fa fa-bar-chart-o'></i>
					<span class="title">Insights</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/artists/insights-audience">
							Audience</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/artists/insights-events">
							Events</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/artists/insights-marketing">
							Marketing</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/artists/insights-repetoire">
							Repetoire</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/artists/insights-social">
							Social</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="javascript:;"><i class='fa fa-bullhorn'></i>
					<span class="title">Promote</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/artists/promote-PR">
							PR</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/artists/promote-keywords">
							Keywords</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/artists/promote-eventagenda">
							Event Agenda</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/artists/promote-engage">
							Engage</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/artists/promote-campaigns">
							Campaigns</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/artists/promote-adsets">
							Adsets</a>
						</li>
						
					</ul>
				</li>
				<li>
					<a href="<?php bloginfo('wpurl'); ?>/artists/repetoire"></i>
					<span class="title">Repetoire</span></a>
				</li>
			</ul>
			<!-- END SIDEBAR MENU -->
			<?php getMenuAgile(); ?>
		</div>
	</div>
	<!-- END SIDEBAR -->
	
	<?php
	
}

function getMenuProductions(){
	
	?>
		<!-- BEGIN SIDEBAR -->
	<div class="page-sidebar-wrapper">
		<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
		<!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
		<div class="page-sidebar navbar-collapse collapse">
			<!-- BEGIN SIDEBAR MENU -->
			<!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
			<!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
			<!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
			<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
			<!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
			<!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
			<ul class="page-sidebar-menu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
				<li>
					<a href="<?php bloginfo('wpurl'); ?>/productions"></i>
					<span class="title">Browse</span></a>
				</li>
				<li class="start ">
					<a href="javascript:;">
					<span class="title">Details</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/productions/artists">
							Artists</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/productions/channels">
							Channels</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/productions/content">
							Content</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/productions/info">
							Info</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/productions/partners">
							Partners</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/productions/schedule">
							Schedule</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="javascript:;"><i class='fa fa-bar-chart-o'></i>
					<span class="title">Insights</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/productions/insights-artists">
							Artists</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/productions/insights-audience">
							Audience</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/productions/insights-marketing">
							Marketing</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/productions/insights-social">
							Social</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/productions/insights-technical">
							Technical</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="javascript:;"><i class='fa fa-bullhorn'></i>
					<span class="title">Promote</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/productions/promote-PR">
							PR</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/productions/promote-keywords">
							Keywords</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/productions/promote-eventagenda">
							Event Agenda</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/productions/promote-engage">
							Engage</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/productions/promote-campaigns">
							Campaigns</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/productions/promote-adsets">
							Adsets</a>
						</li>
					</ul>
				</li>
			</ul>
			<?php getMenuAgile(); ?>
		</div>
	</div>
	<!-- END SIDEBAR -->
	
	<?php
	
}


function getMenuCampaigns(){
	
	?>
		<!-- BEGIN SIDEBAR -->
	<div class="page-sidebar-wrapper">
		<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
		<!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
		<div class="page-sidebar navbar-collapse collapse">
			<!-- BEGIN SIDEBAR MENU -->
			<!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
			<!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
			<!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
			<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
			<!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
			<!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
			<ul class="page-sidebar-menu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
				<li class="start ">
					<a href="javascript:;"><i class='fa fa-folder'></i>
					<span class="title">Browse</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/campaigns/browse-channels">
							Channels</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/campaigns/browse">
							Campaigns</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="javascript:;"><i class='fa fa-bar-chart-o'></i>
					<span class="title">Insights</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/campaigns/insights-spend">
							Spend</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/campaigns/insights-transactions">
							Transactions</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/campaigns/insights-revenue">
							Revenue</a>
						</li>
					
					</ul>
				</li>
				
				<li>
					<a href="<?php bloginfo('wpurl'); ?>/campaigns/create">
					<span class="title">Create Campaign</span>
					</a>
					
				</li>
				
			</ul>
			<!-- END SIDEBAR MENU -->			
			<?php getMenuAgile(); ?>
		</div>
	</div>
	<!-- END SIDEBAR -->
	
	<?php
	
}

function getMenuCompanies(){
	
	?>
		<!-- BEGIN SIDEBAR -->
	<div class="page-sidebar-wrapper">
		<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
		<!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
		<div class="page-sidebar navbar-collapse collapse">
			<!-- BEGIN SIDEBAR MENU -->
			<!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
			<!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
			<!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
			<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
			<!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
			<!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
			<ul class="page-sidebar-menu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
				<li>
					<a href="<?php bloginfo('wpurl'); ?>/companies"></i>
					<span class="title">Browse</span></a>
				</li>
				<li class="start ">
					<a href="javascript:;">
					<span class="title">Details</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/companies/detail">
							Info</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/companies/artists">
							Artists</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/companies/content">
							Content</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/companies/partners">
							Partners</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/companies/schedule">
							Schedule</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/companies/channel">
							Channels</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="javascript:;"><i class='fa fa-bar-chart-o'></i>
					<span class="title">Insights</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/companies/insights-marketing">
							Marketing</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/companies/insights-audience">
							Audience</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/companies/insights-artists">
							Artists</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/companies/insights-social">
							Social</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/companies/insights-technical">
							Technical</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="javascript:;"><i class='fa fa-bullhorn'></i>
					<span class="title">Promote</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/companies/promote-PR">
							PR</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/companies/promote-keywords">
							Keywords</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/companies/promote-eventagenda">
							Event Agenda</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/companies/promote-engage">
							Engage</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/companies/promote-campaigns">
							Campaigns</a>
						</li>
						<li>
							<a href="<?php bloginfo('wpurl'); ?>/companies/promote-adsets">
							Adsets</a>
						</li>
					</ul>
				</li>
			</ul>
			<!-- END SIDEBAR MENU -->			
			<?php getMenuAgile(); ?>
		</div>
	</div>
	<!-- END SIDEBAR -->
	
	<?php
	
}

function getMenuKnowledgebase(){
	
	?>
		<!-- BEGIN SIDEBAR -->
	<div class="page-sidebar-wrapper">
		<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
		<!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
		<div class="page-sidebar navbar-collapse collapse">
			<!-- BEGIN SIDEBAR MENU -->
			<!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
			<!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
			<!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
			<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
			<!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
			<!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
			<ul class="page-sidebar-menu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
				<li>
					<div class="form-group form-md-line-input">
						<a href="javascript:;">
						<div class="col-md-1 menu-searchicon">
							<i class='fa fa-search'></i>
						</div>
						<div class="col-md-10">
							<input type="text" class="form-control" id="form_control_1" placeholder="Search">
							<div class="form-control-focus">
							</div>
						</div>
						</a>
					</div>
				</li><br/>
					<hr/>
				<li>
					<a href="javascript:;"><i class='fa fa-folder'></i>
					<span class="title">Getting Started</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li>
							<a href="#">
							How to do epic stuff</a>
						</li>
						<li>
							<a href="#">
							Your campaign into orbit</a>
						</li>
						<li>
							<a href="#">
							Rock the world</a>
						</li>
					
					</ul>
				</li>
				<li>
					<a href="javascript:;"><i class='fa fa-folder'></i>
					<span class="title">Tutorials</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li>
							<a href="#">
							Productions</a>
						</li>
						<li>
							<a href="#">
							Artists</a>
						</li>
						<li>
							<a href="#">
							Campaigns</a>
						</li>
					
					</ul>
				</li>
			</ul>
			<!-- END SIDEBAR MENU -->
			
		</div>
	</div>
	<!-- END SIDEBAR -->
	
	<?php
	
}

function getMenuAgileMain(){
	
	?>
		<!-- BEGIN SIDEBAR -->
	<div class="page-sidebar-wrapper">
		<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
		<!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
		<div class="page-sidebar navbar-collapse collapse projects">
			<!-- BEGIN SIDEBAR MENU -->
			<!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
			<!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
			<!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
			<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
			<!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
			<!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
			<ul class="page-sidebar-menu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
				<li>
				<a href="<?php bloginfo('wpurl'); ?>/projects/"><i class='fa fa-trello'></i>
				<span class="title">Browse</span></a>
			</li>
			<ul class="page-sidebar-menu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
				<li>
				<a href="<?php bloginfo('wpurl'); ?>/agile/to-do"><i class='fa fa-tasks'></i>
				<span class="title">My Tasks</span></a>
			</li>
			<li>
				<a href="<?php bloginfo('wpurl'); ?>/inbox"><i class='fa fa-trello'></i>
				<span class="title">Inbox</span></a>
			</li>
			
			<li>
				<a href="<?php bloginfo('wpurl'); ?>/agile/notifications"><i class='fa fa-trello'></i>
				<span class="title">Notifications</span></a>
			</li>
			 
			<hr/>
			<li>
				
				<a href="<?php bloginfo('wpurl'); ?>/project/settings"><i class='fa fa-cog'></i>
				<span class="title">Settings</span></a>
			</li>
				
			</ul>
		</div>
	</div>
	<!-- END SIDEBAR -->
	
	<?php
	
}

/**
*
*
*/

function getMenuAgileProjects(){
	// Get the project id.
   $project_id=$_REQUEST['project_id']; 
	?>
		<!-- BEGIN SIDEBAR -->
	<div class="page-sidebar-wrapper">
		<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
		<!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
		<div class="page-sidebar navbar-collapse collapse projects">
			<!-- BEGIN SIDEBAR MENU -->
			<!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
			<!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
			<!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
			<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
			<!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
			<!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
			<ul class="page-sidebar-menu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
				<li>
				<a href="<?php bloginfo('wpurl'); ?>/projects/"><i class='fa fa-trello'></i>
				<span class="title">Browse</span></a>
			</li>
			<ul class="page-sidebar-menu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
				<li>
				<a href="<?php bloginfo('wpurl'); ?>/"><i class='fa fa-tasks'></i>
				<span class="title">Backlog</span></a>
			</li>
			<li>
				<a href="<?php bloginfo('wpurl'); ?>/boards/?project_id=<?php echo $project_id; ?> "><i class='fa fa-trello'></i>
				<span class="title">Boards</span></a>
			</li>
			
			<li>
				<a href="<?php bloginfo('wpurl'); ?>/agile/to-do"><i class='fa fa-trello'></i>
				<span class="title">Tasks</span></a>
			</li>
			
			<li>
				<a href="<?php bloginfo('wpurl'); ?>/planning"><i class='fa fa-tasks'></i>
				<span class="title">Planning</span></a>
			</li>
			 
			<hr/>
			<li>
				
				<a href="<?php bloginfo('wpurl'); ?>/project/settings"><i class='fa fa-cog'></i>
				<span class="title">Settings</span></a>
			</li>
				
			</ul>
		</div>
	</div>
	<!-- END SIDEBAR -->
	
	<?php
	
}


function getMenuAgile($mainsection){
	
	?>
	<hr/>			
	<!-- settings sidebar -->
	<ul class="page-sidebar-menu menu-settings" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
		<li>
		<a href="<?php echo $mainsection; ?>/agile"><i class='fa fa-tasks'></i>
			<span class="title">Agile</span>
			<span class="arrow "></span>
		</a>		
		<ul class="sub-menu">
			<li>
				<a href="<?php bloginfo('wpurl'); ?>/project/backlog"><i class='fa fa-tasks'></i>
				<span class="title">Backlog</span></a>
			</li>
			<li>
				<a href="<?php bloginfo('wpurl'); ?>/project/?project_id=75&tab=kanboard&action=index"><i class='fa fa-trello'></i>
				<span class="title">Boards</span></a>
			</li>
			<li>
				<a href="<?php bloginfo('wpurl'); ?>/project/?project_id=75&tab=chart&action=index"> <i class='fa fa-calendar'></i>
				<span class="title">Planning</span></a>
			</li>
		</ul>
		</li>
	</ul>
	<hr/>
	<ul class="page-sidebar-menu menu-settings" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
		<li>
			
			<a href="<?php bloginfo('wpurl'); ?>/productions/settings"><i class='fa fa-cog'></i>
			<span class="title">Settings</span></a>
		</li>
	</ul>
	<!-- end settings sidebar -->
	<?php
}
