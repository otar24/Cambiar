<?php
/**
 * @global U_Strategy $the_strategy
 */
global $the_strategy;

$disclosures = $the_strategy->disclosures ? $the_strategy->disclosures : get_option('u_'.$the_strategy->type.'_disclosures');
if( !$disclosures ) return;

?>
<div class="fp-block fp-auto-height">
    <section class="section-gains">
        <div class="container">
            <div class="collapse-box">
                <div class="title text-uppercase"><?php _e('Disclosures', 'utheme');?></div>
                <div class="collapse-holder">
                    <div class="text-box collapse">
                        <?php echo apply_filters( 'the_content', $disclosures ); ?>
                    </div>
                    <a class="collapse-btn btn btn-secondary">
                        <i class="icon-arrow-down"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>