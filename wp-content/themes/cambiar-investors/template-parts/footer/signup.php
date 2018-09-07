<?php if ( $signup_popup_content = get_field( 'signup_popup_content', 'options' ) ) : ?>
    <div class="cd-popup" id="sign-up-popup" role="dialog">
        <div class="cd-popup-container" role="document" tabindex="0">
            <div class="title">
                <?php if ( $title = get_field( 'signup_popup_title', 'options' ) ) : ?>
	                <?php echo esc_attr( $title ); ?>
                <?php endif; ?>
            </div>
            <a href="#" class="cd-popup-close" aria-label="close"></a>
	        <?php if ( $signup_popup_content ) : ?>
                <?php echo do_shortcode( $signup_popup_content ); ?>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>