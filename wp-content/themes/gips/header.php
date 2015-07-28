<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage Multitool
 * @since We Cross Multitool 1.0
 * 
 */
?><!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html <?php language_attributes(); ?> class="no-js">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta content="width=device-width, initial-scale=1" name="viewport"/>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
	<![endif]-->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<!-- BEGIN GLOBAL MANDATORY STYLES -->
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
	<link href="<?php bloginfo('template_url'); ?>/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
	<link href="<?php bloginfo('template_url'); ?>/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
	<link href="<?php bloginfo('template_url'); ?>/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link href="<?php bloginfo('template_url'); ?>/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
	<link href="<?php bloginfo('template_url'); ?>/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
	<!-- END GLOBAL MANDATORY STYLES -->
	<!-- BEGIN PAGE LEVEL PLUGIN STYLES -->

	<link href="<?php bloginfo('template_url'); ?>/assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css"/>
	<link href="<?php bloginfo('template_url'); ?>/assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css"/>
	<link href="<?php bloginfo('template_url'); ?>/assets/global/plugins/jqvmap/jqvmap/jqvmap.css" rel="stylesheet" type="text/css"/>
	<link href="<?php bloginfo('template_url'); ?>/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css" rel="stylesheet" type="text/css"/>
	<link href="<?php bloginfo('template_url'); ?>/assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet"/>
	<!-- BEGIN:File Upload Plugin CSS files-->
	<link href="<?php bloginfo('template_url'); ?>/assets/global/plugins/jquery-file-upload/blueimp-gallery/blueimp-gallery.min.css" rel="stylesheet"/>
	<link href="<?php bloginfo('template_url'); ?>/assets/global/plugins/jquery-file-upload/css/jquery.fileupload.css" rel="stylesheet"/>
	<link href="<?php bloginfo('template_url'); ?>/assets/global/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet"/>
	<!-- END PAGE LEVEL PLUGIN STYLES -->
	<!-- BEGIN PAGE STYLES -->
	<link href="<?php bloginfo('template_url'); ?>/assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css"/>
	<!-- END PAGE STYLES -->
	<!-- BEGIN THEME STYLES -->
	<!-- DOC: To use 'rounded corners' style just load 'components-rounded.css' stylesheet instead of 'components.css' in the below style tag -->
	<link href="<?php bloginfo('template_url'); ?>/assets/global/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css"/>
    <!--<link href="<?php bloginfo('template_url'); ?>/assets/global/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>-->
	<link href="<?php bloginfo('template_url'); ?>/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
	<link href="<?php bloginfo('template_url'); ?>/assets/admin/layout4/css/layout.css" rel="stylesheet" type="text/css"/>
	<link href="<?php bloginfo('template_url'); ?>/assets/admin/layout4/css/themes/light.css" rel="stylesheet" type="text/css" id="style_color"/>
	<link href="<?php bloginfo('template_url'); ?>/assets/admin/layout4/css/custom.css" rel="stylesheet" type="text/css"/>
	<link href="<?php bloginfo('template_url'); ?>/assets/admin/pages/css/profile.css" rel="stylesheet" type="text/css"/>
	<link href="<?php bloginfo('template_url'); ?>/assets/admin/pages/css/inbox.css" rel="stylesheet" type="text/css"/>
	<link href="<?php bloginfo('template_url'); ?>/assets/admin/pages/css/timeline-old.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/assets/global/plugins/select2/select2.css"/>
	<link href="<?php bloginfo('template_url'); ?>/assets/admin/pages/css/todo.css" rel="stylesheet" type="text/css">
	<!-- BEGIN CORE PLUGINS -->
	<!--[if lt IE 9]>
	<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/respond.min.js"></script>
	<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/excanvas.min.js"></script> 
	<![endif]-->
	<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
	<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
	<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
	<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
	<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
	<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
	<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
	<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
	<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
	<script src="<?php bloginfo('template_url'); ?>/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
	<!-- END CORE PLUGINS -->
	<!-- BEGIN PAGE LEVEL SCRIPTS -->
	<script src="<?php bloginfo('template_url'); ?>/assets/global/scripts/metronic.js" type="text/javascript"></script>
	<script src="<?php bloginfo('template_url'); ?>/assets/admin/layout4/scripts/layout.js" type="text/javascript"></script>
	<script src="<?php bloginfo('template_url'); ?>/assets/admin/layout4/scripts/demo.js" type="text/javascript"></script>
	<script src="<?php bloginfo('template_url'); ?>/assets/admin/pages/scripts/index.js" type="text/javascript"></script>
	<script src="<?php bloginfo('template_url'); ?>/assets/admin/pages/scripts/tasks.js" type="text/javascript"></script>
    <script src="<?php bloginfo('template_url'); ?>/assets/admin/pages/scripts/profile.js" type="text/javascript"></script>
    
    <!--<link href="<?php bloginfo('template_url'); ?>/style.css" rel="stylesheet" type="text/css"/>-->
    <link href="<?php echo get_stylesheet_directory_uri(); ?>/style.css" rel="stylesheet" type="text/css"/>
	<!-- END PAGE LEVEL SCRIPTS -->
	
	<!-- END THEME STYLES -->
	<?php wp_head(); ?>
</head>
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-fixed-mobile" and "page-footer-fixed-mobile" class to body element to force fixed header or footer in mobile devices -->
<!-- DOC: Apply "page-sidebar-closed" class to the body and "page-sidebar-menu-closed" class to the sidebar menu element to hide the sidebar by default -->
<!-- DOC: Apply "page-sidebar-hide" class to the body to make the sidebar completely hidden on toggle -->
<!-- DOC: Apply "page-sidebar-closed-hide-logo" class to the body element to make the logo hidden on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-hide" class to body element to completely hide the sidebar on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-fixed" class to have fixed sidebar -->
<!-- DOC: Apply "page-footer-fixed" class to the body element to have fixed footer -->
<!-- DOC: Apply "page-sidebar-reversed" class to put the sidebar on the right side -->
<!-- DOC: Apply "page-full-width" class to the body element to have full width page without the sidebar menu -->
<body class="page-header-fixed">


<?php include('menu-top.php'); ?>
<div class="clearfix"></div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
<?php //include('menu-left.php'); ?>
<!-- BEGIN CONTENT -->