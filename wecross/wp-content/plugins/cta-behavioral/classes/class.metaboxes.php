<?php


class CTA_Behavioral_Metaboxes {
	
	/**
	*  Initialize class
	*/
	public function __construct() {
		self::add_hooks();
	}
	
	/**
	*  Adds hooks and filters
	*/
	public static function add_hooks() {
				
		/* Filter the status during post_save hook */
		add_filter( "wp_cta_save_variation_status" , array( __CLASS__ , 'update_variation_status' ) , 10 , 1 );
		
		/* settings for wordpress-call-to-action cpt */
		add_filter( "wp_cta_extension_data", array( __CLASS__ , 'extend_metaboxes' ), 10 , 1 );
	}

	
	/**
	*  Update Variation Status
	*/
	public static function update_variation_status( $status )	{
		/* get variation */
		$vid = $_POST['wp-cta-variation-id'];		

		/* if behavior dependancy is not checked */
		if (!isset($_POST['wp-cta-bt-status-' . $vid])) {
			return $status;
		}

		/* If behavior dependancy is checked */	
		if ( !$_POST['wp-cta-bt-status-' . $vid] && $status == 'behavioral' ) {
			return 'active';
		} else if ( !$_POST['wp-cta-bt-status-' . $vid] && $status == 'active' ) {
			return 'active';
		} else {
			return 'behavioral';
		}
		
	}


	/** 
	*  extend cta metaboxes
	*/
	public static function extend_metaboxes( $wp_cta_data ) {

		$parent_key = 'wp-cta';

		$wp_cta_data[$parent_key]['settings']['bt-header'] =	array(
			'datatype' => 'setting',
			'region' => 'advanced',
			'description'	=> '<h3>Behavioral Targeting</h3>',
			'id'	=> 'behavioral-targeting-header',
			'type'	=> 'html-block'
		);

		$wp_cta_data[$parent_key]['settings']['bt-status'] =	array(
			'datatype' => 'setting',
			'region' => 'advanced',
			'label' => 'Enable',
			'description'	=> 'Turn on behavorial Targeting',
			'id'	=> 'bt-status',
			'options_area' => 'advanced',
			'class' => 'behavorial-targeting',
			'type'	=> 'radio',
			'options' => array( 
				array( 'label' => __( 'Off' , 'inbound-pro' ) ,	'value' => '0' ) ,
				array( 'label' => __( 'On' , 'inbound-pro' ) ,	'value' => '1' ) ,
			)		
		);

		/* build array of lead lists */
		$options = array();
		$categories = get_terms( 'wplead_list_category', array(
			'orderby'	=> 'count',
			'hide_empty' => 0
		) );
						
		if (isset($categories->errors)) {
			return $wp_cta_data;
		}
		
		foreach ($categories as $cat){
			$options[ $cat->term_id ] = $cat->name;
		}


		$wp_cta_data[$parent_key]['settings']['bt-lists'] = array(
			'datatype' => 'setting',
			'region' => 'advanced',
			'label' => 'Which Lists',
			'description'	=> 'Select the lead list(s) that you would like to trigger this call to action',
			'id'	=> 'bt-lists',
			'options_area' => 'advanced',
			'class' => 'behavorial-targeting',
			'type'	=> 'multiselect',
			'options' => $options ,
			);



		return $wp_cta_data;
	}
}

new CTA_Behavioral_Metaboxes;
