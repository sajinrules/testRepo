<?php

/**
*  core extra funcitons 
*/

function esig_total_addons_installed()
{
			$array_Plugins = get_plugins();
			$i = 0 ; 	
			if(!empty($array_Plugins))
			{
				foreach($array_Plugins as $plugin_file => $plugin_data) 
				 {
				   if(is_plugin_active($plugin_file)) 
				   {
				        $plugin_name=$plugin_data['Name'] ; 
						
						// if($plugin_name!="WP E-Signature")
						// {  
						   if(preg_match("/WP E-Signature/",$plugin_name))
						   {  
						      if($plugin_name!="WP E-Signature")
						 	  { 
						      		$i++ ; 
							  }
						   }
					}
				}
			}
			
			return $i ; 			 
}