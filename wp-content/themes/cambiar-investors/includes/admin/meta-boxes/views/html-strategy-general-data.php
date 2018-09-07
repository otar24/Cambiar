<div class="options_group">
    <div class="form-field">
        <label for="ticker"><?php _e('Ticker', 'utheme'); ?></label>
        <input type="text" name="_ticker" id="ticker" value="<?php echo  $the_strategy->ticker; ?>">
    </div>
    <div class="form-field">
        <label for="type"><?php _e('Type', 'utheme'); ?></label>
        <select name="_type" id="type">
            <?php foreach( u_get_strategy_types() as $type_key => $label ){ ?>
                <option value="<?php echo $type_key ?>" <?php selected( $type_key, $the_strategy->type, true); ?> ><?php echo $label; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="form-field">
        <label for="share_class"><?php _e('Share Class', 'utheme'); ?></label>
        <select name="_share_class" id="share_class">
            <?php foreach( u_get_strategy_share_classes() as $item_id => $label ){ ?>
                <option value="<?php echo $label ?>" <?php selected( $label, $the_strategy->share_class, true); ?> ><?php echo $label; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="form-field">
        <label for="strategy_category"><?php _e('Category', 'utheme'); ?></label>
        <select name="_strategy_category" id="strategy_category">
            <?php foreach( u_get_strategy_categories() as $item_id => $label ){ ?>
                <option value="<?php echo $label ?>" <?php selected( $label, $the_strategy->strategy_category, true); ?> ><?php echo $label; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="form-field">
        <label for="strategy_group"><?php _e('Product', 'utheme'); ?></label>
        <select name="_strategy_group" id="strategy_group">
            <?php foreach( u_get_strategy_groups() as $item_id => $label ){ ?>
                <option value="<?php echo $label ?>" <?php selected( $label, $the_strategy->strategy_group, true); ?> ><?php echo $label; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="form-field">
        <label for="geography"><?php _e('Geography', 'utheme'); ?></label>
        <select name="_geography" id="geography">
            <?php foreach( u_get_strategy_geography_items() as $item_id => $label ){ ?>
                <option value="<?php echo $label ?>" <?php selected( $label, $the_strategy->geography, true); ?> ><?php echo $label; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="clear"></div>
</div>
<div class="options_group">
    <div class="form-field">
        <label for="holdings"><?php _e('Holdings', 'utheme'); ?></label>
        <input type="text" name="_holdings" id="holdings" value="<?php echo  $the_strategy->holdings; ?>" class="short">
    </div>
    <div class="form-field">
        <label for="weighting"><?php _e('Weighting', 'utheme'); ?></label>
        <input type="text" name="_weighting" id="weighting" value="<?php echo  $the_strategy->weighting; ?>" class="short">
    </div>
    <div class="form-field">
        <label for="cap_range"><?php _e('Cap Range', 'utheme'); ?></label>
        <input type="text" name="_cap_range" id="cap_range" value="<?php echo  $the_strategy->cap_range; ?>" class="short">
    </div>
    <div class="clear"></div>
</div>
<div class="options_group">
    <div class="form-field">
        <label for="commentary_id"><?php _e('Commentary', 'utheme'); ?></label>
        <?php
            $posts_list = u_get_posts([
                'numberposts' => -1,
                'category_name' => 'commentary'
            ]);
        ?>
        <select name="_commentary_id" id="commentary_id">
            <option value=""></option>
            <?php if($posts_list ): ?>
                <?php foreach ($posts_list as $p): ?>
                    <option <?php selected($p->ID, $the_strategy->commentary_id, true); ?> value="<?php echo $p->ID;?>"><?php echo $p->post_title;?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>
    <div class="clear"></div>
</div>