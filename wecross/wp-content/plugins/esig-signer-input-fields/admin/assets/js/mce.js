(function ($) {
	
    //"use strict";
	
    //$(function () {

        tinymce.PluginManager.add('esig_sif', function (editor, url) {
            editor.addButton('esig_sif', {
                title: 'Add a signer input field',
                type: 'menubutton',
                icon: 'icon esig-icon',
                menu: [
                {
                    text: 'Insert Textbox',
                    value: 'textfield',
                    onclick: function () {
                        esig_sif_admin_controls.popupMenuShow(this.value());
                    }
                },
                {
                    text: 'Insert Date Calendar',
                    value: 'datepicker',
                    onclick: function () {
            
                        if (mysifAjax.invite_count > 1) {
                            esig_sif_admin_controls.popupMenuShow(this.value());
                        } else {
                            var name = 'esig-sif-picker-' + Date.now();
                            editor.insertContent('[esigdatepicker name="' + name + '"]');
                        }
                    }
                },
                {
                    text: 'Insert Signed Date',
                    value: 'todaydate',
                    onclick: function () {
                        editor.insertContent('[esigtodaydate]');
                    }
                },
                {
                    text: 'Insert Radio Buttons',
                    value: 'radio',
                    onclick: function () {
                        esig_sif_admin_controls.popupMenuShow(this.value());
                    }
                },
				{
				    text: 'Insert Checkboxes',
				    value: 'checkbox',
				    onclick: function () {
				        esig_sif_admin_controls.popupMenuShow(this.value());
				    }
				}
           ]
            });
            editor.onLoadContent.add(function (editor, o) {
                esig_sif_admin_controls.mainMenuInit(editor);
            });
        });

        tinymce.init({
            plugins: "advlist"
        });

   // });
  
} (jQuery));