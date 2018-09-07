<?php
/**
 * Template Name: Map
 *
 * @package		U_Theme/Template
 * @author 		uCAT
 */

get_header();
$q = new WP_Query();
$managers = $q->query(['post_type' => 'managers', 'posts_per_page' => -1]);

while ( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('map-page'); ?>>
        <div class="container">
            <section class="section-header">
                <h1 class="title text-uppercase"><?php the_title(); ?></h1>
                <div class="excerpt"><?php the_content(); ?></div>
            </section>
            <div class="map-columns">
                <div class="map">
                    <div id="interactive_map" ></div>
                </div>
                <div class="list">
                    <?php
                    $m_json = [];
                    $s_json = [];
                    if( $managers){
                        ?>
                        <ul id="territory_managers" class="collapsed-list">
                        <?php
                        foreach($managers as $post){ setup_postdata($post);
                            $states = get_field('states');
                            $tip = '';
                            if( is_array($states)){
                                $states = array_map(function($st){
                                    return 'US-' . $st;
                                }, get_field('states') );

                                if( is_array($states)){
                                    foreach($states as $state){
                                        $s_json[$state] =$id;
                                    }
                                }
                            }else{
                                $states = [];
                            }

                            if( have_rows('contacts') ):
                                while ( have_rows('contacts') ) : the_row();
                                    $tip .= '<div class="popup-content">';
                                    $tip .= '<div class="name">' . get_sub_field('name') . '</div>';
                                    $tip .= '<div class="job">' . get_sub_field('title') . '</div>';
                                    $tip .= '<div class="email">' . get_sub_field('email') . '</div>';
                                    $tip .= '<div class="phone">' . get_sub_field('phone') . '</div>';
                                    $tip .= '</div>';
                                    ?>

                                    <li class="collapse-holder mod collapse-other<?php echo get_sub_field('is_collapsed') ? ' collapsed' : ''; ?>" data-manager="<?php the_ID(); ?>">
                                        <div class="collapse-btn mod">
                                            <div class="name"><?php the_sub_field('name'); ?></div>
                                            <div class="job"><?php the_sub_field('title'); ?></div>
                                            <i class="icon-arrow-down"></i>
                                        </div>
                                        <div class="collapse mod">
                                            <div class="email"><a href="mailto:<?php the_sub_field('email'); ?>"><?php the_sub_field('email'); ?></a></div>
                                            <div class="phone"><a href="tel:<?php the_sub_field('phone'); ?>"><?php the_sub_field('phone'); ?></a></div>
                                        </div>
                                    </li>
                                <?php
                                endwhile;
                            endif;

                            $id = get_the_ID();
                            $m_json['_'.$id] = [
                                'id'     => $id,
                                'states' => $states,
                                'tip'    => $tip,
                            ];
                        }
                        ?>
                        </ul>
                        <?php
                        wp_reset_postdata();
                    }
                    ?>
                    <script>var u_territory_managers = <?php echo json_encode(['managers' => $m_json, 'states' => $s_json]); ?>;</script>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </article><!-- #post-## -->
<?php endwhile; // end of the loop. ?>

<?php get_footer();
