<span class="wpcf7-form-control-wrap<?php in_array( $name, [ 'cm-mdilty-mdilty', 'cm-vhymk-vhymk' ] ) ? ' Emailfield' : ''; ?><?php echo $required ? ' text-field-required' : ''; ?>">
    <input
            id="<?php echo $id; ?>"
            type="email"
            name="<?php echo $name; ?>"
            value="<?php echo esc_attr( $value ); ?>"
            class="js-cm-email-input wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-email email-class wpcf7-use-title-as-watermark<?php echo $required ? ' wpcf7-validates-as-required' : ''; ?>"
            size="12"
            placeholder="<?php echo $placeholder ? esc_attr( $placeholder ) : $label; ?>"
	    <?php echo $required ? ' required' : ''; ?>/>
</span>