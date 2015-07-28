
jQuery(document).ready(function($) {

	var stop = 'off';

	var lead_lists = _inbound.Utils.readCookie( 'wp_lead_list' );
	
	if (lead_lists) {
		lead_lists =  JSON.parse( unescape(lead_lists) ) ;
	}
    
	
    if (typeof (lead_lists) != "undefined" && lead_lists != null && lead_lists != "") {	
	
		console.log('Behavior Targeting Enabled.');

		var list_array = lead_lists.ids; // the lists the lead belongs to
		
		console.log('Behavioral: Visitor belongs to these list ids:' + lead_lists.ids);
		
		/* setup matches array */
		var matches = [];
		
		/* loop through variations and find behavioral matches */
		jQuery('.wp_cta_container .wp_cta_variation').each(function()	{	
			
			if (!jQuery(this).hasClass('is_behavioral')){
				return;
			}					
				
			var cta_id = jQuery(this).data('cta-id');
			var vid = jQuery(this).data('vid');
			var lists = jQuery(this).data('lists');
			
			if (typeof lists == 'undefined') {
				return true;
			}

			lists = lists.toString().split(',');

			lists.forEach(function(list_id){ 

				var list_id = parseInt(list_id);
				var in_array = list_array.indexOf(list_id);
				
				if (in_array > -1) {
					console.log("Behavioral: It's a match val: customer belongs to list id " + list_id );
					matches[cta_id] = [];
					matches[cta_id].push(vid);
				} 
			});
			
		});	
	
		/* load ctas */
		var loaded_ctas = _inbound.totalStorage('wp_cta_loaded');
	
		if ( !loaded_ctas ) {			
			var loaded_ctas = {};
		}
		
		if ( matches.length > 0 ) {
			
			/* loop through matches and pick randomize variation */
			for( var cta_id in matches ) {
				/* randomly select variation for cta */
				var vid = matches[cta_id][Math.floor(Math.random() * matches[cta_id].length)];
				loaded_ctas[cta_id] = vid;
			}			
		}
		
		/* load variation */
		localStorage.setItem( 'wp_cta_loaded' , JSON.stringify(loaded_ctas));
		console.log( 'WP CTA Load Object Updated:' + JSON.stringify(loaded_ctas) );
		
    }

});