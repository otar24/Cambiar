<?php
/**
 * Theme functions and definitions
 *
 * @package U_Theme
 * @subpackage U_Theme
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'U_Theme_Setup' ) ) :
	
	/**
	 * Main Theme Class.
	 *
	 * @class UThemeSetup
	 * @version    1.0.0
	 */
	final class U_Theme_Setup {
		
		const MINIMUM_PHP_VERSION = '7.0';
		const MINIMUM_WP_VERSION = '4.9';
		
		/**
		 * Theme version.
		 *
		 * @var string
		 */
		public $version = '1.2.0';
		
		/**
		 * The single instance of the class.
		 *
		 * @var UThemeSetup
		 */
		protected static $_instance = null;
		
		/**
		 * Strategy factory instance.
		 *
		 * @var U_Strategy_Factory
		 */
		public $strategy_factory = null;
		
		/**
		 * Strategy factory instance.
		 *
		 * @var U_Manager_Factory
		 */
		public $manager_factory = null;
		
		/**
		 * Main UThemeSetup Instance.
		 *
		 * Ensures only one instance of UThemeSetup is loaded or can be loaded.
		 *
		 * @static
		 * @see UThemeSetup()
		 * @return UThemeSetup - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			
			return self::$_instance;
		}
		
		/**
		 * Cloning is forbidden.
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'utheme' ), '1.0.0' );
		}
		
		/**
		 * Unserializing instances of this class is forbidden.
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'utheme' ), '1.0.0' );
		}
		
		/**
		 * Auto-load in-accessible properties on demand.
		 *
		 * @param mixed $key
		 *
		 * @return mixed
		 */
		public function __get( $key ) {
			if ( in_array( $key, array( 'test' ) ) ) {
				return $this->$key();
			}
		}
		
		/**
		 * UThemeSetup Constructor.
		 */
		public function __construct() {
			$this->define_constants();
			$this->includes();
			$this->include_vendors();
			$this->init_hooks();
			
			/**
			 * The Theme only works in WordPress 4.9 or later.
			 *
			 * @global string $wp_version WordPress version.
			 */
			if ( version_compare( $GLOBALS['wp_version'], self::MINIMUM_WP_VERSION . '-alpha', '<' ) ) {
				$args = array(
					'minimum_version' => self::MINIMUM_WP_VERSION,
					'current_version' => $GLOBALS['wp_version'],
					'title'           => 'WordPress',
				);
				new U_Back_Compat( $args );
				
				return;
			}
			
			// Check for required PHP version
			if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
				$args = array(
					'minimum_version' => self::MINIMUM_PHP_VERSION,
					'current_version' => phpversion(),
					'title'           => 'PHP',
				);
				new U_Back_Compat( $args );
				
				return;
			}
			
			do_action( 'utheme_loaded' );
		}
		
		/**
		 * Hook into actions and filters.
		 */
		private function init_hooks() {
			add_action( 'tgmpa_register', array( 'U_Require_Plugins', 'register_required_plugins' ) );
			add_action( 'after_setup_theme', array( $this, 'setup' ), 0 );
			add_action( 'init', array( $this, 'init' ), 0 );
			add_action( 'wp', [ $this, 'wp' ] );
			
			add_action( 'widgets_init', array( $this, 'widgets_init' ) );
			
			add_filter( 'get_custom_logo', array( $this, 'add_class_to_logo' ), 10, 2 );
			add_filter( 'excerpt_more', array( $this, 'excerpt_more' ) );
			add_filter( 'widget_posts_args', [ $this, 'widget_posts_args' ], 10, 2 );
			add_filter( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
			add_filter( 'posts_join', [ self::class, 'posts_join' ], 10, 2 );
			add_filter( 'posts_where', [ self::class, 'posts_where' ], 10, 2 );
			add_filter( 'posts_groupby', [ self::class, 'posts_groupby' ], 10, 2 );
			
			add_filter( 'acf/fields/relationship/result/key=field_5b4dbf09baae2', [ $this, 'relationshipResults' ], 10, 4 );
			
			if ( ! WP_DEBUG ) {
				add_filter( 'show_admin_bar', '__return_false' );
			}
		}
		
		/**
		 * Define Constants.
		 */
		private function define_constants() {
			$upload_dir = wp_upload_dir();
			
			$this->define( 'U_THEME_VERSION', $this->version );
			$this->define( 'U_THEME_FILE', __FILE__ );
			
			if ( ! defined( 'U_THEME_DEV' ) ) {
				$this->define( 'ACF_LITE', true );
			}
			
		}
		
		
		/**
		 * Define constant if not already set.
		 *
		 * @param  string $name
		 * @param  string|bool $value
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}
		
		/**
		 * What type of request is this?
		 *
		 * @param  string $type admin, ajax, cron or frontend.
		 *
		 * @return bool
		 */
		private function is_request( $type ) {
			switch ( $type ) {
				case 'admin' :
					return is_admin();
				case 'ajax' :
					return defined( 'DOING_AJAX' );
				case 'cron' :
					return defined( 'DOING_CRON' );
				case 'frontend' :
					return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
			}
		}
		
		
		/**
		 * Include required core files used in admin and on the frontend.
		 */
		public function includes() {
			
			/**
			 * Abstract classes.
			 */
			include_once( 'abstracts/abstract-u-post.php' ); // U_Post for CRUD.
			
			
			include_once( 'class-u-autoloader.php' );
			include_once( 'u-core-functions.php' );
			include_once( 'class-u-ajax.php' );
			include_once( 'class-u-shortcodes.php' );
			
			/**
			 * Widget classes.
			 */
			include_once( 'widgets/class-u-widget-related-posts.php' );
			include_once( 'widgets/class-u-widget-recent-posts.php' );
			
			
			if ( $this->is_request( 'admin' ) ) {
				include_once( 'admin/class-u-admin.php' );
			}
			
			include_once( 'vendor/advanced-custom-fields/acf.php' );
			if ( ! defined( 'U_THEME_DEV' ) ) {
				include_once( 'class-u-acf.php' );
			}
			include_once( 'class-u-post-types.php' ); // Registers post types
			
			/**
			 * Include required frontend files.
			 */
			include_once( 'class-u-frontend-assets.php' );
		}
		
		/**
		 * Include required vendor files
		 */
		public function include_vendors() {
			
			include_once( 'vendor/class-tgm-plugin-activation.php' );
		}
		
		/**
		 * Sets up theme defaults and registers support for various WordPress features.
		 *
		 * Note that this function is hooked into the after_setup_theme hook, which
		 * runs before the init hook. The init hook is too late for some features, such
		 * as indicating support for post thumbnails.
		 */
		public function setup() {
			// Before setup theme.
			do_action( 'before_utheme_setup' );
			
			$this->load_textdomain(); // Set up localisation
			$this->add_theme_support(); //Add theme supports
			
			$this->add_image_size();
			$this->register_nav_menus();
			
			// Load class instances.
			$this->strategy_factory = new U_Strategy_Factory(); // Strategy Factory to create new strategy instances
			$this->manager_factory  = new U_Manager_Factory(); // Manager Factory to create new manager instances
			
			// Setup theme action.
			do_action( 'utheme_setup' );
		}
		
		/**
		 * Init Theme when WordPress Initialises.
		 */
		public function init() {
			if ( ! session_id() ) {
				session_start();
			}
			
			// Before init action.
			do_action( 'before_utheme_init' );
			
			// Init action.
			do_action( 'utheme_init' );
		}
		
		public function wp() {
			global $post;
			
			if ( is_singular( 'people' ) ) {
				$_SESSION['single_person_department'] = wp_get_object_terms( $post->ID, 'department', [ 'fields' => 'ids' ] );
			}
		}
		
		/**
		 * Register widget area.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
		 */
		public function widgets_init() {
			
			unregister_widget( 'WP_Widget_Recent_Posts' );
			
			register_widget( 'U_Widget_Related_Posts' );
			register_widget( 'U_Widget_Recent_Posts' );
			
			
			register_sidebar( array(
				'name'          => __( 'Footer Text', 'utheme' ),
				'id'            => 'footer-text',
				'description'   => __( 'Add widgets here to appear in your footer.', 'utheme' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			) );
			
			register_sidebar( array(
				'name'          => __( 'Footer 1', 'utheme' ),
				'id'            => 'footer-1',
				'description'   => __( 'Add widgets here to appear in your footer.', 'utheme' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			) );
			
			register_sidebar( array(
				'name'          => __( 'Footer 2', 'utheme' ),
				'id'            => 'footer-2',
				'description'   => __( 'Add widgets here to appear in your footer.', 'utheme' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			) );
			
			register_sidebar( array(
				'name'          => __( 'Blog', 'utheme' ),
				'id'            => 'sidebar-blog',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			) );
		}
		
		public function widget_posts_args( $args, $instance ) {
			if ( ! is_admin() && is_single() ) {
				$post_id              = get_queried_object_id();
				$args['post__not_in'] = [ $post_id ];
				$args['category__in'] = wp_get_post_categories( $post_id );
			}
			
			return $args;
		}
		
		/**
		 *
		 */
		public function add_theme_support() {
			// Add default posts and comments RSS feed links to head.
			add_theme_support( 'automatic-feed-links' );
			
			/*
			 * Let WordPress manage the document title.
			 * By adding theme support, we declare that this theme does not use a
			 * hard-coded <title> tag in the document head, and expect WordPress to
			 * provide it for us.
			 */
			add_theme_support( 'title-tag' );
			
			/*
			 * Enable support for Post Thumbnails on posts and pages.
			 *
			 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
			 */
			add_theme_support( 'post-thumbnails' );
			
			/*
			 * Switch default core markup for search form, comment form, and comments
			 * to output valid HTML5.
			 */
			add_theme_support( 'html5', array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			) );
			
			/*
			 * Enable support for Post Formats.
			 *
			 * See: https://codex.wordpress.org/Post_Formats
			 */
			add_theme_support( 'post-formats', array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio',
			) );
			
			// Add theme support for Custom Logo.
			add_theme_support( 'custom-logo', array(
				'width'       => 192,
				'height'      => 48,
				'flex-width'  => true,
				'flex-height' => true,
			) );
			
			// Add theme support for selective refresh for widgets.
			add_theme_support( 'customize-selective-refresh-widgets' );
			
			$starter_content = $this->get_starter_content();
			add_theme_support( 'starter-content', $starter_content );
		}
		
		
		private function get_starter_content() {
			// Define and register starter content to showcase the theme on new sites.
			$starter_content = array(
				'widgets'   => array(
					// Add the core-defined business info widget to the footer 1 area.
					'footer-1'    => array(),
					
					// Add the core-defined business info widget to the footer 2 area.
					'footer-2'    => array(),
					
					// Put two core-defined widgets in the footer text area.
					'footer-text' => array(
						'text_about'
					),
				),
				
				// Specify the core-defined pages to create and add custom thumbnails to some of them.
				'posts'     => array(
					'home',
					'about',
					'contact',
					'blog'
				),
				
				// Default to a static front page and assign the front and posts pages.
				'options'   => array(
					'show_on_front'  => 'page',
					'page_on_front'  => '{{home}}',
					'page_for_posts' => '{{blog}}',
				),
				
				// Set up nav menus for each of the two areas registered in the theme.
				'nav_menus' => array(
					// Assign a menu to the "main" location.
					'main'   => array(
						'name'  => __( 'Main Menu', 'utheme' ),
						'items' => array(
							'page_about',
							'page_blog',
							'page_contact',
						),
					),
					
					// Assign a menu to the "footer" location.
					'footer' => array(
						'name'  => __( 'Footer Menu', 'utheme' ),
						'items' => array(
							'link_home',
							'page_disclosure'
						),
					),
				),
			);
			
			/**
			 * Filters uTheme array of starter content.
			 *
			 * @param array $starter_content Array of starter content.
			 */
			return apply_filters( 'utheme_starter_content', $starter_content );
		}
		
		private function register_nav_menus() {
			register_nav_menus( array(
				'main'   => __( 'Main Menu', 'utheme' ),
				'footer' => __( 'Footer Menu', 'utheme' ),
			) );
		}
		
		private function add_image_size() {
			add_image_size( 'utheme-featured-image', 2000, 1200, true );
			add_image_size( 'post-thumbnail', 896, 448, true );
			add_image_size( 'utheme-thumbnail', 448, 448, true );
			add_image_size( 'utheme-manager-photo-small', 64, 64, true );
			add_image_size( 'post-thumbnail-small', 64, 64, true );
			add_image_size( 'thumbnail-morein', 192, 128, true );
			add_image_size( 'history-thumbnail', 576, 256, true );
		}
		
		/**
		 * Load Localisation files.
		 *
		 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
		 *
		 */
		public function load_textdomain() {
			$locale = apply_filters( 'locale', get_locale(), 'utheme' );
			
			load_textdomain( 'utheme', WP_LANG_DIR . '/utheme/utheme-' . $locale . '.mo' );
			load_theme_textdomain( 'utheme', get_template_directory() . '/i18n/languages' );
		}
		
		
		/**
		 * Get Ajax URL.
		 *
		 * @return string
		 */
		public function ajax_url() {
			return admin_url( 'admin-ajax.php', 'relative' );
		}
		
		
		public function add_class_to_logo( $html, $blog_id ) {
			$html = str_ireplace( 'custom-logo-link', 'custom-logo-link logo', $html );
			
			return $html;
		}
		
		/**
		 * Replaces "[...]" (appended to automatically generated excerpts) with ...
		 *
		 * @since Cambiar Investors 1.0
		 *
		 * @param string $link Link to single post/page.
		 *
		 * @return string 'Continue reading' link prepended with an ellipsis.
		 */
		public function excerpt_more( $link ) {
			if ( is_admin() ) {
				return $link;
			}
			
			return '&hellip;';
		}
		
		public function pre_get_posts( WP_Query $query ) {
			if ( ! is_admin() && $query->is_post_type_archive( [ 'strategy' ] ) ) {
				$query->set( 'orderby', 'menu_order' );
				$query->set( 'order', 'asc' );
			}
			
			if ( ( $query->is_home() || $query->is_category() ) && $query->is_main_query() ) {
				$query->set( 'posts_per_page', -1 );
			}
		}
		
		public static function posts_join( $join, WP_Query $query ) {
			if ( is_admin() ) {
				return $join;
			}
			
			global $wpdb;
			
			$object = get_queried_object();
			
			if (
				(
					( $query->is_home() || $query->is_category() ) && $query->is_main_query() &&
					strpos( $join, 'postmeta' ) === false
				) ||
				( ! is_null( $object ) && isset( $object->post_type ) && 'post' == $object->post_type && ! $query->is_main_query() )
			) {
				$join .= " LEFT JOIN $wpdb->postmeta ON ( $wpdb->posts.ID = $wpdb->postmeta.post_id )" ;
			}
			
			return $join;
		}
		
		public static function posts_where( $where, WP_Query $query ) {
			if ( is_admin() ) {
				return $where;
			}
			
			global $wpdb;
			
			$object = get_queried_object();
			
			if (
				( ( $query->is_home() || $query->is_category() ) && $query->is_main_query() ) ||
		        ( ! is_null( $object ) && isset( $object->post_type ) && 'post' == $object->post_type && ! $query->is_main_query() )
			) {
				$object_id = get_queried_object_id();
				
				if ( $query->is_home() ) {
					$categories_in = join( ',', get_terms( [
						'taxonomy' => 'category',
						'hide_empty' => false,
						'fields'     => 'ids',
					] ) );
				} elseif ( ! is_null( $object ) && 'post' == $object->post_type ) {
					$categories_in = join( ',', wp_get_post_categories( $object_id ) );
				} else {
					$categories_in = $object_id;
				}
				
				$where = preg_replace('/^\s?(AND|OR)/', ' $1 (', $where ) . " ) OR ({$wpdb->posts}.post_type = 'page' AND {$wpdb->posts}.post_status = 'publish' AND ({$wpdb->postmeta}.meta_key = 'put_to_the_category' AND {$wpdb->postmeta}.meta_value IN ({$categories_in}) ) )";
			}
			
			return $where;
		}
		
		public static function posts_groupby( $groupby, WP_Query $query ) {
			if ( is_admin() ) {
				return $groupby;
			}
			
			global $wpdb;
			
			$object = get_queried_object();
			
			if (
				( ( $query->is_home() || $query->is_category() ) && $query->is_main_query() ) ||
				( ! is_null( $object ) && isset( $object->post_type ) && 'post' == $object->post_type && ! $query->is_main_query() )
			) {
				if ( ! $groupby ) {
					$groupby .= "{$wpdb->posts}.ID";
				}
			}
			
			return $groupby;
		}
		
		/**
		 * Customize custom field Strategies (key field_5b4dbf09baae2) of type relationship
		 *
		 * @param $title
		 * @param $post
		 * @param $field
		 * @param $post_id
		 *
		 * @return string
		 */
		public function relationshipResults( $title, $post, $field, $post_id ) {
			if ( 'strategy' == $post->post_type && ( $ticker = $post->_ticker ) ) {
				$title .= ' - ' . $ticker;
			}
			
			return $title;
		}
	}

endif;