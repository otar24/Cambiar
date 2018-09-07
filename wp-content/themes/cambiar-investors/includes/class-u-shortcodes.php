<?php
/**
 *
 * @class     U_Shortcodes
 * @version   1.0.0
 * @package   U_Theme/Classes
 * @category  Class
 * @author    uCAT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * U_Require_Plugins Class.
 */
class U_Shortcodes {
	public static function init() {
		add_shortcode( 'iframe', [ __CLASS__, 'iframe' ] );
		add_shortcode( 'contactform', [ __CLASS__, 'contactform' ] );
		add_shortcode( 'strategies', [ __CLASS__, 'strategies_shortcode' ] );
		add_shortcode( 'u-more-in', [ __CLASS__, 'more_in_shortcode' ] );
		add_shortcode( 'post', [ __CLASS__, 'post_shortcode' ] );
		add_shortcode( 'person', [ __CLASS__, 'person_shortcode' ] );
		add_shortcode( 'campaign-monitor', [ __CLASS__, 'campaign_monitor' ] );
		
		add_filter( 'img_caption_shortcode', [ __CLASS__, 'img_caption_shortcode' ], 10, 3 );
	}
	
	public static function iframe( $atts ) {
		extract( shortcode_atts( array(
			'src'    => "",
			'width'  => "800",
			'height' => "600"
		), $atts ) );
		
		$iframe = '<iframe src="' . $src . '" width="' . $width . '" height="' . $height . '" scrolling="no" allowtransparency="yes" frameborder="0" allowfullscreen></iframe>';
		
		return $iframe;
	}
	
	public static function contactform() {
		return do_shortcode( get_field( 'general_popup_content', 'options' ) );
	}
	
	public static function img_caption_shortcode( $html, $attr, $content ) {
		
		$atts = shortcode_atts( array(
			'id'      => '',
			'align'   => 'alignnone',
			'width'   => '',
			'caption' => '',
			'class'   => '',
		), $attr, 'caption' );
		
		
		$atts['width'] = (int) $atts['width'];
		if ( $atts['width'] < 1 || empty( $atts['caption'] ) ) {
			return $content;
		}
		
		if ( ! empty( $atts['id'] ) ) {
			$atts['id'] = 'id="' . esc_attr( sanitize_html_class( $atts['id'] ) ) . '" ';
		}
		
		$class = trim( 'wp-caption ' . $atts['align'] . ' ' . $atts['class'] );
		
		$html5 = current_theme_supports( 'html5', 'caption' );
		// HTML5 captions never added the extra 10px to the image width
		$width = $html5 ? $atts['width'] : ( 10 + $atts['width'] );
		
		$caption_width = apply_filters( 'img_caption_shortcode_width', $width, $atts, $content );
		
		$style = '';
		if ( $caption_width ) {
			$style = 'style="width: ' . (int) $caption_width . 'px" ';
		}
		preg_match( '/alt="([^"]*)"/i', $content, $matches );
		$image_alt     = isset( $matches[1] ) ? $matches[1] : '';
		$caption_title = $image_alt ? '<div class="caption-title">' . $image_alt . '</div>' : '';
		if ( $html5 ) {
			$html = '<figure ' . $atts['id'] . $style . 'class="' . esc_attr( $class ) . '">' . $caption_title
			        . do_shortcode( $content ) . '<figcaption class="wp-caption-text">' . $atts['caption'] . '</figcaption></figure>';
		} else {
			$html = '<div ' . $atts['id'] . $style . 'class="' . esc_attr( $class ) . '">' . $caption_title
			        . do_shortcode( $content ) . '<p class="wp-caption-text">' . $atts['caption'] . '</p></div>';
		}
		
		return $html;
	}
	
	public static function strategies_shortcode( $atts ) {
		global $post;
		
		$atts = shortcode_atts( array(
			'numberposts'      => - 1,
			'category'         => 0,
			'orderby'          => 'title',
			'order'            => 'DESC',
			'include'          => '',
			'exclude'          => '',
			'meta_key'         => '',
			'meta_value'       => '',
			'share_class'      => '',
			'geography'        => '',
			'group'            => '',
			'type'             => '',
			'suppress_filters' => true
		), $atts, 'strategies' );
		
		if ( ! empty( $atts['include'] ) ) {
			$atts['include'] = explode( ',', $atts['include'] );
		}
		if ( ! empty( $atts['exclude'] ) ) {
			$atts['exclude'] = explode( ',', $atts['exclude'] );
		}
		if ( ! empty( $atts['share_class'] ) ) {
			$atts['share_class'] = explode( ',', $atts['share_class'] );
		}
		if ( ! empty( $atts['geography'] ) ) {
			$atts['geography'] = explode( ',', $atts['geography'] );
		}
		
		$groups = [];
		if ( ! empty( $atts['group'] ) ) {
			$atts['group'] = explode( ',', $atts['group'] );
			foreach ( $atts['group'] as $gr ) {
				$term          = get_term( $gr );
				$groups[ $gr ] = $term->name;
			}
		} else {
			$groups = u_get_strategy_groups( true );
		}
		
		$columns = array(
			array(
				'title' => __( 'Product Name', 'utheme' ),
				'key'   => 'title',
				'class' => 'max-desktop'
			),
			array(
				'title' => __( 'Ticker', 'utheme' ),
				'key'   => 'ticker',
				'class' => ' min-tablet'
			),
			array(
				'title' => __( 'NAV($)', 'utheme' ),
				'key'   => 'monthly_nav',
				'class' => ' min-tablet'
			),
			array(
				'title' => __( 'Geography', 'utheme' ),
				'key'   => 'geography',
				'class' => 'min-tablet'
			),
			array(
				'title' => __( 'Category', 'utheme' ),
				'key'   => 'category',
				'class' => 'min-tablet'
			),
			array(
				'title' => __( 'Holdings', 'utheme' ),
				'key'   => 'holdings',
				'class' => 'min-tablet'
			),
			array(
				'title' => __( 'Weighting', 'utheme' ),
				'key'   => 'weighting',
				'class' => 'min-tablet'
			),
			array(
				'title' => __( 'Cap Range', 'utheme' ),
				'key'   => 'cap_range',
				'class' => 'min-tablet'
			)
		);
		ob_start();
		if ( $groups ) {
			?>
            <table class="responsive-table filter-checkbox-table table table-sticky">
                <thead>
                <tr>
					<?php foreach ( $columns as $column ) { ?>
                        <th class="<?php echo isset( $column['class'] ) ? $column['class'] : ''; ?>">
							<?php echo $column['title']; ?>
                        </th>
					<?php } ?>
                </tr>
                </thead>
                <tbody>
				<?php
				unset( $columns[0] );
				unset( $columns[1] );
				foreach ( $groups as $group_id => $group_name ) {
					$atts['group'] = [ $group_id ];
					$strategies    = u_get_strategies( $atts );
					if ( $strategies ) {
						$strtg = $strategies[0];
						?>
                        <tr>
                            <td class="td-group-right max-desktop">
	                            <?php
	                            foreach ( $strategies as $_index => $strategy ) {
		                            ?>
                                    <div class="strategy-<?php echo $strategy->id; ?>-data u-strategy--data mark-light-green" <?php echo $_index > 0 ? 'style="display: none;"' : ''; ?>>
                                        <a href="<?php echo $strategy->get_permalink(); ?>"><?php echo $group_name; ?></a>
                                    </div>
	                            <?php } ?>
                            </td>
                            <td class="min-tablet">
								<?php if ( count( $strategies ) > 1 ) { ?>
                                    <div class="sort-form">
                                        <span class="fake-select-holder">
                                            <select class="fake-select switch-strategy-table">
                                                <?php foreach ( $strategies as $_strtg ) { ?>
                                                    <option value="<?php echo $_strtg->id; ?>"><?php echo $_strtg->ticker; ?></option>
                                                <?php } ?>
                                            </select>
                                        </span>
                                    </div>
								<?php } else {
									echo $strtg->ticker;
								} ?>
                            </td>
							<?php foreach ( $columns as $column ) { ?>
                                <td class="min-tablet">
									<?php
									foreach ( $strategies as $_index => $strategy ) {
										?>
                                        <div class="strategy-<?php echo $strategy->id; ?>-data u-strategy--data" <?php echo $_index > 0 ? 'style="display: none;"' : ''; ?>>
											<?php echo $column['key'] === 'monthly_nav' ? u_price_format( $strategy->{$column['key']} ) : $strategy->{$column['key']}; ?>
                                        </div>
									<?php } ?>
                                </td>
							<?php } ?>
                        </tr>
						<?php
					}
				} ?>
                </tbody>
            </table>
            <?php
        }
        $content = ob_get_contents();
        ob_clean();

        return $content;
    }
	
	/**
     * Retrieves post by ID
     *
     * Attributes
     *  title   - Title of the block
     *  id      - ID of the post to show
     *
	 * @param $atts
	 *
	 * @return string|void
	 */
	public static function post_shortcode( $atts ) {
		global $post;
		
		/**
		 * @var $title  - Title of the block
		 * @var $id     - ID of the post to show
		 */
		extract( shortcode_atts( [
			'title' => '',
			'id' => null,
		], $atts ) );
		
		if ( is_null( $id ) ) {
			return;
		}
		
		if ( ! $post = get_post( $id ) ) {
			return;
		}
		
		ob_start();
		
		?>
        <div class="more-in-shortcode">
			<?php if ( $title ) : ?>
                <div class="title"><?php echo $title; ?></div>
			<?php endif; ?>
            <div class="more-post">
				<?php if ( has_post_thumbnail() ) : ?>
                    <div class="image">
						<?php the_post_thumbnail( 'thumbnail-morein' ); ?>
                    </div>
				<?php endif; ?>
                <div class="meta">
					<?php echo get_the_category_list( ', ', '' ); ?>
                    - <?php echo get_the_time( 'j M Y' ); ?>
                </div>
                <div class="name"><a
                            href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </div>
            </div>
        </div>
		<?php
		wp_reset_postdata();
		
		return ob_get_clean();
	}
	
    public static function more_in_shortcode($atts){
        global $post;
        $content = '';

        $atts = shortcode_atts( array(
            'count' => 1
        ), $atts, 'u-more-in' );

        $category = get_the_category();
        if( !$category ) return $content;

        $category = $category[0];
        $posts = get_posts(['cat' => [$category->term_id], 'numberposts' => $atts['count'], 'post__not_in' => [$post->ID], ]);

        if( !$posts ) return $content;

        ob_start();

        ?>
        <div class="more-in-shortcode">
            <div class="title"><?php printf(__('More In %s'), $category->name); ?></div>
            <?php foreach ($posts as $p){?>
            <div class="more-post">
                <div class="image">
                    <?php echo get_the_post_thumbnail($p,'thumbnail-morein'); ?>
                </div>
                <div class="meta">
                    <?php echo get_the_category_list(', ', '', $p); ?> - <?php echo get_the_time('j M Y', $p); ?>
                </div>
                <div class="name"><a href="<?php echo get_the_permalink($p); ?>"><?php echo get_the_title($p); ?></a></div>
            </div>
            <?php } ?>
        </div>
        <?php
        $content = ob_get_contents();
        ob_clean();

        return $content;
    }

    public static function person_shortcode($atts){
        global $post;
        $content = '';

        $atts = shortcode_atts( array(
            'id' => 1
        ), $atts, 'person' );

        $manager = u_get_manager($atts['id']);
        if( !$manager ) return $content;

        ob_start();

        ?>
        <div class="person-shortcode">
            <div class="title">
                <?php echo $manager->get_thumbnail( 'utheme-manager-photo-small', [ 'alt' => $manager->get_title() ] ); ?>
                <a href="<?php echo $manager->get_permalink(); ?>"><?php echo $manager->get_title(); ?></a>
            </div>
            <?php echo $manager->get_excerpt(); ?>
        </div>
		<?php
		$content = ob_get_contents();
		ob_clean();
		
		return $content;
	}
	
	/**
     * Renders Campaign Monitor form
     *
     * Available attribites
     *  - id    ID of the Campaign Monitor to show
     *
	 * @param $atts array
	 *
	 * @return string
	 */
	public static function campaign_monitor( $atts ){
		/**
		 * @var $id - ID of the Campaign Monitor post
		 */
		
	    extract( shortcode_atts( [
            'id' => null
        ], $atts ) );
        
        if ( is_null( $id ) ) {
            return null;
        }
		
        if ( ! $cm_form = WP_Post::get_instance( $id ) ) {
            return null;
        }

        ob_start();
        
        $list = get_field( 'lists', $cm_form->ID );
        
        if ( have_rows( 'rows', $id ) ) :

    ?>
            <div class="wpcf7">
                <form id="subForm" class="js-cm-form wpcf7-form" action="https://www.createsend.com/t/subscribeerror?description=" method="post" data-id="<?php echo $list; ?>">
                    <?php while ( have_rows( 'rows', $cm_form->ID ) ) : the_row(); ?>
                        <div class="form-row">
                            <?php if ( have_rows('fields') ) : ?>
                            
                            <?php while ( have_rows( 'fields' ) ) : the_row(); ?>
                                <?php
		                            /**
		                             * @var $name           - Field unique name
                                     * @var $label          - Field label
                                     * @var $id             - Field unique id
                                     * @var $value          - Field value attribute
                                     * @var $placeholder    - Field placeholder attribute value
                                     * @var $require        - Field flag that shows whether field is required or no
		                             */
		                            $type = '92D4C54F0FEC16E5ADC2B1904DE9ED1A206F12948B5F1EB3EC317A303DEAE285CDB3CC8EF8680836A46E17FACE2E8D38CE00F6FA3ADCC23A3D46695C1DB6FBB9' == $list ? get_sub_field( 'type' ) : get_sub_field( 'type_signup' );
                                    list( $name, $label ) = array_values( $type );
		
		                            $id          = md5( sprintf( '%d-%s-%s', $cm_form->ID, $name, $label ) );
		                            $value       = get_sub_field( 'value' );
		                            $placeholder = get_sub_field( 'placeholder' );
		                            $required    = get_sub_field( 'required' );
		                            $flexible    = get_sub_field( 'flexible' );
                                ?>
                                <div class="form-group<?php echo $flexible ? ' grow' : ''; ?>">
                                    <?php
                                        switch ( $name ) {
                                            case 'cm-mdilty-mdilty':
                                            case 'cm-vhymk-vhymk':
	                                            $field_tpl = locate_template( 'template-parts/campaign-monitor/fields/email.php' );
                                                break;
                                            case 'cm-f-bukdyi':
                                            case 'cm-f-zlykll':
	                                            $field_tpl = locate_template( 'template-parts/campaign-monitor/fields/tel.php' );
                                                break;
                                            case 'cm-f-firhid':
                                            case 'cm-f-zlixi':
	                                            $field_tpl = locate_template( 'template-parts/campaign-monitor/fields/textarea.php' );
                                                break;
                                            case 'cm-fo-firklk':
	                                            $field_tpl = locate_template( 'template-parts/campaign-monitor/fields/us-states.php' );
                                                break;
	                                        case 'cm-fo-zlykyh':
		                                        $field_tpl = locate_template( 'template-parts/campaign-monitor/fields/us-states-signup.php' );
	                                            break;
                                            case 'cm-fo-fudyhl':
	                                            $field_tpl = locate_template( 'template-parts/campaign-monitor/fields/countries.php' );
                                                break;
	                                        case 'cm-fo-zlykyd':
		                                        $field_tpl = locate_template( 'template-parts/campaign-monitor/fields/countries-signup.php' );
	                                            break;
                                            default:
                                                $field_tpl = locate_template( 'template-parts/campaign-monitor/fields/text.php' );
                                        }
                                        
                                        if ( $field_tpl ) {
                                            require $field_tpl;
                                        }
                                    ?>
                                </div>
                            <?php endwhile; ?>
                            
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                    <div class="form-row">
                        <div class="form-group grow">
                            <div class="btn-holder">
                                <button class="js-cm-submit-button submit-class btn btn-secondary" type="submit"><?php the_field( 'button_text', $cm_form->ID ); ?></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
    <?php
        endif;
        
		return ob_get_clean();
	}
	
	public static function insert_post_related( $content ) {
		$related_code = do_shortcode( "[u-more-in]" );
		
		if ( is_singular( 'post' ) && ! is_admin() ) {
			return self::insert_after_paragraph( $related_code, 2, $content );
		}
		
		return $content;
	}
	
	private static function insert_after_paragraph( $insertion, $paragraph_id, $content ) {
		$closing_p  = '</p>';
		$paragraphs = explode( $closing_p, $content );
		$c          = count( $paragraphs );
		$c          = isset( $paragraphs[ $c ] ) && ! trim( $paragraphs[ $c ] ) ? $c - 2 : $c - 1;
		
		foreach ( $paragraphs as $index => $paragraph ) {
			
			if ( trim( $paragraph ) ) {
				$paragraphs[ $index ] .= $closing_p;
			}
			
			if ( $index + $paragraph_id === $c ) {
				$paragraphs[ $index ] .= $insertion;
			}
		}
		
		return implode( '', $paragraphs );
	}
}

U_Shortcodes::init();