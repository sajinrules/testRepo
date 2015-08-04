<?php
/**
 * Template Name: Companies > Details Content News
 *
 * The template and functionality for displaying an account
 * @package WordPress
 * @subpackage Multitool
 * @since We Cross 1.0
 */
get_header(); 
the_post();
?><?php getMenuCompanies(); ?>
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
					<h1><?php // the_title(); ?>Trending Content</h1>
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
					<a href="/">Content</a>
					<i class="fa fa-circle"></i>
				</li>
				<li>
					<a href="#">...</a>
				</li>
			</ul>
			<!-- END PAGE BREADCRUMB -->
			<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
			<div class="portlet light">
				<div class="portlet-body">
					<div class="row">
						<div class="col-md-12 news-page">
							<div class="row">
								<div class="col-md-5">
									<div id="myCarousel" class="carousel image-carousel slide">
										<div class="carousel-inner">
											<div class="active item">
												<img src="<?php bloginfo('template_url'); ?>/assets/admin/pages/media/gallery/image5.jpg" class="img-responsive" alt="">
												<div class="carousel-caption">
													<h4>
													<a href="page_news_item.html">
													First Thumbnail label </a>
													</h4>
													<p>
														 Cras justo odio, dapibus ac facilisis in, egestas eget quam.
													</p>
												</div>
											</div>
											<div class="item">
												<img src="<?php bloginfo('template_url'); ?>/assets/admin/pages/media/gallery/image2.jpg" class="img-responsive" alt="">
												<div class="carousel-caption">
													<h4>
													<a href="page_news_item.html">
													Second Thumbnail label </a>
													</h4>
													<p>
														 Cras justo odio, dapibus ac facilisis in, egestas eget quam.
													</p>
												</div>
											</div>
											<div class="item">
												<img src="<?php bloginfo('template_url'); ?>/assets/admin/pages/media/gallery/image1.jpg" class="img-responsive" alt="">
												<div class="carousel-caption">
													<h4>
													<a href="page_news_item.html">
													Third Thumbnail label </a>
													</h4>
													<p>
														 Cras justo odio, dapibus ac facilisis in, egestas eget quam.
													</p>
												</div>
											</div>
										</div>
										<!-- Carousel nav -->
										<a class="carousel-control left" href="#myCarousel" data-slide="prev">
										<i class="m-icon-big-swapleft m-icon-white"></i>
										</a>
										<a class="carousel-control right" href="#myCarousel" data-slide="next">
										<i class="m-icon-big-swapright m-icon-white"></i>
										</a>
										<ol class="carousel-indicators">
											<li data-target="#myCarousel" data-slide-to="0" class="active">
											</li>
											<li data-target="#myCarousel" data-slide-to="1">
											</li>
											<li data-target="#myCarousel" data-slide-to="2">
											</li>
										</ol>
									</div>
									<div class="top-news margin-top-10">
										<a href="javascript:;" class="btn blue">
										<span>
										Featured News </span>
										<em>
										<i class="fa fa-tags"></i>
										USA, Business, Apple </em>
										<i class="fa fa- icon-bullhorn top-news-icon"></i>
										</a>
									</div>
									<div class="news-blocks">
										<h3>
										<a href="page_news_item.html">
										Google Glass Technology.. </a>
										</h3>
										<div class="news-block-tags">
											<strong>CA, USA</strong>
											<em>3 hours ago</em>
										</div>
										<p>
											<img class="news-block-img pull-right" src="<?php bloginfo('template_url'); ?>/assets/admin/pages/media/gallery/image1.jpg" alt="">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident
										</p>
										<a href="page_news_item.html" class="news-block-btn">
										Read more <i class="m-icon-swapright m-icon-black"></i>
										</a>
									</div>
									<div class="news-blocks">
										<h3>
										<a href="page_news_item.html">
										Sint occaecati cupiditat </a>
										</h3>
										<div class="news-block-tags">
											<strong>London, UK</strong>
											<em>7 hours ago</em>
										</div>
										<p>
											<img class="news-block-img pull-right" src="<?php bloginfo('template_url'); ?>/assets/admin/pages/media/gallery/image4.jpg" alt="">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident
										</p>
										<a href="page_news_item.html" class="news-block-btn">
										Read more <i class="m-icon-swapright m-icon-black"></i>
										</a>
									</div>
									<div class="news-blocks">
										<h3>
										<a href="page_news_item.html">
										Accusamus et iusto odio </a>
										</h3>
										<div class="news-block-tags">
											<strong>CA, USA</strong>
											<em>3 hours ago</em>
										</div>
										<p>
											<img class="news-block-img pull-right" src="<?php bloginfo('template_url'); ?>/assets/admin/pages/media/gallery/image5.jpg" alt="">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident
										</p>
										<a href="page_news_item.html" class="news-block-btn">
										Read more <i class="m-icon-swapright m-icon-black"></i>
										</a>
									</div>
								</div>
								<!--end col-md-5-->
								<div class="col-md-4">
									<div class="top-news">
										<a href="javascript:;" class="btn red">
										<span>
										World News </span>
										<em>
										<i class="fa fa-tags"></i>
										UK, Canada, Asia </em>
										<i class="fa fa-globe top-news-icon"></i>
										</a>
									</div>
									<div class="news-blocks">
										<h3>
										<a href="page_news_item.html">
										Odio dignissimos ducimus </a>
										</h3>
										<div class="news-block-tags">
											<strong>Berlin, Germany</strong>
											<em>2 hours ago</em>
										</div>
										<p>
											<img class="news-block-img pull-right" src="<?php bloginfo('template_url'); ?>/assets/admin/pages/media/gallery/image3.jpg" alt="">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident
										</p>
										<a href="page_news_item.html" class="news-block-btn">
										Read more <i class="m-icon-swapright m-icon-black"></i>
										</a>
									</div>
									<div class="news-blocks">
										<h3>
										<a href="page_news_item.html">
										Sanditiis praesentium vo </a>
										</h3>
										<div class="news-block-tags">
											<strong>Ankara, Turkey</strong>
											<em>5 hours ago</em>
										</div>
										<p>
											<img class="news-block-img pull-right" src="<?php bloginfo('template_url'); ?>/assets/admin/pages/media/gallery/image5.jpg" alt="">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint praesentium voluptatum delenitioccaecati cupiditate non provident
										</p>
										<a href="page_news_item.html" class="news-block-btn">
										Read more <i class="m-icon-swapright m-icon-black"></i>
										</a>
									</div>
									<div class="top-news">
										<a href="javascript:;" class="btn green">
										<span>
										Finance </span>
										<em>
										<i class="fa fa-tags"></i>
										Money, Business, Google </em>
										<i class="fa fa-briefcase top-news-icon"></i>
										</a>
									</div>
									<div class="news-blocks">
										<h3>
										<a href="page_news_item.html">
										Odio dignissimos ducimus </a>
										</h3>
										<div class="news-block-tags">
											<strong>Berlin, Germany</strong>
											<em>2 hours ago</em>
										</div>
										<p>
											<img class="news-block-img pull-right" src="<?php bloginfo('template_url'); ?>/assets/admin/pages/media/gallery/image3.jpg" alt="">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint non provident
										</p>
										<a href="page_news_item.html" class="news-block-btn">
										Read more <i class="m-icon-swapright m-icon-black"></i>
										</a>
									</div>
									<div class="news-blocks">
										<h3>
										<a href="page_news_item.html">
										Sanditiis praesentium vo </a>
										</h3>
										<div class="news-block-tags">
											<strong>Ankara, Turkey</strong>
											<em>5 hours ago</em>
										</div>
										<p>
											<img class="news-block-img pull-right" src="<?php bloginfo('template_url'); ?>/assets/admin/pages/media/gallery/image5.jpg" alt="">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint praesentium voluptatum delenitioccaecati cupiditate non provident
										</p>
										<a href="page_news_item.html" class="news-block-btn">
										Read more <i class="m-icon-swapright m-icon-black"></i>
										</a>
									</div>
								</div>
								<!--end col-md-4-->
								<div class="col-md-3">
									<div class="top-news">
										<a href="javascript:;" class="btn purple">
										<span>
										Science </span>
										<em>
										<i class="fa fa-tags"></i>
										Hi-Tech, Medicine, Space </em>
										<i class="fa fa-beaker top-news-icon"></i>
										</a>
									</div>
									<div class="news-blocks">
										<h3>
										<a href="page_news_item.html">
										Vero eos et accusam </a>
										</h3>
										<div class="news-block-tags">
											<strong>CA, USA</strong>
											<em>3 hours ago</em>
										</div>
										<p>
											<img class="news-block-img pull-right" src="<?php bloginfo('template_url'); ?>/assets/admin/pages/media/gallery/image2.jpg" alt="">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident
										</p>
										<a href="page_news_item.html" class="news-block-btn">
										Read more <i class="m-icon-swapright m-icon-black"></i>
										</a>
									</div>
									<div class="news-blocks">
										<h3>
										<a href="page_news_item.html">
										Sias excepturi sint occae </a>
										</h3>
										<div class="news-block-tags">
											<strong>Vancouver, Canada</strong>
											<em>3 hours ago</em>
										</div>
										<p>
											<img class="news-block-img pull-right" src="<?php bloginfo('template_url'); ?>/assets/admin/pages/media/gallery/image4.jpg" alt="">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident
										</p>
										<a href="page_news_item.html" class="news-block-btn">
										Read more <i class="m-icon-swapright m-icon-black"></i>
										</a>
									</div>
									<div class="top-news">
										<a href="javascript:;" class="btn yellow">
										<span>
										Sport </span>
										<em>
										<i class="fa fa-tags"></i>
										Football, Swimming, Tennis </em>
										<i class="fa fa-trophy top-news-icon"></i>
										</a>
									</div>
									<div class="news-blocks">
										<h3>
										<a href="page_news_item.html">
										Vero eos et accusam </a>
										</h3>
										<div class="news-block-tags">
											<strong>CA, USA</strong>
											<em>3 hours ago</em>
										</div>
										<p>
											<img class="news-block-img pull-right" src="<?php bloginfo('template_url'); ?>/assets/admin/pages/media/gallery/image2.jpg" alt="">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident
										</p>
										<a href="page_news_item.html" class="news-block-btn">
										Read more <i class="m-icon-swapright m-icon-black"></i>
										</a>
									</div>
								</div>
								<!--end col-md-3-->
							</div>
							<div class="space20">
							</div>
							<h3>News Option</h3>
							<div class="row">
								<div class="col-md-3">
									<div class="news-blocks">
										<h3>
										<a href="page_news_item.html">
										Google Glass Technology.. </a>
										</h3>
										<div class="news-block-tags">
											<strong>LA, USA</strong>
											<em>2 hours ago</em>
										</div>
										<p>
											<img class="news-block-img pull-right" src="<?php bloginfo('template_url'); ?>/assets/admin/pages/media/gallery/image5.jpg" alt="">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident
										</p>
										<a href="page_news_item.html" class="news-block-btn">
										Read more <i class="m-icon-swapright m-icon-black"></i>
										</a>
									</div>
									<div class="news-blocks">
										<h3>
										<a href="page_news_item.html">
										Google Glass Technology.. </a>
										</h3>
										<div class="news-block-tags">
											<strong>Berlin, Germany</strong>
											<em>6 hours ago</em>
										</div>
										<p>
											 At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident
										</p>
										<a href="page_news_item.html" class="news-block-btn">
										Read more <i class="m-icon-swapright m-icon-black"></i>
										</a>
									</div>
								</div>
								<!--end col-md-3-->
								<div class="col-md-3">
									<div class="news-blocks">
										<h3>
										<a href="page_news_item.html">
										Google Glass Technology.. </a>
										</h3>
										<div class="news-block-tags">
											<strong>CA, USA</strong>
											<em>3 hours ago</em>
										</div>
										<p>
											<img class="news-block-img pull-right" src="<?php bloginfo('template_url'); ?>/assets/admin/pages/media/gallery/image3.jpg" alt="">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident
										</p>
										<a href="page_news_item.html" class="news-block-btn">
										Read more <i class="m-icon-swapright m-icon-black"></i>
										</a>
									</div>
									<div class="news-blocks">
										<h3>
										<a href="page_news_item.html">
										Google Glass Technology.. </a>
										</h3>
										<div class="news-block-tags">
											<strong>CA, USA</strong>
											<em>3 hours ago</em>
										</div>
										<p>
											 At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident
										</p>
										<a href="page_news_item.html" class="news-block-btn">
										Read more <i class="m-icon-swapright m-icon-black"></i>
										</a>
									</div>
								</div>
								<!--end col-md-3-->
								<div class="col-md-6">
									<div class="row">
										<div class="col-md-12">
											<div class="news-blocks">
												<h3>
												<a href="page_news_item.html">
												Pusto odio dignissimos ducimus i quos dolores et qui blanditiis praesentium.. </a>
												</h3>
												<div class="news-block-tags">
													<strong>CA, USA</strong>
													<em>3 hours ago</em>
												</div>
												<p>
													<img class="news-block-img pull-right" src="<?php bloginfo('template_url'); ?>/assets/admin/pages/media/gallery/image2.jpg" alt="">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident iti..
												</p>
												<a href="page_news_item.html" class="news-block-btn">
												Read more <i class="m-icon-swapright m-icon-black"></i>
												</a>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="news-blocks">
												<h3>
												<a href="page_news_item.html">
												Vero eos et accusamus et iusto od qui.. </a>
												</h3>
												<div class="news-block-tags">
													<strong>CA, USA</strong>
													<em>3 hours ago</em>
												</div>
												<p>
													 At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident
												</p>
												<a href="page_news_item.html" class="news-block-btn">
												Read more <i class="m-icon-swapright m-icon-black"></i>
												</a>
											</div>
										</div>
										<div class="col-md-6">
											<div class="news-blocks">
												<h3>
												<a href="page_news_item.html">
												Google Glass Technology.. </a>
												</h3>
												<div class="news-block-tags">
													<strong>CA, USA</strong>
													<em>3 hours ago</em>
												</div>
												<p>
													 At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident
												</p>
												<a href="page_news_item.html" class="news-block-btn">
												Read more <i class="m-icon-swapright m-icon-black"></i>
												</a>
											</div>
										</div>
									</div>
								</div>
								<!--end col-md-6-->
							</div>
							<div class="space20">
							</div>
							<h3>News Feeds</h3>
							<div class="row">
								<div class="col-md-3">
									<div class="top-news">
										<a href="javascript:;" class="btn red">
										<span>
										Metronic News </span>
										<em>Posted on: April 16, 2013</em>
										<em>
										<i class="fa fa-tags"></i>
										Money, Business, Google </em>
										<i class="fa fa-briefcase top-news-icon"></i>
										</a>
									</div>
								</div>
								<div class="col-md-3">
									<div class="top-news">
										<a href="javascript:;" class="btn green">
										<span>
										Top Week </span>
										<em>Posted on: April 15, 2013</em>
										<em>
										<i class="fa fa-tags"></i>
										Internet, Music, People </em>
										<i class="fa fa-music top-news-icon"></i>
										</a>
									</div>
								</div>
								<div class="col-md-3">
									<div class="top-news">
										<a href="javascript:;" class="btn blue">
										<span>
										Gold Price Falls </span>
										<em>Posted on: April 14, 2013</em>
										<em>
										<i class="fa fa-tags"></i>
										USA, Business, Apple </em>
										<i class="fa fa-globe top-news-icon"></i>
										</a>
									</div>
								</div>
								<div class="col-md-3">
									<div class="top-news">
										<a href="javascript:;" class="btn yellow">
										<span>
										Study Abroad </span>
										<em>Posted on: April 13, 2013</em>
										<em>
										<i class="fa fa-tags"></i>
										Education, Students, Canada </em>
										<i class="fa fa-book top-news-icon"></i>
										</a>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<div class="top-news">
										<a href="javascript:;" class="btn green">
										<span>
										Top Week </span>
										<em>Posted on: April 15, 2013</em>
										<em>
										<i class="fa fa-tags"></i>
										Internet, Music, People </em>
										<i class="fa fa-music top-news-icon"></i>
										</a>
									</div>
								</div>
								<div class="col-md-3">
									<div class="top-news">
										<a href="javascript:;" class="btn yellow">
										<span>
										Study Abroad </span>
										<em>Posted on: April 13, 2013</em>
										<em>
										<i class="fa fa-tags"></i>
										Education, Students, Canada </em>
										<i class="fa fa-book top-news-icon"></i>
										</a>
									</div>
								</div>
								<div class="col-md-3">
									<div class="top-news">
										<a href="javascript:;" class="btn red">
										<span>
										Metronic News </span>
										<em>Posted on: April 16, 2013</em>
										<em>
										<i class="fa fa-tags"></i>
										Money, Business, Google </em>
										<i class="fa fa-briefcase top-news-icon"></i>
										</a>
									</div>
								</div>
								<div class="col-md-3">
									<div class="top-news">
										<a href="javascript:;" class="btn blue">
										<span>
										Gold Price Falls </span>
										<em>Posted on: April 14, 2013</em>
										<em>
										<i class="fa fa-tags"></i>
										USA, Business, Apple </em>
										<i class="fa fa-globe top-news-icon"></i>
										</a>
									</div>
								</div>
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
<script>
jQuery(document).ready(function() {       
   // initiate layout and plugins
   Metronic.init(); // init metronic core components
   Layout.init(); // init current layout
   Demo.init(); // init demo features

   
   
});

</script>
<?php //get_sidebar(); ?>
<?php get_footer(); ?>