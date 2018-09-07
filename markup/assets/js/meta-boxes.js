;(function ( $, window ) {
    var app = {
        downloadable_file_frame :false,
        file_path_field         : false,
        init : function () {

            $(document)
                .on( 'init-datepicker', this.initDatepicker)
                .on( 'click', '.u-tabs li a', this.initTabs)
                .on( 'click', '.add_document_row, .add_table_row', this.addTableRow)
                .on( 'click', 'table.u-data-table .delete', this.deleteTableRow)
                .on( 'click', '.upload_file_button', this.showMediaPopup);

            $( 'div.panel-wrap' ).each( function() {
                $( this ).find( 'ul.u-tabs li' ).eq( 0 ).find( 'a' ).click();
            });

            $(document).trigger('init-datepicker');
        },
        initTabs : function( e ){
            e.preventDefault();
            var panel_wrap = $( this ).closest( 'div.panel-wrap' );
            $( 'ul.u-tabs li', panel_wrap ).removeClass( 'active' );
            $( this ).parent().addClass( 'active' );
            $( 'div.panel', panel_wrap ).hide();
            $( $( this ).attr( 'href' ) ).show();
        },
        initDatepicker : function ( e ) {
            $( '.u-init-date:not(.hasDatepicker)' ).datepicker({
                dateFormat  : "mm/dd/yy",
                changeYear  : true,
                changeMonth : true,
            });
        },
        addTableRow : function ( e ) {
            var tmpl_id = $(this).data('tmpl'),
                container_id = $(this).data('container'),
                tmpl  = $(tmpl_id).html(),
                table = $(container_id);
            $('tbody', table). append(tmpl);
            $(document).trigger('init-datepicker');
        },
        deleteTableRow : function ( e ) {
            e.preventDefault();
            $(this).closest('tr').remove();
        },
        showMediaPopup : function ( e ) {
            var $el = $( this );

            app.file_path_field = $el.closest( 'tr' ).find( 'td.column-file_url input, td.column-photo_url input' );

            event.preventDefault();

            // If the media frame already exists, reopen it.
            if ( app.downloadable_file_frame ) {
                app.downloadable_file_frame.open();
                return;
            }

            var downloadable_file_states = [
                // Main states.
                new wp.media.controller.Library({
                    library:   wp.media.query(),
                    multiple:  true,
                    title:     $el.data('choose'),
                    priority:  20,
                    filterable: 'uploaded'
                })
            ];

            // Create the media frame.
            app.downloadable_file_frame = wp.media.frames.downloadable_file = wp.media({
                // Set the title of the modal.
                title: $el.data('choose'),
                library: {
                    type: ''
                },
                button: {
                    text: $el.data('update')
                },
                multiple: true,
                states: downloadable_file_states
            });

            // When an image is selected, run a callback.
            app.downloadable_file_frame.on( 'select', function() {
                var file_path = '';
                var selection = app.downloadable_file_frame.state().get( 'selection' );

                selection.map( function( attachment ) {
                    attachment = attachment.toJSON();
                    if ( attachment.url ) {
                        file_path = attachment.url;
                    }
                });

                app.file_path_field.val( file_path ).change();
                var img = app.file_path_field.closest('tr').find('img');
                if(  img.length ){
                    img.attr('src', file_path).show();
                }
            });

            // Set post to 0 and set our custom type.
            app.downloadable_file_frame.on( 'ready', function() {
                app.downloadable_file_frame.uploader.options.uploader.params = {
                    type: 'downloadable_product'
                };
            });

            // Finally, open the modal.
            app.downloadable_file_frame.open();
        }

    };

    jQuery(function() {
        app.init();
    });
})( jQuery, window );
