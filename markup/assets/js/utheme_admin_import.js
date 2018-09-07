/*global ajaxurl, utheme_import_params */
;(function ( $, window ) {

	/**
	 * uthemeImportForm handles the import process.
	 */
	var uthemeImportForm = function( $form ) {
		this.$form           = $form;
		this.xhr             = false;
		this.mapping         = utheme_import_params.mapping;
		this.position        = 0;
		this.file            = utheme_import_params.file;
		this.update_existing = utheme_import_params.update_existing;
		this.delimiter       = utheme_import_params.delimiter;
		this.security        = utheme_import_params.import_nonce;

		// Number of import successes/failures.
		this.imported = 0;
		this.failed   = 0;
		this.updated  = 0;
		this.skipped  = 0;

		this.maxitems  = 16;

		// Initial state.
		this.$form.find('.utheme-importer-progress').val( 0 );

		/*this.run_import = this.run_import.bind( this );

		// Start importing.
		this.run_import();*/

        this.test_import = this.test_import.bind( this );
        this.test_import();
	};

    uthemeImportForm.prototype.test_import = function() {
        var $this = this;
        var percentage = 0;
        $this.imported +=1;
        percentage = $this.imported / $this.maxitems * 100;

        $this.$form.find('.utheme-importer-progress').val( percentage );

        console.log($this.imported);
        console.log(percentage);
        if ( percentage === 100 ) {
            window.location = utheme_import_params.redirect + '&products-imported=' + parseInt( $this.imported, 10 ) + '&products-failed=' + parseInt( $this.failed, 10 ) + '&products-updated=' + parseInt( $this.updated, 10 ) + '&products-skipped=' + parseInt( $this.skipped, 10 );
        } else {
            setTimeout(function () {
                $this.test_import();
            }, 1500);
        }

	}
	
	/**
	 * Run the import in batches until finished.
	 */
	uthemeImportForm.prototype.run_import = function() {
		var $this = this;

		$.ajax( {
			type: 'POST',
			url: ajaxurl,
			data: {
				action          : 'utheme_product_import',
				position        : $this.position,
				mapping         : $this.mapping,
				file            : $this.file,
				update_existing : $this.update_existing,
				delimiter       : $this.delimiter,
				security        : $this.security
			},
			dataType: 'json',
			success: function( response ) {
				if ( response.success ) {
					$this.position  = response.data.position;
					$this.imported += response.data.imported;
					$this.failed   += response.data.failed;
					$this.updated  += response.data.updated;
					$this.skipped  += response.data.skipped;
					$this.$form.find('.utheme-importer-progress').val( response.data.percentage );

					if ( 'done' === response.data.position ) {
						window.location = response.data.url + '&products-imported=' + parseInt( $this.imported, 10 ) + '&products-failed=' + parseInt( $this.failed, 10 ) + '&products-updated=' + parseInt( $this.updated, 10 ) + '&products-skipped=' + parseInt( $this.skipped, 10 );
					} else {
						$this.run_import();
					}
				}
			}
		} ).fail( function( response ) {
			window.console.log( response );
		} );
	};

	/**
	 * Function to call uthemeImportForm on jQuery selector.
	 */
	$.fn.utheme_importer = function() {
		new uthemeImportForm( this );
		return this;
	};

	console.log('dfsdfsdf');
	$( '.utheme-importer' ).utheme_importer();

})( jQuery, window );
