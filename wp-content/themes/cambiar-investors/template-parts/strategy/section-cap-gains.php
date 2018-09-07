<?php
/**
 * @global U_Strategy $the_strategy
 */
global $the_strategy;

$disclosures = $the_strategy->disclosures ? $the_strategy->disclosures : get_option('u_'.$the_strategy->type.'_disclosures');

if( !$the_strategy->cap_gains && !$disclosures ){
    return;
}

$columns = U_Meta_Box_Strategy_Data::get_cap_gains_columns();
unset($columns['action']);

$year = [];
foreach ($the_strategy->cap_gains as $cap_gains ){
    $year[] = $cap_gains['year'];
    sort($year);
}

?>
<div class="fp-block fp-auto-height" data-anchor="cap_gains">
    <section class="section-gains">
        <div class="container">
            <?php if ( $the_strategy->cap_gains ) : ?>
                <div class="collapse-box">
                    <header class="section-header">
                        <h1 class="title text-uppercase"><?php _e( 'Cap Gains & Income', 'utheme' ); ?></h1>
                    </header>
                    <div class="table-holder">
                        <div class="collapse-holder collapse-holder-table-5">
                            <div class="collapse">
                                <table id="cap_gains-table" class="display-table">
                                    <thead>
                                    <tr>
                                        <?php foreach ($columns as $c_i => $c_d ){ ?>
                                        <th class="no-sort"><?php echo $c_d['label']; ?></th>
                                        <?php } ?>
                                    </tr>
                
                                    </thead>
                                    <tbody>
                                    <?php foreach ($the_strategy->cap_gains as $cap_gains ){ ?>
                                        <tr>
                                            <?php foreach ($columns as $c_i => $c_d ){ ?>
                                                <td><?php echo $c_i === 'year' ? $cap_gains[$c_i] : u_price_format( $cap_gains[$c_i], 4 ); ?></td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="4"><?php echo apply_filters( 'the_content', $the_strategy->cap_gains_description ); ?></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <?php if ( count( $the_strategy->cap_gains ) > 5 ) : ?>
                                <a class="collapse-btn btn btn-secondary">
                                    <i class="icon-arrow-down"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php
                endif;
                
                if ( $disclosures ) :
            ?>
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
            <?php endif; ?>
        </div>
    </section>
</div>