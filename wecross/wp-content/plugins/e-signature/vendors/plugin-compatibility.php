<?php


	/**
	*  excldue css handler 
	*  plugin compatibility check with others 
	*/
	
   add_action('admin_init','esig_dequeue_other_plugin',20);
   function esig_dequeue_other_plugin()
   {
   	   $page = (isset($_GET['page']))?$_GET['page']:null ;  
   	   if(!empty($page))
   	   {
   	   	  if(preg_match('/^esign/',$page))
   	   	  {
		  	 wp_dequeue_style( 'jquery-ui-lightness');
		  }
	   	  
	   }
   	  
   }

  
?>