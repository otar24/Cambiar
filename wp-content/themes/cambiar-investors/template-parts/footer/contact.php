<?php if ( get_field( 'general_show_contact_button', 'options' ) ) : ?>
    <div class="cd-popup" id="contact-popup" role="dialog">
        <div class="cd-popup-container" role="document" tabindex="0">
            <?php if ( $title = get_field( 'general_popup_title', 'options' ) ) : ?>
                <div class="title"><?php echo esc_attr( $title ); ?></div>
            <?php endif; ?>
            <a href="#" class="cd-popup-close" aria-label="close"></a>
	        <?php if ( $content = get_field( 'general_popup_content', 'options' ) ) : ?>
                <?php echo do_shortcode( $content ); ?>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>