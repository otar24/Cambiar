<?php
/**
 * Admin View: Product import form
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form class="utheme-progress-form-content utheme-importer" enctype="multipart/form-data" method="post">
	<header>
		<h2><?php esc_html_e( 'Import products from a CSV file', 'utheme' ); ?></h2>
		<p><?php esc_html_e( 'This tool allows you to import (or merge) product data from a CSV file.', 'utheme' ); ?></p>
	</header>
	<section>
		<table class="form-table utheme-importer-options">
			<tbody>
				<tr>
					<th scope="row">
						<label for="upload">
							<?php _e( 'Choose a CSV file from your computer:', 'utheme' ); ?>
						</label>
					</th>
					<td>
						<?php
						if ( ! empty( $upload_dir['error'] ) ) {
							?><div class="inline error">
								<p><?php esc_html_e( 'Before you can upload your import file, you will need to fix the following error:', 'utheme' ); ?></p>
								<p><strong><?php echo esc_html( $upload_dir['error'] ); ?></strong></p>
							</div><?php
						} else {
							?>
							<input type="file" id="upload" name="import" size="25" />
							<input type="hidden" name="action" value="save" />
							<input type="hidden" name="max_file_size" value="<?php echo esc_attr( $bytes ); ?>" />
							<br><small><?php
								/* translators: %s: maximum upload size */
								printf(
									__( 'Maximum size: %s', 'utheme' ),
									$size
								);
							?></small>
							<?php
						}
					?>
					</td>
				</tr>
				<tr>
					<th><label for="utheme-importer-update-existing"><?php _e( 'Update existing products', 'utheme' ); ?></label><br/></th>
					<td>
						<input type="hidden" name="update_existing" value="0" />
						<input type="checkbox" id="utheme-importer-update-existing" name="update_existing" value="1" />
						<label for="utheme-importer-update-existing"><?php esc_html_e( 'If a product being imported matches an existing product by Fund Name and Ticker, update the existing product rather than creating a new product or skipping the row.', 'utheme' ); ?></label>
					</td>
				</tr>
				<tr class="utheme-importer-advanced hidden">
					<th><label><?php _e( 'CSV Delimiter', 'utheme' ); ?></label><br/></th>
					<td><input type="text" name="delimiter" placeholder="," size="2" /></td>
				</tr>
			</tbody>
		</table>
	</section>
	<script type="text/javascript">
		jQuery(function() {
			jQuery( '.utheme-importer-toggle-advanced-options' ).on( 'click', function() {
				var elements = jQuery( '.utheme-importer-advanced' );
				if ( elements.is( '.hidden' ) ) {
					elements.removeClass( 'hidden' );
					jQuery( this ).text( jQuery( this ).data( 'hidetext' ) );
				} else {
					elements.addClass( 'hidden' );
					jQuery( this ).text( jQuery( this ).data( 'showtext' ) );
				}
				return false;
			} );
		});
	</script>
	<div class="utheme-actions">
		<a href="#" class="utheme-importer-toggle-advanced-options" data-hidetext="<?php esc_html_e( 'Hide advanced options', 'utheme' ); ?>" data-showtext="<?php esc_html_e( 'Hide advanced options', 'utheme' ); ?>"><?php esc_html_e( 'Show advanced options', 'utheme' ); ?></a>
		<input type="submit" class="button button-primary button-next" value="<?php esc_attr_e( 'Continue', 'utheme' ); ?>" name="save_step" />
		<?php wp_nonce_field( 'utheme-csv-importer' ); ?>
	</div>
</form>
