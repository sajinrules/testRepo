

(function($){

    
	$('#template_view').click(function(){
		tb_show("", '#TB_inline?width=480&height=300&inlineId=template-option-step2');
	});
	
	
	$("#addRecipient_temp").on("click", function(e){
		e.preventDefault();
		$("#recipient_emails_temp").append( "<div id=\"newsigeradded\"><div id=\"input_left\"><input type=\"text\" name=\"recipient_fnames[]\" placeholder=\"Signers Name\" /></div>"
			+ "<div id=\"input_right\"> <input type=\"text\" name=\"recipient_emails[]\" class=\"deletable\" placeholder=\"email@address.com\"  value=\"\" /></div></div>" );
			
			$('input.deletable').wrap('<span class="deleteicon" />').after($('<span/>').click(function() {
                   e.preventDefault();
				$('#newsigeradded').remove();
                }));

		
	}); 
	
	$('#esig_template_upload').click(function(){
	      
		 jQuery.ajax({  
        type:"POST",  
        url:"admin-ajax.php?action=templateupload",   
         
        success:function(data, status, jqXHR){  
				$('#create_template').remove();
				$('#upload_template_button').remove();
				$('#template_type').show();
				$('#upload_template_content').show();
				$('#insert_template_button').show();
				$('#rupom').show();
				$('#template_id').empty(); 
             
				 $('#template_id').append(data);
				  
				 // selecting defalut value if todo add template
				 if(esigtemplateAjax.esig_add_template != ""){
				 $("#template_id").find('option').each(function( i, opt ) {
						if( opt.value === esigtemplateAjax.esig_add_template ) 
						$(opt).attr('selected', 'selected');
				 });
				 }
				 // tempalte trigger updated 
				 $('#template_id').trigger("chosen:updated");
				
				$(".chosen-container").css("min-width","250px");
				
				
				$(".chosen-drop").show(0, function () { 
				$(this).parents("div").css("overflow", "visible");
				});
			 
        },  
        error: function(xhr, status, error){  
            alert('Template Upload Error:' + xhr.responseText); 
        }  
    });  
	 
	 });
	
	
	// create template button clicked  
	$('#esig_template_create').click(function(){
	$(".chosen-container").css("min-width","250px");
	   $('#esig_template_create').hide();
	   $('#no_of_signer').show();
	    $('#create_template_basic_next').show();
	   $('#upload_template_button').hide();
	
	}) ;
	
	// create template next click 
	$('#esig_template_basic_next').click(function(){
	  
	  var noofsigner = $('input[name="signerno"]').val();
	  if(noofsigner == ""){
	    alert('please input how many signer?');
		return ; 
	  }
	  else if(isNaN(noofsigner)){
	    alert('Woah Tiger!  Looks like you\'re entering text in a field that only accepts numbers.  Try and using a number instead');
		return ; 
	  }
	   //var noofsigner = $('#no_of_signer option:selected').val();
		var doc_id = $(this).data('document');
	window.location = "edit.php?post_type=esign&page=esign-add-document&esig_type=template&document_id="+ doc_id +"&sif_signer="+ noofsigner;
	}) ;   
	// Show or hide the stand alone console when the box is checked.
		$('input[name="esig_template"]').on('change', function(){
			if($('input[name="esig_template"]').attr('checked')){
				$('#esig_template_input').show();
			} else {
				$('#esig_template_input').hide();
			}
		});
		
	
	
	// validation and submit start here 
	$('#template_insert').click(function(){
	  
	  var template_id = $('#template_id option:selected').val();
	  
	  var template_type = $('#esig_temp_doc_type option:selected').val();
	   var error = '' ;
	   if(template_type == 'doctype') {
		    error += 'You must select document type.\n\n';
		  }
	   if(template_id == 'sel_temp_name'){
		     error +='You must select template name.\n\n';
		  }
	   if(error != '')
				alert( error );
				
	   if(template_type == 'sad'){
	      $('#esig_select_template').submit();
	    }
		
		if(template_type == 'basic'){
		
		jQuery.ajax({  
        type:"POST",  
        url:"admin-ajax.php?action=sifinputfield",   
         data: {
			template_id:template_id,
		},  
        success:function(data, status, jqXHR){  
		//$(this).parents("div").css("overflow", "scroll");
          
			if(data != ""){
	
			  $('#recipient_emails_temp').html(data);
			 }
        },  
        error: function(xhr, status, error){  
            alert('Template insert error: ' + xhr.responseText + 'Please Try Again');
				return false ; 
        }  
    }); 
	
	$(this).parents("div").css("overflow", "");
	        $('#template_top').remove();	
			$('#standard_view_popup_bottom').show();
			$('input[name="template_id"]').val(template_id);
			$('input[name="esig_temp_document_type"]').val(template_type);		
	    }
	   
	});
	
	
	// esig temp todo start hree 
	if(esigtemplateAjax.esig_add_template != ""){
	 
$(document).ready(function() {
        tb_show("", '#TB_inline?width=480&height=300&inlineId=template-option-step2');
    });	
	
$( "#esig_template_upload" ).trigger( "click" );

	}
	// esig temp todo end here 
	if(esigtemplateAjax.esig_template_preview != "" ) {
			
		$('.basic_esign').hide();
		$('#submit_send').hide();
		$('#submit_save').hide();

		if(esigtemplateAjax.esig_template_edit == '1'){
		$('#esig_submit_section').append('<input type="submit" value="Update Template"  class="button button-primary button-large" id="submit_add_template"  name="add_template">');
		} else {
		$('#esig_submit_section').append('<input type="submit" value="Add Template"  class="button button-primary button-large" id="submit_add_template"  name="add_template">');
		}

		$('#esig_submit_section').append('<input type="submit" value="Save as Draft"  class="button button-secondary button-large" id="submit_save_stand"  name="save_template">');

		}
		
		
})(jQuery);
