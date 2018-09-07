<?php
    $columns = array(
        array(
            'title' => __( 'Product Name', 'utheme' ),
            'key'   => 'title',
            'class' => 'max-desktop'
        ),
        array(
            'title' => __( 'Ticker(s)', 'utheme' ),
            'key'   => 'ticker',
            'class' => ' min-tablet'
        ),
        array(
            'title' => __( 'NAV($)', 'utheme' ),
            'key'   => 'monthly_nav',
            'class' => ' min-tablet'
        ),
        array(
            'title' => __( 'Geography', 'utheme' ),
            'key'   => 'geography',
            'class' => 'min-tablet'
        ),
        array(
            'title' => __( 'Category', 'utheme' ),
            'key'   => 'category',
            'class' => 'min-tablet'
        ),
        array(
            'title' => __( 'Holdings Range', 'utheme' ),
            'key'   => 'holdings',
            'class' => 'min-tablet'
        ),
        array(
            'title' => __( 'Avg. Security Weighting', 'utheme' ),
            'key'   => 'weighting',
            'class' => 'min-tablet'
        ),
        array(
            'title' => __( 'Market Cap Range', 'utheme' ),
            'key'   => 'cap_range',
            'class' => 'min-tablet'
        )
    );
    
    if ( $strategies ) :
        $strategies = array_map( 'u_get_strategy', $strategies );
        $found_types = [];
        foreach ($strategies as $strategy){
            array_push($found_types, $strategy->type );
        }
	    $found_types = array_unique($found_types);
     
	    if ( $groups = array_unique( array_column( $strategies, 'group_id' ) ) ) :
?>
        <table class="responsive-table filter-checkbox-table table table-sticky">
            <thead>
                <tr>
                    <?php foreach ( $columns as $column ) : ?>
                        <?php if ( count( $found_types ) > 1 || 'mutual_funds' == $found_types[0] || ( 'managed_accounts' == $found_types[0] && ! in_array( $column['key'], [ 'ticker', 'monthly_nav' ] ) ) ) : ?>
                            <th class="<?php echo isset( $column['class'] ) ? $column['class'] : ''; ?>">
                                <?php echo $column['title']; ?>
                            </th>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
            <?php
                /**
                 * @var $strategy U_Strategy
                 */
                unset( $columns[0] );
                unset( $columns[1] );
                
                foreach ( $groups as $group ) :
                    $group_strategies = array_values( array_filter( $strategies, function( $value ) use ( $group ) {
                        return $group == $value->group_id ? $value : false;
                    } ) );
                
                    if ( $group_strategies ) :
                        $first_strategy = $group_strategies[0];
            ?>
                    <tr>
                        <td class="td-group-right max-desktop">
                            <?php foreach ( $group_strategies as $key => $strategy ) : ?>
                                <div class="strategy-<?php echo $strategy->id; ?>-data u-strategy--data mark-light-green" <?php echo $key > 0 ? 'style="display: none;"' : ''; ?>>
                                    <a href="<?php echo $strategy->get_permalink(); ?>"><?php echo $strategy->get_title(); ?></a>
                                </div>
                            <?php endforeach; ?>
                        </td>
	                    <?php if ( count( $found_types ) > 1 || 'mutual_funds' == $found_types[0] ) : ?>
                            <td class="min-tablet">
                                <?php if ( count( $group_strategies ) > 1 ) : ?>
                                    <div class="sort-form">
                                        <span class="fake-select-holder">
                                            <select class="fake-select switch-strategy-table">
                                                <?php foreach ( $group_strategies as $strategy ) : ?>
                                                    <option value="<?php echo $strategy->id; ?>"><?php echo $strategy->ticker; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </span>
                                    </div>
                                <?php
                                    else :
                                        echo $first_strategy->ticker;
                                    endif;
                                ?>
                            </td>
                        <?php endif; ?>
                        <?php foreach ( $columns as $column ) { ?>
	                        <?php if ( count( $found_types ) > 1 || 'mutual_funds' == $found_types[0] || ( 'managed_accounts' == $found_types[0] && ! in_array( $column['key'], [ 'monthly_nav' ] ) ) ) : ?>
                                <td class="min-tablet">
                                    <?php foreach ( $group_strategies as $_index => $strategy ) : ?>
                                        <div class="strategy-<?php echo $strategy->id; ?>-data u-strategy--data" <?php echo $_index > 0 ? 'style="display: none;"' : ''; ?>>
                                            <?php echo $column['key'] === 'monthly_nav' ? u_price_format( $strategy->{$column['key']} ) : $strategy->{$column['key']}; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </td>
                            <?php endif; ?>
                        <?php } ?>
                    </tr>
            <?php
                    endif;
                endforeach;
            ?>
            </tbody>
        </table>
<?php
        endif;
    endif;
?>