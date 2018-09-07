<?php
    $head_fields = is_array( $fields ) ? array_shift( $fields ) : [];
    $columns = array_values( $columns );
    $head_col = array_shift( $columns );
    
    if ( is_array( $fields ) && count( $columns ) < count( $fields ) ) {
        $columns = array_pad( $columns, count( $fields ), [
            'type' => 'number',
            'label' => '',
        ] );
    }
?>
<div class="options_group">
    <div class="form-field">
        <?php $related_strategy_name = 'related_strategy_' . $tab_id; ?>
        <label for="<?php echo $related_strategy_name; ?>"><?php _e('Related Strategy Data', 'utheme'); ?></label>
        <select name="_<?php echo $related_strategy_name; ?>" id="<?php echo $related_strategy_name; ?>" class="related-strategy-data" data-holder=".<?php echo str_replace( '_', '-', $table_id ); ?>-holder">
            <option value=""><?php _e( 'None', 'utheme' ); ?></option>
            <?php if ( $existing_strategies ) : ?>
                <?php foreach ( $existing_strategies as $strategy ): ?>
                    <option <?php selected( $strategy->id, $the_strategy->{$related_strategy_name}, true); ?> value="<?php echo $strategy->id;?>"><?php echo $strategy->get_title(); ?><?php echo ( $ticker = $strategy->ticker ) ? ' (' . $ticker . ')' : ''; ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>
    <div class="clear"></div>
</div>
<div class="<?php echo str_replace( '_', '-', $table_id ); ?>-holder">
    <div class="options_group">
        <?php $date_field_name = $tab_id . '_date'; ?>
        <div class="form-field">
            <label for="<?php echo $date_field_name; ?>"><?php _e('Date', 'utheme'); ?></label>
            <input type="text"  name="_<?php echo $date_field_name; ?>" id="<?php echo $date_field_name; ?>"  class="form-control short u-init-date" value="<?php echo  $the_strategy->{$date_field_name}; ?>">
        </div>
    </div>
    <table class="wp-list-table widefat striped u-data-table attributes" id="<?php echo $table_id; ?>">
        <thead>
            <tr>
                <th class="column-0">
                    <input
                            name="_<?php echo $tab_id; ?>[0][]"
                            type="text"
                            class="form-control"
                            placeholder="<?php echo $head_col['placeholder']; ?>"
                            value="<?php echo isset( $head_fields[0] ) ? $head_fields[0] : $head_col['label']; ?>"
                            readonly
                            required>
                </th>
                <?php
                    array_shift( $head_fields );
                    foreach( $head_fields as $col_num => $value ) :
                ?>
                    <th class="column-<?php echo $col_num + 1; ?> accept">
                        <div class="drag-handle dashicons dashicons-leftright"></div>
                        <a href="#" class="delete-table-column" data-container="#<?php echo $table_id; ?>"><span class="dashicons dashicons-no-alt"></span></a>
                        <input
                                name="_<?php echo $tab_id; ?>[0][]"
                                type="text"
                                class="form-control"
                                placeholder="<?php echo $head_col['placeholder']; ?>"
                                value="<?php echo $value; ?>"
                                required>
                    </th>
                <?php endforeach; ?>
                <th class="column-<?php echo $col_num + 1; ?> action">
                    <a href="#" class="add-table-column" data-container="#<?php echo $table_id; ?>"><span class="dashicons dashicons-plus"></span></a>
                </th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ( $columns as $row_index => $row ) : ?>
            <?php $cols = isset( $fields[ $row_index ] ) ? $fields[ $row_index ] : [ $row['label'] ]; ?>
            <tr>
                <?php foreach( $cols as $col_num => $value ) : ?>
                    <td class="column-<?php echo $col_num; ?>">
                    <?php
                        $default_column_atts = [
                                'label' => '',
                                'type' => 'number',
                        ];
                        $col = isset( $columns[ $row_index ] ) ? $columns[ $row_index ] : [];
                        $col = array_merge( $default_column_atts, $col );
                        if ( ! $col_num ) {
                            $col['type'] = 'text';
                        }
                        $name = '_' . $tab_id . '[' . ( $row_index + 1 ) . '][]';
                        $value = ( '' == $value && $col['label'] ) ? $col['label'] : $value;
                        $placeholder = isset( $col['placeholder'] ) ? 'placeholder="' . $col['placeholder'] . '"' : '';
                        $class = isset( $col['class'] ) ? [ 'form-control', $col['class'] ] : [ 'form-control' ];
                        $class = implode( ' ', $class );
                        switch ( $col['type'] ) {
                            case 'text':
                                ?>
                                <input name="<?php echo $name; ?>" type="text" <?php echo $placeholder; ?> class="<?php echo $class; ?>" value="<?php echo $value; ?>">
                                <?php
                                break;
                            case 'textarea':
                                ?>
                                <textarea name="<?php echo $name; ?>" class="<?php echo $class; ?>" ><?php echo $value; ?></textarea>
                                <?php
                                break;
                            case 'select':
                                ?>
                                <select name="<?php echo $name; ?>" class="<?php echo $class; ?>">
                                    <?php
                                    $fv = $value;
                                    if( $col['options'] && is_array($col['options']) ){
                                        foreach ( $col['options'] as $v => $l ){
                                            ?>
                                            <option value="<?php echo $v; ?>" <?php selected($v, $fv, true); ?> ><?php echo $l; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <?php
                                break;
                            case 'number':
                                ?>
                                <input name="<?php echo $name; ?>" type="number" step="any" <?php echo $placeholder; ?> class="<?php echo $class; ?>" value="<?php echo $value; ?>">
                                <?php
                                break;
                            case 'percent':
                                ?>
                                <div class="input-group short">
                                    <input name="<?php echo $name; ?>" <?php echo $placeholder; ?> id="daily_price_ytd_return" type="number" step="0.1" class="<?php echo $class; ?>" value="<?php echo $value; ?>">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                <?php
                                break;
                            case 'upload_file':
                                ?>
                                <a href="#" class="button upload_file_button" data-choose="<?php _e('Choose file', 'utheme'); ?>" data-update="<?php _e('Insert file URL', 'utheme'); ?>">
                                    <?php _e('Choose file', 'utheme'); ?>
                                </a>
                    <?php
                                break;
                        }
                    ?>
                    </td>
                <?php endforeach; ?>
                <td class="column-<?php echo $col_num + 1; ?> action">
                    <a href="#" class="delete"><?php _e('Delete', 'utheme'); ?></a>
                </td>
            </tr>
        <?php
                    $col_num ++;
                endforeach;
        ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="<?php echo count( $columns ); ?>">
                    <div class="toolbar">
                        <button type="button" data-tmpl="#tmpl-strategy-<?php echo $table_id; ?>-row" data-container="#<?php echo $table_id; ?>" class="button add_table_row"><?php _e('Add row', 'utheme'); ?></button>
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>
    <div class="clear"></div>
    <?php $note_name = $tab_id . '_note'; ?>
    <div class="options_group">
        <div class="form-field">
            <label for="<?php echo $note_name; ?>"><?php _e('Note', 'utheme'); ?></label>
            <textarea id="<?php echo $note_name; ?>" name="_<?php echo $note_name; ?>"><?php echo  $the_strategy->{$note_name}; ?></textarea>
        </div>
    </div>
</div>