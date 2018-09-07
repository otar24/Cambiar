<script type="text/template" id="tmpl-strategy-<?php echo $tab_id; ?>-row">
    <tr>
        <?php foreach( $columns as $key => $col ){ ?>
            <td class="column-<?php echo $key; ?>">
                <?php
                switch ( $key ) {
                    case 'name':
                        ?>
                        <input name="_<?php echo $prf_type; ?>[0][<?php echo $key; ?>]" type="text" class="form-control" value="">
                        <?php
                        break;
                    case 'action':
                        ?>
                        <a href="#" class="delete"><?php _e('Delete', 'utheme'); ?></a>
                        <?php
                        break;
                    default:
                        ?>
                        <input name="_<?php echo $prf_type; ?>[0][<?php echo $key; ?>]" type="number" step="any" class="form-control" value="">
                        <?php
                        break;
                }
                ?>
            </td>
            <?php
        } ?>
    </tr>
</script>