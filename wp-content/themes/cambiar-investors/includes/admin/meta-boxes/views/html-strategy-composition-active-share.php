<div class="options_group">
    <div class="form-field">
        <label for="active_share_date"><?php _e('Active Share Date', 'utheme'); ?></label>
        <input type="text"  name="_active_share_date" id="active_share_date"  class="form-control short u-init-date" value="<?php echo  $the_strategy->active_share_date; ?>">
    </div>
</div>
<div class="options_group">
    <div class="form-field">
        <label for="active_share"><?php _e('Active Share', 'utheme'); ?></label>
        <div class="input-group short">
            <input name="_active_share" id="active_share" type="number" min="0"  max="100" step="0.01" class="form-control" value="<?php echo $the_strategy->active_share; ?>">
            <div class="input-group-append">
                <span class="input-group-text">%</span>
            </div>
        </div>
    </div>
    <div class="clear" style="padding: 80px 0;"></div>
</div>