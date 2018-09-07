<?php
$columns = [
    'file_name' => [
        'label'       => __( 'Name', 'utheme' ),
        'placeholder' => __( 'File name', 'utheme' )
    ],
    'file_url' => [
        'label'       => __( 'File URL', 'utheme' ),
        'placeholder' => __( 'http://', 'utheme' )
    ],
    'upload_file' => [
        'label'       => '&nbsp;'
    ],
    'action' => [
        'label'       => '&nbsp;'
    ],
];
$fields = $the_strategy->get_documents_list();
?>
    <table class="wp-list-table widefat striped u-data-table" id="documents_table">
        <thead>
        <tr>
            <?php foreach( $columns as $key => $col ){
                ?>
                <th  class="column-<?php echo $key; ?>" ><?php echo $col['label']; ?></th>
                <?php
            } ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($fields as $field_id => $label){ ?>
        <tr id="row-<?php echo $field_id; ?>">
            <?php foreach( $columns as $key => $col ){ ?>
                <td class="column-<?php echo $key; ?>">
                <?php
                switch ( $key ) {
                    case 'file_name':
                        echo $label;
                        break;
                    case 'file_url':
                        ?>
                        <input name="_document[<?php echo $field_id; ?>]" type="text" placeholder="<?php echo $col['placeholder']; ?>" class="form-control" value="<?php echo $the_strategy->get_document_url($field_id); ?>">
                        <?php
                        break;
                    case 'upload_file':
                        ?>
                        <a href="#" class="button upload_file_button" data-choose="<?php _e('Choose file', 'utheme'); ?>" data-update="<?php _e('Insert file URL', 'utheme'); ?>">
                            <?php _e('Choose&nbsp;file', 'utheme'); ?>
                        </a>
                        <?php
                        break;
                }
                ?>
                </td>
                <?php
            } ?>
        </tr>
        <?php } ?>

        <?php
        if( $the_strategy->additional_doc && is_array($the_strategy->additional_doc) ){
        foreach ($the_strategy->additional_doc as $doc_index=> $doc){
        ?>
        <tr>
            <?php foreach( $columns as $key => $col ){ ?>
                <td class="column-<?php echo $key; ?>">
                    <?php
                    switch ( $key ) {
                        case 'file_name':
                            ?>
                            <input name="_additional_doc_<?php echo $key; ?>[]" type="text" placeholder="<?php echo $col['placeholder']; ?>" class="form-control" value="<?php echo $doc['name']; ?>">
                            <?php
                            break;
                        case 'file_url':
                            ?>
                            <input name="_additional_doc_<?php echo $key; ?>[]" type="text" placeholder="<?php echo $col['placeholder']; ?>" class="form-control" value="<?php echo $doc['url']; ?>">
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
        <?php }
        } ?>
        </tbody>
    </table>
    <div class="clear"></div>

<div class="toolbar">
    <button type="button" data-tmpl="#tmpl-strategy-document-row"  data-container="#documents_table" class="button add_document_row"><?php _e('Add file', 'utheme'); ?></button>
</div>

<?php
include 'tmpl/tmpl-strategy-document-row.php';