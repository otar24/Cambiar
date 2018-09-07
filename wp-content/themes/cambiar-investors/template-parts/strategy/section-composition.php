<?php
/**
 * @global U_Strategy $the_strategy
 */
global $the_strategy;

$top_10_holdings_related_strategy = $the_strategy->getRelatedStrategy( 'top_10_holdings' );
$top_10_holdings                  = $top_10_holdings_related_strategy
	? $top_10_holdings_related_strategy->top_10_holdings
	: $the_strategy->top_10_holdings;
$top_10_holdings_date             = $top_10_holdings_related_strategy
	? $top_10_holdings_related_strategy->top_10_holdings_date
	: $the_strategy->top_10_holdings_date;
$top_10_holdings_note             = $top_10_holdings_related_strategy
	? $top_10_holdings_related_strategy->top_10_holdings_note
	: $the_strategy->top_10_holdings_note;
$top_10_holdings_disclosure       = $top_10_holdings_related_strategy
	? $top_10_holdings_related_strategy->top_10_holdings_disclosure
	: $the_strategy->top_10_holdings_disclosure;

$sector_weights_related_strategy = $the_strategy->getRelatedStrategy( 'sector_weights' );
$sector_weights                  = $sector_weights_related_strategy
	? $sector_weights_related_strategy->sector_weights
	: $the_strategy->sector_weights;
$sector_weights_date             = $sector_weights_related_strategy
	? $sector_weights_related_strategy->sector_weights_date
	: $the_strategy->sector_weights_date;
$sector_weights_note             = $sector_weights_related_strategy
	? $sector_weights_related_strategy->sector_weights_note
	: $the_strategy->sector_weights_note;

$attributes_related_strategy = $the_strategy->getRelatedStrategy( 'attributes' );
$attributes                  = $attributes_related_strategy
	? $attributes_related_strategy->attributes
	: $the_strategy->attributes;
$attributes_date             = $attributes_related_strategy
	? $attributes_related_strategy->attributes_date
	: $the_strategy->attributes_date;
$attributes_note             = $attributes_related_strategy
	? $attributes_related_strategy->attributes_note
	: $the_strategy->attributes_note;

$risk_statistics      = $the_strategy->risk_statistics;
$risk_statistics_date = $the_strategy->risk_statistics_date;
$risk_statistics_note = $the_strategy->risk_statistics_note;

$top_countries_related_strategy = $the_strategy->getRelatedStrategy( 'top_countries' );
$top_countries                  = $top_countries_related_strategy
	? $top_countries_related_strategy->top_countries
	: $the_strategy->top_countries;
$top_countries_date             = $top_countries_related_strategy
	? $top_countries_related_strategy->top_countries_date
	: $the_strategy->top_countries_date;
$top_countries_note             = $top_countries_related_strategy
	? $top_countries_related_strategy->top_countries_note
	: $the_strategy->top_countries_note;

$active_share = $the_strategy->active_share;

if ( ! $top_10_holdings && ! $sector_weights && ! $attributes && ! $risk_statistics && ! $active_share ) {
	return;
}

$holdings_columns = U_Meta_Box_Strategy_Data::get_holdings_columns();
?>
<div class="fp-block fp-auto-height" data-anchor="composition">
    <section class="section-fund">
        <div class="container">
            <header class="section-header">
                <h1 class="title text-uppercase"><?php _e('Composition', 'utheme'); ?></h1>
            </header>
            <div class="columns align-start">
                <?php if ( $top_10_holdings && is_array( $top_10_holdings ) ) : ?>
                    <div class="columns-item">
                        <div class="diagramm-legend">
                            <div class="chart-header">
                                <h2 class="chart-title h6 text-uppercase"><?php _e('Top 10 holdings', 'utheme'); ?><?php echo $top_10_holdings_note ? '*' : ''; ?></h2>
                                <?php if ( $top_10_holdings_date ) : ?>
                                    <div class="chart-subtitle">
                                        <p><?php printf( __( 'as of %s', 'utheme' ), $top_10_holdings_date ); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="table-holder">
                            <table class="legend-table">
                            <thead>
                                <tr>
                                    <th colspan="3" class="text-right"><?php _e( '% Weight', 'utheme' ); ?></th>
                                </tr>
                            </thead>
                                <tbody>
                                <?php
                                    $colors         = u_get_colors();
                                    $colors_count   = count( $colors );
                                    $holdings_count = count( $top_10_holdings );
                                    $percent        = absint( 100 / $holdings_count ) / 100;
                                    $color          = $holdings_count > $colors_count ? '#00457c' : $colors[0];
                                    $i              = 0;
                                    foreach ( $top_10_holdings as $holding ): $i ++;
                                ?>
                                    <tr>
                                        <td>
                                            <div style="background: <?php echo $color; ?> ">
                                                <?php echo $i > 9 ? $i : '0'.$i ?>
                                            </div>
                                        </td>
                                        <td><?php echo esc_attr( $holding['name'] ); ?></td>
                                        <td ><?php echo $holding['data']; ?></td>
                                    </tr>
                                <?php
                                        $color = $holdings_count > $colors_count ? u_luminance($color, $percent) : $colors[$i];
                                    endforeach;
                                ?>
                                </tbody>
                            </table>
                            <?php if ( $top_10_holdings_note || $top_10_holdings_disclosure ) : ?>
                                <div class="table-note">
                                    <p><?php echo esc_attr( $top_10_holdings_note ); ?></p>

                                    <?php echo apply_filters( 'the_content', $top_10_holdings_disclosure ); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="columns-item">
                        <div class="composition-round-diagram">
                            <canvas id="holdChart"  data-chartopt="top_holdings"  style="width: 100%; height: 315px" ></canvas>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if( $sector_weights && is_array( $sector_weights ) ) : ?>
                    <div class="columns-item column-2">
                        <div class="diagramm-legend">
                            <div class="chart-header">
                                <h2 class="chart-title h6 text-uppercase"><?php _e('Sector Weights (%)', 'utheme'); ?><?php echo $sector_weights_note ? '*' : ''; ?></h2>
                                <?php if ( $sector_weights_date ) : ?>
                                    <div class="chart-subtitle">
                                        <p><?php printf( __( 'as of %s', 'utheme' ), $sector_weights_date ); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="composition-horisonBar-diagram">
                            <canvas id="sectorChart" class="performance-diagr-horisontBarChart" data-chartopt="sector" style="width: 100%; height: 360px" ></canvas>
                        </div>
                        <?php if ( $sector_weights_note ) : ?>
                            <table>
                                <tfoot>
                                    <td><?php echo esc_attr( $sector_weights_note ); ?></td>
                                </tfoot>
                            </table>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="columns">
                <?php if( $active_share || ( $attributes && is_array( $attributes ) ) ) : ?>
                    <div class="columns-item">
                        <?php
                            if ( $attributes && is_array( $attributes ) ) :
                                $table_head = array_shift( $attributes );
                                array_shift( $table_head );
                        ?>
                            <div class="diagramm-legend">
                                <div class="chart-header">
                                    <h2 class="chart-title h6 text-uppercase"><?php _e('Attributes', 'utheme'); ?><?php echo $attributes_note ? '*' : ''; ?></h2>
                                    <?php if ( $attributes_date ) : ?>
                                        <div class="chart-subtitle">
                                            <p><?php printf( __( 'as of %s', 'utheme' ), $attributes_date ); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="table-holder">
                                <table id="attributes-table" class="display-table" data-ordering="false">
                                    <thead>
                                        <tr>
                                            <th class="no-sort"></th>
                                            <?php foreach ( $table_head as $value ) : ?>
                                                <th class="no-sort"><?php echo $value; ?></th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ( $attributes as $row_key => $row ) : ?>
                                        <?php
                                            $first_col = array_shift( $row );
                                            $values = array_filter( $row, function( $el ){
                                                return '' != $el;
                                            } );
                                            
                                            if ( ! empty( $values ) ) :
	                                            $values = array_pad( $values, count( $row ), '-' );
                                        ?>
                                            <tr>
                                                <td><?php echo esc_attr( $first_col ); ?></td>
                                                <?php foreach ( $values as $value ) : ?>
                                                    <td><?php echo $value; ?></td>
                                                <?php endforeach; ?>
                                            </tr>
                                    <?php
                                            endif;
                                        endforeach;
                                    ?>
                                    </tbody>
                                    <?php if ( $attributes_note ) : ?>
                                        <tfoot>
                                            <tr>
                                                <td colspan="<?php echo count( $attributes ) + 1; ?>"><?php echo esc_attr( $attributes_note ); ?></td>
                                            </tr>
                                        </tfoot>
                                    <?php endif; ?>
                                </table>
                            </div>
                        <?php
                            endif;
                            
                            if ( $active_share ) :
                        ?>
                            <div class="chart-instance">
                                <div class="chart-header">
                                    <h2 class="chart-title h6 text-uppercase"><?php _e('Active Share', 'utheme'); ?></h2>
	                                <?php if ( $active_share_date = $the_strategy->active_share_date ) : ?>
                                        <div class="chart-subtitle">
                                            <p><?php printf( __( 'as of %s', 'utheme' ), $active_share_date ); ?></p>
                                        </div>
	                                <?php endif; ?>
                                </div>
                                <div class="composition-round-diagram">
                                    <canvas id="shareChart" data-chartopt="active_share" style="width: 100%; height: 315px" ></canvas>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            
                <?php if( $risk_statistics || count( $top_countries ) > 1 ): ?>
                    <div class="columns-item">
                        <?php if( $risk_statistics && is_array($risk_statistics) ) : ?>
                        <div class="diagramm-legend">
                            <div class="chart-header">
                                <h2 class="chart-title h6 text-uppercase"><?php _e('RISK STATISTICS', 'utheme'); ?><?php echo $risk_statistics_note ? '*' : ''; ?></h2>
                                <?php if ( $risk_statistics_date ) : ?>
                                    <div class="chart-subtitle">
                                        <p><?php printf( __( 'as of %s', 'utheme' ), $risk_statistics_date ); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="table-holder">
                            <table class="display-table">
                                <thead>
                                    <tr>
                                        <th class="no-sort"></th>
                                        <?php foreach ( $risk_statistics as $risk ): ?>
                                            <th class="no-sort"><?php echo $risk['name']; ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $rows = U_Meta_Box_Strategy_Data::get_risk_statistics_columns();
                                    unset($rows['name']);
                                    unset($rows['action']);
                                    foreach ( $rows as $row_key => $row):
                                       if ( $row_risks_statistics = array_filter( array_column( $risk_statistics, $row_key ), function( $el ){
	                                       return '' != $el;
                                       }  ) ) :
                                           $row_risks_statistics = array_pad( $row_risks_statistics, count( $risk_statistics ), '-' );
                                ?>
                                    <tr>
                                        <td><?php echo esc_attr( $row['label'] ); ?></td>
                                        <?php foreach ( $row_risks_statistics as $risk ) : ?>
                                            <td><?php echo $risk; ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php
                                        endif;
                                    endforeach;
                                ?>
                                </tbody>
                                <?php if ( $risk_statistics_note ) : ?>
                                    <tfoot>
                                        <tr>
                                            <td colspan="<?php echo count( $risk_statistics ) + 1; ?>"><?php echo esc_attr( $risk_statistics_note ); ?></td>
                                        </tr>
                                    </tfoot>
                                <?php endif; ?>
                            </table>
                        </div>
                        <?php endif; ?>
                        
                        <?php if( $top_countries && count( $top_countries ) > 1 ): ?>
                            <div class="chart-instance">
                                <div class="diagramm-legend">
                                    <div class="chart-header">
                                        <h2 class="chart-title h6 text-uppercase"><?php _e('Top 10 Countries (%)', 'utheme'); ?><?php echo $top_countries_note ? '*' : ''; ?></h2>
                                        <?php if ( $top_countries_date ) : ?>
                                            <div class="chart-subtitle">
                                                <p><?php printf( __( 'as of %s', 'utheme' ), $top_countries_date ); ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="composition-horisonBar-diagram">
                                    <canvas id="countriesChart" class="performance-diagr-horisontBarChart"  data-chartopt="countries" style="width: 100%; height: 296px" ></canvas>
                                </div>
                                <?php if ( $top_countries_note ) : ?>
                                    <table>
                                        <tfoot>
                                            <td><?php echo esc_attr( $top_countries_note ); ?></td>
                                        </tfoot>
                                    </table>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>