<?php
/**
 * @global WP_Post $post
 */
global $post;

$strategies = null;
if ( $category_groups = wp_get_post_terms( $post->ID, 'strategy_group', [ 'fields' => 'ids' ] ) ) {
	$strategies = u_get_strategies( [ 'group' => $category_groups, 'type' => $post->_type ] );
}

$share_class = wp_get_post_terms( $post->ID, 'share_class', [ 'fields' => 'names' ] );
?>
<div class="nav-panel sticky-panel">
    <div class="container">
        <div class="holder">
            <div class="form-holder">
                <?php if ( ! is_null( $strategies ) &&  count( $strategies ) > 1 ): ?>
                <div class="select-form">
                    <span class="fake-select-holder">
                        <select class="fake-select switchFund">
                            <?php foreach ( $strategies as $strategy ) : ?>
                            <option value="<?php echo $strategy->get_permalink(); ?>" <?php selected($strategy->id, $post->ID, true); ?> ><?php echo $strategy->share_class; ?> (<?php echo $strategy->ticker; ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </span>
                </div>
                <?php else:
                    echo array_shift( $share_class );
                    if ( $post->_ticker ) {
	                    echo ' (' . $post->_ticker . ')';
                    }
                endif; ?>
            </div>
            <ul class="link-holder">
                <li>
                    <button class="link text-uppercase" onclick="window.print()" type="button"><i class="icon-print"></i><?php _e( 'print', 'utheme' ); ?></button>
                </li>
                <li>
                    <a href="mailto:?subject=<?php _e('Look at this website', 'utheme'); ?>&body=<?php printf(__('Hi,I found this website and thought you might like it %s', 'utheme'), get_the_permalink()); ?>" class="link text-uppercase"><i class="icon-share"></i>share</a>
                </li>
            </ul>
            <div class="anchor-box">
                <ul id="anchor-menu" class="slick-carousel-base text-uppercase">
                    <li class="base-slide" data-menuanchor="overview">
                        <a href="#overview"><?php _e('Overview', 'utheme'); ?></a>
                    </li>
                    <li class="base-slide" data-menuanchor="performance">
                        <a href="#performance"><?php _e('Performance', 'utheme'); ?></a>
                    </li>
                    <li class="base-slide" data-menuanchor="composition">
                        <a href="#composition"><?php _e('Composition', 'utheme'); ?></a>
                    </li>
                    <li class="base-slide" data-menuanchor="commentary">
                        <a href="#commentary"><?php _e('Commentary', 'utheme'); ?></a>
                    </li>
                    <li class="base-slide" data-menuanchor="cap_gains">
                        <a href="#cap_gains"><?php _e('Cap gains & Income', 'utheme'); ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>