<script type="text/template" id="tmpl-strategy-manager-row">
    <tr>
        <?php foreach( $columns as $key => $col ){ ?>
            <td class="column-<?php echo $key; ?>">
                <?php
                switch ( $key ) {
                    case 'name':
                        ?>
                        <input name="_managers_<?php echo $key; ?>[]" type="text" placeholder="<?php echo $col['placeholder']; ?>" class="form-control" value="">
                        <?php
                        break;
                    case 'description':
                        ?>
                        <textarea name="_managers_<?php echo $key; ?>[]"></textarea>
                        <?php
                        break;
                    case 'photo_url':
                        ?>
                        <img src="" alt="" style="display: none" width="50" height="50">
                        <input name="_managers_<?php echo $key; ?>[]" type="hidden" value="">
                        <?php
                        break;
                    case 'upload_file':
                        ?>
                        <a href="#" class="button upload_file_button" data-choose="<?php _e('Choose file', 'utheme'); ?>" data-update="<?php _e('Insert file URL', 'utheme'); ?>">
                            <?php _e('Choose&nbsp;photo', 'utheme'); ?>
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
        } ?>
    </tr>
</script>