<script type="text/template" id="tmpl-strategy-document-row">
    <tr>
        <?php foreach( $columns as $key => $col ){ ?>
            <td class="column-<?php echo $key; ?>">
                <?php
                switch ( $key ) {
                    case 'file_name':
                    case 'file_url':
                        ?>
                        <input name="_additional_doc_<?php echo $key; ?>[]" type="text" placeholder="<?php echo $col['placeholder']; ?>" class="form-control" value="">
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
        } ?>
    </tr>
</script>