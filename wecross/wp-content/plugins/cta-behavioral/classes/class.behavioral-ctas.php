<?php

class WPCTA_Behavioral {
	
	private $bt_ctas;
	private $protected;
	
	/**
	*  Initialize class
	*/
	function __construct() {
		
		add_action('wp_enqueue_scripts', array( $this , 'enqueue_scripts' ) , 99  );
		
		add_filter('wp_cta_variation_class', array( $this, 'add_behavioral_class' ) , 10 , 3 );
		
		add_filter('wp_cta_variation_attributes', array( $this, 'add_behavioral_attributes' ) , 10 , 3 );
		
	}
	
	/**
	*  Enqueue scripts
	*/
	public static function enqueue_scripts() {
		wp_enqueue_script('underscore');
		wp_enqueue_script( 'wp-cta-bt-script' , CTA_BT_URLPATH.'js/targeting.js' , array() , false , true );
		$params = array( 'ajax_url'=> WP_CTA_URLPATH.'modules/module.ajax-get-variation.php' ,  'admin_url' => admin_url( 'admin-ajax.php' ) );
		wp_localize_script( 'wp-cta-bt-script', 'wp_cta_bt', $params );
	}
	
	/**
	*  Add class to cta
	*/
	function add_behavioral_class( $class , $cta_id , $vid) {
		
		$is_behavioral = get_post_meta( $cta_id, 'wp-cta-bt-status-'.$vid , true );
		
		
		if ($is_behavioral) {
			$class = $class.' is_behavioral';			
		}
		
		return $class;
	}
	
	/**
	*  Add data attributes
	*/
	function add_behavioral_attributes( $attributes , $cta_id , $vid) {

		$suffix = '-'.$vid;
		
		$is_behavioral = get_post_meta( $cta_id, 'wp-cta-bt-status'.$suffix , true );
		
		if ($is_behavioral)	{

			$lists = get_post_meta( $cta_id, 'wp-cta-bt-lists'.$suffix , true );
			if($lists) {
				$lists = implode(',', $lists);
				$attributes = $attributes.' data-lists="'.$lists.'" data-cta-id="'.$cta_id.'" data-vid="'.$vid.'"';
			}
		}
		
		return $attributes;
	}
	
		
}


$wp_cta_bt = new WPCTA_Behavioral();
