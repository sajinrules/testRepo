<?php

function px_register_menus() {
	register_nav_menu( 'primary-nav', __( 'Primary Navigation', TEXTDOMAIN ) );
    register_nav_menu( 'mobile-nav', __( 'Mobile Navigation', TEXTDOMAIN ) );
    register_nav_menu( 'top-nav', __( 'Top Navigation', TEXTDOMAIN ) );
}

add_action( 'init', 'px_register_menus' );

function px_add_search_menu_item($items, $args)
{
    if( 'primary-nav' != $args->theme_location )
        return $items;

    ob_start();
    ?>
    <li id="menu-item-share" class="menu-item menu-item-share">
        <a href="#"><span class="glyphicon glyphicon-heart"></span></a>
        
    </li>
    <li id="menu-item-search" class="menu-item menu-item-searchtoggle">
        <a href="#"><span class="glyphicon glyphicon-search"></span></a>
        
    </li>
    <?php
    $items .= ob_get_clean();
    return $items;
}

add_filter('wp_nav_menu_items', 'px_add_search_menu_item', 10, 2);