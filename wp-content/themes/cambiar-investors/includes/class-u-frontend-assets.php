<?php
/**
 * Load assets
 *
 * @author      uCAT
 * @package     U_Theme/Classes/Assets
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'U_Frontend_Assets' ) ) :

	/**
	 * U_Frontend_Assets Class.
	 */
	class U_Frontend_Assets {

		/**
		 * Hook in tabs.
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'add_styles' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts' ) );
		}

		/**
		 * Enqueue styles.
		 */
		public function add_styles() {
			// Theme stylesheet.
			#wp_enqueue_style( 'utheme-style', get_stylesheet_uri() );

			wp_enqueue_style( 'utheme-jvectormap', u_get_assets_uri( 'css/jquery-jvectormap-2.0.3.css' ), array(), '2.0.3' );
			wp_enqueue_style( 'utheme-frontend', u_get_assets_uri( 'css/frontend.css' ), array(), U_THEME_VERSION );
			wp_enqueue_style( 'utheme-general-style', u_get_assets_uri( 'css/style.css' ), array(), U_THEME_VERSION );
		}


		/**
		 * Enqueue scripts.
		 */
		public function add_scripts() {
			wp_deregister_script( 'jquery' );
			wp_register_script( 'jquery', u_get_assets_uri( 'js/jquery-3.2.1.min.js' ), [], '3.2.1' );
			wp_enqueue_script( 'utheme-scripts', u_get_assets_uri( 'js/jquery.main.js' ), [ 'jquery' ], U_THEME_VERSION, true );

			if ( is_page_template( 'template-contact.php' ) && ( $api_key = get_field( 'gmap_api_key', 'options' ) ) ) {
				wp_enqueue_script( 'gmaps', 'https://maps.googleapis.com/maps/api/js?key=' . $api_key, array( 'utheme-scripts' ), U_THEME_VERSION, true );
			}

			wp_enqueue_script( 'utheme-jvectormap', u_get_assets_uri( 'js/jquery-jvectormap.min.js' ), [ 'jquery' ], '2.0.3', true );
			wp_enqueue_script( 'utheme-jvectormap-us-aea', u_get_assets_uri( 'js/jquery-jvectormap-us-aea.js' ), [ 'utheme-jvectormap' ], '2.0.3', true );

			wp_enqueue_script( 'jquery-validate', u_get_assets_uri( 'js/jquery.validate.min.js' ), [ 'jquery' ], '1.17.0', true );
			wp_enqueue_script( 'utheme-campaign-monitor-scripts', u_get_assets_uri( 'js/campaign-monitor.js' ), [ 'jquery-validate' ], U_THEME_VERSION, true );
			wp_enqueue_script( 'utheme-youtube-scripts', u_get_assets_uri( 'js/youtube.api.js' ), array( 'jquery' ), U_THEME_VERSION );

			$depth = [ 'utheme-jvectormap', 'utheme-jvectormap-us-aea' ];
			wp_enqueue_script( 'utheme-frontend-scripts', u_get_assets_uri( 'js/frontend.js' ), $depth, rand(), true );
			wp_enqueue_script( 'utheme-main-scripts', u_get_assets_uri( 'js/main.js' ), $depth, rand(), true );

			if ( is_singular( 'strategy' ) ) {
				global $post;
				$the_strategy = u_get_strategy( $post->ID );
				$args         = array(
					'ajax_url'  => admin_url( 'admin-ajax.php' ),
					'chartData' => $the_strategy->getChartData(),
					'colors'    => u_get_colors()
				);
				wp_localize_script( 'utheme-frontend-scripts', 'utheme_charts_args', $args );
			}
		}

	}

endif;

return new U_Frontend_Assets();
