<?php
/**
 * Strategy Data
 *
 * Functions for displaying the strategy data meta box.
 *
 * @class     U_Meta_Box_Strategy_Data
 * @version   1.0.0
 * @package   U_Theme/Classes
 * @category  Class
 * @author    Elena Zhyvohliad
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * U_Meta_Box_Strategy_Data Class.
 */
class U_Meta_Box_Strategy_Data {

    /**
     * Output the metabox.
     *
     * @param WP_Post $post
     */
    public static function output( $post ) {
        global $the_strategy;

        if ( ! is_object( $the_strategy ) ) {
            $the_strategy = u_get_strategy( $post->ID );
        }

        wp_nonce_field( 'utheme_save_data', 'utheme_meta_nonce' );

        $tabs = [
            'general' => [
                'title' => __( 'General', 'utheme' ),
                'file'  => 'html-strategy-general-data.php'
            ],
            'daily-price' => [
                'title' => __( 'Daily Price & YTD Return', 'utheme' ),
                'file'  => 'html-strategy-daily-price.php'
            ],
            'facts' => [
                'title' => __( 'Key facts', 'utheme' ),
                'file'  => 'html-strategy-facts.php'
            ],
            'literature' => [
                'title' => __( 'Literature', 'utheme' ),
                'file'  => 'html-strategy-literature.php'
            ],
            'managers' => [
                'title' => __( 'Managers', 'utheme' ),
                'file'  => 'html-strategy-section.php'
            ],
            'morningstar' => [
                'title' => __( 'Morningstar', 'utheme' ),
                'file'  => 'html-strategy-morningstar.php'
            ],
            'why_invest' => [
                'title' => __( 'Description', 'utheme' ),
                'file'  => 'html-strategy-description.php'
            ],
            'disclosures' => [
                'title' => __( 'Disclosures', 'utheme' ),
                'file'  => 'html-wp-editor.php'
            ],
        ];

        include 'views/html-strategy-data.php';
    }

    /**
     * Output the metabox.
     *
     * @param WP_Post $post
     */
    public static function output_perfomance( $post ) {
        global $the_strategy;

        if ( ! is_object( $the_strategy ) ) {
            $the_strategy = u_get_strategy( $post->ID );
        }

        $tabs = [
            'perfomance_general' => [
                'title' => __( 'Description', 'utheme' ),
                'file'  => 'html-strategy-perfomance-general.php'
            ],
            'perfomance_monthly' => [
                'title' => __( 'Monthly', 'utheme' ),
                'file'  => 'html-strategy-perfomance.php'
            ],
            'perfomance_quarterly' => [
                'title' => __( 'Quarterly', 'utheme' ),
                'file'  => 'html-strategy-perfomance.php'
            ],
            'historical_data' => [
                'title' => __( 'Historical data', 'utheme' ),
                'file'  => 'html-strategy-historical-data.php'
            ],

        ];

        include 'views/html-strategy-data.php';
    }

    /**
     * Output the metabox.
     *
     * @param WP_Post $post
     */
    public static function output_composition( $post ) {
        global $the_strategy;

        if ( ! is_object( $the_strategy ) ) {
            $the_strategy = u_get_strategy( $post->ID );
        }
        
        $existing_strategies = u_get_strategies( [
        	'post__not_in' => [ $the_strategy->id ],
        ] );

        $tabs = [
            'composition_general' => [
                'title' => __( 'Active Share', 'utheme' ),
                'file'  => 'html-strategy-composition-active-share.php'
            ],
            'top_10_holdings' => [
                'title' => __( 'Top 10 holdings', 'utheme' ),
                'file'  => 'html-strategy-section.php'
            ],
            'sector_weights' => [
                'title' => __( 'Sector weights', 'utheme' ),
                'file'  => 'html-strategy-section.php'
            ],
            'attributes' => [
                'title' => __( 'Attributes', 'utheme' ),
                'file'  => 'html-strategy-section.php'
            ],
            'risk_statistics' => [
                'title' => __( 'Risk statistics', 'utheme' ),
                'file'  => 'html-strategy-section.php'
            ],
            'top_countries' => [
                'title' => __( 'Top countries', 'utheme' ),
                'file'  => 'html-strategy-section.php'
            ],
        ];

        include 'views/html-strategy-data.php';
    }

    /**
     * Output the metabox.
     *
     * @param WP_Post $post
     */
    public static function output_cap_gains( $post ) {
        global $the_strategy;

        if ( ! is_object( $the_strategy ) ) {
            $the_strategy = u_get_strategy( $post->ID );
        }

        $tabs = [
            'cap_gains' => [
                'title' => __( 'General', 'utheme' ),
                'file'  => 'html-strategy-section.php'
            ],
            'cap_gains_description' => [
                'title' => __( 'Description', 'utheme' ),
                'file'  => 'html-wp-editor.php'
            ],
        ];

        include 'views/html-strategy-data.php';
    }

	/**
	 * Save meta box data.
	 *
	 * @param int $post_id
	 * @param WP_Post $post
	 */
	public static function save( $post_id, $post ) {

        $fields = array(
            'active_share',
            'disclosures',
            'commentary_id',
            'ticker',
            'type',
            'cusip',
            'holdings',
            'weighting',
            'cap_range',
            'inception_date',
            'minimum_investment',
            'gross_expense_ratio',
            'net_expense_ratio',
            'daily_price',
	        'key_facts_description',
            'monthly',
            'monthly_y_axis_min',
            'monthly_y_axis_max',
            'monthly_y_axis_step',
            'quarterly',
            'quarterly_y_axis_min',
            'quarterly_y_axis_max',
            'quarterly_y_axis_step',
            'commentary',
            'total_net_assets',
            'document',
            'morningstar_rating',
            'morningstar_description',
            'morningstar_short_description',
            'why_invest_title',
            'why_invest_description',
            'performance_description',
            'active_share_date',
            'related_strategy_top_10_holdings',
            'top_10_holdings_date',
            'top_10_holdings_note',
            'top_10_holdings_disclosure',
            'related_strategy_sector_weights',
            'sector_weights_date',
            'sector_weights_note',
            'related_strategy_attributes',
            'attributes_date',
            'attributes_note',
            'risk_statistics_date',
            'risk_statistics_note',
            'related_strategy_top_countries',
            'top_countries_date',
            'top_countries_note',
            'perfomance_title',
            'perfomance_monthly_date',
            'perfomance_quarterly_date',
            'historical_data_start_date',
            'historical_data_start_price',
            'cap_gains_description',
        );
		
        // Update meta
        foreach ($fields as $field_id => $field){
            $meta_key = '_' . $field;

            if( isset($_POST[$meta_key]) ){
                update_post_meta( $post_id, $meta_key, $_POST[$meta_key] );

                if( is_array($_POST[$meta_key]) ){
                    foreach ($_POST[$meta_key] as $key => $val){
                        update_post_meta( $post_id, $meta_key . '_' . $key, $val );
                    }
                }

            }
        }

        $taxonomies = array('share_class','strategy_category','geography', 'strategy_group');
        foreach ($taxonomies as $taxonomy){
            if( isset($_POST['_' . $taxonomy]) ) {
                $term = $_POST['_' . $taxonomy];
                wp_set_post_terms($post_id, $term, $taxonomy, false);
            }
        }

        $docs = [];
        if( isset($_POST['_additional_doc_file_url']) ){
            foreach ($_POST['_additional_doc_file_url'] as $index => $url){
                $name = isset($_POST['_additional_doc_file_name']) && isset($_POST['_additional_doc_file_name'][$index]) ? $_POST['_additional_doc_file_name'][$index] : '';
                $docs[$index] = [
                    'url' => $url,
                    'name' => $name
                ];
            }
        }
        update_post_meta( $post_id, '_additional_doc', $docs );

        $perfomance_types   = array('perfomance_monthly','perfomance_quarterly');

        $perfomance_columns = self::get_perfomance_columns();
        reset($perfomance_columns);
        $first_key = key($perfomance_columns);

        foreach ( $perfomance_types as $perfomance_type ){
            $meta_key   = '_additional_' . $perfomance_type;
            $post_key   = $meta_key . '_' . $first_key;
            $perfomance = [];

            if( isset( $_POST[ $post_key ]) ){
                foreach ($_POST[ $post_key ] as $index => $post_val){

                    $perfomance[$index] = [];

                    foreach ( $perfomance_columns as $suffix => $d ){
                        $post_key = $meta_key . '_' . $suffix;
                        $val = isset( $_POST[$post_key]) && isset($_POST[$post_key][$index]) ? $_POST[$post_key][$index] : '';
                        $perfomance[$index][$suffix] = $val;
                    }
                    if(!array_filter($perfomance[$index])) {
                        unset($perfomance[$index]);
                    }
                }
            }
            update_post_meta( $post_id, $meta_key, $perfomance );
        }

        $docs = [];
        if( isset($_POST['_additional_doc_file_url']) ){
            foreach ($_POST['_additional_doc_file_url'] as $index => $url){
                $name = isset($_POST['_additional_doc_file_name']) && isset($_POST['_additional_doc_file_name'][$index]) ? $_POST['_additional_doc_file_name'][$index] : '';
                $docs[$index] = [
                    'url' => $url,
                    'name' => $name
                ];
            }
        }
        update_post_meta( $post_id, '_additional_doc', $docs );

        $meta = self::get_section_columns('all');

        foreach ($meta as $k => $cols){
            reset($cols);
            $first_key = key($cols);

            $prefix   = '_' . $k;
            $post_key = $prefix . '_' . $first_key;

            $data = [];

            if ( in_array( $k, [ 'attributes', 'top_countries' ] ) && isset( $_POST[ $prefix ] ) ) {
	            update_post_meta( $post_id, $prefix, $_POST[ $prefix ] );
            } elseif( isset( $_POST[ $post_key ]) ){
                foreach ($_POST[ $post_key ] as $index => $val){

                    $data[$index] = [];

                    foreach ( $cols as $suffix => $d ){
                        if( isset($d['skip']) && $d['skip'] ) continue;

                        $post_key = $prefix . '_' . $suffix;
                        $val = isset( $_POST[$post_key]) && isset($_POST[$post_key][$index]) ? $_POST[$post_key][$index] : '';
                        $data[$index][$suffix] = $val;
                    }
                    if(!array_filter($data[$index])) {
                        unset($data[$index]);
                    }
                }
	
	            update_post_meta( $post_id, $prefix, $data );
            }
        }

		clean_post_cache( $post_id );
	}


    public static function get_holdings_columns(){
        return [
            'name' => [
                'label'       => __( 'Title', 'utheme' ),
                'placeholder' => __( 'Row title', 'utheme' ),
                'type'        => 'text',
            ],
            'data' => [
                'label'       => __( 'Data', 'utheme' ),
                'type'        => 'number',
            ],
            'action' => [
                'label'       => '&nbsp;',
                'type'        => 'action',
                'skip'        => true
            ],
        ];
    }
    public static function get_sector_weights_columns (){
        return [
            'name' => [
                'label'       => __( 'Title', 'utheme' ),
                'type'        => 'text',
            ],
            'consumer_discretionary' => [
                'label'       => __( 'Consumer Discretionary', 'utheme' ),
                'type'        => 'number',
            ],
            'consumer_staples' => [
                'label'       => __( 'Consumer Staples', 'utheme' ),
                'type'        => 'number',
            ],
            'energy' => [
                'label'       => __( 'Energy', 'utheme' ),
                'type'        => 'number',
            ],
            'financials' => [
                'label'       => __( 'Financials', 'utheme' ),
                'type'        => 'number',
            ],
            'health_care' => [
                'label'       => __( 'Health Care', 'utheme' ),
                'type'        => 'number',
            ],
            'industrials' => [
                'label'       => __( 'Industrials', 'utheme' ),
                'type'        => 'number',
            ],
            'information_tech' => [
                'label'       => __( 'Information Tech', 'utheme' ),
                'type'        => 'number',
            ],
            'materials' => [
                'label'       => __( 'Materials', 'utheme' ),
                'type'        => 'number',
            ],
            'real_estate' => [
                'label'       => __( 'Real Estate', 'utheme' ),
                'type'        => 'number',
            ],
            'telecom_services' => [
                'label'       => __( 'Telecom Services', 'utheme' ),
                'type'        => 'number',
            ],
            'utilities' => [
                'label'       => __( 'Utilities', 'utheme' ),
                'type'        => 'number',
            ],
            'cash' => [
                'label'       => __( 'Cash', 'utheme' ),
                'type'        => 'number',
            ],
            'action' => [
                'label'       => '&nbsp;',
                'type'        => 'action',
                'skip'        => true
            ],
        ];
    }
    public static function get_attributes_columns(){
        return [
            'name' => [
                'label'       => __( 'Title', 'utheme' ),
                'placeholder' => __( 'Column title', 'utheme' ),
                'type'        => 'text',
            ],
            'earnings_f1y' => [
                'label'       => __( 'Price/Earnings F1Y', 'utheme' ),
                'type'        => 'number',
            ],
            'book' => [
                'label'       => __( 'Price/Book', 'utheme' ),
                'type'        => 'number',
            ],
            'equity' => [
                'label'       => __( 'Debt/Equity', 'utheme' ),
                'type'        => 'number',
            ],
            'eps_growth' => [
                'label'       => __( 'EPS Growth', 'utheme' ),
                'type'        => 'number',
            ],
            'market_cap_wtd_av' => [
                'label'       => __( 'Market Cap Wtd Avg', 'utheme' ),
                'type'        => 'number',
            ],
            'market_cap_median' => [
                'label'       => __( 'Market Cap Median', 'utheme' ),
                'type'        => 'number',
            ],
        ];
    }
    public static function get_risk_statistics_columns(){
        return [
            'name' => [
                'label'       => __( 'Title', 'utheme' ),
                'placeholder' => __( 'Column title', 'utheme' ),
                'type'        => 'text',
            ],
            'alpha' => [
                'label'       => __( 'Alpha', 'utheme' ),
                'type'        => 'number',
            ],
            'beta' => [
                'label'       => __( 'Beta', 'utheme' ),
                'type'        => 'number',
            ],
            'r_squared' => [
                'label'       => __( 'R-Squared', 'utheme' ),
                'type'        => 'number',
            ],
            'sharpe_ratio' => [
                'label'       => __( 'Sharpe Ratio', 'utheme' ),
                'type'        => 'number',
            ],
            'standard_deviation' => [
                'label'       => __( 'Standard Deviation', 'utheme' ),
                'type'        => 'number',
            ],
            'action' => [
                'label'       => '&nbsp;',
                'type'        => 'action',
                'skip'        => true
            ],
        ];
    }
    public static function get_top_countries_columns(){
        return [
            'country' => [
                'label'       => __( 'Country', 'utheme' ),
                'type'        => 'text',
            ],
            'cambiar' => [
                'label'       => __( 'Cambiar', 'utheme' ),
                'type'        => 'number',
            ],
            'msci_eafe' => [
                'label'       => __( 'MSCI EAFE', 'utheme' ),
                'type'        => 'number',
            ],
            'action' => [
                'label'       => '&nbsp;',
                'type'        => 'action',
                'skip'        => true
            ],
        ];
    }

    public static function get_cap_gains_columns(){
        return [
            'year' => [
                'label'       => __( 'Year', 'utheme' ),
                'type'        => 'text',
            ],
            'dividends' => [
                'label'       => __( 'Dividends ($)', 'utheme' ),
                'type'        => 'number',
            ],
            'short_term' => [
                'label'       => __( 'Short-term Capital Gains ($)', 'utheme' ),
                'type'        => 'number',
            ],
            'long_term' => [
                'label'       => __( 'Long-term Capital Gains ($)', 'utheme' ),
                'type'        => 'number',
            ],
            'action' => [
                'label'       => '&nbsp;',
                'type'        => 'action',
                'skip'        => true
            ],
        ];
    }

    public static function get_managers_columns(){
        return [
            'id' => [
                'label'       => __( 'Manager', 'utheme' ),
                'type'        => 'select',
                'options'     => u_get_managers_list( [
                	'numberposts' => -1,
                ] )
            ],
            'description' => [
                'label'       => __( 'Description', 'utheme' ),
                'type'        => 'textarea'
            ],
            'action' => [
                'label'       => '&nbsp;',
                'type'        => 'action',
                'skip'        => true
            ],
        ];
    }

    public static function get_perfomance_columns (){
        return array(
            'name' => __( 'Name', 'utheme' ),
            'qtr' => __( 'QTR', 'utheme' ),
            'ytd' => __( 'YTD', 'utheme' ),
            '1yr' => __( '1 YR', 'utheme' ),
            '3yr' => __( '3 YR', 'utheme' ),
            '5yr' => __( '5 YR', 'utheme' ),
            '10yr' => __( '10 YR', 'utheme' ),
            '15yr' => __( '15 YR', 'utheme' ),
            'incpt' => __( 'Since Inception', 'utheme' )
        );
    }

    public static function get_historical_data_columns (){
        return array(
            'date' => [
                'label'       => __( 'Date', 'utheme' ),
                'type'        => 'text',
                'class'       => 'u-init-date',
            ],
            'percentage' => [
                'label'       => __( 'Growth', 'utheme' ),
                'type'        => 'percent',
	            'class'       => 'percentage',
            ],
            'action' => [
	            'label'       => '&nbsp;',
	            'type'        => 'action',
	            'skip'        => true
            ],
        );

    }

    public static function get_section_columns($section){
	    $columns = [
	        'historical_data' => self::get_historical_data_columns(),
	        'top_10_holdings' => self::get_holdings_columns(),
	        'sector_weights'  => self::get_sector_weights_columns(),
	        'attributes'      => self::get_attributes_columns(),
	        'risk_statistics' => self::get_risk_statistics_columns(),
	        'top_countries'   => self::get_top_countries_columns(),
	        'cap_gains'       => self::get_cap_gains_columns(),
	        'managers'        => self::get_managers_columns()
        ];

	    if( $section === 'all') return $columns;

	    return isset($columns[$section]) ? $columns[$section] : array();
    }
}
