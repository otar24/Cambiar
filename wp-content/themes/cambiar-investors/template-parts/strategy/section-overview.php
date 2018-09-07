<?php
/**
 * @global U_Strategy $the_strategy
 */
global $the_strategy;

$key_facts = [
    [
        'title' => __('Ticker', 'utheme'),
        'value' => $the_strategy->cusip
    ],
    [
        'title' => __('Inception Date', 'utheme'),
        'value' => $the_strategy->inception_date
    ],
    [
        'title' => __('Minimum', 'utheme'),
        'value' => [ $the_strategy->minimum_investment, $precision = 0 ],
        'callback' => 'u_price_format'
    ],
    [
        'title' => __('Gross Expense', 'utheme'),
        'value' => [ $the_strategy->gross_expense_ratio ],
        'callback' => 'u_percent_format'
    ],
    [
        'title' => __('Net Expense', 'utheme'),
        'value' => [ $the_strategy->net_expense_ratio ],
        'callback' => 'u_percent_format'
    ],
];
$key_facts = array_filter($key_facts, function($d){
    return !empty($d['value']);
} );
$managers = $the_strategy->get_managers();
$documents_list = $the_strategy->get_documents();
$why_invest_title = $the_strategy->why_invest_title;
$why_invest_description = $the_strategy->why_invest_description;
$key_facts_description = $the_strategy->key_facts_description;

$show_list_info = $the_strategy->daily_price_nav || $the_strategy->daily_price_nav || $the_strategy->total_net_assets || $the_strategy->morningstar_rating;
$show_facts     = $key_facts || $managers || $documents_list || $key_facts_description;
?>
<div class="fp-block fp-auto-height" data-anchor="overview">
    <section class="section-fund text-white mb-70 pt-100" style="background-color: #455560;">
        <div class="container">
            <header class="section-header">
                <h1 class="title text-uppercase"><?php echo $the_strategy->group; ?></h1>
            </header>
            <div class="box-info">
                <?php if($show_list_info ): ?>
                <ul class="list-info">
                    <?php if( $the_strategy->daily_price_ytd_return ): ?>
                    <li>
                        <h2 class="title h6"><?php _e('YTD Return', 'utheme'); ?></h2>
                        <div class="subtitle"><?php echo u_percent_format($the_strategy->daily_price_ytd_return); ?></div>
                        <div class="text-holder">
                            <p>as of <time datetime="<?php echo $the_strategy->daily_price_date; ?>"><?php echo $the_strategy->daily_price_date; ?></time></p>
                        </div>
                    </li>
                    <?php
                    endif;
                    if( $the_strategy->total_net_assets ): ?>
                    <li>
                        <h2 class="title h6"><?php _e('Total Assets ($MM)', 'utheme'); ?></h2>
                        <div class="subtitle"><?php echo u_price_format($the_strategy->total_net_assets, 1); ?></div>
                        <div class="text-holder">
                            <p>as of <time datetime="<?php echo $the_strategy->daily_price_date; ?>"><?php echo $the_strategy->daily_price_date; ?></time></p>
                        </div>
                    </li>
                    <?php
                    endif;
                    if( $the_strategy->morningstar_rating ): ?>
                    <li>
                        <h2 class="title h6"><?php _e('Morningstar Rating', 'utheme'); ?></h2>
                        <div class="subtitle">
                            <?php
                            if( $the_strategy->morningstar_rating ){
                                for ($st = 0; $st < $the_strategy->morningstar_rating; $st++ ){
                                    echo '<i class="icon-star"></i>';
                                }
                            }
                            ?>
                        </div>
                        <div class="text-holder display-xs-hidden">
                            <p><?php echo $the_strategy->morningstar_short_description; ?></p>
                        </div>
                    </li>
                    <?php endif; ?>
                </ul>
                <?php endif; ?>
                <div class="collapse-wrap">
                    <?php if ( $why_invest_title || $why_invest_description ) : ?>
                        <div class="text-info" <?php echo !$show_facts ? 'style="width: 100%;"' : ''; ?>>
                            <div class="collapse-holder mod">
                                <?php if ( $why_invest_title ) : ?>
                                    <a class="collapse-btn mod text-uppercase">
                                        <h2 class="title h6"><?php echo esc_attr( $why_invest_title ); ?></h2>
                                        <i class="icon-arrow-down"></i>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ( $why_invest_description ) : ?>
                                    <div class="text-box collapse">
                                        <div class="text-holder">
                                            <?php echo apply_filters( 'the_content', $why_invest_description ); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if( $show_facts ): ?>
                    <div class="lists-wrap">
                        <div class="lists-box">
                            <?php if ( $key_facts || $key_facts_description ) : ?>
                            <div class="list-item">
                                <div class="collapse-holder mod">
                                    <a class="collapse-btn mod text-uppercase">
                                        <h2 class="title h6"><?php _e('Key Facts', 'utheme'); ?></h2>
                                        <i class="icon-arrow-down"></i>
                                    </a>
                                    <div class="collapse">
                                        <?php if ( $key_facts ) : ?>
                                            <dl class="facts-list list-group">
                                                <?php foreach ( $key_facts as $fact ) : ?>
                                                    <div>
                                                        <dt><?php echo $fact['title']; ?></dt>
                                                        <dd><?php echo isset($fact['callback']) ? call_user_func_array($fact['callback'], $fact['value']) : $fact['value']; ?></dd>
                                                    </div>
                                                <?php endforeach; ?>
                                            </dl>
                                        <?php endif; ?>
                                        
                                        <?php if ( $key_facts_description ) : ?>
                                            <div class="list-info">
                                                <div class="text-holder display-xs-hidden">
                                                    <?php echo wpautop( esc_attr( $key_facts_description ) ); ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php
                            if( $managers && is_array($managers) ): ?>
                            <div class="list-item">
                                <div class="collapse-holder mod">
                                    <a class="collapse-btn mod text-uppercase">
                                        <h2 class="title h6"><?php echo _n('Portfolio Manager', 'Portfolio Managers', count( $managers ), 'utheme'); ?></h2>
                                        <i class="icon-arrow-down"></i>
                                    </a>
                                    <div class="collapse">
                                        <ul class="manegers-list list-group">
                                            <?php
                                            foreach ($managers as $manager ):
                                                if (!isset($manager['id'])) continue;
                                                $the_manager = u_get_manager($manager['id']);
                                                ?>
                                                <li>
                                                    <div class="img-holder">
                                                        <?php echo $the_manager->get_thumbnail('utheme-manager-photo-small', ['alt' => $the_manager->get_title() ]); ?>
                                                    </div>
                                                    <div class="text-holder">
                                                        <div class="list-title">
                                                            <a href="<?php echo $the_manager->get_permalink(); ?>"><?php echo $the_manager->get_title(); ?></a>
                                                        </div>
                                                        <div class="text">
                                                            <p><?php echo $manager['description']; ?></p>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if( $documents_list ): ?>
                            <div class="list-item">
                                <div class="collapse-holder mod">
                                    <a class="collapse-btn mod text-uppercase">
                                        <h2 class="title h6"><?php _e('Literature', 'utheme'); ?></h2>
                                        <i class="icon-arrow-down"></i>
                                    </a>
                                    <div class="collapse">
                                        <ul class="literature-list list-group">
                                            <?php
                                            foreach ( $documents_list as $doc_id => $doc){ ?>
                                            <li>
                                                <a download="<?php echo $doc['url']; ?>" href="<?php echo $doc['url']; ?>" target="_blank" class="load-link"><?php echo $doc['name']; ?> <i class="icon-download"></i></a>
                                            </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="describe-box display-xs-hidden">
                <?php echo apply_filters( 'the_content', $the_strategy->morningstar_description ); ?>
            </div>
            <div class="anhor-holder display-xs-hidden">
                <a href="#focus" class="anhor-link">
                    <i class="icon-arrow-down-big"></i>
                </a>
            </div>
        </div>
    </section>
</div>