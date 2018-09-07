<?php
	$columns = U_Meta_Box_Strategy_Data::get_section_columns($tab_id);
	$fields = $the_strategy->{$tab_id};
	$table_id = $tab_id;
	$start_date_key = $tab_id . '_start_date';
	$start_price_key = $tab_id . '_start_price';
?>
<div class="options_group">
	<div class="form-field">
		<label for="historica-start-date"><?php _e('Start Date', 'utheme'); ?></label>
		<input type="text"  name="_<?php echo $start_date_key; ?>" id="historica-start-date"  class="form-control short u-init-date" value="<?php echo  $the_strategy->{$start_date_key}; ?>">
	</div>
	<div class="form-field">
		<label for="historica-start-price"><?php _e('Start Value', 'utheme'); ?></label>
		<div class="input-group short">
			<div class="input-group-prepend">
				<span class="input-group-text">$</span>
			</div>
			<input name="_<?php echo $start_price_key; ?>" type="number" min="0" step="1" class="form-control" value="<?php echo  ( $price = $the_strategy->{$start_price_key} ) ? $price : 10000; ?>">
		</div>
	</div>
</div>
<?php
include 'html-table-repeater.php';