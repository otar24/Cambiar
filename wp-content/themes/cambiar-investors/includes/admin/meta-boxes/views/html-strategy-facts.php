<div class="options_group">
    <div class="form-field">
        <label for="inception_date"><?php _e('Incept. Date', 'utheme'); ?></label>
        <input type="text" name="_inception_date" id="inception_date"  class="form-control short u-init-date" value="<?php echo  $the_strategy->inception_date; ?>">
    </div>
    <div class="form-field">
        <label for="cusip"><?php _e('Ticker', 'utheme'); ?></label>
        <input type="text" name="_cusip" id="cusip" class="form-control short" value="<?php echo  $the_strategy->cusip; ?>">
    </div>
    <div class="form-field">
        <label for="minimum_investment"><?php _e('Minimum Investment', 'utheme'); ?></label>
        <input type="number" min="0" step="0.01" class="form-control short"  name="_minimum_investment" id="minimum_investment" value="<?php echo  $the_strategy->minimum_investment; ?>">
    </div>
    <div class="form-field">
        <label for="gross_expense_ratio"><?php _e('Gross Expense Ratio', 'utheme'); ?></label>
        <div class="input-group short">
            <input type="number" min="0" max="100" step="0.01" class="form-control short"  name="_gross_expense_ratio" id="gross_expense_ratio" value="<?php echo  $the_strategy->gross_expense_ratio; ?>">
            <div class="input-group-append">
                <span class="input-group-text">%</span>
            </div>
        </div>
    </div>
    <div class="form-field">
        <label for="net_expense_ratio"><?php _e('Net Expense Ratio', 'utheme'); ?></label>
        <div class="input-group short">
            <input type="number" min="0"  max="100" step="0.01" class="form-control short"  name="_net_expense_ratio" id="net_expense_ratio" value="<?php echo  $the_strategy->net_expense_ratio; ?>">
            <div class="input-group-append">
                <span class="input-group-text">%</span>
            </div>
        </div>
    </div>
    <div class="form-field">
        <label for="key-facts-description"><?php _e('Description', 'utheme'); ?></label>
        <textarea name="_key_facts_description" id="key-facts-description"><?php echo  $the_strategy->key_facts_description; ?></textarea>
    </div>
    <div class="clear" style="padding: 20px 0;"></div>
</div>