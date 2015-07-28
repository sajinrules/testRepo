(function ($) {

    // defining popup method


    $.fn.esig_popup_open = function () {
        window.scrollTo(0, 0);
        $("#esig-mobile-popup").css('display', 'block');
        $("#mobile-type-signature").hide();
    }

    $(".sig-container").click(function () {

        var signature_added = $('input[name="output"]').val();
        if (signature_added) {
            return;
        }
        if ($("#mobile-sigpad").hasClass("esig-box-shadow")) {
            $("#mobile-sigpad").removeClass("esig-box-shadow");
        }
        else {
            $("#mobile-sigpad").addClass("esig-box-shadow");
        }

    });

    $("#mobile-sig").click(function () {
    	
        $("#esig_mobile_drop_down").show();
        
        if (!$("#esig-type-style").hasClass("esig-type-style")) {
            $("#esig-draw-style").addClass("esig-type-style");
        }
        if ($("#mobile-sigpad").hasClass("esig-box-shadow")) {
            $("#mobile-sigpad").removeClass("esig-box-shadow");
        }
    });

    $(".sig-middle").on("tap", function () {
        $("#esig_mobile_drop_down").hide();
    });

    // when some tab in draw section next step button enabled
    $("#mobile-sigpad").on("vmousedown", function () {
        $("#mobile-next-step").removeClass("disabled");
        //Once signature has been added we need to fade out/add opacity to the text "Draw Your Signature" 
        $('.signature-description').fadeTo("slow", 0.2);

    });

    $(".clearButton").click(function () {
        $("#mobile-next-step").addClass("disabled");
        $('.signature-description').fadeTo("slow", 1);
    });


    $("#mobile-type-sig").click(function () {
        $("#mobile-draw-signature").hide();
        $("#mobile-type-signature").show();
        $("#esig_mobile_drop_down").hide();
        $("#mobile-draw-sig").removeClass("active");
        $("#mobile-type-sig").addClass("active");

        $("#mobile-next-step").removeClass("disabled");
        // giving type border
        $("#esig-draw-style").removeClass("esig-type-style").addClass("esig-type-style-inactive");
        $("#esig-type-style").addClass("esig-type-style").removeClass("esig-type-style-inactive");

    });

    $("#mobile-draw-sig").click(function () {
        $("#mobile-draw-signature").show();
        $("#mobile-type-signature").hide();
        $("#esig_mobile_drop_down").hide();
       
        $("#mobile-type-sig").removeClass("active");
        $("#mobile-draw-sig").addClass("active");
        // giving type border
        $("#esig-type-style").removeClass("esig-type-style").addClass("esig-type-style-inactive");
        if (!$("#esig-draw-style").hasClass("esig-type-style")) {
            $("#esig-draw-style").addClass("esig-type-style").removeClass("esig-type-style-inactive");
        }
        
        var signature_added = $('input[name="output"]').val();
        if (signature_added) {
             if ($("#mobile-next-step").hasClass("disabled")) 
             {
            		$("#mobile-next-step").removeClass("disabled");
        	 }
        }
        else 
        {
			 $("#mobile-next-step").addClass("disabled");
		}

    });


    $("#mobile-next-step").click(function () {

        if ($("#mobile-next-step").hasClass("disabled")) {
            return;
        }
        // auto filling mobile agreement name 
        var fname = $('input[name="recipient_first_name"]').val();
        $('#esig-auto-fill-name').html(fname);

        $(".sig-header").hide();
        $(".sig-header-next-page").show();
        $(".signature-description-nextpage").show();
        // hiding description of draw type
        $(".signature-description").hide();
        // hiding signature text type field
        $(".signature-type-input").hide();
        $(".esig-change-font").hide();
        // hiding clear button 
        $(".clearButton").hide();
        // showing draw section 
        $("#mobile-type-signature").show();
        // showing type section 
        // if signature not added hide signature pad
        //var signature_added = $('input[name="output"]').val();
        
        if ($("#esig-type-style").hasClass("esig-type-style")) 
        {
        	 $("#esig-type-in-preview").show();
        	$("#mobile-draw-signature").hide();
            //$("#esig-type-in-preview").addClass("esig-type-bg");
            
        }
        else 
        {
			 $("#mobile-draw-signature").show();
            $("#esig-type-in-preview").hide();
		}
       


        if ($("#mobile-sigpad").hasClass("esig-box-shadow")) {
            $("#mobile-sigpad").removeClass("esig-box-shadow");
        }
        // when only type selected display name as blue color

        $("#esig-mobile-type-selection").show();
        var typesignature = $('input[name="esignature_in_text"]').val();

        var htmlcontent = '<span class="esig-big-desc">' + typesignature + '</span>';
        	
        $("#esig-mobile-type-selection").html(htmlcontent);
        

    });

    $("#mobile-go-back").click(function () {
        $(".sig-header").show();
        $(".sig-header-next-page").hide();
        $(".signature-description-nextpage").hide();
        $(".sig-header").show();
        // hiding description of draw type
        $(".signature-description").show();

        $(".esig-change-font").show();
        // hiding signature text type field
        $(".signature-type-input").show();
        // showing draw section 
        $("#mobile-type-signature").hide();
        $("#esig-type-in-preview").show();
        // showing clear button 
        $(".clearButton").show();
        // showing type section 
        $("#mobile-draw-signature").show();
        // only type selection display 
        $("#esig-mobile-type-selection").hide();
        
    });




    var w = $(window).width();
    var h = $(window).height();

    // $("#esig-mobile-popup").attr("width", w);
    // $("#esig-mobile-popup").attr("width", h);

    var canvaswidth = (w / 4) * 3;
    $("#signatureCanvas").attr("width", canvaswidth);


    // when terms of use hide trigger
    $(document).on('hide.bs.modal', '.esig-terms-modal-lg', function () {
        $("#esig-mobile-popup").popup("open");
    });


})(jQuery);
