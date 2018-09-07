<div class="fp-block fp-auto-height no-bg-image">
    <section<?php echo $id ? ' id="' . esc_attr( $id ) . '"' : ''; ?> class="<?php echo esc_attr( $class ); ?>">

        <div class="fp-container">
            <div class="container">
                <?php if ( $title = get_sub_field( 'title' ) ) : ?>
                    <header class="section-header">
                        <h1 class="title text-uppercase"><?php echo esc_attr( $title ); ?></h1>
                    </header>
                <?php
                    endif;
                
                    if( $desc = get_sub_field('description') ) :
                ?>
                    <div class="text-holder">
                        <?php echo apply_filters( 'the_content', $desc ); ?>
                    </div>
                <?php
                    endif;
                    
                    if( have_rows('tabs') ) :
                ?>
                    <div class="tab-box">
                        <div class="tabset-holder">
                            <div class="anchor-box full-width">
                                <ul class="tabset text-uppercase">
                                <?php
                                    $content = [];
                                    $i = 0;
                                    while ( have_rows('tabs') ) : the_row(); $i++;
                                        $cl = $i === 1 ? 'active' : '';
                                        echo '<li><a href="#tab-'.$i.'" class="'.$cl.'">'.get_sub_field('tab_title').'</a></li>';
    
                                        $content[] = get_sub_field('tab_content');
                                    endwhile;
                                ?>
                                </ul>
                            </div>
                        </div>
                        <div class="tab-content">
                            <?php foreach ($content as $index => $tab_content ) : ?>
                                <div id="tab-<?php echo $index+1; ?>">
                                    <div class="tab-holder">
                                        <?php echo apply_filters( 'the_content', $tab_content ); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>