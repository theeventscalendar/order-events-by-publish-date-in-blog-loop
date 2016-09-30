<?php
/**
 * Plugin Name: The Events Calendar Extension: Order Events by Publish Date in Blog Loop
 * Description: Make events that end up in the blog loop per the "Show events in blog loop" option show up sorted by their publish date.
 * Version: 1.0.0
 * Author: Modern Tribe, Inc.
 * Author URI: http://m.tri.be/1971
 * License: GPLv2 or later
 */

defined( 'WPINC' ) or die;

class Tribe__Extension__Order_Events_by_Publish_Date_in_Blog_Loop {

    /**
     * The semantic version number of this extension; should always match the plugin header.
     */
    const VERSION = '1.0.0';

    /**
     * Each plugin required by this extension
     *
     * @var array Plugins are listed in 'main class' => 'minimum version #' format
     */
    public $plugins_required = array(
        'Tribe__Events__Main' => '4.2'
    );

    /**
     * The constructor; delays initializing the extension until all other plugins are loaded.
     */
    public function __construct() {
        add_action( 'plugins_loaded', array( $this, 'init' ), 100 );
    }

    /**
     * Extension hooks and initialization; exits if the extension is not authorized by Tribe Common to run.
     */
    public function init() {

        // Exit early if our framework is saying this extension should not run.
        if ( ! function_exists( 'tribe_register_plugin' ) || ! tribe_register_plugin( __FILE__, __CLASS__, self::VERSION, $this->plugins_required ) ) {
            return;
        }

        add_action( 'pre_get_posts', array( $this, 'post_date_ordering' ), 51 );
    }

    /**
     * Order the blog-loop events by their publish date.
     *
     * @param object $query
     */
    public function post_date_ordering( $query ) {

        if ( ! empty( $query->tribe_is_multi_posttype ) ) {

            remove_filter( 'posts_fields', array( 'Tribe__Events__Query', 'multi_type_posts_fields' ) );
            $query->set( 'order', 'DESC' );
        }
    }
}

new Tribe__Extension__Order_Events_by_Publish_Date_in_Blog_Loop();
