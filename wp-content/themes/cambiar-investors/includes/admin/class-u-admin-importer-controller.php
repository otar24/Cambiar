<?php
/**
 *
 * @author      uCAT
 * @category    Admin
 * @package     U_Theme/Admin/
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'WP_LOAD_IMPORTERS' ) )
    return;

/** Display verbose errors */
if( ! defined('IMPORT_DEBUG') ){
    define( 'IMPORT_DEBUG', false );
}

// Load Importer API
require_once ABSPATH . 'wp-admin/includes/import.php';

if ( ! class_exists( 'WP_Importer' ) ) {
    $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
    if ( file_exists( $class_wp_importer ) )
        require $class_wp_importer;
}

if ( ! class_exists( 'U_Admin_Importer_Controller', false ) ) :

/**
 * U_Admin_Importer_Controller Class.
 */
class U_Admin_Importer_Controller{

    protected $headers;

    protected $lines;

    /**
     * The ID of the current file.
     *
     * @var string
     */
    protected $id = '';

    /**
     * The tath to the current file.
     *
     * @var string
     */
    protected $file = '';

    /**
     * The current import step.
     *
     * @var string
     */
    protected $step = '';

    /**
     * Progress steps.
     *
     * @var array
     */
    protected $steps = array();

    /**
     * Errors.
     *
     * @var array
     */
    protected $errors = array();

    /**
     * The current delimiter for the file being read.
     *
     * @var string
     */
    protected $delimiter = ',';

    /**
     * Whether to skip existing products.
     *
     * @var bool
     */
    protected $update_existing = false;

    /**
     * Get importer instance.
     *
     * @param  string $file File to import.
     * @param  array  $args Importer arguments.
     * @return U_Strategies_CSV_Importer
     */
    public static function get_importer( $file, $args = array() ) {
        include_once( dirname( __FILE__ ) . '/importers/class-u-strategies-csv-importer.php' );
        return new U_Strategies_CSV_Importer( $file, $args );
    }

    /**
     * Constructor.
     */
    public function __construct() {
        $default_steps = array(
            'upload' => array(
                'name'    => __( 'Upload CSV file', 'utheme' ),
                'view'    => array( $this, 'upload_form' ),
                'handler' => array( $this, 'upload_form_handler' ),
            ),
            'mapping' => array(
                'name'    => __( 'Column mapping', 'utheme' ),
                'view'    => array( $this, 'mapping_form' ),
                'handler' => '',
            ),
            'import' => array(
                'name'    => __( 'Import', 'utheme' ),
                'view'    => array( $this, 'import' ),
                'handler' => '',
            ),
            'done' => array(
                'name'    => __( 'Done!', 'utheme' ),
                'view'    => array( $this, 'done' ),
                'handler' => '',
            ),
        );

        $this->steps = $default_steps;

        $this->step            = isset( $_REQUEST['step'] ) ? sanitize_key( $_REQUEST['step'] ) : current( array_keys( $this->steps ) );
        $this->id              = isset( $_REQUEST['file'] ) ? (int) $_REQUEST['file'] : '';
        $this->update_existing = isset( $_REQUEST['update_existing'] ) ? (bool) $_REQUEST['update_existing'] : false;
        $this->delimiter       = ! empty( $_REQUEST['delimiter'] ) ? u_clean( $_REQUEST['delimiter'] ) : ',';

        $this->file = isset( $_REQUEST['file'] ) ? get_attached_file( $this->id ) : '';
    }

    /**
     * Get the URL for the next step's screen.
     * @param string step   slug (default: current step)
     * @return string       URL for next step if a next step exists.
     *                      Admin URL if it's the last step.
     *                      Empty string on failure.
     */
    public function get_next_step_link( $step = '' ) {
        if ( ! $step ) {
            $step = $this->step;
        }

        $keys = array_keys( $this->steps );

        if ( end( $keys ) === $step ) {
            return admin_url();
        }

        $step_index = array_search( $step, $keys );

        if ( false === $step_index ) {
            return '';
        }

        $params = array(
            'step'            => $keys[ $step_index + 1 ],
            'file'            => $this->id,
            'delimiter'       => $this->delimiter,
            'update_existing' => $this->update_existing,
            '_wpnonce'        => wp_create_nonce( 'utheme-csv-importer' ), // wp_nonce_url() escapes & to &amp; breaking redirects.
        );

        return add_query_arg( $params );
    }



    // Display import page title
    public function output_header() {
        include( dirname( __FILE__ ) . '/views/import/html-csv-import-header.php' );
    }

    /**
     * Output steps view.
     */
    protected function output_steps() {
        include( dirname( __FILE__ ) . '/views/import/html-csv-import-steps.php' );
    }

    // Close div.wrap
    public function output_footer() {
        include( dirname( __FILE__ ) . '/views/import/html-csv-import-footer.php' );
    }

    /**
     * Add error message.
     *
     * @param string $message Error message.
     * @param array  $actions List of actions with 'url' and 'label'.
     */
    protected function add_error( $message, $actions = array() ) {
        $this->errors[] = array(
            'message' => $message,
            'actions' => $actions,
        );
    }

    /**
     * Add error message.
     */
    protected function output_errors() {
        if ( ! $this->errors ) {
            return;
        }

        foreach ( $this->errors as $error ) {
            echo '<div class="error inline">';
            echo '<p>' . esc_html( $error['message'] ) . '</p>';

            if ( ! empty( $error['actions'] ) ) {
                echo '<p>';
                foreach ( $error['actions'] as $action ) {
                    echo '<a class="button button-primary" href="' . esc_url( $action['url'] ) . '">' . esc_html( $action['label'] ) . '</a> ';
                }
                echo '</p>';
            }
            echo '</div>';
        }
    }

    public function dispatch(){
        if ( ! current_user_can( 'import' ) ) {
            wp_die( __( 'Sorry, you are not allowed to import content.' ) );
        }

        if ( ! empty( $_POST['save_step'] ) && ! empty( $this->steps[ $this->step ]['handler'] ) ) {
            call_user_func( $this->steps[ $this->step ]['handler'], $this );
        }
        $this->output_header();
        $this->output_steps();
        $this->output_errors();
        call_user_func( $this->steps[ $this->step ]['view'], $this );
        $this->output_footer();
    }

    /**
     * Output information about the uploading process.
     */
    protected function upload_form() {
        $bytes      = apply_filters( 'import_upload_size_limit', wp_max_upload_size() );
        $size       = size_format( $bytes );
        $upload_dir = wp_upload_dir();

        include( dirname( __FILE__ ) . '/views/import/html-product-csv-import-form.php' );
    }

    /**
     * Handle the upload form and store options.
     */
    public function upload_form_handler() {
        check_admin_referer( 'utheme-csv-importer' );

        $file = $this->handle_upload();

        if ( is_wp_error( $file ) ) {
            $this->add_error( $file->get_error_message() );
            return;
        } else {
            $this->id   = (int) $file['id'];
            $this->file = $file['file'];
        }

        wp_redirect( esc_url_raw( $this->get_next_step_link() ) );
        exit;
    }

    /**
     * Handles the CSV upload and initial parsing of the file to prepare for
     * displaying author import options
     *
     * @return bool False if error uploading or invalid file, true otherwise
     */
    public function handle_upload() {

        $valid_filetypes = array( 'csv' => 'text/csv', 'txt' => 'text/plain' );

        if ( ! isset( $_FILES['import'] ) ) {
            return new WP_Error( 'utheme_csv_importer_upload_file_empty', __( 'File is empty. Please upload something more substantial. This error could also be caused by uploads being disabled in your php.ini or by post_max_size being defined as smaller than upload_max_filesize in php.ini.', 'utheme' ) );
        }

        $filetype = wp_check_filetype( $_FILES['import']['name'], $valid_filetypes );
        if ( ! in_array( $filetype['type'], $valid_filetypes ) ) {
            return new WP_Error( 'utheme_csv_importer_upload_file_invalid', __( 'Invalid file type. The importer supports CSV and TXT file formats.', 'utheme' ) );
        }

        $file = wp_import_handle_upload();

        if ( isset( $file['error'] ) ) {
            $error = sprintf( __( 'Sorry, there has been an error: %s', 'utheme' ), esc_html( $file['error'] ));
            return new WP_Error( 'utheme_csv_importer_upload_error', $error  );
        } else if ( ! file_exists( $file['file'] ) ) {
            $msg = sprintf( __( 'The export file could not be found at %s. It is likely that this was caused by a permissions problem.', 'utheme' ), esc_html( $file['file'] ) );;
            $error = sprintf( __( 'Sorry, there has been an error: %s', 'utheme' ), $msg);
            return new WP_Error( 'utheme_csv_importer_upload_error', $error  );
        }


        /*
			 * Schedule a cleanup for one day from now in case of failed
			 * import or missing wp_import_cleanup() call.
			 */
        wp_schedule_single_event( time() + DAY_IN_SECONDS, 'importer_scheduled_cleanup', array( $this->id ) );

        return $file;
    }

    /**
     * Mapping step.
     */
    protected function mapping_form() {
        $args         = array(
            'lines'     => 1,
            'delimiter' => $this->delimiter,
        );

        $this->parse();

        $headers      = $this->headers;
        $mapped_items = $this->auto_map_columns( $headers );
        $sample       = current( $this->lines );


        if ( empty( $sample ) ) {
            $this->add_error(
                __( 'The file is empty or using a different encoding than UTF-8, please try again with a new file.', 'utheme' ),
                array(
                    array(
                        'url'   => admin_url( 'admin.php?import=u-strategies' ),
                        'label' => __( 'Upload a new file', 'utheme' ),
                    ),
                )
            );

            // Force output the errors in the same page.
            $this->output_errors();
            return;
        }

        include_once( dirname( __FILE__ ) . '/views/import/html-csv-import-mapping.php' );
    }

    /**
     * Import the file if it exists and is valid.
     */
    public function import() {
        if ( ! is_file( $this->file ) ) {
            $this->add_error( __( 'The file does not exist, please try again.', 'utheme' ) );
            return;
        }

        if ( ! empty( $_POST['map_to'] ) ) {
            $mapping_from = wp_unslash( $_POST['map_from'] );
            $mapping_to   = wp_unslash( $_POST['map_to'] );
        } else {
            wp_redirect( esc_url_raw( $this->get_next_step_link( 'upload' ) ) );
            exit;
        }

        wp_localize_script( 'utheme_admin_import', 'utheme_import_params', array(
            'import_nonce'    => wp_create_nonce( 'utheme-product-import' ),
            'mapping'         => array(
                'from' => $mapping_from,
                'to'   => $mapping_to,
            ),
            'file'            => $this->id,
            'update_existing' => $this->update_existing,
            'delimiter'       => $this->delimiter,
            'redirect'        => add_query_arg( array( 'nonce' => wp_create_nonce( 'product-csv' ) ), admin_url( 'admin.php?import=u-strategies&step=done' ) )
        ) );
        wp_enqueue_script( 'utheme_admin_import' );

        include_once( dirname( __FILE__ ) . '/views/import/html-csv-import-progress.php' );
    }

    /**
     * Done step.
     */
    protected function done() {
        $imported = isset( $_GET['products-imported'] ) ? absint( $_GET['products-imported'] ) : 0;
        $updated  = isset( $_GET['products-updated'] ) ? absint( $_GET['products-updated'] ) : 0;
        $failed   = isset( $_GET['products-failed'] ) ? absint( $_GET['products-failed'] ) : 0;
        $skipped  = isset( $_GET['products-skipped'] ) ? absint( $_GET['products-skipped'] ) : 0;
        $errors   = array_filter( (array) get_user_option( 'product_import_error_log' ) );

        include_once( dirname( __FILE__ ) . '/views/import/html-csv-import-done.php' );
    }



    /**
     * Parse a CSV file
     *
     * @param string $file Path to CSV file for parsing
     * @return array Information gathered from the CSV file
     */
    public function parse( ) {
        $this->lines   = array();
        $data   = explode( "\n", file_get_contents( $this->file ) );
        $this->headers = str_getcsv( array_shift( $data ), $this->delimiter );

        foreach ( $data as $line ) {
            $row = str_getcsv( $line, $this->delimiter );
            foreach ( str_getcsv( $line, $this->delimiter ) as $key => $field )
                $row[ $key ] = $field;
            $row = array_filter( $row );
            $this->lines[] = $row;
        }
    }

    /**
     * Auto map column names.
     *
     * @param  array $raw_headers Raw header columns.
     * @param  bool  $num_indexes If should use numbers or raw header columns as indexes.
     * @return array
     */
    protected function auto_map_columns( $raw_headers, $num_indexes = true ) {

        $default_columns =  array(
            'fundname' => 'name',
            'ticker' => 'ticker',
            'eff_incept_dt' => 'inception_date',
            'classname' => 'share_class	',
        );

        $headers = array();
        foreach ( $raw_headers as $key => $field ) {
            $field             = strtolower( $field );
            $index             = $num_indexes ? $key : $field;
            $headers[ $index ] = $field;

            if ( isset( $default_columns[ $field ] ) ) {
                $headers[ $index ] = $default_columns[ $field ];
            }
        }

        return $headers;
    }

    /**
     * Get mapping options.
     *
     * @param  string $item Item name
     * @return array
     */
    protected function get_mapping_options( $item = '' ) {

        // Get index for special column names.
        $index = $item;

        if ( preg_match( '/\d+$/', $item, $matches ) ) {
            $index = $matches[0];
        }

        // Properly format for meta field.
        $meta = str_replace( 'meta:', '', $item );

        // Available options.
        $options        = array(
            'name'               => __( 'Product Name', 'utheme' ),
            'ticker'             => __( 'Ticker', 'utheme' ),
            'category'           => __( 'Category', 'utheme' ),
            'geography'          => __( 'Geography', 'utheme' ),
            'short_description'  => __( 'Short description', 'utheme' ),
            'description'        => __( 'Description', 'utheme' ),
            'share_class'         => __( 'Share Class', 'utheme' ),
            'average_annual'     => array(
                'name'    => __( 'Average Annual Total Returns (%)', 'utheme' ),
                'options' => array(
                    'qtr' => __( 'QTR', 'utheme' ),
                    'ytd' => __( 'YTD', 'utheme' ),
                    '1yr' => __( '1 YR', 'utheme' ),
                    '2yr' => __( '2 YR', 'utheme' ),
                    '3yr' => __( '3 YR', 'utheme' ),
                    '5yr' => __( '5 YR', 'utheme' ),
                    '10yr' => __( '10 YR', 'utheme' ),
                    'incpt' => __( 'Since Inception', 'utheme' ),
                ),
            ),
            'inception_date'      => __( 'Inception Date', 'utheme' ),
            'holdings'  => __( 'Holdings', 'utheme' ),
            'weighting' => __( 'Weighting', 'utheme' ),
            'cap_range' => __( 'Cap range', 'utheme' ),
            'meta:' . $meta      => __( 'Import as meta', 'utheme' ),
        );

        return $options;
    }
}

endif;
