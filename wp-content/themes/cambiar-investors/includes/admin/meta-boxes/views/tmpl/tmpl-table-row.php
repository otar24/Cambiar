<script type="text/template" id="<?php echo $tpl_id; ?>">
    <tr data-name="_<?php echo $tab_id; ?>[<?php echo $col_num; ?>][]">
        <?php foreach( $columns as $key => $col ){ ?>
            <td class="column-<?php echo $key; ?>">
                <?php
                $name = '_' . $tab_id . '_' . $key . '[]';
                if ( in_array( $table_id, [ 'top_countries' ] ) ) {
	                $name = '_' . $tab_id . '[' . $col_num . '][]';
                }
                $placeholder = isset($col['placeholder']) ? 'placeholder="'.$col['placeholder'].'"' : '';

                $class = isset($col['class']) ? ['form-control', $col['class']] : ['form-control'];
                $class = implode(' ', $class);
                switch ( $col['type'] ) {
                    case 'text':
                        ?>
                        <input name="<?php echo $name; ?>" type="text" <?php echo $placeholder; ?> class="<?php echo $class; ?>" value="">
                        <?php
                        break;
                    case 'textarea':
                        ?>
                        <textarea name="<?php echo $name; ?>" class="<?php echo $class; ?>" ></textarea>
                        <?php
                        break;
                    case 'select':
                        ?>
                        <select name="<?php echo $name; ?>" class="<?php echo $class; ?>">
                            <?php
                            if( $col['options'] && is_array($col['options']) ){
                                foreach ( $col['options'] as $v => $l ){
                                    ?>
                                    <option value="<?php echo $v; ?>" ><?php echo $l; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                        <?php
                        break;
                    case 'number':
                        ?>
                        <input name="<?php echo $name; ?>" type="number" step="0.01" <?php echo $placeholder; ?> class="<?php echo $class; ?>" value="">
                        <?php
                        break;
	                case 'percent':
		                ?>
                        <div class="input-group short">
                            <input name="<?php echo $name; ?>" <?php echo $placeholder; ?> id="daily_price_ytd_return" type="number" step="0.1" class="<?php echo $class; ?>" value="">
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
        }
        $col_num ++;
        ?>
    </tr>
</script>