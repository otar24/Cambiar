<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('Disclosures', 'utheme'); ?></h1>
    <form method="post" id="mainform" action="" enctype="multipart/form-data">

        <?php
        foreach ($settings as $setting){
            $content = get_option($setting['id']);
            ?>
            <div class="setting" style="padding-bottom: 40px;">
                <strong ><?php echo $setting['label']; ?></strong>
            <?php
            wp_editor( $content, $setting['id'], $settings = array('media_buttons' => false) );
            ?>
            </div>
            <?php
            }
            ?>

        <p class="submit">
            <?php if ( empty( $GLOBALS['hide_save_button'] ) ) : ?>
                <input name="save" class="button-primary utheme-save-button" type="submit" value="<?php esc_attr_e( 'Save changes', 'utheme' ); ?>" />
            <?php endif; ?>
            <?php wp_nonce_field( 'utheme-settings' ); ?>
        </p>
    </form>
</div>