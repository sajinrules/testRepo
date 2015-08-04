<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Multitool
 * @since We Cross Multitool 1.0
 */
?>

<!-- BEGIN QUICK SIDEBAR -->
		<!--Cooming Soon...-->
		<!-- END QUICK SIDEBAR -->
	</div>
	<!-- END CONTAINER -->
	<!-- BEGIN FOOTER -->
	<div class="page-footer"> 
		<div class="page-footer-inner">
			 2015 &copy; We Cross
		</div>
		<div class="scroll-to-top">
			<i class="icon-arrow-up"></i>
		</div>
	</div>
	<!-- END FOOTER -->
</div>
<div id="bg">
  <img src="<?php bloginfo('template_url'); ?>/images/bg.jpg" alt="">
</div>
<?php wp_footer(); ?>

<script>
jQuery(document).ready(function() {    
	Metronic.init(); // init metronic core componets
	Layout.init(); // init layout
	Demo.init(); // init demo features 
	Index.init();   
	Index.initDashboardDaterange();
	Index.initJQVMAP(); // init index page's custom scripts
	Index.initCalendar(); // init index page's custom scripts
	Index.initCharts(); // init index page's custom scripts
	Index.initChat();
	Index.initMiniCharts();
	Tasks.initDashboardWidget();
	TableManaged.init();
	TableAdvanced.init();
	UIExtendedModals.init();
	ComponentsDropdowns.init();
	Calendar.init();


 
});
</script>
<!-- END JAVASCRIPTS -->

</body>
<!-- END BODY -->
</html>