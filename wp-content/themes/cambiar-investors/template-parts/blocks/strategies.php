<?php
$columns = array(
    array(
        'title' => __('Product Name', 'utheme'),
        'key'   => 'title'
    ),
    array(
        'title' => __('Geography', 'utheme'),
        'key'   => 'geography'
    ),
    array(
        'title' => __('Category', 'utheme'),
        'key'   => 'category'
    ),
    array(
        'title' => __('Holdings Range', 'utheme'),
        'key'   => 'holdings'
    ),
    array(
        'title' => __('Avg. Security Weighting', 'utheme'),
        'key'   => 'weighting'
    ),
    array(
        'title' => __('Market Cap Range', 'utheme'),
        'key'   => 'cap_range'
    )
);

$groups = u_get_strategy_groups(true);
?>
<div class="fp-block fp-auto-height no-bg-image" data-anchor="strategies">
    <section <?php echo $id ? ' id="' . esc_attr( $id ) . '"' : ''; ?> class="tab-section <?php echo esc_attr( $class ); ?>">
        <div class="fp-container">
        <div class="container">
            <header class="section-header">
                <h1 class="title text-uppercase">
                    <?php the_sub_field('title'); ?>
                </h1>
            </header>
            <div class="text-holder">
                <?php the_sub_field('description'); ?>
            </div>
            <div class="tab-box">
                <div class="tabset-holder">
                    <div class="anchor-box full-width">
                        <ul class="tabset slick-carousel-base text-uppercase">
                            <?php $i = 0; foreach ( u_get_strategy_types() as $type_key => $type_name ): ?>
                            <li class="base-slide"><a href="#tab-<?php echo $type_key; ?>" <?php $i === 0 ? 'class="active"' : ''; ?>><?php echo $type_name; ?></a></li>
                            <?php $i++; endforeach; ?>

                            <?php if ( get_sub_field( 'show_private_offering' ) ) : ?>
                                <li class="base-slide"><a href="#tab-private-offering" class="display-xs-hidden"><?php echo esc_attr( get_sub_field( 'private_offering_title' ) ); ?></a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                <div class="tab-content">
                    <?php
                    foreach ( u_get_strategy_types() as $type_key => $type_name ):
                    ?>
                    <div id="tab-<?php echo $type_key; ?>">
                        <div class="tab-holder">
                            <div class="scroll-holder">
                                <div class="scroll-wrap">
                                    <table class="responsive-table table display">
                                        <thead>
                                        <tr>
                                            <?php $p = 0; foreach ($columns as $column ): ?>
                                            <th class="<?php echo ($p==0) ? 'max-desktop' : 'min-tablet';?> no-sort"><?php echo $column['title']; ?></th>
                                            <?php if( $type_key == 'mutual_funds' && $column['key'] === 'title' ) : ?>
                                                <th class="min-tablet no-sort"><?php _e('Ticker', 'utheme'); ?></th>
                                            <?php endif; ?>
                                            <?php $p++; endforeach; ?>
                                            <th class="min-tablet no-sort display-xs-visible"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if( $groups ){
                                            foreach ($groups as $group_id => $group_title ){
                                                $strategies = u_get_strategies( array('type' => $type_key, 'group' => $group_id ) );
                                                if( !$strategies ) continue;
                                                $strtg = $strategies[0];
                                                ?>
                                            <tr>
                                                <?php $i = 0; foreach ($columns as $column ):
                                                    if( $i === 0 ): ?>
                                                    <td class="max-desktop">
                                                        <div class="mark-royal-blue">
                                                            <a href="<?php echo $strtg->get_permalink(); ?>"><?php echo $group_title; ?></a>
                                                        </div>
                                                    </td>

                                                    <?php if( $type_key == 'mutual_funds' ){ ?>
                                                            <td class="min-tablet">
                                                                <?php if( count($strategies) > 1 ){ ?>
                                                                <div class="sort-form">
																	<span class="fake-select-holder">
																		<select class="fake-select switch-strategy-table">
                                                                            <?php foreach ($strategies as $_strtg){ ?>
																			<option value="<?php echo $_strtg->id; ?>"><?php echo $_strtg->ticker; ?></option>
                                                                            <?php } ?>
																		</select>
																	</span>
                                                                </div>
                                                                <?php }else{
                                                                echo $strtg->ticker;
                                                                } ?>
                                                            </td>
                                                    <?php } ?>

                                                    <?php else: ?>
                                                    <td class="min-tablet">
                                                        <?php
                                                        foreach ($strategies as $_index => $strategy){
                                                            ?>
                                                            <div class="strategy-<?php echo $strategy->id; ?>-data u-strategy--data" <?php echo $_index > 0 ? 'style="display: none;"' : ''; ?>>
                                                                <?php echo $strategy->{$column['key']}; ?>
                                                            </div>
                                                        <?php } ?>
                                                    </td>
                                                    <?php endif;
                                                $i++; endforeach; ?>
                                                <td class="min-tablet display-xs-visible">
                                                    <?php
                                                    foreach ($strategies as $_index => $strategy){
                                                        ?>
                                                        <div class="strategy-<?php echo $strategy->id; ?>-data u-strategy--data" <?php echo $_index > 0 ? 'style="display: none;"' : ''; ?>>
                                                            <a href="<?php echo $strategy->get_permalink(); ?>" class="btn btn-secondary text-uppercase">Learn More</a>
                                                        </div>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php }
                                        } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="btn-box display-xs-hidden">
                                <a href="<?php echo get_post_type_archive_link( 'strategy' ); ?>" class="btn btn-secondary text-uppercase"><?php _e('View All', 'utheme'); ?></a>
                            </div>

                            <?php if ( $disclosures = get_option( 'u_home_' . $type_key . '_disclosures' ) ) : ?>
                                <div class="collapse-box">
                                    <div class="title text-uppercase"><?php _e( 'Disclosures', 'utheme' ); ?></div>
                                    <div class="collapse-holder">
                                        <div class="text-box collapse">
                                            <div class="text">
                                                <?php echo apply_filters( 'the_content', $disclosures ); ?>
                                            </div>
                                        </div>
                                        <a class="collapse-btn btn btn-secondary">
                                            <i class="icon-arrow-down"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
	
	                <?php if ( get_sub_field( 'show_private_offering' ) ) : ?>
                        <div id="tab-private-offering">
                            <div class="tab-holder">
                                <?php if ( $private_offering_description = get_sub_field( 'private_offering_description' ) ) : ?>
                                    <div class="text-wrap">
                                        <?php echo $private_offering_description; ?>
                                    </div>
                                <?php
                                    endif;
                                    
                                    if ( $embed_campaign_monitor_form = get_sub_field( 'embed_campaign_monitor_form' ) ) {
	                                    echo do_shortcode( $embed_campaign_monitor_form );
                                    }
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        </div>
    </section>
</div>