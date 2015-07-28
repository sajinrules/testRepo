

(function($){

    
	// Show or hide the stand alone console when the box is checked.
    $('input[name="esig_custom_message"]').on('change', function () {
        if ($('input[name="esig_custom_message"]').attr('checked')) {
            $('#esig-custom-message-input').show();
			} else {
            $('#esig-custom-message-input').hide();
			}
		});


    if ($('input[name="esig_custom_message"]').attr('checked')) {
        $('#esig-custom-message-input').show();
    } else {
        $('#esig-custom-message-input').hide();
    }
	
		
	
	
		
})(jQuery);
