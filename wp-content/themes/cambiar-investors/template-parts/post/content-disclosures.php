<?php if( $disclosures = get_field( 'disclosures_content' ) ) : ?>
    <div class="clearfix"></div>
    <div class="collapse-box">
        <?php if( $title = get_field( 'disclosures_title' ) ) : ?>
            <div class="title text-uppercase"><?php echo esc_attr( $title ); ?></div>
        <?php endif; ?>
        <div class="collapse-holder">
            <div class="text-box collapse">
                <?php echo apply_filters( 'the_content', $disclosures ); ?>
            </div>
            <a class="collapse-btn btn btn-secondary">
                <i class="icon-arrow-down"></i>
            </a>
        </div>
    </div>
<?php endif; ?>