;(function ( $, window ) {
    var app = {
        downloadable_file_frame :false,
        file_path_field         : false,
        init : function () {
            $('#attributes, #top_countries').dragtable({
              dragaccept: '.accept',
              dragHandle: '.drag-handle'
            });

            $(document)
                .on( 'init-datepicker', this.initDatepicker)
                .on( 'click', '.u-tabs li a', this.initTabs)
                .on( 'click', '.add_document_row, .add_table_row', this.addTableRow)
                .on( 'click', '.add-table-column', this.addTableColumn)
                .on( 'click', 'table.u-data-table .delete', this.deleteTableRow)
                .on( 'click', '.delete-table-column', this.deleteTableColumn)
                .on( 'click', '.upload_file_button', this.showMediaPopup)
                .on( 'change', '.related-strategy-data', this.initRelatedStrategyData);

            $( 'div.panel-wrap' ).each( function() {
                $( this ).find( 'ul.u-tabs li' ).eq( 0 ).find( 'a' ).click();
            });

            $(document).trigger('init-datepicker');
            $('.related-strategy-data').trigger('change');
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
        initRelatedStrategyData: function(e){
            var $select = $(e.target),
                $holder = $($select.data('holder'));
  
            $holder.css('display', 'none');
            if ('' == $select.val()){
                $holder.removeAttr('style');
            }
        },
        addTableRow : function ( e ) {
            var $this = $(this),
                rowTemplate = $($this.data('tmpl')),
                table = $($this.data('container')),
                tr = table.find('tbody tr').last().clone();
            
            if (rowTemplate.length) {
              tr = rowTemplate.html().replace(/\[\d+\]\[(.*)\]/g, '[' + (table.find('tbody tr').length + 1) + '][$1]' );
            }else{
              tr.find('input').each(function(){
                var $input = $(this);
    
                $input.attr('name', $input.attr('name').replace(/\[\d+\]\[(.*)\]/g, '[' + (table.find('tbody tr').length + 1) + '][$1]' ));
                $input.val('');
              });
            }
            
            $('tbody', table). append(tr);
            $(document).trigger('init-datepicker');
        },
        addTableColumn: function(e) {
            e.preventDefault(e)
          
            var $this = $(this),
                $table = $($this.data('container')),
                $th = $table.find('thead th').not('.action').last().clone(),
                $tr = $table.find('tbody tr');
            
            if ($th.length){
                $th.find('input').each(function(){
                   var $input = $(this);
                   
                   $input.removeAttr('readonly');
                   $input.val('');
                });
  
                $th.insertBefore('th.action', $table);
            }
            
            if ($tr.length){
                $tr.each(function(){
                    var $this = $(this),
                        $td = $this.find('td').not('.action').last().clone();
                        console.log($this);
                      $td.find('input').each(function(){
                        var $input = $(this);
        
                        $input.attr('type', 'number');
                        $input.attr('step', 'any');
                        $input.val('');
                      });
  
                      $td.insertBefore($this.find('td.action'));
                });
            }
        },
        deleteTableRow : function ( e ) {
            e.preventDefault();
            $(this).closest('tr').remove();
        },
        deleteTableColumn : function ( e ) {
            e.preventDefault();
            
            var $this = $(this),
                $table = $($this.data('container')),
                $th = $this.parent('th'),
                $tr = $table.find('tbody tr'),
                columnIndex = $th.index();
            
            $th.remove();
            
            if($tr.length){
                $tr.each(function(){
                  $(this).find('td:eq(' + columnIndex + ')').remove();
                });
            }
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
