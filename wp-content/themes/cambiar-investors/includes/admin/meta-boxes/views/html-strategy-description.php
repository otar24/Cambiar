<?php
    $title_name = $tab_id . '_title';
    $description_name = $tab_id . '_description';
?>
<div class="options_group">
    <div class="form-field">
        <label for="<?php echo $tab_id; ?>-title"><?php _e( 'Heading', 'utheme' ); ?></label>
        <input id="<?php echo $tab_id; ?>-title" type="text" name="_<?php echo $title_name; ?>"value="<?php echo ( $title = $the_strategy->{$title_name} ) ? $title : __('Profile', 'utheme'); ?>">
    </div>
    <?php
    wp_editor( $the_strategy->{$description_name}, '_' . $description_name, $settings = array('media_buttons' => false, 'editor_height' => 250) ); ?>
    <div class="clear"></div>
</div>