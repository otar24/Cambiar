<div class="options_group">
    <div class="form-field">
        <label for="daily_price_date"><?php _e('Date', 'utheme'); ?></label>
        <input type="text"  name="_daily_price[date]" id="daily_price_date"  class="form-control short u-init-date" value="<?php echo  $the_strategy->daily_price_date; ?>">
    </div>
    <div class="form-field">
        <label for=""><?php _e('CHG', 'utheme'); ?></label>
        <div class="input-group short">
            <div class="input-group-prepend">
                <span class="input-group-text">$</span>
            </div>
            <input name="_daily_price[chg]" type="number" min="0" step="0.01" class="form-control" value="<?php echo $the_strategy->daily_price_chg; ?>">
        </div>
    </div>

    <div class="form-field">
        <label for="total_net_assets"><?php _e('Total Assets ($MM)', 'utheme'); ?></label>
        <div class="input-group short">
            <div class="input-group-prepend">
                <span class="input-group-text">$</span>
            </div>
            <input type="number" min="0" step="0.01" class="form-control short"  name="_total_net_assets" id="total_net_assets" value="<?php echo  $the_strategy->total_net_assets; ?>">
        </div>
    </div>

    <div class="form-field">
        <label for="daily_price_ytd_return"><?php _e('YTD Return', 'utheme'); ?></label>
        <div class="input-group short">
            <input name="_daily_price[ytd_return]" id="daily_price_ytd_return" type="number" min="-100"  max="100" step="0.01" class="form-control" value="<?php echo $the_strategy->daily_price_ytd_return; ?>">
            <div class="input-group-append">
                <span class="input-group-text">%</span>
            </div>
        </div>
    </div>



    <div class="clear"></div>
</div>