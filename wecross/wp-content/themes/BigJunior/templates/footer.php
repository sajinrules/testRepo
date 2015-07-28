<?php
$footerWidgets = opt('footer_widgets');
if($footerWidgets=='') $footerWidgets = DEFAULT_FOOTER_WIDGETS;

//Check if there is an override in page
if(2 == get_meta('footer-widget-area'))
    $footerWidgets = false;
?>
<footer class="footer-default">
    <?php if($footerWidgets){ ?>
    <div class="footer-widgets">
        <div class="container">
            <div class="row widget-area">
                <?php
                $widgetSize = 12 / $footerWidgets;

                for($i = 1; $i <= $footerWidgets; $i++)
                {
                ?>
                    <div class="span<?php echo $widgetSize ?>"><?php
                        /* Widgetised Area */
                        if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'footer-widget-' . $i ) ){}	?>
                        &nbsp;
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
    <div id="footer-bottom">
        <div class="container">
            <?php if(opt('logo-footer') != ''){ ?>
            <div class="logo"><img src="<?php eopt('logo-footer'); ?>" alt="<?php _e('Logo', TEXTDOMAIN); ?>"/></div>
            <?php } 
	            
	            $year = date('Y');
            ?>
            <a href="http://www.wecross.nl/home"><p class="copyright"><?php /* eopt('footer-copyright'); */ echo $year; ?>&nbsp;&copy;&nbsp;WE CROSS&nbsp;</p>
</a>            <ul class="social-icons">
                <?php
                $icons =
                    array(
                        'social_facebook_url' => 'icon-facebook-3',//Facebook
                        'social_twitter_url'  => 'icon-twitter-3 ',//twitter
                        'social_vimeo_url'    => 'icon-vimeo-2',//vimeo
                        'social_youtube_url'  => 'icon-youtube',//youtube
                        'social_googleplus_url' => 'icon-google-plus-4',//Google+
                        'social_dribbble_url' => 'icon-dribbble-3',//dribbble
                        'social_tumblr_url'   => 'icon-tumblr-2',//Tumblr
                        'social_linkedin_url' => 'icon-linkedin',//LinkedIn
                        'social_flickr_url'   => 'icon-flickr-4',//flickr
                        'social_forrst_url'   => 'icon-forrst-2',//forrst
                        'social_github_url'   => 'icon-github-5',//GitHub
                        'social_lastfm_url'   => 'icon-lastfm-2',//Last.fm
                        'social_paypal_url'   => 'icon-paypal-3',//Paypal
                        'social_rss_url'      => 'icon-feed-3',//rss
                        'social_skype_url'    => 'icon-skype',//skype
                        'social_wordpress_url'=> 'icon-wordpress-2',//wordpress
                        'social_yahoo_url'    => 'icon-yahoo',//yahoo
                        'social_deviantart_url' => 'icon-deviantart-2',//DeviantArt
                        'social_steam_url'    => 'icon-steam-2',//Steam
                        'social_reddit_url'   => 'icon-reddit',//reddit
                        'social_stumbleupon_url' => 'icon-stumbleupon-2',//StumbleUpon
                        'social_pinterest_url' => 'icon-pinterest',//Pinterest
                        'social_xing_url'      => 'icon-xing-2 ',//XING
                        'social_blogger_url'   => 'icon-blogger-2',//Blogger
                        'social_soundcloud_url' => 'icon-soundcloud-2',//SoundCloud
                        'social_delicious_url'  => 'icon-delicious',//delicious
                        'social_foursquare_url' => 'icon-foursquare',//Foursquare
                );
                foreach($icons as $key => $icon)
                {
                    if(opt($key) != '')
                    {
                ?>
                <li><a href="<?php echo esc_attr(opt($key)); ?>"><span class="<?php echo $icon; ?>"></span></a></li>
                <?php
                    }//endif
                }
                ?>

            </ul>
        </div>
    </div>
</footer>
<script>
jQuery(document).ready(function(){
	
	jQuery('.navigation').css('top','40px');
	jQuery('.navigation ul li a').css('height', '120px');
	jQuery('.navigation ul li a').css('line-height', '120px');
	jQuery('.share-template').css('top','80px');
	jQuery('.search-template').css('top','80px');
	
	jQuery(window).scroll(function (event) {
   		var scroll = jQuery(window).scrollTop();
   		if(scroll>0){
	   		jQuery('.navigation-top').fadeOut();
	   		jQuery('.navigation').css('top','5px');
	   		jQuery('.navigation ul li a').css('height', '50px');
	   		jQuery('.navigation ul li a').css('line-height', '50px');
	   		jQuery('.share-template').css('top','-5px');
	   		jQuery('.search-template').css('top','10px');
	   		jQuery('.header-default').css('background', 'none repeat scroll 0 0 rgba(255, 255, 255, 1)');
   		}
   		else
   		{
	   		jQuery('.navigation-top').fadeIn();
	   		jQuery('.navigation').css('top','40px');
	   		jQuery('.navigation ul li a').css('height', '120px');
	   		jQuery('.navigation ul li a').css('line-height', '120px');
	   		jQuery('.share-template').css('top','80px');
	   		jQuery('.search-template').css('top','80px');
	   		jQuery('.header-default').css('background', 'none repeat scroll 0 0 rgba(255, 255, 255, 0.5)');
   		}
	});
	
	jQuery('.menu-item-share').click(function(){
		jQuery('#menu-hoofdmenu').fadeOut();
		jQuery('.search-template').fadeOut();
		jQuery('.share-template').fadeIn();
	});
	
	jQuery('.close-share').click(function(){
		jQuery('.share-template').fadeOut();
		jQuery('#menu-hoofdmenu').fadeIn();
	});
	
	jQuery('#menu-item-search a').click(function(){
		jQuery('#menu-hoofdmenu').fadeOut();
		jQuery('.share-template').fadeOut();
		jQuery('.search-template').fadeIn();
	});
	
	jQuery('.menu-item-searchtoggle').click(function(){
		jQuery('#menu-hoofdmenu').fadeOut();
		jQuery('.share-template').fadeOut();
		jQuery('.search-template').fadeIn();
	});
	
	jQuery('.close-search').click(function(){
		jQuery('.search-template').fadeOut();
		jQuery('#menu-hoofdmenu').fadeIn();
	});
	
	jQuery('.tabs ul li').click(function(){
		
	});
	loadAddThis();
	function loadAddThis() {
	    addthis.init();
	}
	
});
</script>
<!-- removed footer old tags -->