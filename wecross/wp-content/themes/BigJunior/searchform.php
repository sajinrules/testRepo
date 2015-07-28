<div class="search-form">
	<div class="searchicon"><span class="glyphicon glyphicon-search"></span></div>
    <form action="<?php echo home_url( '/' ); ?>">
        <fieldset>
            <input type="text" name="s" placeholder="<?php _e('Zoek _', TEXTDOMAIN); ?>" value="<?php if(!empty($_GET['s'])) echo get_search_query(); ?>">
            
        </fieldset>
    </form>
    
     <div class="close-search">
	     <span class="icon-close"></span>
     </div>
     <div id="menu-item-share" class="menu-item menu-item-share">
	<a href="#">
	<span class="glyphicon glyphicon-heart"></span>
	</a>
	</div>
</div>