<span class="wpcf7-form-control-wrap<?php echo $required ? ' textarea-required' : ''; ?>">
    <textarea
            id="<?php echo $id; ?>"
            name="<?php echo $name; ?>"
            class="wpcf7-form-control wpcf7-textarea textarea-class wpcf7-use-title-as-watermark"
            cols="10"
            rows="1"
            placeholder="<?php echo $placeholder ? esc_attr( $placeholder ) : $label; ?>"
	        <?php echo $required ? ' required' : ''; ?>><?php echo esc_attr( $value ); ?></textarea>
</span>