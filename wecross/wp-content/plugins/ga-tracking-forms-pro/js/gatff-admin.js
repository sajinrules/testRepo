
jQuery(document).ready( function($) {
	//for option 1
	$("#gatff_active_option_1_id").click(function(){
		if( $(this).is(':checked') == false ){
			$("#gatff_option_1_form_div").fadeOut();
		}else{
			$("#gatff_option_1_form_div").fadeIn();
		}
	});
	
	$("#gatff_option_1_form_type_gravity_form_id").click(function(){
		if( $(this).is(':checked') ){
			$("#gatff_option_1_gravity_form_ID_id").removeAttr("disabled");
			$(".gatff-option-1-gf-field-css").css("display", "inline-block");
			$(".gatff-option-1-html-field-css").css("display", "none");			
		}
	});
	
	$("#gatff_option_1_form_type_html_id").click(function(){
		if( $(this).is(':checked') ){
			$("#gatff_option_1_gravity_form_ID_id").attr("disabled", "disabled");
			$(".gatff-option-1-gf-field-css").css("display", "none");
			$(".gatff-option-1-html-field-css").css("display", "inline-block");			
		}
	});
	
	$("#gatff_option_1_gravity_form_ID_id").change(function(){
		//get fields of the selected form to fields selector
		var form_id = $(this).val();
		if( form_id == 0 ){
			$(".gatff-option-1-gf-field-css").attr("disabled", "disabled");
			return;
		}
		form_id = form_id.replace('gform_', '');
		$("#gatff_option_1_gf_fields_ajax_loader").css("display", "inline-block");
		$.post( 
			ajaxurl, 
			{action: "gatff_get_gform_field", formid: form_id}, 
			function( response ){
				$("#gatff_option_1_gf_fields_ajax_loader").css( "display", "none" );
				if( response.indexOf('ERROR') != -1 ){
					alert( response );
				}else{
					$(".gatff-option-1-gf-field-css").html( response );
					$(".gatff-option-1-gf-field-css").removeAttr("disabled");
				}
			}
		);
	});
	
	//for option 2
	$("#gatff_option_2_page_ID_id, #gatff_option_2_post_ID_id").change(function(){
		if( $(this).val() == 0 ){
			return;
		}
		if( $(this).attr('id') == 'gatff_option_2_page_ID_id'){
			$("#gatff_option_2_post_ID_id").val(0);
		}else if( $(this).attr('id') == 'gatff_option_2_post_ID_id'){
			$("#gatff_option_2_page_ID_id").val(0);
		}
	});
	$("#gatff_active_option_2_id").click(function(){
		if( $(this).is(':checked') == false ){
			$("#gatff_option_2_form_div").fadeOut();
		}else{
			$("#gatff_option_2_form_div").fadeIn();
		}
	});
	
	$("#gatff_option_2_form_type_gravity_form_id").click(function(){
		if( $(this).is(':checked') ){
			$("#gatff_option_2_gravity_form_ID_id").removeAttr("disabled");
			$(".gatff-option-2-gf-field-css").css("display", "inline-block");
			$(".gatff-option-2-html-field-css").css("display", "none");			
		}
	});
	
	$("#gatff_option_2_form_type_html_id").click(function(){
		if( $(this).is(':checked') ){
			$("#gatff_option_2_gravity_form_ID_id").attr("disabled", "disabled");
			$(".gatff-option-2-gf-field-css").css("display", "none");
			$(".gatff-option-2-html-field-css").css("display", "inline-block");			
		}
	});
	
	$("#gatff_option_2_gravity_form_ID_id").change(function(){
		//get fields of the selected form to fields selector
		var form_id = $(this).val();
		if( form_id == 0 ){
			$(".gatff-option-2-gf-field-css").attr("disabled", "disabled");
			return;
		}
		form_id = form_id.replace('gform_', '');
		$("#gatff_option_2_gf_fields_ajax_loader").css("display", "inline-block");
		$.post( 
			ajaxurl, 
			{action: "gatff_get_gform_field", formid: form_id}, 
			function( response ){
				$("#gatff_option_2_gf_fields_ajax_loader").css( "display", "none" );
				if( response.indexOf('ERROR') != -1 ){
					alert( response );
				}else{
					$(".gatff-option-2-gf-field-css").html( response );
					$(".gatff-option-2-gf-field-css").removeAttr("disabled");
				}
			}
		);
	});
	
	$("#gatff_option_2_add_configuration_id").click(function(){
		var page_id = $("#gatff_option_2_post_ID_id").val();
		var post_id = $("#gatff_option_2_page_ID_id").val();
		
		if( page_id == 0 && post_id == 0 ){
			alert('Please select a Page/Post');
			return false;
		}
		add_configration_4_option_2();
	});
	
	function add_configration_4_option_2(){
		if( $("#gatff_option_2_form_type_gravity_form_id").is(':checked') ){
			if( $("#gatff_option_2_gravity_form_ID_id").val() == 0 ){
				alert('Please select a gravity form');
				$("#gatff_option_2_gravity_form_ID_id").focus();
				return false;
			}
			//check if the entered page_id, form_id exist or not ?
			if( check_if_page_form_id_exist_or_not($("#gatff_option_2_gravity_form_ID_id").val() ) == false ){
				return false;
			}
			
			var all_fields_valid = true;
			var fields_id = new Array();
			$(".gatff-option-2-gf-field-css").each(function(){
				var label = $(this).parent().find('span').html();
				var val = $(this).val();
				if( val < 1 ){
					alert( 'Please select field for ' + label );
					$(this).focus();
					all_fields_valid = false;
					return false;
				}
				if( jQuery.inArray( val, fields_id ) != -1 ){
					alert( 'Please choses a different field for ' + label );
					$(this).focus();
					all_fields_valid = false;
					return false;
				}
				fields_id.push( val );
			});
			
			//add a new row to existing configration
			if( all_fields_valid == true ){
				insert_hidden_configration_and_table_row_4_gravity_form();
			}
		}else if( $("#gatff_option_2_form_type_html_id").is(':checked') ){
			if( $.trim($("#gatff_option_2_html_form_ID_id").val()) == "" ){
				alert('Please enter value for Specify one HTML Form ID');
				$("#gatff_option_2_html_form_ID_id").focus();
				return false;
			}
			//check if the entered page_id, form_id exist or not ?
			if( check_if_page_form_id_exist_or_not( $.trim($("#gatff_option_2_html_form_ID_id").val()) ) == false ){
				return false;
			}
			
			var all_fields_valid = true;
			var fields_id = new Array();
			$(".gatff-option-2-html-field-css").each(function(){
				var label = $(this).parent().find('span').html();
				var val = $.trim($(this).val());
				if( val == "" ){
					alert( 'Please select field for ' + label );
					$(this).focus();
					all_fields_valid = false;
					return false;
				}
				if( jQuery.inArray( val, fields_id ) != -1 ){
					alert( 'Please choses a different field for ' + label );
					$(this).focus();
					all_fields_valid = false;
					return false;
				}
				fields_id.push( val );
			});
			
			//add a new row to existing configration
			if( all_fields_valid == true ){
				insert_hidden_configration_and_table_row_4_html_form();
			}
		}
	}
	function check_if_page_form_id_exist_or_not( opiton_2_form_id ){
		//first check if exist in Load on all Pages checked
		var option_1_form_id = '';
		if( $("#gatff_active_option_1_id").is(':checked') ){
			if( $("#gatff_option_1_form_type_gravity_form_id").is(':checked') ){
				if( !( $("#gatff_option_1_gravity_form_ID_id").val() == 0 ) ){
					option_1_form_id = $("#gatff_option_1_gravity_form_ID_id").val()
				}
			}else if( $("#gatff_option_1_form_type_html_id").is(':checked') ){
				if( $.trim($("#gatff_option_1_html_form_ID_id").val()) != "" ){
					option_1_form_id = $.trim($("#gatff_option_1_html_form_ID_id").val());
				}
			}
			if( option_1_form_id == opiton_2_form_id ){
				alert("The form Id you want to add already exist in Load on all Pages");
				return false;;
			}
		}
		
		//checkif exist in option 2
		var existing_hidden_str = $("#gatff_option_2_exist_configuration_id").val();
		if( existing_hidden_str == "" ){
			return true;
		}
		var page_id = $("#gatff_option_2_page_ID_id").val();
		var post_id = $("#gatff_option_2_post_ID_id").val();
		if( page_id == 0 ){
			page_id = post_id;
		}
		var is_page_form_id_exist = false;
		$(".gattf-configuration-row").each(function(){
			var table_row_tr_id = 'page_id_' + page_id + '_form_id_' + opiton_2_form_id;
			//alert(table_row_tr_id);
			if( table_row_tr_id == $(this).attr('id') ){
				is_page_form_id_exist = true;
				$("#" + table_row_tr_id).css("background-color", 'yellow');
				return false;
			}
		});
		if( is_page_form_id_exist == true ){
			return false;
		}
		
		return true;
	}
	function insert_hidden_configration_and_table_row_4_gravity_form(){
		var page_id = $("#gatff_option_2_page_ID_id").val();
		var post_id = $("#gatff_option_2_post_ID_id").val();
		if( page_id == 0 ){
			page_id = post_id;
		}
		var form_id = $.trim($("#gatff_option_2_gravity_form_ID_id").val());
		field_source = "";
		field_medium = "";
		field_term = "";
		field_content = "";
		field_campaign = ""
		field_segment = "";
		
		$(".gatff-option-2-gf-field-css").each(function(){
			var id = $(this).attr("id");
			var val = $.trim($(this).val());
			switch ( id ) {
				case 'gatff_option_2_gravity_form_fields_list_source_id':
					field_source = val;
				break;
				case 'gatff_option_2_gravity_form_fields_list_medium_id':
					field_medium = val;
				break;
				case 'gatff_option_2_gravity_form_fields_list_term_id':
					field_term = val;
				break;
				case 'gatff_option_2_gravity_form_fields_list_content_id':
					field_content = val;
				break;
				case 'gatff_option_2_gravity_form_fields_list_campaign_id':
					field_campaign = val;
				break;
				case 'gatff_option_2_gravity_form_fields_list_segment_id':
					field_segment = val;
				break;
			}
		});
		
		var insert_str = page_id + '#' + 
						 form_id + '#' + 
						 field_source + '#' + 
						 field_medium + '#' +
						 field_term + '#' + 
						 field_content + '#' + 
						 field_campaign + '#' + 
						 field_segment + ';';
		var fields_in_table_row_str = field_source + ', ' + field_medium + ', ' + field_term + ', ' + field_content + ', ' + field_campaign + ', ' + field_segment;
		var table_row_tr_id = 'page_id_' + page_id + '_form_id_' + form_id;
		var existing_hidden_str = $("#gatff_option_2_exist_configuration_id").val();
		var insert_str_hidden = '<input type="hidden" id="' + table_row_tr_id + '_hidden_id" value="' + insert_str + '" />'; //add this for delete
		var trash_icon_url = $("#gatff_option_2_trash_icon_image_url_id").val();
		var trash_button = '<div id="trash_button_' + table_row_tr_id + '" style="cursor:pointer;" class="gatff-option-2-trash-button"><img src="' + trash_icon_url + '" width="16" height="16"></div>';
		if( existing_hidden_str == "" ){
			$("#gatff_option_2_exist_configuration_id").val( insert_str );
			$("#gatff_existing_configuration_4_specified_page").html('<tr id="' + table_row_tr_id + '" class="gattf-configuration-row"><td>'+page_id+'</td><td>'+form_id+'</td><td>'+fields_in_table_row_str+'</td><td>' + insert_str_hidden + trash_button + '</td></tr>');
		}else{
			$("#gatff_option_2_exist_configuration_id").val( existing_hidden_str + insert_str );
			$("#gatff_existing_configuration_4_specified_page").append('<tr id="' + table_row_tr_id + '" class="gattf-configuration-row"><td>'+page_id+'</td><td>'+form_id+'</td><td>'+fields_in_table_row_str+'</td><td>' + insert_str_hidden + trash_button + '</td></tr>');
		}
		
	}
	
	function insert_hidden_configration_and_table_row_4_html_form(){
		var page_id = $("#gatff_option_2_page_ID_id").val();
		var post_id = $("#gatff_option_2_post_ID_id").val();
		if( page_id == 0 ){
			page_id = post_id;
		}
		var form_id = $.trim($("#gatff_option_2_html_form_ID_id").val());
		field_source = "";
		field_medium = "";
		field_term = "";
		field_content = "";
		field_campaign = ""
		field_segment = "";
		
		$(".gatff-option-2-html-field-css").each(function(){
			var id = $(this).attr("id");
			var val = $.trim($(this).val());
			switch ( id ) {
				case 'gatff_option_2_field_source_id':
					field_source = val;
				break;
				case 'gatff_option_2_field_medium':
					field_medium = val;
				break;
				case 'gatff_option_2_field_term':
					field_term = val;
				break;
				case 'gatff_option_2_field_content':
					field_content = val;
				break;
				case 'gatff_option_2_field_campaign':
					field_campaign = val;
				break;
				case 'gatff_option_2_field_segment':
					field_segment = val;
				break;
			}
		});
		
		var insert_str = page_id + '#' + 
						 form_id + '#' + 
						 field_source + '#' + 
						 field_medium + '#' +
						 field_term + '#' + 
						 field_content + '#' + 
						 field_campaign + '#' + 
						 field_segment + ';';
		var fields_in_table_row_str = field_source + ', ' + field_medium + ', ' + field_term + ', ' + field_content + ', ' + field_campaign + ', ' + field_segment;
		var table_row_tr_id = 'page_id_' + page_id + '_form_id_' + form_id;
		var existing_hidden_str = $("#gatff_option_2_exist_configuration_id").val();
		var insert_str_hidden = '<input type="hidden" id="' + table_row_tr_id + '_hidden_id" value="' + insert_str + '" />'; //add this for delete
		var trash_icon_url = $("#gatff_option_2_trash_icon_image_url_id").val();
		var trash_button = '<div id="trash_button_' + table_row_tr_id + '" style="cursor:pointer;" class="gatff-option-2-trash-button"><img src="' + trash_icon_url + '" width="16" height="16"></div>';
		if( existing_hidden_str == "" ){
			$("#gatff_option_2_exist_configuration_id").val( insert_str );
			$("#gatff_existing_configuration_4_specified_page").html('<tr id="' + table_row_tr_id + '" class="gattf-configuration-row"><td>'+page_id+'</td><td>'+form_id+'</td><td>'+fields_in_table_row_str+'</td><td>' + insert_str_hidden + trash_button + '</td></tr>');
		}else{
			$("#gatff_option_2_exist_configuration_id").val( existing_hidden_str + insert_str );
			$("#gatff_existing_configuration_4_specified_page").append('<tr id="' + table_row_tr_id + '" class="gattf-configuration-row"><td>'+page_id+'</td><td>'+form_id+'</td><td>'+fields_in_table_row_str+'</td><td>' + insert_str_hidden + trash_button + '</td></tr>');
		}
		
	}
	
	$(".gatff-option-2-trash-button").live("click", function(){
		var table_row_tr_id = $(this).attr("id").replace('trash_button_', '');
		var hidden_inserted_str_id = table_row_tr_id + '_hidden_id';
		var hidden_inserted_str_val = $("#" + hidden_inserted_str_id).val();
		//remove table row
		$("#" + table_row_tr_id).fadeOut(400, function(){
            $("#" + table_row_tr_id).remove();
        });
		//remove inserted str from hidden text
		var existing_hidden_str = $("#gatff_option_2_exist_configuration_id").val();
		existing_hidden_str = existing_hidden_str.replace(hidden_inserted_str_val, '');
		$("#gatff_option_2_exist_configuration_id").val(existing_hidden_str);
	});
	
	//function to celar background
	//celar background in 3 seconds
	function clear_tr_background(){
		$(".gattf-configuration-row").each(function(){
			$(this).css("background-color", 'transparent');
		});
	}
	setInterval(clear_tr_background, 3000);
				
	//save all stting
	$("#gatff_save_id").click(function(){
		var is_all_valid = true;
		//check if Load on all Pages checked
		if( $("#gatff_active_option_1_id").is(':checked') ){
			is_all_valid = check_all_fields_valid_4_option_1();
			if( is_all_valid == false ){
				return false;
			}
		}
		
		if( $("#gatff_active_option_2_id").is(':checked') ){
		is_all_valid = check_all_fields_valid_4_option_2();
			if( is_all_valid == false ){
				return false;
			}
		}
		
		$("#gatff_action_id").val('save_settings');
		$("#gatff_setting_form_id").submit();
	});
	
	function check_all_fields_valid_4_option_1(){
		if( $("#gatff_option_1_form_type_gravity_form_id").is(':checked') ){
			if( $("#gatff_option_1_gravity_form_ID_id").val() < 1 ){
				alert('Please select a gravity form');
				$("#gatff_option_1_gravity_form_ID_id").focus();
				return false;
			}
			var all_fields_valid = true;
			var fields_id = new Array();
			$(".gatff-option-1-gf-field-css").each(function(){
				var label = $(this).parent().find('span').html();
				var val = $(this).val();
				if( val < 1 ){
					alert( 'Please select field for ' + label );
					$(this).focus();
					all_fields_valid = false;
					return false;
				}
				if( jQuery.inArray( val, fields_id ) != -1 ){
					alert( 'Please choses a different field for ' + label );
					$(this).focus();
					all_fields_valid = false;
					return false;
				}
				fields_id.push( val );
			});
			
			return all_fields_valid;
		}else if( $("#gatff_option_1_form_type_html_id").is(':checked') ){
			if( $.trim($("#gatff_option_1_html_form_ID_id").val()) == "" ){
				alert('Please enter value for Specify one HTML Form ID');
				$("#gatff_option_1_html_form_ID_id").focus();
				return false;
			}
			var all_fields_valid = true;
			var fields_id = new Array();
			$(".gatff-option-1-html-field-css").each(function(){
				var label = $(this).parent().find('span').html();
				var val = $.trim($(this).val());
				if( val == "" ){
					alert( 'Please select field for ' + label );
					$(this).focus();
					all_fields_valid = false;
					return false;
				}
				if( jQuery.inArray( val, fields_id ) != -1 ){
					alert( 'Please choses a different field for ' + label );
					$(this).focus();
					all_fields_valid = false;
					return false;
				}
				fields_id.push( val );
			});
			
			return all_fields_valid;
		}
	}
	
	function check_all_fields_valid_4_option_2(){
		var existing_configuration = $("#gatff_option_2_exist_configuration_id").val();
		if( existing_configuration == "" ){
			alert("No existing configuration, please add first");
			$("#gatff_active_option_2_id").focus();
			return false;
		}
		return true;
	}
});