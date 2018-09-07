<div class="options_group">
    <div class="form-field">
        <label for="morningstar_rating"><?php _e('Rating', 'utheme'); ?></label>
        <input type="number" step="1" min="0" max="5" class="short" name="_morningstar_rating" id="morningstar_rating" value="<?php echo  $the_strategy->morningstar_rating; ?>">
    </div>
    <div class="form-field">
        <label for="morningstar_short_description"><?php _e('Short Description', 'utheme'); ?></label>
        <textarea name="_morningstar_short_description" id="morningstar_short_description"><?php echo  $the_strategy->morningstar_short_description; ?></textarea>
    </div>
    <div class="form-field">
        <label for="_morningstar_description"><?php _e('Description', 'utheme'); ?></label>
    </div>
    <?php
    wp_editor( $the_strategy->morningstar_description, '_morningstar_description', $settings = array('media_buttons' => false, 'editor_height' => 250) ); ?>
    <div class="clear"></div>
</div>