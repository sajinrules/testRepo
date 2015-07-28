
var esig_sif_admin_controls = null;

(function ($) {

//"use strict";

		
	$(function () {
	
		
		// Clone btn
		$('.esig-sif-main-panels .clone-btn').click(function(){
			var target = $(this).data('target');
			if($(target).length){
				$(this).before($(target).html());
			}
			return false;
		});
		
    // textbox advanced button start here 
   // $("#sif_textbox_advanced_button").on("click", function(e){

    
		// });
		// date picker start here 

	
		$('ul li #addRadio').live('click',function() {
             $("#radio_html").append( "<li id=\"removeradio\">" +
					"<input type=\"radio\" name=\"\"/>" +
				   "<input type=\"text\" class=\"deletablesif\" name=\"label[]\" placeholder=\"Label\" value=\"\" />"+
				"<input type=\"hidden\" class=\"hidden_radio\" name=\"\" value=\"\">" + 
                "<span class=\"icon-plus\" id=\"addRadio\"></span><span class=\"icon-minus\" id=\"minusRadio\"></span></span>" +
				"</li>" );
		});

		$('ul li #minusRadio').live('click',function() {
			$(this).parent().remove();
			 return false;
		});


		$('ul li #addCheckbox').live('click',function() {
             $("#checkbox_html").append( "<li id=\"removecheckbox\">" +
					"<input type=\"checkbox\" name=\"\"/>" +
				   "<input type=\"text\" class=\"deletablesif\" name=\"label[]\" placeholder=\"Label\" value=\"\" />"+
				"<input type=\"hidden\" class=\"hidden_checkbox\" name=\"\" value=\"\">" + 
                "<span class=\"icon-plus\" id=\"addCheckbox\"></span><span class=\"icon-minus\" id=\"minusCheckbox\"></span></span>" +
				"</li>" );
		});

		$('ul li #minusCheckbox').live('click',function() {
			$(this).parent().remove();
			 return false;
		});
		
		// Textfield
		$('body').on('click', '.esig-sif-panel-textfield .insert-btn', function() {
		//$('.esig-sif-panel-textfield .insert-btn').click(function(){
			
			var name = 'esig-sif-' + Date.now();
			var verifysigner = $( "#sif_invite_select" ).val();
            var maxsize= $("input[name='maxsize']").val();
            
			var required = $('.esig-sif-panel-textfield input.required').prop('checked') ? 'required="1"' : '';
			var label = $(".esig-sif-panel-textfield input[name='textbox']").val();
			var return_text = ' [esigtextfield name="'+name+'" verifysigner="'+verifysigner+'" size="'+ maxsize +'" label="'+ label +'" '+ required +' ] ';
			esig_sif_admin_controls.insertContent(return_text);
			
			tb_remove();
			return false;
		});


        $('body').on('change keyup paste', '.popover #maxsize', function () {

                   
                 var maxsize= $("body .popover input[name='textbox_width']").val();
                 var label = $(".esig-sif-panel-textfield input[name='textbox']").val();
               
               var htmltext = 'Enter your placeholder text <br> <input type="text" name="textbox" style="width:'+ maxsize +'px;"  class="sif_input_field label" value="'+ label +'" placeholder="'+ label +'"><input type="hidden" name="maxsize" value="'+ maxsize +'">';
              
               $('.sif_text_placeholder_Text').html(htmltext);
        });

      
        // date picket
		$('.esig-sif-panel-datepicker .insert-date').click(function(){
			 var name = 'esig-sif-picker-' + Date.now();
			var verifysigner = $( "#sif_invite_select" ).val();
			
			var return_text = '[esigdatepicker name="' + name + '"  verifysigner="'+ verifysigner +'"]';
			esig_sif_admin_controls.insertContent(return_text);
			
			tb_remove();
			return false;
		});

		
		// Radios
		$('.esig-sif-panel-radio .insert-btn').click(function(){
			var name = 'esig-sif-' + Date.now();
            var radio_label= $("input[name='radiolabel']").val();
			var sif_display= $("input[name='display_position']").val();
            if(sif_display != 'horizontal'){
                sif_display = 'vertical';
            }
			var verifysigner = $( "#sif_invite_select" ).val();
			var required = $('.esig-sif-panel-radio input.required').prop('checked') ? 'required="1"' : '';
			var radios = $('.esig-sif-panel-radio .hidden_radio').serialize();
			var return_text = ' [esigradio name="'+ name +'" label="'+ radio_label  +'" display="'+ sif_display +'" verifysigner="'+verifysigner+'" labels="'+ radios +'" '+ required +' ] ';
			esig_sif_admin_controls.insertContent(return_text);
			
			tb_remove();
			return false;
		});

        $('body').on('change keyup paste', '.popover #radiocheck', function () {

                   
                 var sif_display= $("body .popover input[name='sif_radio_position']:checked").val();
               
               var htmltext = '<input type="hidden" name="display_position" value="'+ sif_display +'">';
              
               $('#radio_html').append(htmltext);
        });


		
		// Checkboxes
		$('.esig-sif-panel-checkbox .insert-btn').click(function(){
			var name = 'esig-sif-' + Date.now();
			var checkbox_label= $("input[name='checkboxlabel']").val();
			var sif_display= $("input[name='display_position']").val();
            if(sif_display != 'horizontal'){
                sif_display = 'vertical';
            }
			var verifysigner = $('select[name="sif_invite_select"]').val();
			var required = $('.esig-sif-panel-checkbox input.required').prop('checked') ? ' required="1"' : '';
			var boxes = $('.esig-sif-panel-checkbox .hidden_checkbox').serialize();
			var return_text = ' [esigcheckbox name="'+name+'" label="'+ checkbox_label +'" display="'+ sif_display +'" verifysigner="'+verifysigner+'" boxes="'+ boxes +'"  '+ required +' ] ';
			
			esig_sif_admin_controls.insertContent(return_text);
			
			tb_remove();
			return false;
		});

         $('body').on('change keyup paste', '.popover #checkboxcheck', function () {

                   
                 var sif_display= $("body .popover input[name='sif_checkbox_position']:checked").val();
               
               var htmltext = '<input type="hidden" name="display_position" value="'+ sif_display +'">';
              
               $('#checkbox_html').append(htmltext);
        });

		// Enter user's label into name attribute of hidden checkbox
		$('.esig-sif-panel-checkbox').on('change', 'input:text' ,function(){
			var name = $(this).val();
			var box = $(this).closest('li').find('.hidden_checkbox');
			if(box.length){
				$(box).attr('name', name);
			}
		});

		// Enter checked into value of hidden checkbox
		$('.esig-sif-panel-checkbox').on('change', 'input:checkbox' ,function(){
			var box = $(this).closest('li').find('.hidden_checkbox');
			if(box.length){
				var checked = $(this).attr('checked') ? '1':'0';
				$(box).val(checked);
			}
		});

		// Enter user's label into name attribute of radio
		$('.esig-sif-panel-radio').on('change', 'input:text' ,function(){
			var name = $(this).val();
			var box = $(this).closest('li').find('.hidden_radio');
			if(box.length){
				$(box).attr('name', name);
			}
		});
		
		// Enter checked into value of hidden radio
		$('.esig-sif-panel-radio').on('change', 'input:radio' ,function(){
			var box = $(this).closest('li').find('.hidden_radio');
			if(box.length){
				var checked = $(this).attr('checked') ? '1':'0';
				$(box).val(checked);
			}
		});
		
		// advanced option start here 
		
         $("#sif_textbox_advanced_button").popover({
                     placement: 'bottom',
                     html: 'true',
                     title : '<span><strong>Advanced Settings</strong></span>'+
                            '<span class="close">&times;</span>',
                     content : $('.sif_textbox_advanced_content').html(),
		             template:'<div class="popover" role="tooltip"><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
         });

          $('body').on('click', '.popover .close', function() {
				$("#sif_textbox_advanced_button").popover('hide');
		  });

//advanced settings of radio button 
		$("#sif_radio_advanced_button").popover({
                     placement: 'bottom',
                     html: 'true',
                     title : '<span><strong>Advanced Settings</strong></span>'+
                            '<span class="close">&times;</span>',
                     content : $('.sif_radio_advanced_content').html(),
		             template:'<div class="popover" role="tooltip"><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
         });

         $('body').on('click', '.popover .close', function() {
					$("#sif_radio_advanced_button").popover('hide');
		 });

		$("#sif_checkbox_advanced_button").popover({
                     placement: 'bottom',
                     html: 'true',
                     title : '<span><strong>Advanced Settings</strong></span>'+
                            '<span class="close">&times;</span>',
                     content : $('.sif_radio_advanced_content').html(),
		             template:'<div class="popover" role="tooltip"><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
         });

          $('body').on('click', '.popover .close', function() {
				$("#sif_checkbox_advanced_button").popover('hide');
		  });
		
});
	
	
	/*
	Main Class for admin controls
	*/
	esig_sif_admin_controls = {
			
	    menu_class: "mce-esig-sif-adminMainMenu",
		initialized: false,
		menu_timer: null,
		editor: null,
		mode: 'mce', // mce or quicktag mode
		quicktag: null, // wp quicktag for text-only mode
		canvas: null, // wp post textarea
		element: null, // the button the user clicked to open this menu

		// Initializes the main menu
		// canvas and element are only used for quicktags
		mainMenuInit: function(editor) {
			var self = this;
			self.editor = editor;
			var commands = {
				'textfield': { label:'Insert Text Box' },
				'todaydate': { label:'Insert Date' },
				'radio': { label:'Insert Radio Buttons' },
				'checkbox': { label:'Insert Checkboxes' }
			};
			var buttons = '';
			$.each(commands, function(key,command){
				buttons =  buttons + '<li class="btn" data-cmd="'+key+'">' +  command.label + "</li>\n";
			});
			var ul = '<ul style="display:none;" class="'+self.menu_class+'">'+buttons+'</ul>';
			$('.mceIcon.mce_esig_sif').append(ul); // Add menu html to mce
			
			// Add wrapper around quicktag
			$('#qt_document_content_esig_1').wrap('<span id="qt_document_content_esig_1_wrap"></span>');
			
			// Add menu html to quicktag wrapper
			$('#qt_document_content_esig_1_wrap').append(ul); 
			
			$('.'+self.menu_class).mouseout(function(){
			    var menu = this;
			    self.menu_timer = setTimeout(function(){
			        $('.'+self.menu_class).hide();
			    }, 200);
			}).mouseover(function(){
				if(self.menu_timer){
					clearTimeout(self.menu_timer);
				}
			});
			$('.'+self.menu_class+' > li.btn').click(function(){
			
				var cmd = $(this).data('cmd');

				if(cmd == 'textfield'){

					self.popupMenuShow(cmd);

				}else if(cmd == 'todaydate'){

					self.insertContent('[esigtodaydate]');

				}else if(cmd == 'radio'){

					self.popupMenuShow(cmd);
					
				}else if(cmd == 'checkbox'){

					self.popupMenuShow(cmd);
					
				}
			});
			this.initialized = true;
		},

		// Show the main menu attached to element
		// mode = 'mce' or 'quicktag'
	    mainMenuShow: function (mode, element) {
			this.mode = (mode == 'mce')? 'mce':'quicktag';
			$('.'+this.menu_class, element).show();
	    },

		// Shows the pop-up modal window
		popupMenuShow: function(cmd) {
				
			var width = jQuery(window).width();
			
		if(mysifAjax.document_id) {
		       
			jQuery.ajax({  
        type:"POST",  
        url: mysifAjax.ajaxurl+"?action=signerdefine",   
        data: {
			esig_sif_document_id:mysifAjax.document_id,
			sif_signer:mysifAjax.sif_signer,
		},  
        success:function(data, status, jqXHR){  
			
			//if ($("#signer_display").length == 0){
			if(cmd == 'textfield'){
             $("#sif_text_advanced_button").show();
			jQuery(".sif_text_signer_info").html(data);
			}
			else if (cmd == 'radio')
			{
            $("#sif_radio_advanced_button").show();
			jQuery(".sif_radio_signer_info").html(data);
			}
			else if (cmd == 'checkbox')
			{
			jQuery(".sif_checkbox_signer_info").html(data);
			}
            else if (cmd == 'datepicker')
			{
                      jQuery(".sif_popup_main_datepicker").append(data);
			}
            
			//}
        },  
        error: function(xhr, status, error){  
            alert(xhr.responseText); 
        }  
    });  
	
	}
			
			if(cmd == 'textfield'){
			var H ='250';
			var W ='520';
			}
			else if (cmd == 'radio')
			{
			var H ='100%';
			var W ='520';
			}
			else if (cmd == 'checkbox')
			{
			var H ='100%';
			var W ='520';
			}
            else if (cmd == 'datepicker')
			{
			var H ='300';
			var W ='520';
			}
            
			$('.esig-sif-main-panels .panel').hide();
			$('.esig-sif-panel-'+cmd).show();
			
			tb_show( '+ Signer input fields', '#TB_inline?width='+ W +'&height='+ H +'&inlineId=esig-sif-admin-panel');
			
		},

		// Inserts content into the post canvas
		insertContent: function(content){
			// Visual mode
			if(this.mode == 'mce'){
				this.editor.execCommand('mceInsertContent', 0, content);
				
			// Quicktag
			} else {
				this.quicktag.tagStart = content;
				QTags.TagButton.prototype.callback.call(this.quicktag, this.element, this.canvas, this.editor);
				
			}
		},
		
		// Settings required for quicktag
		initQuicktag: function(quicktag, element, canvas){
			this.quicktag = quicktag;
			this.element = element;
			this.canvas = canvas;
		}
	}
	

}(jQuery));


function esig_sif_quicktag(element, canvas, editor)
{
	
	if(!esig_sif_admin_controls.initialized){
		esig_sif_admin_controls.mainMenuInit(editor);
	}
	if(!esig_sif_admin_controls.quicktag){
		esig_sif_admin_controls.initQuicktag(this, element, canvas);
	}
	esig_sif_admin_controls.mainMenuShow('quicktag', jQuery('#qt_document_content_esig_1_wrap'));
	
} 
