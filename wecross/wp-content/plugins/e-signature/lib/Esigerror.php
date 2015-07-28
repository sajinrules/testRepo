<?php
/**
 * E-signature Message Functions
 *
 * Functions for error/message handling and display.
 *
 * @author 		Abushoaib
 * @category 	Core
 * @package 	E-signature/lib
 * @version     1.0.13
 */


class WP_E_Esigerror{
	

	/***
	* adding constract 
	* */
	
	public function __constract(){
		
	}
	
	
	/***
	 * add error msg 
	 * Since 1.0.13 
	 * */
	
	public function esig_add_error($msg,$msg_type){
		
		if(!get_transient('esig_error_msg')){
			$notices =array();	
		}
		else {
			$notices=json_decode(get_transient('esig_error_msg'));
		}
		
		$notices[]=array('msg' => $msg,'msg_type' =>$msg_type);
		$esig_notice=json_encode($notices);
		set_transient('esig_error_msg',$esig_notice,60);
		
	}


}