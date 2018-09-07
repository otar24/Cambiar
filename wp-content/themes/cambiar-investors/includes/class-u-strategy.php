<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Strategy
 *
 * These are regular strategies, which extend the abstract post class.
 *
 * @class     U_Strategy
 * @version   1.0.0
 * @package   U_Theme/Classes
 * @category  Class
 * @author    Elena Zhyvohliad
 */
class U_Strategy extends U_Abstract_Post{
	public $group_id = null;
	
	/**
	 * @var string
	 */
	protected $related_startegy_field_pattern = 'related_strategy_%s';
	
	/**
	 * List of related srategies
	 *
	 * @var array
	 */
	protected $related_strategy_data = [
		'top_10_holdings' => null,
		'sector_weights' => null,
		'attributes' => null,
		'top_countries' => null,
	];
	
	public function populate( $result ) {
		parent::populate( $result );
		
		$groups = $this->get_terms('strategy_group');
		if ( $group = array_shift( $groups ) ) {
			$this->group_id = $group->term_id;
		}
	}
	
	/**
     * __get function.
     *
     * @param mixed $key
     * @return mixed
     */
    public function __get( $key ) {
        // Get values or default if not set.

        $value = parent::__get( $key );

        $taxonomies = array('share_class','strategy_category','geography', 'strategy_group');

        if( in_array($key, $taxonomies) ){
            $terms = $this->get_terms( $key );
            $value = '';
            if( !is_wp_error( $terms ) && !empty($terms)){
                $value = $terms[0]->name;
            }
        }
        elseif ($key === 'category'){
            $terms = $this->get_terms( 'strategy_category' );
            $value = '';
            if( !is_wp_error( $terms ) && !empty($terms)){
                $value = $terms[0]->name;
            }
        }
        elseif ($key === 'group'){
            $terms = $this->get_terms( 'strategy_group' );
            $value = '';
            if( !is_wp_error( $terms ) && !empty($terms)){
                $value = $terms[0]->name;
            }
        }
        elseif ($key === 'title'){
            return $this->get_title();
        }
        elseif ($key === 'active_share'){
            return floatval($value);
        }

        return $value;
    }

    public function get_share_class( $obj = false ){
        $terms = $this->get_terms('share_class');
        if( $obj ){
            return $terms;
        }else if( !is_wp_error( $terms ) && !empty($terms)){
            return $terms[0]->name;
        }
    }



	/**
	 * Init/load the strategy object. Called from the constructor.
	 *
	 * @param  int|object|U_Strategy $strategy Strategy to init.
	 */
	protected function init( $strategy ) {
        if ( $strategy instanceof U_Strategy ) {
            $this->id   = absint( $strategy->id );
            $this->post = $strategy->post;
            $this->get_post( $this->id );
        }else{
            parent::init( $strategy );
        }
	}

	public function add_ticker( $data = array() )
	{
		$args = array(
			'post_type'     => 'ticker',
			'post_status'   => 'publish',
			'ping_status'   => 'closed',
			'post_author'   => 1,
			'post_password' => uniqid( 'strategy_ticker_' ),
			'post_title'    => sprintf( __( 'Ticker &ndash; %s', 'utheme' ), strftime( _x( '%b %d, %Y @ %I:%M %p', 'Ticker date parsed by strftime', 'utheme' ) ) ),
			'post_parent'   => $this->id
		);
		$ticker_id = wp_insert_post( $args, true );
		if ( is_wp_error( $ticker_id ) ) {
			return $ticker_id;
		}

		update_post_meta( $ticker_id, '_utheme_version', U_THEME_VERSION );
		return $ticker_id;
	}

    public function get_documents(){
        $docs = !is_array($this->additional_doc) ? array() : $this->additional_doc;
        $docs = array_merge($this->get_documents_list(), $docs);
        $documents_list = [];
        foreach ($docs as $doc_id => $doc ){
            $url  = $this->get_document_url($doc_id);
            $name = $this->get_document_name($doc_id);
            if( $url ) {
                $documents_list[$doc_id] = [
                    'url' => $url,
                    'name' => $name
                ];
            }
        }
        return $documents_list;
    }
	public function get_documents_list(){
        return [
            'commentary'       => __( 'Commentary', 'utheme' ),
            'fact_sheet'       => __( 'Fact Sheet', 'utheme' ),
            'cap_gains'        => __( 'Cap Gains', 'utheme' ),
        ];
    }
	public function get_document_url($id)
	{
        $docs = $this->get_documents_list();
        if( isset($docs[$id]) ){
            return $this->{'document_'. $id };
        }else{
            $additional_doc = $this->additional_doc;
            if (isset($additional_doc[$id])){
                return $additional_doc[$id]['url'];
            }
        }
        return false;
	}

    public function get_document_name($id)
    {
        $docs = $this->get_documents_list();
        if( isset($docs[$id]) ){
            return $docs[$id];
        }else{
            $additional_doc = $this->additional_doc;
            if (isset($additional_doc[$id])){
                return $additional_doc[$id]['name'];
            }
        }
        return false;
    }
    public function get_managers()
    {
        return $this->managers;
    }

    public function getChartData(){
		$colors = u_get_colors();
        $totalData = [
            'monthly' => [
                'labels' => [],
                'datasets' => []
            ],
            'quarterly' => [
                'labels' => [],
                'datasets' => []
            ],
            'historical' => [],
            'top_holdings' => [
                'labels' => [],
                'datasets' => []
            ],
            'sector' => [
                'labels' => [],
                'datasets' => []
            ],
            'countries'  => [
                'labels' => [],
                'datasets' => []
            ],
            'attributes'  => [
                'labels' => [],
                'datasets' => []
            ],
            'active_share'  => [
                'labels' => [],
                'datasets' => [
                    [
                        'data' => [$this->active_share, 100 - $this->active_share ],
                        'backgroundColor' => []
                    ]
                ]
            ],
        ];

        foreach (['monthly', 'quarterly'] as $data_id ){
        	if ( $rows = $this->{$data_id} ) {
		        $columns = U_Meta_Box_Strategy_Data::get_perfomance_columns();
		
		        unset( $columns['name'] );
		        $columns['qtr'] = $data_id === 'monthly' ? __( 'MTD', 'utheme' ) : __( 'QTR', 'utheme' );
		
		        array_walk( $columns, function ( & $value, $key ) use ( &$rows ) {
			        $col_values = array_filter( array_column( $rows, $key ), function ( $el ) {
				        return '' != $el;
			        } );
			
			        if ( empty( $col_values ) ) {
				        foreach ( $rows as $row_key => $row ) {
					        unset( $rows[ $row_key ][ $key ] );
				        }
				
				        $value = '';
			        }
		        } );
		
		        $columns                         = array_filter( $columns );
		        $totalData[ $data_id ]['labels'] = array_values( $columns );
		        
		        //Y-axis data
		        $field_y_axis_min = $data_id . '_y_axis_min';
		        $field_y_axis_max = $data_id . '_y_axis_max';
		        $field_y_axis_step = $data_id . '_y_axis_step';
		        
		        $totalData[ $data_id ]['yAxis'] = [
		        	'min' => ( $y_axis_min = $this->{$field_y_axis_min} ) ? $y_axis_min : 0,
		        	'max' => ( $y_axis_max = $this->{$field_y_axis_max} ) ? $y_axis_max : 30,
		        	'step' => ( $y_axis_step = $this->{$field_y_axis_step} ) ? $y_axis_step : 10,
		        ];
		
		        foreach ( array_values( $rows ) as $row_key => $row ) {
			        $color_key = count( $colors ) - 1 - ( ( $row_key - 1 ) % count( $colors ) );
			        $label     = $row['name'];
			        unset( $row['name'] );
			
			        $totalData[ $data_id ]['datasets'][] = [
				        'label'           => esc_attr( $label ),
				        'data'            => array_values( $row ),
				        'backgroundColor' => ! $row_key ? 'rgb(0, 69, 124)' : $colors[ $color_key ],
				        'borderWidth'     => 0,
				        'yAxisid'         => "y-axis-camwx"
			        ];
		        }
	        }
        }
        
        if( $historical_data = $this->historical_data ) {
        	$start_date = $this->historical_data_start_date;
        	$start_price = $this->historical_data_start_price;
	
	        $totalData['historical'] = [
		        'step'    => 5,
		        'dataset' => [
			        [
				        'x'     => $start_date,
				        'y'     => floatval( $start_price ),
				        'value' => u_price_format( $start_price, 0 ),
			        ]
		        ]
	        ];
	        
	        usort( $historical_data, function ( $a, $b ) {
		        $timestamp_a = strtotime( $a['date'] );
		        $timestamp_b = strtotime( $b['date'] );
		
		        if ( $timestamp_a == $timestamp_b ) {
			        return 0;
		        }
		
		        return ( $timestamp_a < $timestamp_b ) ? - 1 : 1;
	        } );
        	
	        $amount = $start_price;
	        foreach ( $historical_data as $hd ) {
		        $amount += round( abs( $amount ) / 100 * $hd['percentage'] );
		
		        $totalData['historical']['dataset'][] = [
			        'x'          => $hd['date'],
			        'y'          => $amount,
			        'percentage' => u_percent_format( $hd['percentage'] ),
			        'value'      => u_price_format( $amount, 0 ),
		        ];
	        }
	
	        $values                          = array_column( $totalData['historical']['dataset'], 'y' );
	        $min_value                       = min( $values );
	        $max_value                       = max( $values );
	        $delimiter = 10;
	        while( ( $max_value - $min_value ) / $delimiter > 2 ){
	        	$delimiter = $delimiter * 10;
	        }
	        
	        $totalData['historical']['step'] = ceil( round( ( $max_value - $min_value ) / 3 ) / $delimiter ) * $delimiter;
        }

        $sector_weights_labels = U_Meta_Box_Strategy_Data::get_sector_weights_columns();
	    $sector_weights_related_strategy = $this->getRelatedStrategy( 'sector_weights' );
	    $sector_weights = $sector_weights_related_strategy
		    ? $sector_weights_related_strategy->sector_weights
		    : $this->sector_weights;
        
        unset($sector_weights_labels['name']);
        unset($sector_weights_labels['action']);
        foreach ( $sector_weights_labels as $key => $label){
        	$col_values = array_filter( array_column( $sector_weights, $key ), function( $el ) {
        		return '' != $el;
	        } );
        	
        	if ( ! empty( $col_values ) ) {
		        $totalData['sector']['labels'][] = $label['label'];
	        } else {
		        foreach ( $sector_weights as $sw_key => $sector_weight ) {
			        unset( $sector_weights[ $sw_key ][ $key ] );
		        }
	        }
        }
        
        if( $sector_weights && is_array($sector_weights )) {

            $colors_count = count($colors)-3;
            $sector_count = count($sector_weights);
            $percent = absint(100/ $sector_count  )/100 * (-1);
            $color = '#00457c';

            $i = $colors_count; foreach ($sector_weights as $sector_weight) {
                $dataset = [
                    'label' => $sector_weight['name'],
                    'data' => [],
                    'backgroundColor' => $color,
                    'borderWidth' => 0,
                    'yAxisid' => "x-axis-cambi",
                    'borderColor' => [0]
                ];

                foreach ($sector_weight as $key => $data){
                    if( $key == 'name') continue;
                    $dataset['data'][] = $data;
                }

                $totalData['sector']['datasets'][] = $dataset;
                $color = $colors[$i];
                $i--;
            }
        }

        $top_10_holdings_related_strategy = $this->getRelatedStrategy( 'top_10_holdings' );
		$top_10_holdings = $top_10_holdings_related_strategy
			? $top_10_holdings_related_strategy->top_10_holdings
			: $this->top_10_holdings;
        if ( $top_10_holdings && is_array( $top_10_holdings ) ) {
            $colors         = u_get_colors();
            $colors_count   = count($colors);
            $holdings_count = count($this->top_10_holdings);
            $percent = absint(100/ $holdings_count  )/100;
            $color = $holdings_count > $colors_count ? '#00457c' : $colors[0];

            $totalData['top_holdings']['datasets'][] = [
                'data'            => [],
                'backgroundColor' => []
            ];

            $i = 0; foreach ( $top_10_holdings as $holding ) { $i++;
                $totalData['top_holdings']['labels'][] = $holding['name'];
                $totalData['top_holdings']['datasets'][0]['data'][] = $holding['data'];
                $totalData['top_holdings']['datasets'][0]['backgroundColor'][] = $color;
                $color = $holdings_count > $colors_count ? u_luminance($color, $percent) : $colors[$i];
            }
        }

        $attributes_related_strategy = $this->getRelatedStrategy( 'attributes' );
		$attributes = $attributes_related_strategy
			? $attributes_related_strategy->attributes
			: $this->attributes;
        if ( $attributes && is_array( $attributes )){
        	$labels = array_shift( $attributes );
        	array_shift( $labels );

            foreach ($labels as $row_key => $label ){
                $totalData['attributes']['labels'][] = $label;
                $totalData['attributes']['datasets'][0]['data'][] = array_column( $attributes, $row_key + 1 );
            }
            $totalData['attributes']['datasets'][0]['backgroundColor'] = ["#00457c"];

            $totalData['attributes']['datasets'][] = [
                'data'            => [],
                'backgroundColor' => ["#00457c"]
            ];
        }
	
	
	
	    $top_countries_related_strategy = $this->getRelatedStrategy( 'top_countries' );
	    $top_countries = $top_countries_related_strategy
		    ? $top_countries_related_strategy->top_countries
		    : $this->top_countries;
        if( $top_countries && is_array( $top_countries )) {
	        $labels = array_shift( $top_countries );
	        array_shift( $labels );
	        
	        $totalData['countries']['labels'] = array_column( $top_countries, 0 );
	        foreach ($labels as $row_key => $label ){
		        $totalData['countries']['datasets'][] = [
			        'label' => $label,
			        'data' => array_column( $top_countries, $row_key + 1 ),
			        'backgroundColor' => ! $row_key ? 'rgb(0, 69, 124)' : 'rgba(69, 85, 96, 0.25)',
			        'borderWidth' => 0,
			        'yAxisid' => "x-axis-cambi",
			        'borderColor' => [0]
		        ];
	        }
        }

        return $totalData;
    }


    public function has_performance_data(){
        $prf_types = ['monthly', 'quarterly'];
        foreach ($prf_types as $prf_type){
			if ( $this->{$prf_type} ) {
				return true;
			}
        }
        
	    return false;
    }

    public function get_strategies_in_group(){
        $terms    = $this->get_terms( 'strategy_group' );
        if( !is_wp_error( $terms ) && !empty($terms)){
            $group_id = $terms[0]->term_id;
            return u_get_strategies( array('group' => $group_id, 'type' => $this->type ) );
        }
        return false;
    }
	
	/**
	 * Checks whether the key is in the related_strategy_data list
	 *
	 * @param $key
	 *
	 * @return bool
	 */
    protected function isKeyInRelatedData( $key ) {
		return in_array( $key, array_keys( $this->related_strategy_data ) );
    }
	
	/**
	 * Retrieves loaded related strategy by key
	 *
	 * @param $key
	 *
	 * @return U_Strategy|null
	 */
    public function getRelatedStrategy( $key ) {
    	if ( ! $this->isKeyInRelatedData( $key ) ) {
    		return null;
	    }
	    
	    if ( ! $this->related_strategy_data[ $key ] ) {
		    $this->related_strategy_data[ $key ] = $this->loadRelatedStrategy( $key );
	    }
	    
	    return $this->related_strategy_data[ $key ];
    }
	
	/**
	 * Loads the strategy
	 *
	 * @param $key
	 *
	 * @return null|U_Strategy
	 */
    protected function loadRelatedStrategy( $key ) {
	    $field_name = $this->getRelatedStrategyFieldName( $key );
	    if ( $related_strategy = $this->{$field_name} ) {
	    	return new U_Strategy( $related_strategy );
	    }
	    
	    return null;
    }
	
	/**
	 * Prepares field name for loading strategy
	 *
	 * @param $key
	 *
	 * @return string
	 */
    protected function getRelatedStrategyFieldName( $key ) {
    	return sprintf( $this->related_startegy_field_pattern, $key );
    }
}
