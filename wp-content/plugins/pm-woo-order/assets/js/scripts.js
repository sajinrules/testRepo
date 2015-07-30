(function($) {
    var product_project = {
        init: function() {
            this.AutoComplete();
            $('.cpmw-magic-project').on( 'click', '.cpmw-add-more', this.addMore );
            $('.cpmw-magic-project').on( 'change', '.cpmw-form-fields .cpmw-type', this.typeHandel );
            $('.cpmw-magic-project').on( 'click', '.cpmw-del-proj-role', this.deleteRole );
            $('.cpmw-magic-project').on( 'submit', '#cpmw-product-project', this.productProjectAdd );
            $('.cpmw-magic-project').on( 'click', '.cpmw-delete-li', this.removeLi );
            
        },

        removeLi: function() {
            var self = $(this);
            self.closest('.cpmw-clone-area').remove();
        },

        productProjectAdd: function(e) {
            e.preventDefault();
            var self = $(this);
            data = self.serialize();
            self.find('.cpmw-spinner').show();
            self.find( 'input[type=submit]' ).attr( 'disabled', true );
            $.post( product.ajaxurl, data, function(res) {
                self.find('.cpmw-spinner').hide();
                self.find( 'input[type=submit]' ).attr( 'disabled', false );

                if(res.success) {
                    self.closest('.cpmw-magic-project').find('.cpmw-error').addClass('updated').html('<p><strong>'+res.data+'</strong></p>');
                } else {
                    self.closest('.cpmw-magic-project').find('.cpmw-error').addClass('error updated').html('<p><strong>'+res.data+'</strong></p>');
                }
            });
        },

        deleteRole: function() {
            var self = $(this);
            self.parents('tr').remove();
        },

        typeHandel: function() {

            var self = $(this),
                value = self.val(),
                li = self.closest('.cpmw-clone-area'),
                project = li.find('.cpmw-project-fields');
            if( value === 'create' ) {
                li.find('.cpmw-project-fields-wrap').html('<input type="hidden" name=project_id[] value="null" >');
                li.find('.cpmw-role-wrap').show();
            } else {

                if( project.length == 0) {

                    var clone_project = $('.cpmw-form-clone-wrap').find('.cpmw-project-fields').clone(true);
                    li.find('.cpmw-role-wrap').hide();
                    li.find('.cpmw-project-fields-wrap').html(clone_project);

                }
            }
        },

        addMore: function(e) {
            e.preventDefault();
            var self = $(this);
                form = self.closest('form')
                clone = $('.cpmw-form-clone-wrap').find('.cpmw-clone-area').clone(true);
            form.find('.cpmw-form-fields').append( clone );
            $('.cpmw-magic-project').on( 'change', '.cpmw-form-fields .cpmw-type', product_project.typeHandel);
            product_project.AutoComplete();

        },

        AutoComplete: function() {
            $( ".cpmw-magic-project .cpmw-project-coworker" ).autocomplete({

            minLength: 3,
            source: function( request, response) {
                var self = $(this.element),
                    li = self.closest('.cpmw-clone-area'),
                    count_row = self.closest('.cpmw-form-fields').find('.cpmw-clone-area').index(li);

                var data = {
                    action: 'user_autocomplete',
                    term: request.term,
                    count_row: count_row,
                };

                $.post( product.ajaxurl, data, function( resp ) {

                    if( resp.success ) {
                        var nme = eval( resp.data );
                        
                        response( eval( resp.data ) );
                    } else {
                        response( '' );
                    }

                });
            },

            search: function() {
                $(this).addClass('cpm-spinner');
            },

            open: function(){
                var self = $(this);
                self.autocomplete('widget').css('z-index', 9999);
                self.removeClass('cpm-spinner');

                return false;
            },

            select: function( event, ui ) {

                var self = $(this);
                self.closest('.cpmw-clone-area').find('.cpmw-project-role table').append( ui.item._user_meta );
                $( "input.cpmw-project-coworker" ).val('');

                return false;
            }
            
            }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
                return $( "<li>" )
                        .append( "<a>" + item.label + "</a>" )
                        .appendTo( ul );
            };
        }
    }
    product_project.init();
    
})(jQuery);