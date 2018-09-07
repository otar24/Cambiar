<?php
/**
 * Post Types
 *
 * Registers post types and taxonomies.
 *
 * @class     U_Post_Types
 * @version   1.0.0
 * @package   U_Theme/Classes
 * @category  Class
 * @author    uCAT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * U_Post_Types Class.
 */
class U_Post_Types {

	/**
	 * Hook in methods.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_post_types' ), 5 );
        add_action( 'init', array( __CLASS__, 'register_taxonomies' ), 5 );
        add_action( 'the_post', array( __CLASS__, 'the_post' ), 5, 1 );
        add_action( 'term_links-post_tag', array( __CLASS__, 'add_hash_to_post_tags_list' ), 5, 1 );
	}

	/**
	 * Register core post types.
	 */
	public static function register_post_types() {
		if ( post_type_exists('strategy') ) {
			return;
		}
		
		register_post_type(
			'strategy',
				array(
					'labels'              => array(
							'name'                  => __( 'Strategies', 'utheme' ),
							'singular_name'         => _x( 'Strategy', 'Strategy post type singular name', 'utheme' ),
							'add_new'               => __( 'Add Strategy', 'utheme' ),
							'add_new_item'          => __( 'Add New Strategy', 'utheme' ),
							'edit'                  => __( 'Edit', 'utheme' ),
							'edit_item'             => __( 'Edit Strategy', 'utheme' ),
							'new_item'              => __( 'New Strategy', 'utheme' ),
							'view'                  => __( 'View Strategy', 'utheme' ),
							'view_item'             => __( 'View Strategy', 'utheme' ),
							'search_items'          => __( 'Search Strategies', 'utheme' ),
							'not_found'             => __( 'No Strategies found', 'utheme' ),
							'not_found_in_trash'    => __( 'No Strategies found in trash', 'utheme' ),
							'parent'                => __( 'Parent Strategies', 'utheme' ),
							'menu_name'             => _x( 'Strategies', 'Admin menu name', 'utheme' ),
							'filter_items_list'     => __( 'Filter Strategies', 'utheme' ),
							'items_list_navigation' => __( 'Strategies navigation', 'utheme' ),
							'items_list'            => __( 'Strategies list', 'utheme' ),
						),
					'description'         => __( 'This is where Strategies are stored.', 'utheme' ),
					'public'              => true,
					'capability_type'     => 'post',
					'map_meta_cap'        => true,
					'show_in_menu'        => true,
					'hierarchical'        => false,
					'query_var'           => false,
					'supports'            => array( 'title', 'page-attributes' ),
					'has_archive'         => true,
					'menu_icon'           => 'dashicons-chart-line',
                    'rewrite'             => array( 'slug' => 'strategy', 'with_front' => false, 'feeds' => true )
				)
		);

        register_post_type(
            'people',
            array(
                'labels'              => array(
                    'name'                  => __( 'People', 'utheme' ),
                    'singular_name'         => _x( 'People', 'People post type singular name', 'utheme' ),
                    'add_new'               => __( 'Add Person', 'utheme' ),
                    'add_new_item'          => __( 'Add New', 'utheme' ),
                    'edit'                  => __( 'Edit', 'utheme' ),
                    'edit_item'             => __( 'Edit People', 'utheme' ),
                    'new_item'              => __( 'New People', 'utheme' ),
                    'view'                  => __( 'View People', 'utheme' ),
                    'view_item'             => __( 'View People', 'utheme' ),
                    'search_items'          => __( 'Search People', 'utheme' ),
                    'not_found'             => __( 'No People found', 'utheme' ),
                    'not_found_in_trash'    => __( 'No People found in trash', 'utheme' ),
                    'parent'                => __( 'Parent People', 'utheme' ),
                    'menu_name'             => _x( 'People', 'Admin menu name', 'utheme' ),
                    'filter_items_list'     => __( 'Filter People', 'utheme' ),
                    'items_list_navigation' => __( 'People navigation', 'utheme' ),
                    'items_list'            => __( 'People list', 'utheme' ),
                ),
                'description'         => __( 'This is where People are stored.', 'utheme' ),
                'public'              => true,
                'show_ui'             => true,
                'capability_type'     => 'post',
                'map_meta_cap'        => true,
                'publicly_queryable'  => true,
                'show_in_menu'        => true,
                'hierarchical'        => false,
                'show_in_nav_menus'   => false,
                'rewrite'             => true,
                'query_var'           => true,
                'supports'            => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
                'has_archive'         => false,
                'menu_icon'           => 'dashicons-groups',
            )
        );

        register_post_type(
            'managers',
            array(
                'labels'              => array(
                    'name'                  => __( 'Territory Managers', 'utheme' ),
                    'singular_name'         => __( 'Manager', 'utheme' ),
                    'add_new'               => __( 'Add Manager', 'utheme' ),
                    'add_new_item'          => __( 'Add New', 'utheme' ),
                    'edit'                  => __( 'Edit', 'utheme' ),
                    'edit_item'             => __( 'Edit Manager', 'utheme' ),
                    'new_item'              => __( 'New Manager', 'utheme' ),
                    'view'                  => __( 'View Manager', 'utheme' ),
                    'view_item'             => __( 'View Manager', 'utheme' ),
                    'search_items'          => __( 'Search Manager', 'utheme' ),
                    'not_found'             => __( 'No Manager found', 'utheme' ),
                    'not_found_in_trash'    => __( 'No Manager found in trash', 'utheme' ),
                    'parent'                => __( 'Parent Manager', 'utheme' ),
                    'menu_name'             => _x( 'Territory Managers', 'Admin menu name', 'utheme' ),
                    'filter_items_list'     => __( 'Filter Managers', 'utheme' ),
                    'items_list_navigation' => __( 'Managers navigation', 'utheme' ),
                    'items_list'            => __( 'Manager list', 'utheme' ),
                ),
                'description'         => __( 'This is where Regions are stored.', 'utheme' ),
                'public'              => true,
                'show_ui'             => true,
                'capability_type'     => 'post',
                'map_meta_cap'        => true,
                'publicly_queryable'  => true,
                'show_in_menu'        => true,
                'hierarchical'        => false,
                'show_in_nav_menus'   => false,
                'query_var'           => false,
                'supports'            => array( 'title' ),
                'has_archive'         => false,
                'menu_icon'           => 'dashicons-location-alt',
            )
        );
		
		register_post_type(
			'campaign-monitor',
			array(
				'labels'              => array(
					'name'                  => __( 'Campaign Monitor', 'utheme' ),
					'singular_name'         => __( 'Campaign Monitor', 'utheme' ),
					'add_new'               => __( 'Add Form', 'utheme' ),
					'add_new_item'          => __( 'Add New', 'utheme' ),
					'edit'                  => __( 'Edit', 'utheme' ),
					'edit_item'             => __( 'Edit Form', 'utheme' ),
					'new_item'              => __( 'New Form', 'utheme' ),
					'view'                  => __( 'View Form', 'utheme' ),
					'view_item'             => __( 'View Form', 'utheme' ),
					'search_items'          => __( 'Search Campaign Monitor forms', 'utheme' ),
					'not_found'             => __( 'No Campaign Monitor forms found', 'utheme' ),
					'not_found_in_trash'    => __( 'No Campaign Monitor forms found in trash', 'utheme' ),
					'parent'                => __( 'Parent Form', 'utheme' ),
					'menu_name'             => _x( 'Campaign Monitor', 'Admin menu name', 'utheme' ),
					'filter_items_list'     => __( 'Filter forms', 'utheme' ),
					'items_list_navigation' => __( 'Campaign Monitor forms navigation', 'utheme' ),
					'items_list'            => __( 'Campaign Monitor forms list', 'utheme' ),
				),
				'description'         => __( 'This is where Campaign Monitor forms are stored.', 'utheme' ),
				'public'              => true,
				'show_ui'             => true,
				'capability_type'     => 'post',
				'map_meta_cap'        => true,
				'publicly_queryable'  => true,
				'show_in_menu'        => true,
				'hierarchical'        => false,
				'show_in_nav_menus'   => false,
				'query_var'           => false,
				'supports'            => array( 'title' ),
				'has_archive'         => false,
				'menu_icon'           => 'dashicons-forms',
			)
		);

	}

    /**
     * Register core taxonomies.
     */
    public static function register_taxonomies() {

        if ( ! is_blog_installed() ) {
            return;
        }

        if ( taxonomy_exists( 'strategy_category' ) ) {
            return;
        }

        register_taxonomy( 'strategy_category',
            array( 'strategy'),
            array(
                'hierarchical'          => false,
                'label'                 => __( 'Categories', 'utheme' ),
                'show_admin_column'     => true,
                'show_ui'               => true,
                'query_var'             => true,
                'rewrite'          => array(
                    'slug'         => 'category',
                    'with_front'   => false
                ),
                'labels' => array(
                    'name'              => __( 'Category', 'utheme' ),
                    'singular_name'     => __( 'Category', 'utheme' ),
                    'menu_name'         => _x( 'Categories', 'Admin menu name', 'utheme' ),
                    'search_items'      => __( 'Search categories', 'utheme' ),
                    'all_items'         => __( 'All categories', 'utheme' ),
                    'parent_item'       => __( 'Parent category', 'utheme' ),
                    'parent_item_colon' => __( 'Parent category:', 'utheme' ),
                    'edit_item'         => __( 'Edit category', 'utheme' ),
                    'update_item'       => __( 'Update category', 'utheme' ),
                    'add_new_item'      => __( 'Add new category', 'utheme' ),
                    'new_item_name'     => __( 'New category name', 'utheme' ),
                    'not_found'         => __( 'No categories found', 'utheme' ),
                ),
            )
        );

        register_taxonomy( 'strategy_group',
            array( 'strategy'),
            array(
                'hierarchical'          => false,
                'label'                 => __( 'Product', 'utheme' ),
                'show_admin_column'     => true,
                'show_ui'               => true,
                'query_var'             => true,
                'rewrite'          => array(
                    'slug'         => 'group',
                    'with_front'   => false
                ),
                'labels' => array(
                    'name'              => __( 'Product', 'utheme' ),
                    'singular_name'     => __( 'Product', 'utheme' ),
                    'menu_name'         => _x( 'Products', 'Admin menu name', 'utheme' ),
                    'search_items'      => __( 'Search products', 'utheme' ),
                    'all_items'         => __( 'All products', 'utheme' ),
                    'parent_item'       => __( 'Parent product', 'utheme' ),
                    'parent_item_colon' => __( 'Parent product:', 'utheme' ),
                    'edit_item'         => __( 'Edit product', 'utheme' ),
                    'update_item'       => __( 'Update product', 'utheme' ),
                    'add_new_item'      => __( 'Add new product', 'utheme' ),
                    'new_item_name'     => __( 'New product name', 'utheme' ),
                    'not_found'         => __( 'No products found', 'utheme' ),
                ),
            )
        );

        register_taxonomy( 'share_class',
            array( 'strategy'),
            array(
                'hierarchical'          => false,
                'label'                 => __( 'Share Class', 'utheme' ),
                'show_admin_column'     => true,
                'show_ui'               => true,
                'query_var'             => true,
                'rewrite'          => array(
                    'slug'         => 'share_class',
                    'with_front'   => false
                ),
                'labels' => array(
                    'name'              => __( 'Share Class', 'utheme' ),
                    'singular_name'     => __( 'Share Class', 'utheme' ),
                    'menu_name'         => _x( 'Share Class', 'Admin menu name', 'utheme' ),
                    'search_items'      => __( 'Search Share Class', 'utheme' ),
                    'all_items'         => __( 'All Share Classes', 'utheme' ),
                    'parent_item'       => __( 'Parent Share Class', 'utheme' ),
                    'parent_item_colon' => __( 'Parent Share Class:', 'utheme' ),
                    'edit_item'         => __( 'Edit Share Class', 'utheme' ),
                    'update_item'       => __( 'Update Share Class', 'utheme' ),
                    'add_new_item'      => __( 'Add new Class', 'utheme' ),
                    'new_item_name'     => __( 'New Class name', 'utheme' ),
                    'not_found'         => __( 'No Share Classes found', 'utheme' ),
                ),
            )
        );

        register_taxonomy( 'geography',
            array( 'strategy'),
            array(
                'hierarchical'          => false,
                'label'                 => __( 'Geography', 'utheme' ),
                'show_admin_column'     => true,
                'show_ui'               => true,
                'query_var'             => true,
                'rewrite'          => array(
                    'slug'         => 'geography',
                    'with_front'   => false
                )
            )
        );
	
	    /**
	     * Registers taxonomy for the People custom post type
	     */
	    register_taxonomy( 'department',
		    array( 'people'),
		    array(
			    'hierarchical'          => true,
			    'label'                 => __( 'Departments', 'utheme' ),
			    'show_admin_column'     => true,
			    'show_ui'               => true,
			    'query_var'             => true,
			    'rewrite'          => array(
				    'slug'         => 'department',
				    'with_front'   => false
			    ),
			    'labels' => array(
				    'name'              => __( 'Department', 'utheme' ),
				    'singular_name'     => __( 'Department', 'utheme' ),
				    'menu_name'         => _x( 'Departments', 'Admin menu name', 'utheme' ),
				    'search_items'      => __( 'Search departments', 'utheme' ),
				    'all_items'         => __( 'All departments', 'utheme' ),
				    'parent_item'       => __( 'Parent department', 'utheme' ),
				    'parent_item_colon' => __( 'Parent department:', 'utheme' ),
				    'edit_item'         => __( 'Edit department', 'utheme' ),
				    'update_item'       => __( 'Update department', 'utheme' ),
				    'add_new_item'      => __( 'Add new department', 'utheme' ),
				    'new_item_name'     => __( 'New department name', 'utheme' ),
				    'not_found'         => __( 'No departments found', 'utheme' ),
			    ),
		    )
	    );
    }

    public static function the_post($post){
        global $the_strategy;
        if( $post->post_type === 'strategy'){
            $the_strategy = u_get_strategy( $post->ID );
        }else{
            $the_strategy = null;
        }
    }

    public static function add_hash_to_post_tags_list($links){
        foreach ($links as $link_i => &$link){
            $link = preg_replace('#(<a[^>]+>)([^<]+)(<\/a>)#', "$1#$2$3", $link);
        }
        return $links;
    }

}

U_Post_Types::init();
