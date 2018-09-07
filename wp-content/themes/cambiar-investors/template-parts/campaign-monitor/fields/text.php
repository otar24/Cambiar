<span class="wpcf7-form-control-wrap<?php echo $required ? ' text-field-required' : ''; ?>">
    <input
            id="<?php echo $id; ?>"
            type="text"
            name="<?php echo $name; ?>"
            value="<?php echo esc_attr( $value ); ?>"
            class="wpcf7-form-control wpcf7-text text-field-class wpcf7-use-title-as-watermark<?php echo $required ? ' wpcf7-validates-as-required' : ''; ?>"
            size="12"
            placeholder="<?php echo $placeholder ? esc_attr( $placeholder ) : $label; ?>"
            <?php echo $required ? ' required' : ''; ?>/>
</span>