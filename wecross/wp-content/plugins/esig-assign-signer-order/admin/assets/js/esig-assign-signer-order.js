(function ($) {


    // default is disable 

    $("#addRecipient_view").on("click", function (e) {
        $('#esign-signer-order-link').fadeIn(1600, "linear");

    });

    $("#addRecipient").on("click", function (e) {

        $('#esign-signer-order-link').fadeIn(1600, "linear");

    });
    // Show or hide signer order 
    $('input[name="esign-assign-signer-order"]').on('change', function () {

        if ($('input[name="esign-assign-signer-order"]').attr('checked')) {

            $.fn.signer_order_checked();
        } else {
            $.fn.signer_order_unchecked();
        }

    });

    $('input[name="esign-assign-signer-order-view"]').on('change', function () {

        if ($('input[name="esign-assign-signer-order-view"]').attr('checked')) {
            $.fn.signer_order_checked_view();
        } else {
            $.fn.signer_order_unchecked_view();
        }

    });

    $('input[name="esign-assign-signer-order-ajax"]').on('change', function () {
       
        if ($('input[name="esign-assign-signer-order-ajax"]').attr('checked')) {
            $.fn.signer_order_checked_view();
        } else {
            $.fn.signer_order_unchecked_view();
        }

    });


    // signer order checked funciton 
    $.fn.signer_order_checked = function () {

        var fname = $("input[name='recipient_fnames\\[\\]']").map(function () { return $(this).val(); });

        var signer_name = $("input[name='recipient_emails\\[\\]']").map(function () { return $(this).val(); });

        var html = '';

        for (i = 0; i < fname.length; i++) {

            var j = i + 1;
            html += '<div id="signer_main">' +
                       '<span id="signer-sl" class="signer-sl">' + j + '.</span><span class="field_arrows"><span id="esig_signer_up"  class="up"> &nbsp; </span><span id="esig_signer_down"  class="down"> &nbsp; </span></span>' +
					    '<input type="text" name="recipient_fnames_ajax[]" placeholder="Signers Name" value="' + fname[i] + '" readonly />' +
					    '<input type="text" name="recipient_emails_ajax[]" placeholder="Signer Email"  value="' + signer_name[i] + '" readonly /><a href="#" id="standard_view">Edit</a></div>';

        }

        $('#recipient_emails_ajax').html(html);

    }
    // for view 
    $.fn.signer_order_checked_view = function () {

        var fname = $("#recipient_emails input[name='recipient_fnames\\[\\]']").map(function () { return $(this).val(); });

        var signer_name = $("#recipient_emails input[name='recipient_emails\\[\\]']").map(function () { return $(this).val(); });

        var html = '';
        var delicon = '';

        for (i = 0; i < fname.length; i++) {
            var j = i + 1;

            if (j == '1') {
                delicon = '';
            } else {
                delicon = '<span id="esig-del-signer" class="deleteIcon"></span>';
            }

            html += '<div id="signer_main">' +
                       '<span id="signer-sl" class="signer-sl">' + j + '.</span><span class="field_arrows"><span id="esig_signer_up"  class="up"> &nbsp; </span><span id="esig_signer_down"  class="down"> &nbsp; </span></span>' +
					    '<input type="text"  name="recipient_fnames[]" placeholder="Signers Name" value="' + fname[i] + '" />' +
					    '<input type="text" name="recipient_emails[]" placeholder="Signer Email" style="width:200px;"  value="' + signer_name[i] + '" />' + delicon + '</div>';


        }

        $('#recipient_emails').html(html);

    }


    // signer order unchecked funciton 
    $.fn.signer_order_unchecked = function () {

        var fname = $("input[name='recipient_fnames_ajax\\[\\]']").map(function () { return $(this).val(); });

        var signer_name = $("input[name='recipient_emails_ajax\\[\\]']").map(function () { return $(this).val(); });

        var html = '';

        for (i = 0; i < fname.length; i++) {
            var j = i + 1;

            html += '<div id="signer_main">' +
					    '<input type="text" name="recipient_fnames[]" placeholder="Signers Name" value="' + fname[i] + '" readonly />' +
					    '<input type="text" name="recipient_emails[]" placeholder="Signer Email"  value="' + signer_name[i] + '" readonly /></div>';

        }

        $('#recipient_emails_ajax').html(html);

    }

    $.fn.signer_order_unchecked_view = function () {

        var fname = $("#recipient_emails input[name='recipient_fnames\\[\\]']").map(function () { return $(this).val(); });

        var signer_name = $("#recipient_emails input[name='recipient_emails\\[\\]']").map(function () { return $(this).val(); });

        var html = '';

        for (i = 0; i < fname.length; i++) {
            var j = i + 1;
            if (j == '1') {
                delicon = '';
            } else {
                delicon = '<span class="deleteIcon"></span>';
            }
            html += '<div id="signer_main">' +
					    '<input type="text" name="recipient_fnames[]" placeholder="Signers Name" value="' + fname[i] + '"  />' +
					    '<input type="text" name="recipient_emails[]" placeholder="Signer Email" style="width:230px;"  value="' + signer_name[i] + '"  />' + delicon + '</div>';

        }

        $('#recipient_emails').html(html);

    }

    // when js load checking signer order checked or not if checked then show order
    if ($('input[name="esign-assign-signer-order"]').attr('checked')) {

        $.fn.signer_order_checked();
    }

    $('body').on('click', '#recipient_emails .deleteIcon', function () {

        // checking if signer only one then hide signer order checkbox 

        $(this).parent().remove();
        var fname = $("#recipient_emails input[name='recipient_fnames\\[\\]']").map(function () { return $(this).val(); });
        if (fname.length == 1) {

            $('#esign-signer-order-link').fadeOut(1600, "linear");
        }
        return false;
    });

    // esign signer order up down click event code here 
    $('body').on('click', '#esig_signer_up', function () {

        var current = $(this).parent().parent().find('#signer-sl').html();

        var upper = $(this).parent().parent().prev().find("#signer-sl:first").html();

        if (upper == undefined) {
            return;
        }

        // setting upper and current
        var parent = $(this).parent().parent();
        parent.animate({ top: '-20px' }, 500, function () {
            parent.prev().animate({ top: '20px' }, 500, function () {
                parent.css('top', '0px');
                parent.prev().css('top', '0px');
                parent.insertBefore(parent.prev());
            });
        });

        $(this).parent().parent().prev().find("#signer-sl:first").html(current);
        $(this).parent().parent().find('#signer-sl').html(upper);



    });

    $('body').on('click', '#esig_signer_down', function () {

        // getting current and down value 
        var current = $(this).parent().parent().find('#signer-sl').html();
        var down = $(this).parent().parent().next().find("#signer-sl:first").html();

        if (down == undefined) {
            return;
        }
        // setting current and next value 
        var parent = $(this).parent().parent();
        parent.animate({ top: '20px' }, 500, function () {
            parent.next().animate({ top: '-20px' }, 500, function () {
                parent.css('top', '0px');
                parent.next().css('top', '0px');
                parent.insertAfter(parent.next());
            });
        });
        $(this).parent().parent().find('#signer-sl').html(down);
        $(this).parent().parent().next().find("#signer-sl:first").html(current);


    });

    // changing add reciepent button in view page  . 
    $('#recipient_emails').bind("contentchange", function () {
        if ($('input[name="esign-assign-signer-order-view"]').attr('checked')) {
            $.fn.signer_order_checked_view();
        }
    });

})(jQuery);
