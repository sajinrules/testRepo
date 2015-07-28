var InboundGoogleCalendars = ( function() {

    var input;

    var construct = {
        /**
         *  Initialize JS Class
         */
        init: function() {
            this.addListeners();
            this.updateCalendar();
        },
        /**
         *  Add UI Listeners
         */
        addListeners: function() {
            InboundGoogleCalendars.addCalandarSelectionListeners();
            InboundGoogleCalendars.addNewEventRevealListeners();
        },
        /**
         *  Add oauth workflow listeners
         */
        addCalandarSelectionListeners: function() {

            /* add listeners for 'add new custom fields  */
            jQuery( 'body' ).on( 'change' , '#google-calendars' , function() {
                /* set static var */
                InboundGoogleCalendars.input = jQuery( this );
                InboundGoogleCalendars.saveSelection();
                InboundGoogleCalendars.updateCalendar();
            });
        },
        /**
         *  Add oauth workflow listeners
         */
        addNewEventRevealListeners: function() {

            /* add listeners for 'add new custom fields  */
            jQuery( 'body' ).on( 'click' , '#add_new_event' , function() {
                /* set static var */
                InboundGoogleCalendars.input = jQuery( this );
                InboundGoogleCalendars.createEvent();
            });
        },
        /**
         *  Save Input Data
         */
        saveSelection: function() {
            jQuery.ajax({
                type: "POST",
                url: ajaxurl ,
                data: {
                    action: 'inbound_save_calendar_selection',
                    id: InboundGoogleCalendars.input.val()
                },
                dataType: 'html',
                timeout: 10000,
                success: function (response) {

                },
                error: function(request, status, err) {
                    alert(status);
                }
            });
        },
        /**
         *  Save Input Data
         */
        addEvent: function() {
            jQuery.ajax({
                type: "POST",
                url: ajaxurl ,
                data: {
                    action: 'inbound_save_calendar_selection',
                    id: InboundGoogleCalendars.input.val()
                },
                dataType: 'html',
                timeout: 10000,
                success: function (response) {

                },
                error: function(request, status, err) {
                    alert(status);
                }
            });
        },
        /**
         *
         */
        updateCalendar: function() {
            var id = jQuery('#google-calendars').val();


            if (jQuery('#google-calendars').length < 1 ) {
                return;
            }

            /* get calendar */
            jQuery.ajax({
                type: "POST",
                url: ajaxurl ,
                data: {
                    action: 'inbound_get_calendar',
                    id: id
                },
                dataType: 'json',
                timeout: 10000,
                success: function (response) {
                    jQuery('#calendar-display').attr('src', InboundGoogleCalendars.buildCalendarURL(response));

                },
                error: function(request, status, err) {
                    alert('here');
                    alert(status);
                }
            });
        },
        /**
         * builds a calendar URL from a json response
         */
        buildCalendarURL: function( json ) {
            return 'https://www.google.com/calendar/embed?showPrint=0&showCalendars=0&showTitle=0&mode=MONTH&src=' + json.id + '&ctz' + json.timezon;
        },
        createEvent: function() {
            swal({
                title: "Are you sure?",
                text: "Are you sure you want to create this event?",
                type: "info",
                showCancelButton: true,
                confirmButtonColor: "#0091cd",
                confirmButtonText: "Yes, create event!",
                closeOnConfirm: false
                },
                function(){
                    /* get calendar */
                    jQuery.ajax({
                        type: "POST",
                        url: ajaxurl ,
                        data: {
                            action: 'inbound_create_calendar_event',
                            query: jQuery('#event-query').val(),
                            notify: jQuery('#notify_me').is(':checked'),
                            id: jQuery('#google-calendars').val()
                        },
                        dataType: 'html',
                        timeout: 10000,
                        success: function (response) {
                            InboundGoogleCalendars.updateCalendar();
                            swal(
                                "Created!",
                                "Your event has been created",
                                "success"
                            );
                        },
                        error: function(request, status, err) {
                            alert(status);
                        }
                    });

                }
            );
        }

    }


    return construct;

})();


/**
 *  Once dom has been loaded load listeners and initialize components
 */
jQuery(document).ready(function() {

    InboundGoogleCalendars.init();

});