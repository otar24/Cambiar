<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Abstract Post
 *
 * Handles generic post data and database interaction which is extended by both
 * U_Strategy and U_People.
 *
 *
 * @class     U_Abstract_Post
 * @version   1.0.0
 * @package   U_Theme/Classes
 * @category  Class
 * @author    Elena Zhyvohliad
 */
class U_Abstract_Post {
	/** @public int Post (post) ID. */
	public $id                          = 0;

	/** @var $post WP_Post. */
	public $post                        = null;

	/** @public string Status. */
	public $post_status                 = '';

	/** @public string Date. */
	public $post_date                  = '';

	/** @public string Modified Date. */
	public $modified_date               = '';


    /**
     * Get the order if ID is passed, otherwise the order is new and empty.
     * This class should NOT be instantiated, but the get_order function or new WC_Order_Factory.
     * should be used. It is possible, but the aforementioned are preferred and are the only.
     * methods that will be maintained going forward.
     *
     * @param  int|object
     */
	public function __construct( $post = 0 ) {
		$this->init( $post );
	}

	/**
	 * Init/load the strategy object. Called from the constructor.
	 *
	 * @param  int|object $post Post to init.
	 */
	protected function init( $post ) {
		if ( is_numeric( $post ) ) {
			$this->id   = absint( $post );
			$this->post = get_post( $post );
			$this->get_post( $this->id );
		} elseif ( $post instanceof WP_Post ) {
            $this->id   = absint( $post->ID );
            $this->post = $post->post;
            $this->get_post( $this->id );
        } elseif ( isset( $post->ID ) ) {
			$this->id   = absint( $post->ID );
			$this->post = $post;
			$this->get_post( $this->id );
		}
	}


	/**
	 * Gets an strategy from the database.
	 *
	 * @param int $id (default: 0).
	 * @return bool
	 */
	public function get_post( $id = 0 ) {

		if ( ! $id ) {
			return false;
		}

		if ( $result = get_post( $id ) ) {
			$this->populate( $result );
			return true;
		}

		return false;
	}

	/**
	 * Populates an post from the loaded post data.
	 *
	 * @param mixed $result
	 */
	public function populate( $result ) {

		// Standard post data
		$this->id                  = $result->ID;
		$this->date_registered     = $result->post_date;
		$this->modified_date       = $result->post_modified;
		$this->post_status         = $result->post_status;

	}

	/**
	 * __isset function.
	 *
	 * @param mixed $key
	 * @return bool
	 */
	public function __isset( $key ) {

		if ( ! $this->id ) {
			return false;
		}

		return metadata_exists( 'post', $this->id, '_' . $key );
	}

	/**
	 * __get function.
	 *
	 * @param mixed $key
	 * @return mixed
	 */
	public function __get( $key ) {
		// Get values or default if not set.

        $value = get_post_meta( $this->id, '_' . $key, true );

		return $value;
	}

    /**
     * Returns the unique ID for this object.
     *
     * @return int
     */
    public function get_id() {
        return $this->id;
    }

    public function get_terms( $taxonomy = '', $args = [] ){
        if( empty($taxonomy) ) return false;
        $defaults = array('fields' => 'all');
        wp_parse_args( $args, $defaults );
        
        $terms = wp_get_post_terms( $this->id, $taxonomy, $args );
        return $terms;
    }

    /**
     * Get the product's title. For products this is the product name.
     *
     * @return string
     */
    public function get_title() {
        return get_the_title($this->get_id());
    }

    /**
     * Post permalink.
     * @return string
     */
    public function get_permalink() {
        return get_permalink( $this->get_id() );
    }

    /**
     * Post permalink.
     * @return string
     */
    public function get_excerpt() {
        $post_excerpt = get_post_field('post_excerpt', $this->get_id() );
        if ( empty( $post_excerpt ) ) {
            $post_content = get_post_field('post_content', $this->get_id() );
            return wp_kses_post( wp_trim_words( $post_content, 20 ) );
        }
        return wp_kses_post( $post_excerpt );
    }

    /**
    * Return the post thumbnail URL.
    *
    * @param string|array $size Optional. Registered image size to retrieve the source for or a flat
    *                           array of height and width dimensions. Default 'post-thumbnail'.
    * @return string|false Post thumbnail URL or false if no URL is available.
    */
    public function get_thumbnail_url($size = 'post-thumbnail') {
        return get_the_post_thumbnail_url( $this->get_id(), $size );
    }

    /**
     * Retrieve the post thumbnail.
     *
     * @param string|array $size Optional. Registered image size to retrieve the source for or a flat
     *                           array of height and width dimensions. Default 'post-thumbnail'.
     * @param string|array $attr Optional. Query string or array of attributes. Default empty.
     * @return string The post thumbnail image tag.
     */
    public function get_thumbnail( $size = 'post-thumbnail', $attr = '') {
        return get_the_post_thumbnail( $this->get_id(), $size, $attr);
    }
}
