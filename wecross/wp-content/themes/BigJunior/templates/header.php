<?php
//Default header template
?>
<script src="//s7.addthis.com/js/300/addthis_widget.js#async=1#&pubid=ra-52fa3c2636ede06e" type="text/javascript"></script>

<header class="header-default">

    <div class="container clearfix">
	    <div class="row">
	        <?php
	        $logo = opt('logo') == "" ? path_combine(THEME_IMAGES_URI, "placeholders/logo.png") : opt('logo');
	        ?>
	
	        <div class="logo">
	            <a href="<?php echo home_url(); ?>">
	                <img src="<?php echo $logo; ?>" alt="Logo" />
	            </a>
	        </div>
			<nav class="navigation-top hidden-tablet hidden-phone">
	            <?php
	            wp_nav_menu(array(
	                'container' =>'',
	                'menu_class' => 'clearfix',
	                'before'     => '',
	                'theme_location' => 'top-nav',
	                'walker'     => new Custom_Nav_Walker(),
	                'fallback_cb' => false
	            ));
	            ?>
	        </nav>
	        <nav class="navigation hidden-tablet hidden-phone">
	            <?php
	            wp_nav_menu(array(
	                'container' =>'',
	                'menu_class' => 'clearfix',
	                'before'     => '<div class="background"></div>',
	                'theme_location' => 'primary-nav',
	                'walker'     => new Custom_Nav_Walker(),
	                'fallback_cb' => false
	            ));
	            ?>
	        </nav>
	        <div class="search-template">
	            <?php get_search_form(); ?>
	        </div>
			<div class="share-template">
				<div id="menu-item-share" class="menu-item menu-item-share">
				<span class="glyphicon glyphicon-heart"></span>
				</div>
				<div class="addthis_horizontal_follow_toolbox"></div>
				<div class="close-share"><span class="icon-close"></span></div>
				<div id="menu-item-search" class="menu-item menu-item-searchtoggle">
				<a href="#">
					<span class="glyphicon glyphicon-search"></span>
				</a>
				</div>
	        </div>
	        <a class="navigation-button hidden-desktop" href="#">
	            <span class="icon-paragraph-justify-2"></span>
	        </a>
	    </div>
    </div>
</header>