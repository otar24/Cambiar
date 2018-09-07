<div class="options_group">
    <div class="form-field">
        <label for="perfomance-title"><?php _e('Title', 'utheme'); ?></label>
        <input type="text"  name="_perfomance_title" id="perfomance-title"  class="form-control" value="<?php echo $the_strategy->perfomance_title; ?>">
    </div>
    <div class="form-field">
        <label for="perfomance_monthly_date"><?php _e('Monthly Date', 'utheme'); ?></label>
        <input type="text"  name="_perfomance_monthly_date" id="perfomance_monthly_date"  class="form-control short u-init-date" value="<?php echo  $the_strategy->perfomance_monthly_date; ?>">
    </div>
    <div class="form-field">
        <label for="perfomance_quarterly_date"><?php _e('Quarterly Date', 'utheme'); ?></label>
        <input type="text"  name="_perfomance_quarterly_date" id="perfomance_quarterly_date"  class="form-control short u-init-date" value="<?php echo  $the_strategy->perfomance_quarterly_date; ?>">
    </div>
    <div class="form-field">
        <label for="_morningstar_description"><?php _e('Description', 'utheme'); ?></label>
    </div>
    <?php
    wp_editor( $the_strategy->performance_description, '_performance_description', $settings = array('media_buttons' => false, 'editor_height' => 250) ); ?>
    <div class="clear"></div>
</div>