<?php
/**
 * CSV importer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Include dependencies.
 */
/*if ( ! class_exists( 'WC_Product_Importer', false ) ) {
	include_once( dirname( __FILE__ ) . '/abstract-wc-product-importer.php' );
}*/

/**
 * U_Strategies_CSV_Importer Class.
 */
class U_Strategies_CSV_Importer {

	/**
	 * Initialize importer.
	 *
	 * @param string $file   File to read.
	 * @param array  $params Arguments for the parser.
	 */
	public function __construct( $file, $params = array() ) {
		$default_args = array(
			'start_pos'        => 0, // File pointer start.
			'end_pos'          => -1, // File pointer end.
			'lines'            => -1, // Max lines to read.
			'mapping'          => array(), // Column mapping. csv_heading => schema_heading.
			'parse'            => false, // Whether to sanitize and format data.
			'update_existing'  => false, // Whether to update existing items.
			'delimiter'        => ',', // CSV delimiter.
			'prevent_timeouts' => true, // Check memory and time usage and abort if reaching limit.
			'enclosure'        => '"', // The character used to wrap text in the CSV.
		);

		$this->params = wp_parse_args( $params, $default_args );
		$this->file   = $file;

		if ( isset( $this->params['mapping']['from'], $this->params['mapping']['to'] ) ) {
			$this->params['mapping'] = array_combine( $this->params['mapping']['from'], $this->params['mapping']['to'] );
		}

		$this->read_file();
	}

	/**
	 * Read file.
	 */
	protected function read_file() {
		if ( false !== ( $handle = fopen( $this->file, 'r' ) ) ) {
			$this->raw_keys = fgetcsv( $handle, 0, $this->params['delimiter'], $this->params['enclosure'] );

			// Remove BOM signature from the first item.
			if ( isset( $this->raw_keys[0] ) ) {
				$this->raw_keys[0] = $this->remove_utf8_bom( $this->raw_keys[0] );
			}

			if ( 0 !== $this->params['start_pos'] ) {
				fseek( $handle, (int) $this->params['start_pos'] );
			}

			while ( false !== ( $row = fgetcsv( $handle, 0, $this->params['delimiter'], $this->params['enclosure'] ) ) ) {
				$this->raw_data[]                                 = $row;
				$this->file_positions[ count( $this->raw_data ) ] = ftell( $handle );

				if ( ( $this->params['end_pos'] > 0 && ftell( $handle ) >= $this->params['end_pos'] ) || 0 === --$this->params['lines'] ) {
					break;
				}
			}

			$this->file_position = ftell( $handle );
		}

		if ( ! empty( $this->params['mapping'] ) ) {
			$this->set_mapped_keys();
		}

		if ( $this->params['parse'] ) {
			$this->set_parsed_data();
		}
	}

	/**
	 * Remove UTF-8 BOM signature.
	 *
	 * @param  string $string String to handle.
	 * @return string
	 */
	protected function remove_utf8_bom( $string ) {
		if ( 'efbbbf' === substr( bin2hex( $string ), 0, 6 ) ) {
			$string = substr( $string, 3 );
		}

		return $string;
	}

	/**
	 * Set file mapped keys.
	 */
	protected function set_mapped_keys() {
		$mapping = $this->params['mapping'];

		foreach ( $this->raw_keys as $key ) {
			$this->mapped_keys[] = isset( $mapping[ $key ] ) ? $mapping[ $key ] : $key;
		}
	}

	/**
	 * Check if strings starts with determined word.
	 *
	 * @param  string $haystack Complete sentence.
	 * @param  string $needle   Excerpt.
	 * @return bool
	 */
	protected function starts_with( $haystack, $needle ) {
		return substr( $haystack, 0, strlen( $needle ) ) === $needle;
	}


	/**
	 * Map and format raw data to known fields.
	 */
	protected function set_parsed_data() {
		//$parse_functions = $this->get_formating_callback();
		$mapped_keys     = $this->get_mapped_keys();
		$use_mb          = function_exists( 'mb_convert_encoding' );

		// Parse the data.
		foreach ( $this->raw_data as $row ) {
			// Skip empty rows.
			if ( ! count( array_filter( $row ) ) ) {
				continue;
			}
			$data = array();


			foreach ( $row as $id => $value ) {
				// Skip ignored columns.
				if ( empty( $mapped_keys[ $id ] ) ) {
					continue;
				}

				// Convert UTF8.
				if ( $use_mb ) {
					$encoding = mb_detect_encoding( $value, mb_detect_order(), true );
					if ( $encoding ) {
						$value = mb_convert_encoding( $value, 'UTF-8', $encoding );
					} else {
						$value = mb_convert_encoding( $value, 'UTF-8', 'UTF-8' );
					}
				} else {
					$value = wp_check_invalid_utf8( $value, true );
				}

				//$data[ $mapped_keys[ $id ] ] = call_user_func( $parse_functions[ $id ], $value );
				$data[ $mapped_keys[ $id ] ] = $value;
			}

			$this->parsed_data[] = $data;
		}
	}

	/**
	 * Get a string to identify the row from parsed data.
	 *
	 * @param  array $parsed_data Parsed data.
	 * @return string
	 */
	protected function get_row_id( $parsed_data ) {
        $name     = isset( $parsed_data['name'] ) ? esc_attr( $parsed_data['name'] ) : '';
		$ticker     = isset( $parsed_data['ticker'] ) ? esc_attr( $parsed_data['ticker'] ) : '';
		$row_data = array();

		if ( $name ) {
			$row_data[] = $name;
		}
		if ( $ticker ) {
			$row_data[] = sprintf( __( 'Ticker %s', 'utheme' ), $ticker );
		}

		return implode( ', ', $row_data );
	}

	/**
	 * Process importer.
	 *
	 * Do not import products with Name or Tickers that already exist if option
	 * update existing is false, and likewise, if updating products, do not
	 * process rows which do not exist if an Name/Ticker is provided.
	 *
	 * @return array
	 */
	public function import() {
		$this->start_time = time();
		$index            = 0;
		$update_existing  = $this->params['update_existing'];
		$data             = array(
			'imported'  => array(),
			'failed'    => array(),
			'updated'   => array(),
			'skipped'   => array(),
		);

		foreach ( $this->parsed_data as $parsed_data_key => $parsed_data ) {

			$ticker     = isset( $parsed_data['ticker'] ) ? esc_attr( $parsed_data['ticker'] ) : '';
			$id_exists  = false;
			$sku_exists = false;



			//$result = $this->process_item( $parsed_data );

			if ( is_wp_error( $result ) ) {
				$result->add_data( array( 'row' => $this->get_row_id( $parsed_data ) ) );
				$data['failed'][]   = $result;
			} elseif ( $result['updated'] ) {
				$data['updated'][]  = $result['id'];
			} else {
				$data['imported'][] = $result['id'];
			}

			$index ++;

			if ( $this->params['prevent_timeouts'] && ( $this->time_exceeded() || $this->memory_exceeded() ) ) {
				$this->file_position = $this->file_positions[ $index ];
				break;
			}
		}

		return $data;
	}
}
