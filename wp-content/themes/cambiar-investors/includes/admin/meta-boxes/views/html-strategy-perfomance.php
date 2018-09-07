<?php
$columns = U_Meta_Box_Strategy_Data::get_perfomance_columns();
$columns['action'] = '&nbsp;';

$prf_type = $tab_id == 'perfomance_monthly' ? 'monthly' : 'quarterly';

$rows = $the_strategy->$prf_type;
$name_y_axis_min = $prf_type . '_y_axis_min';
$name_y_axis_max = $prf_type . '_y_axis_max';
$name_y_axis_step = $prf_type . '_y_axis_step';
?>
    <div class="options_group">
        <div class="form-field">
            <label for="<?php echo $name_y_axis_min; ?>"><?php _e('Y-axis Min', 'utheme'); ?></label>
            <input type="number" step="any" name="_<?php echo $name_y_axis_min; ?>" id="<?php echo $name_y_axis_min; ?>"  class="form-control short" value="<?php echo  ( $y_min = $the_strategy->{$name_y_axis_min} ) ? $y_min : 0; ?>">
        </div>
        <div class="form-field">
            <label for="<?php echo $name_y_axis_max; ?>"><?php _e('Y-axis Max', 'utheme'); ?></label>
            <input type="number" step="any" name="_<?php echo $name_y_axis_max; ?>" id="<?php echo $name_y_axis_max; ?>"  class="form-control short" value="<?php echo  ( $y_max = $the_strategy->{$name_y_axis_max} ) ? $y_max : 30; ?>">
        </div>
        <div class="form-field">
            <label for="<?php echo $name_y_axis_step; ?>"><?php _e('Y-axis step', 'utheme'); ?></label>
            <input type="number" step="any" name="_<?php echo $name_y_axis_step; ?>" id="<?php echo $name_y_axis_step; ?>"  class="form-control short" value="<?php echo  ( $y_step = $the_strategy->{$name_y_axis_step} ) ? $y_step : 10; ?>">
        </div>
    </div>
    <table class="wp-list-table widefat striped u-data-table" id="<?php echo $tab_id; ?>_table">
        <thead>
            <tr>
                <?php foreach( $columns as $key => $label ){
                    if( $key === 'qtr' && $prf_type == 'monthly'){
                        $label = __( 'MTD', 'utheme' );
                    }
                    ?>
                    <th  class="column-<?php echo $key; ?>" ><?php echo $label; ?></th>
                    <?php
                } ?>
            </tr>
        </thead>
        <tbody>
            <?php if ( ! empty( $rows ) ) : ?>
                <?php foreach ( $rows as $row_key => $row ) : ?>
                    <?php if ( ! empty( $row ) ) : ?>
                        <tr>
                            <?php foreach( $row as $key => $col ) : ?>
                                <td class="column-<?php echo $key; ?>">
                                    <?php
                                        switch ( $key ) {
                                            case 'name':
                                                echo '<input id="' . $prf_type .'_' . $key . '" name="_' . $prf_type . '[' . $row_key . '][' . $key . ']" type="text" class="form-control" value="' . $col . '">';
                                                break;
                                            default:
                                                echo '<input id="' . $prf_type . '_' . $key . '" name="_' . $prf_type . '[' . $row_key . '][' . $key . ']" type="number" step="any" class="form-control" value="' . $col . '">';
                                        }
                                    ?>
                                </td>
                            <?php endforeach; ?>
                            <td class="column-action">
                                <a href="#" class="delete"><?php _e( 'Delete', 'utheme' ); ?></a>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="clear"></div>
    <div class="toolbar">
        <button type="button" data-tmpl="#tmpl-strategy-<?php echo $tab_id; ?>-row"  data-container="#<?php echo $tab_id; ?>_table" class="button add_document_row"><?php _e('Add row', 'utheme'); ?></button>
    </div>

<?php
include 'tmpl/tmpl-strategy-perfomance-row.php';