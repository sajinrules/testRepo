(function($){

	$("#esig_url_redirect").click(function() {

	var esig_url_tag_id = $('input[name="esig_url_id"]');
    

    jQuery.ajax({  
        type:"GET",  
        url: esig_url_ajax_script.ajaxurl,   
        data:{
				url_id:esig_url_tag_id.val(),
			},  
        success:function(data, status, jqXHR){    
            jQuery("#esig_url_redirect").html(data);  
        },  
        error: function(xhr, status, error){  
            alert(xhr.responseText); 
        }  
    });  

  });

})(jQuery);

