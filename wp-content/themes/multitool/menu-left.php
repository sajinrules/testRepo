<?php
/**
 * The sidebar containing the main widget area
 *
 * @package WordPress
 * @subpackage Multitool
 * @since Multitool 1.0
 */
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
				
				<?php wp_nav_menu(
					array( 
						'theme_location' => 'left-menu-main',
						'menu_class'=> 'page-sidebar-menu page-sidebar-menu-hover-submenu page-sidebar-menu-compact',
						'walker' => new mainmenu_left_walker
						)
 					); ?>
				<!--
				<ul class="page-sidebar-menu page-sidebar-menu-hover-submenu page-sidebar-menu-compact" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
					<li class="start active ">
						<a href="/">
						<i class="icon-home"></i>
						<span class="title">Dashboard</span>
						</a>
					</li>
					<li>
						<a href="/account">
						<i class="icon-user"></i>
						<span class="title">Mijn Account</span>
						<span class="arrow "></span>
						</a>
					</li>
					
				</ul>
				-->
					<!--
					<li>
						<a href="javascript:;">
						<i class="icon-envelope-open"></i>
						<span class="title">Email Templates</span>
						<span class="arrow "></span>
						</a>
						<ul class="sub-menu">
							<li>
								<a href="email_template1/index.html">
								New Email Template 1</a>
							</li>
							<li>
								<a href="email_template2/index.html">
								New Email Template 2</a>
							</li>
							<li>
								<a href="email_template3/index.html">
								New Email Template 3</a>
							</li>
							<li>
								<a href="email_template4/index.html">
								New Email Template 4</a>
							</li>
							<li>
								<a href="email_newsletter.html">
								Old Email Template 1</a>
							</li>
							<li>
								<a href="email_system.html">
								Old Email Template 2</a>
							</li>
						</ul>
					</li>
					
				</ul>
				-->
				<!-- END SIDEBAR MENU -->
			</div>
		</div>
		<!-- END SIDEBAR -->