<?php
/**
 * @global U_Strategy $the_strategy
*/
global $the_strategy;

$has_performance_data = $the_strategy->has_performance_data();
$historical_data      = $the_strategy->historical_data;
$perfomance_monthly_date = $the_strategy->perfomance_monthly_date;
$perfomance_quarterly_date = $the_strategy->perfomance_quarterly_date;

if ( ! $historical_data && ! $has_performance_data ) {
	return;
}

$columns = U_Meta_Box_Strategy_Data::get_perfomance_columns();
unset( $columns['name'] );
?>
<div class="fp-block fp-auto-height" data-anchor="performance">
    <section class="section-fund">
        <div class="container">
            <header class="section-header">
                <h1 class="title text-uppercase"><?php _e( 'Performance', 'utheme' ); ?></h1>
            </header>
            <?php if( $has_performance_data ) : ?>
                <div class="diagramm-legend tab-holder">
                    <div class="chart-header">
                        <?php if ( $performance_title = $the_strategy->perfomance_title ) : ?>
                            <h2 class="chart-title h6 text-uppercase"><?php echo esc_attr( $performance_title ); ?></h2>
                        <?php
                            endif;
                            
                            if ( $perfomance_monthly_date || $perfomance_quarterly_date ) :
                                $chart_subtitle = '';
                            
                                if ( $perfomance_monthly_date ) {
                                    $chart_subtitle .= '<span class="strtg-mth-data">' . $perfomance_monthly_date . '</span>';
                                }
                                
                                if ( $perfomance_quarterly_date ) {
	                                $chart_subtitle .= '<span class="strtg-qtr-data">' . $perfomance_quarterly_date . '</span>';
                                }
                            
                                if ( $chart_subtitle ) :
                        ?>
                            <div class="chart-subtitle">
                                <p><?php printf( __( 'as of %s', 'utheme' ), $chart_subtitle ); ?></p>
                            </div>
                        <?php
                                endif;
                            endif;
                        ?>
                    </div>
                    <ul class="tabset-table mod text-uppercase">
                        <?php if ( $the_strategy->monthly ) : ?>
                            <li><a href="#table-perfomance-monthly" data-type="monthly" class="change-qtr-mth"><?php _e('Monthly', 'utheme'); ?></a></li>
                        <?php
                            endif;
                            
                            if ( $the_strategy->quarterly ) :
                        ?>
                            <li><a href="#table-perfomance-quarterly" data-type="quarterly" class="active change-qtr-mth"><?php _e('Quarterly', 'utheme'); ?></a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="tab-content">
                    <?php
                        $prf_types = ['monthly', 'quarterly'];
                        foreach ($prf_types as $prf_type) :
                            if ( $rows = $the_strategy->$prf_type ) :
                    ?>
                            <div class="table-perfomance" id="table-perfomance-<?php echo $prf_type; ?>">
                                <div class="performance-diagr" >
                                    <canvas id="barChart-<?php echo $prf_type; ?>" data-chartopt="<?php echo $prf_type; ?>" class="performance-diagr-barChart" style="width: 100%; height: 300px;"></canvas>
                                </div>
                                <div class="table-holder">
                                    <table class="display-table" data-ordering="false">
                                        <thead>
                                            <tr>
                                                <th class="no-sort"></th>
                                                <?php
                                                    foreach ( $columns as $c_id => $c_label ) :
                                                        $col_values = array_filter( array_column( $rows, $c_id ), function( $el ) {
                                                            return '' != $el;
                                                        } );
                                                        
                                                        if ( $col_values ) :
                                                ?>
                                                        <th class="no-sort">
                                                            <?php
                                                                if($c_id == 'qtr' && $prf_type == 'monthly'){
                                                                    _e('MTD', 'utheme');
                                                                }else{
                                                                    echo $c_label;
                                                                }
                                                            ?>
                                                        </th>
                                                <?php
                                                        endif;
                                                    endforeach;
                                                ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ( $rows as $row ) : ?>
                                                <tr>
                                                    <td><?php echo esc_attr( $row['name'] ); ?></td>
                                                    <?php
                                                        foreach( $columns as $col_key => $col ) :
                                                            $col_values = array_filter( array_column( $rows, $col_key ), function( $el ) {
                                                                return '' != $el;
                                                            } );
        
                                                            if ( $col_values ) :
                                                    ?>
                                                            <td>
                                                                <?php echo u_number_format( $row[ $col_key ] ); ?>
                                                            </td>
                                                    <?php
                                                            endif;
                                                        endforeach;
                                                    ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                    <?php
                            endif;
                        endforeach;
                    ?>
                </div>
            <?php endif; ?>
            <?php if( $historical_data ): ?>
                <section class="investors-diagramm">
                    <div class="diagramm-legend tab-holder">

                    <div class="chart-header">
                        <h2 class="chart-title h6 text-uppercase"><?php printf( __('GROWTH OF %s', 'utheme'), u_price_format( $the_strategy->historical_data_start_price, 0, '.', ',' ) ); ?></h2>
                        <?php if ( $historical_data_start_date = $the_strategy->historical_data_start_date ) : ?>
                            <div class="chart-subtitle">
                                <p><?php printf( __( 'as of %s', 'utheme' ), $historical_data_start_date ); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                    </div>
                    <div class="line-diagram">
                        <canvas id="areaChart-historical-data" data-chartopt="historical" class="performance-diagr-areaChart" style="width: 100%; height: 161px" ></canvas>
                    </div>
                </section>
            <?php endif; ?>
            <div class="collapse-box mb-35">
                <div class="collapse-holder">
                    <div class="text-box collapse">
                        <div class="text">
                            <?php echo apply_filters( 'the_content', $the_strategy->performance_description ); ?>
                            <p></p>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <a class="collapse-btn btn btn-secondary" href="javascript:void(0)">
                        <i class="icon-arrow-down"></i>
                    </a>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </section>
</div>