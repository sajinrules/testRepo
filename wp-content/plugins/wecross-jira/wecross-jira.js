jQuery(document).ready(function(){
	// do stuff when page is loaded	
	
	jQuery('#jira-save-submit').click(function(){
		var url = encodeURI(jQuery('#form_control_1').val());
		var nr = jQuery('#form_control_2').val();
		var cur = window.location.href;
		
		cur = cur.replace("http://wecross.dev.wecross.nl", "");
		cur = cur.replace("https://www.wecross.com", "");
		cur = cur.replace("http://www.wecross.com", "");
		cur = cur.replace("http://85.222.225.4", "");
		//console.log(url);
		//console.log(nr);
		//console.log(cur);
		//console.log(encodeURIComponent(cur));
		//console.log(encodeURI(cur));
		jQuery.ajax({
	      type: "POST",
	      url: "/wp-content/plugins/wecross-jira/storejira.php?cur="+encodeURIComponent(cur)+"&nr="+nr+"&url="+url,
	      cache: false,
	      success: function(html) {
		      jQuery("#jira-ajax-response").html(html);
			  jQuery("#jiralink").attr( "href", jQuery('#form_control_1').val() );
			  if(nr!=''){
				 jQuery("#jiralink").html( nr ); 
			  }
			  else{
				 
			  	jQuery("#jiralink").html( 'Nog geen jiralink opgeslagen' ); 
			  }
	      }
	      
		  });
	
	});
});