<?php
/**
 * Admin View: Importer - Done!
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="utheme-progress-form-content utheme-importer">
	<section class="utheme-importer-done">
		<?php
			$results   = array();

			if ( 0 < $imported ) {
				$results[] = sprintf(
					/* translators: %d: products count */
					_n( '%s product imported', '%s products imported', $imported, 'utheme' ),
					'<strong>' . number_format_i18n( $imported ) . '</strong>'
				);
			}

			if ( 0 < $updated ) {
				$results[] = sprintf(
					/* translators: %d: products count */
					_n( '%s product updated', '%s products updated', $updated, 'utheme' ),
					'<strong>' . number_format_i18n( $updated ) . '</strong>'
				);
			}

			if ( 0 < $skipped ) {
				$results[] = sprintf(
					/* translators: %d: products count */
					_n( '%s product was skipped', '%s products were skipped', $skipped, 'utheme' ),
					'<strong>' . number_format_i18n( $skipped ) . '</strong>'
				);
			}

			if ( 0 < $failed ) {
				$results [] = sprintf(
					/* translators: %d: products count */
					_n( 'Failed to import %s product', 'Failed to import %s products', $failed, 'utheme' ),
					'<strong>' . number_format_i18n( $failed ) . '</strong>'
				);
			}

			if ( 0 < $failed || 0 < $skipped ) {
				$results[] = '<a href="#" class="utheme-importer-done-view-errors">' . __( 'View import log', 'utheme' ) . '</a>';
			}

			/* translators: %d: import results */
			echo wp_kses_post( __( 'Import complete!', 'utheme' ) . ' ' . implode( '. ', $results ) );
		?>
	</section>
	<section class="utheme-importer-error-log" style="display:none">
		<table class="widefat utheme-importer-error-log-table">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Product', 'utheme' ); ?></th>
					<th><?php esc_html_e( 'Reason for failure', 'utheme' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
					if ( count( $errors ) ) {
						foreach ( $errors as $error ) {
							if ( ! is_wp_error( $error ) ) {
								continue;
							}
							$error_data = $error->get_error_data();
							?>
							<tr>
								<th><code><?php echo esc_html( $error_data['row'] ); ?></code></th>
								<td><?php echo esc_html( $error->get_error_message() ); ?></td>
							</tr>
							<?php
						}
					}
				?>
			</tbody>
		</table>
	</section>
	<script type="text/javascript">
		jQuery(function() {
			jQuery( '.utheme-importer-done-view-errors' ).on( 'click', function() {
				jQuery( '.utheme-importer-error-log' ).slideToggle();
				return false;
			} );
		} );
	</script>
	<div class="utheme-actions">
		<a class="button button-primary" href="<?php echo esc_url( admin_url( 'edit.php?post_type=strategy' ) ); ?>"><?php esc_html_e( 'View Strategies', 'utheme' ); ?></a>
	</div>
</div>
