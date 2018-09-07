<?php
/**
 * Strategies Archive
 *
 * @package		U_Theme/Template
 * @author 		uCAT
 */

$columns = array(
    array(
        'items' => array(
            array(
                'type'  => 'title',
                'title' => __('Product Name', 'utheme'),
                'key'   => 'title',
                'class' => 'td-group-right max-desktop no-sort'
            )
        )
    ),
    array(
        'items' => array(
            array(
                'type'   => 'ticker',
                'title'  => __('Ticker(s)', 'utheme'),
                'key'    => 'ticker',
                'class'  => 'td-group-left min-tablet no-sort'
            ),
            array(
                'title' => __('Category', 'utheme'),
                'key'   => 'category',
                'class'   => 'td-group-right min-tablet no-sort'
            )
        )
    ),
    'downlods' => array(
        'items' => array(
            array(
                'type'   => 'download',
                'title'  => __('Fact Sheet', 'utheme'),
                'key'    => 'fact_sheet',
                'class'  => 'td-group-left min-tablet no-sort'
            ),
            array(
                'type'   => 'download',
                'title' => __('Commentary', 'utheme'),
                'key'   => 'commentary',
                'class'   => 'min-tablet no-sort'
            ),
            array(
                'type'   => 'download',
                'title' => __('Cap Gains & Income', 'utheme'),
                'key'   => 'cap_gains',
                'class'   => 'td-group-right min-tablet no-sort'
            )
        )
    ),
    'annual_total' => array(
        'title'    => __('Performance', 'utheme'),
        'subtitle' => __('as of %s', 'utheme'),
        'items'    => array(
            array(
                'title' => __('QTR', 'utheme'),
                'key'   => 'qtr',
                'class'   => 'td-group-left min-tablet no-sort',
                'callback' => 'u_number_format'
            ),
            array(
                'title' => __('YTD', 'utheme'),
                'key'   => 'ytd',
                'class'   => 'min-tablet no-sort',
                'callback' => 'u_number_format'
            ),
            array(
                'title' => __('1 YR', 'utheme'),
                'key'   => '1yr',
                'class'   => 'min-tablet no-sort',
                'callback' => 'u_number_format'
            ),
            array(
                'title' => __('3 YR', 'utheme'),
                'key'   => '3yr',
                'class'   => 'min-tablet no-sort',
                'callback' => 'u_number_format'
            ),
            array(
                'title' => __('5 YR', 'utheme'),
                'key'   => '5yr',
                'class'   => 'min-tablet no-sort',
                'callback' => 'u_number_format'
            ),
            array(
                'title' => __('10 YR', 'utheme'),
                'key'   => '10yr',
                'class'   => 'min-tablet no-sort',
                'callback' => 'u_number_format'
            ),
            array(
                'title' => __('Since Inception', 'utheme'),
                'key'   => 'incpt',
                'class'   => 'min-tablet no-sort',
                'callback' => 'u_number_format'
            ),
            array(
                'title' => __('Inception Date', 'utheme'),
                'key'   => 'inception_date',
                'class'   => 'min-tablet no-sort'
            ),
            array(
                'type'    => 'href',
                'title'   => '',
                'key'     => '',
                'class'   => 'min-tablet no-sort display-xs-visible'
            ),
        )
    )
);
$strategy_types     = u_get_strategy_types();
$strategy_type_keys = array_keys( $strategy_types );
$groups             = u_get_strategy_groups( true );

$group_keys         = array_keys( $groups );
$first_strategy = null;
while ( empty( $first_strategy ) ) {
	$first_strategy = u_get_strategies( array(
        'type'  => $strategy_type_keys[0],
        'group' => array_shift( $group_keys ),
        'orderby' => 'menu_order',
        'order' => 'asc',
	) );
}
$first_strategy = array_shift( $first_strategy );
get_header(); ?>
<div id="main" class="no-autoscroll">
    <section class="section-strategies">
        <div class="tabset-holder">
            <ul class="tabset text-uppercase">
                <?php $i = 0; foreach ( $strategy_types as $type_key => $type_name ): ?>
                    <li><a href="#<?php echo $type_key; ?>" <?php $i === 0 ? 'class="active"' : ''; ?>><?php echo $type_name; ?></a></li>
                    <?php $i++; endforeach; ?>
            </ul>
        </div>
        <div class="tab-content">
            <?php foreach ( $strategy_types as $type_key => $type_name ): ?>

            <div id="<?php echo $type_key; ?>">
                <div class="tab-holder">
                    <div class="text-title-wrap">
                        <div class="container">
                            <header class="section-header">
                                <h1 class="title text-uppercase"><?php _e('Strategies', 'utheme'); ?></h1>
                            </header>
                            <?php if ( $top_content = get_option('u_mutual_funds_top_content') ) : ?>
                                <div class="text-holder">
                                    <?php echo apply_filters( 'the_content', $top_content ); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="table-wrap">
                        <div class="container-fluid">
                            <div class="scroll-holder">
                                <div class="scroll-wrap">
                                    <ul class="tabset-table pseudo-tabset text-uppercase display-xs-hidden">
                                        <li><a href="#" data-type="monthly" class="change-qtr-mth"><?php _e( 'Monthly', 'utheme' ); ?></a></li>
                                        <li><a href="#" data-type="quarterly" class="active change-qtr-mth"><?php _e( 'Quarterly', 'utheme' ); ?></a></li>
                                    </ul>
                                    <div class="tab-content">

                                        <div id="table-аа">
                                            <table class="responsive-table filter-checkbox-table table table-sticky mod" data-ordering="false">
                                                <thead>
													<tr>
														<?php
                                                            $p = 0;
                                                            foreach ($columns as $column_gr_key => $column_group ){
                                                                $colspan = ( ( 1 === $column_gr_key || 'downlods' == $column_gr_key ) && in_array( $type_key, [ 'managed_accounts' ] ) ) ? count($column_group['items']) - 1 : count($column_group['items']);
														?>
                                                            <th <?php echo $colspan > 1 ? 'colspan="'.$colspan.'"' : ''; ?> class="<?php echo ( $p == 0 ) ? 'td-group-right max-desktop' : 'td-group min-tablet';?> no-sort text-left">
                                                                <?php if ( isset( $column_group['title'] ) ) : ?>
                                                                    <div class="title"><?php echo $column_group['title']; ?></div>
                                                                <?php endif; ?>
                                                                
                                                                <?php if ( isset( $column_group['subtitle'] ) ) : ?>
                                                                    <div class="subtitle">
                                                                        <?php if ( 'annual_total' == $column_gr_key ) : ?>
                                                                            <?php if ( $perfomance_monthly_date = $first_strategy->perfomance_monthly_date ) : ?>
                                                                                <span class="strtg-mth-data"><?php printf( __( $column_group['subtitle'], 'utheme' ), $perfomance_monthly_date ); ?></span>
                                                                            <?php endif; ?>
			
			                                                                <?php if ( $perfomance_quarterly_date = $first_strategy->perfomance_quarterly_date ) : ?>
                                                                                <span class="strtg-qtr-data"><?php printf( __( $column_group['subtitle'], 'utheme' ), $perfomance_quarterly_date ); ?></span>
                                                                            <?php endif; ?>
                                                                        <?php else : ?>
                                                                            <?php echo $column_group['subtitle']; ?>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </th>
														<?php
                                                                $p++;
                                                            }
                                                        ?>
													</tr>
													<tr>
														<?php
														foreach ($columns as $column_gr_key => $column_group ){
															foreach ($column_group['items'] as $column ){
															    $class = isset($column['class']) ? $column['class'] : 'td-group';
																$columntype = isset($column['type']) ? $column['type'] : '';
																$columnkey = isset($column['key']) ? $column['key'] : '';
																if ( ( 'ticker' == $columntype || ( 'download' == $columntype && 'cap_gains' == $columnkey ) ) && in_array( $type_key, [ 'managed_accounts' ] ) ) {
																    continue;
                                                                }
															?>
															<th class="<?php echo $class; ?>">
															<?php
																if($column_gr_key == 'annual_total' && $column['key'] == 'qtr'){
																	?>
																	<span class="strtg-mth-data"><?php _e('MTD', 'utheme'); ?></span>
																	<span class="strtg-qtr-data"><?php echo $column['title']; ?></span>
																	<?php
																}else{
																	echo $column['title'];
																}
															?>
															</th>
															<?php
															}
														} ?>
													</tr>
                                                </thead>
                                                <tbody>
                                                <?php if( $groups ){
                                                    foreach ($groups as $group_id => $group_title ){
                                                        $strategies = u_get_strategies( array(
                                                            'type' => $type_key,
                                                            'group' => $group_id,
                                                            'orderby' => 'menu_order',
                                                            'order' => 'desc',
                                                        ) );
                                                        if( !$strategies ) continue;
                                                        $strtg = $strategies[0];
                                                        ?>
                                                    <tr>
                                                    <?php
                                                    $i = 0; foreach ($columns as $column_gr_key => $column_group ){
                                                        foreach ($column_group['items'] as $column ){
                                                            $class      = isset($column['class']) ? $column['class'] : 'td-group';
                                                            $columntype = isset($column['type']) ? $column['type'] : '';
                                                            $columnkey = isset($column['key']) ? $column['key'] : '';
                                                            $columnwidth = '';
                                                            if( $columntype === 'title' ){
                                                                $columnwidth = ' style="width: 280px"';
                                                            } elseif( $columntype === 'download' ) {
                                                                $columnwidth = ' style="width: 130px"';
                                                            }
                                                            
                                                            if ( ( 'ticker' == $columntype || ( 'download' == $columntype && 'cap_gains' == $columnkey ) ) && in_array( $type_key, [ 'managed_accounts' ] ) ) {
                                                                continue;
                                                            }
                                                            ?>
                                                            <td class="<?php echo $class; ?><?php echo ($i==0) ? 'max-desktop' : 'min-tablet';?>"<?php if( $columnwidth ){ echo $columnwidth;  } ?>>
                                                                <?php if( $columntype === 'title' ) : ?>
                                                                    <?php foreach ($strategies as $_index => $strategy) : ?>
                                                                        <div class="strategy-<?php echo $strategy->id; ?>-data u-strategy--data mark-royal-blue"<?php echo $_index > 0 ? ' style="display: none;"' : ''; ?>>
                                                                            <a href="<?php echo $strategy->get_permalink(); ?>"><?php echo $strategy->get_title(); ?></a>
                                                                        </div>
                                                                    <?php endforeach; ?>
                                                                <?php
                                                                    elseif ( $columntype === 'ticker' ) :

                                                                        if ( count( $strategies ) > 1 ) :
                                                                ?>
                                                                        <div class="sort-form">
                                                                            <span class="fake-select-holder">
                                                                                <select class="fake-select switch-strategy-table">
                                                                                    <?php foreach ($strategies as $_strtg){ ?>
                                                                                    <option value="<?php echo $_strtg->id; ?>"><?php echo $_strtg->ticker; ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </span>
                                                                        </div>
                                                                <?php
                                                                        else :
                                                                            echo $strtg->ticker;
                                                                        endif;
                                                                    else :
                                                                        foreach ($strategies as $_index => $strategy){
                                                                ?>
                                                                        <div class="strategy-<?php echo $strategy->id; ?>-data u-strategy--data" <?php echo $_index > 0 ? 'style="display: none;"' : ''; ?>>
                                                                        <?php
                                                                            if( $columntype === 'download' ) {
                                                                                $url = $strategy->get_document_url($column['key']);
                                                                                if( $url ){
                                                                                    ?>
                                                                                    <a download="" href="<?php echo $url; ?>"><i class="icon-download"></i> <?php _e('Download', 'utheme' ); ?></a>
                                                                                <?php } ?>
                                                                                <?php
                                                                            } elseif( $columntype === 'href') { ?>
                                                                                <a href="<?php echo $strategy->get_permalink(); ?>" class="btn btn-secondary text-uppercase"><?php _e('Learn More', 'utheme' ); ?></a>
                                                                                <?php
                                                                            } elseif ( $column_gr_key == 'annual_total' && $column['key'] != 'inception_date' ){
                                                                                $m_key = 'monthly';
                                                                                $q_key = 'quarterly';
                                                                                $m_val = array_values( $strategy->{$m_key} );
                                                                                $q_val = array_values( $strategy->{$q_key} );
                                                                                
                                                                                if ( 'managed_accounts' == $type_key ) {
                                                                                    if ( count( $m_val ) > 1 ) {
                                                                                        // get the second row if exists
                                                                                        $m_val = $m_val[1];
                                                                                    } else {
                                                                                        // get the first row
                                                                                        $m_val = array_shift( $m_val );
                                                                                    }
                                                                                    
                                                                                    if ( count( $q_val ) > 1 ) {
	                                                                                    // get the second row if exists
                                                                                        $q_val = $q_val[1];
                                                                                    } else {
	                                                                                    // get the first row
                                                                                        $q_val = array_shift( $q_val );
                                                                                    }
                                                                                } else {
	                                                                                // get the first row
                                                                                    $m_val = array_shift( $m_val );
                                                                                    $q_val = array_shift( $q_val );
                                                                                }
                                                                                
                                                                                if( isset($column['callback']) ){
                                                                                    $m_val = call_user_func($column['callback'], $m_val[ $column['key'] ]);
                                                                                    $q_val = call_user_func($column['callback'], $q_val[ $column['key'] ]);
                                                                                }
                                                                                ?>
                                                                                <span class="strtg-mth-data"><?php echo $m_val; ?></span>
                                                                                <span class="strtg-qtr-data"><?php echo $q_val; ?></span>
                                                                                <?php
                                                                            }else{
                                                                                if( isset($column['callback']) ){
                                                                                    echo call_user_func($column['callback'], $strategy->{$column['key']});;
                                                                                }else{
                                                                                    echo $strategy->{$column['key']};
                                                                                }
                                                                            }
                                                                        ?>
                                                                        </div>
                                                                <?php
                                                                        }
                                                                    endif;
                                                                ?>
                                                            </td>
                                                            <?php
                                                        }
                                                    } ?>
                                                    </tr>
                                                <?php }
                                                } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
	                <?php if ( $disclosures = get_option( 'u_' . $type_key . '_disclosures' ) ) : ?>
                        <div class="text-box-wrap">
                            <div class="container">
                                <div class="collapse-box">
                                    <div class="title text-uppercase"><?php _e( 'Disclosures', 'utheme' ); ?></div>
                                    <div class="collapse-holder">
                                        <div class="text-box collapse">
                                            <div class="text">
                                                <?php echo apply_filters( 'the_content', $disclosures ); ?>
                                            </div>
                                        </div>
                                        <a class="collapse-btn btn btn-secondary">
                                            <i class="icon-arrow-down"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php endforeach; ?>

        </div>
    </section>
</div>
<?php get_footer();
