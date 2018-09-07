<?php if ( in_array( $table_id, [ 'top_10_holdings', 'sector_weights', 'top_countries' ] ) ) : ?>
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
<?php endif; ?>

<div class="<?php echo str_replace( '_', '-', $table_id ); ?>-holder">
    <?php if ( in_array( $table_id, [ 'top_10_holdings', 'sector_weights', 'risk_statistics', 'top_countries' ] ) ) : ?>
        <div class="options_group">
            <?php $date_field_name = $tab_id . '_date'; ?>
            <div class="form-field">
                <label for="<?php echo $date_field_name; ?>"><?php _e('Date', 'utheme'); ?></label>
                <input type="text"  name="_<?php echo $date_field_name; ?>" id="<?php echo $date_field_name; ?>"  class="form-control short u-init-date" value="<?php echo  $the_strategy->{$date_field_name}; ?>">
            </div>
        </div>
    <?php endif; ?>
    <table class="wp-list-table widefat striped u-data-table" id="<?php echo $table_id; ?>">
        <thead>
            <tr>
                <?php
                    $col_num = 0;
                    $row_num = 0;
                    
                    foreach( $columns as $key => $col ){
                ?>
                    <th  class="column-<?php echo $key; ?><?php echo ( in_array( $table_id, [ 'top_countries' ] ) && ! in_array( $key, [ 'name', 'country', 'action' ] ) ) ? ' accept' : ''; ?>">
                        <?php if ( in_array( $table_id, [ 'top_countries' ] ) && ! in_array( $key, [ 'action' ] ) ) : ?>
                            <?php if ( ! in_array( $key, [ 'name', 'country' ] ) ) : ?>
                                <div class="drag-handle dashicons dashicons-leftright"></div>
                            <?php endif; ?>
                            <input name="_<?php echo $tab_id; ?>[<?php echo $col_num; ?>][]" type="text" class="<?php echo $class; ?>" value="<?php echo isset( $fields[0][ $row_num ] ) ? $fields[0][ $row_num ] : $col['label']; ?>"<?php echo in_array( $key, [ 'name', 'country' ] ) ? ' readonly' : ''; ?> required>
                        <?php else : ?>
                            <?php echo $col['label']; ?>
                        <?php endif; ?>
                    </th>
                <?php
                        $row_num ++;
                    }
                    
                    $col_num ++
                ?>
            </tr>
        </thead>
        <tbody>
    
            <?php
            if ( in_array( $table_id, [ 'top_countries' ] ) && is_array( $fields ) ) {
                array_shift( $fields );
            }
            
            if( $fields && is_array($fields) ){
                foreach ($fields as $field_index => $field){
            ?>
            <tr>
                <?php
                    $row_num = 0;
                    foreach( $columns as $key => $col ){
                ?>
                    <td class="column-<?php echo $key; ?>">
                        <?php
                        $name = '_' . $tab_id . '_' . $key . '[]';
                        $value = isset( $field[ $key ] ) ? $field[ $key ] : '';
                        if ( in_array( $table_id, [ 'top_countries' ] ) ) {
                            $name = '_' . $tab_id . '[' . $col_num . '][]';
                            $value = isset( $fields[ $field_index ][ $row_num ] ) ? $fields[ $field_index ][ $row_num ] : '';
                        }
                        
                        $placeholder = isset($col['placeholder']) ? 'placeholder="'.$col['placeholder'].'"' : '';
    
                        $class = isset($col['class']) ? ['form-control', $col['class']] : ['form-control'];
                        $class = implode(' ', $class);
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
                                <input name="<?php echo $name; ?>" type="number" step="<?php echo 'cap_gains' == $table_id ? '0.0001' : '0.01'; ?>" <?php echo $placeholder; ?> class="<?php echo $class; ?>" value="<?php echo $value; ?>">
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
                                    <?php _e('Choose&nbsp;file', 'utheme'); ?>
                                </a>
                                <?php
                                break;
                            case 'action':
                                ?>
                                <a href="#" class="delete"><?php _e('Delete', 'utheme'); ?></a>
                                <?php
                                break;
                        }
                        ?>
                    </td>
                    <?php
                        $row_num ++;
                } ?>
            </tr>
            <?php $col_num ++; }
            } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="<?php echo count( $columns ); ?>">
                    <div class="toolbar">
                        <button type="button" data-tmpl="#tmpl-strategy-<?php echo $table_id; ?>-row"  data-container="#<?php echo $table_id; ?>" class="button add_table_row"><?php _e('Add row', 'utheme'); ?></button>
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>
    <div class="clear"></div>
    <?php
        $tpl_id = 'tmpl-strategy-'.$table_id.'-row';
        include 'tmpl/tmpl-table-row.php';
        
        if ( in_array( $tab_id, [ 'top_10_holdings', 'sector_weights', 'attributes', 'risk_statistics', 'top_countries' ] ) ) :
            $note_name = $tab_id . '_note';
    ?>
        <div class="options_group">
            <div class="form-field">
                <label for="<?php echo $note_name; ?>"><?php _e('Note', 'utheme'); ?></label>
                <textarea id="<?php echo $note_name; ?>" name="_<?php echo $note_name; ?>"><?php echo  $the_strategy->{$note_name}; ?></textarea>
            </div>
            <?php if ( in_array( $tab_id, [ 'top_10_holdings' ] ) ) : ?>
                <div class="form-field">
                    <label for="_top_10_holdings_disclosure"><?php _e('Disclosure', 'utheme'); ?></label>
                    <?php
                    wp_editor( $the_strategy->top_10_holdings_disclosure, '_top_10_holdings_disclosure', $settings = array('media_buttons' => false, 'editor_height' => 250) ); ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>