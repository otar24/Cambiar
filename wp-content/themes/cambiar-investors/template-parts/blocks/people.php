<?php
    $current_departments = [];
    if ( isset( $_SESSION['single_person_department'] ) ) {
        $current_departments = $_SESSION['single_person_department'];
    }
    
    if ( $departments = u_get_people_departments() ) :
	    $id = get_sub_field( 'id' );
?>
    <div<?php echo $id ? ' id="' . $id . '"' : ''; ?> class="fp-block fp-auto-height no-bg-image <?php echo esc_attr( $class ); ?>">
        <section class="section-peoples">
            <div class="fp-container">
                <div class="tabset-holder">
                    <div class="container">
                        <div class="anchor-box full-width">
                            <ul class="tabset slick-carousel-base text-uppercase">
                                <?php $k = 0; ?>
                                <?php foreach ( $departments as $key => $department ) : ?>
                                    <li class="base-slide"><a href="#tab-<?php echo $key; ?>"<?php echo ( ! $k && empty( $current_departments ) ) || ( ! empty( $current_departments ) && in_array( $key, $current_departments ) ) ? ' class="active"' : ''; ?>><?php echo $department ?></a></li>
                                    <?php $k ++; ?>
                                <?php endforeach; ?>
                                <?php unset( $_SESSION['single_person_department'] ); ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="tab-content">
	                <?php foreach ( $departments as $key => $department ) : ?>
                        <div id="tab-<?php echo $key; ?>">
                            <div class="tab-holder">
                                <div class="scroll-holder">
                                    <div class="scroll-wrap">
                                        <div class="people-list">
	                                        <?php
                                                if (
                                                    $people = u_get_managers( [
                                                        'numberposts' => - 1,
                                                        'department'  => $department,
                                                        'orderby'     => 'menu_order',
                                                        'order'       => 'asc'
                                                    ] )
                                                ) :
	
	                                                $posts_per_row = get_field( 'posts_per_row', 'department_' . $key  );
                                            ?>
                                                <ul class="people-row">
                                                    <?php
                                                    $count_p = count($people);
                                                    $p_in_row = $posts_per_row ? $posts_per_row : 7;
                                                    foreach ($people as $key => $post) : ?>
                                                        <li>
                                                            <?php
                                                                if ( has_post_thumbnail() ) {
                                                                    the_post_thumbnail( [ '220', '240' ] );
                                                                }
                                                            ?>
                                                            <div class="text">
                                                                <div class="title" data-mh="title"><?php the_title(); ?></div>
                                                                <?php if ( $position = get_field( 'position' ) ) : ?>
                                                                    <div class="subtitle"><?php echo esc_attr( $position ); ?></div>
                                                                <?php endif; ?>
                                                                <p><a href="<?php the_permalink(); ?>"><?php _e( 'View Profile', 'utheme' ); ?></a></p>
                                                            </div>
                                                        </li>
                                                
                                                        <?php if ( ( $key + 1 ) % $p_in_row == 0 && $key + 1 != $count_p ) : ?>
                                                            </ul>
                                                            <ul class="people-row">
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                    <?php wp_reset_postdata(); ?>
                                                </ul>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </div>
<?php endif; ?>