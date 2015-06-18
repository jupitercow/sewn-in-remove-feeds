<?php

/**
 * @link              https://github.com/jupitercow/sewn-in-remove-feeds
 * @since             1.0.1
 * @package           Sewn_Remove_Feeds
 *
 * @wordpress-plugin
 * Plugin Name:       Sewn In Remove Feeds
 * Plugin URI:        https://wordpress.org/plugins/sewn-in-remove-feeds/
 * Description:       Disable feeds on non-blog sites to stop leaking information out through unused feeds.
 * Version:           1.0.1
 * Author:            jcow
 * Author URI:        http://Jupitercow.com/
 * Contributer:       ekaj
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$class_name = 'Sewn_Remove_Feeds';
if (! class_exists($class_name) ) :

class Sewn_Remove_Feeds
{
	/**
	 * The unique prefix for Sewn In.
	 *
	 * @since    1.0.1
	 * @access   protected
	 * @var      string    $prefix         The string used to uniquely prefix for Sewn In.
	 */
	protected $prefix;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.1
	 * @access   protected
	 * @var      string    $settings       The array used for settings.
	 */
	protected $settings;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * @since    1.0.1
	 */
	public function __construct()
	{
		$this->prefix      = 'sewn';
		$this->settings    = array(
			'feed_types' => array( '', 'rdf', 'rss', 'rss2', 'atom', 'rss2_comments', 'atom_comments' ),
		);
		$this->settings = apply_filters( "{$this->prefix}/remove_feeds/settings", $this->settings );
	}

	/**
	 * Load the plugin.
	 *
	 * @since	1.0.1
	 * @return	void
	 */
	public function run()
	{
		remove_action( 'wp_head', 'feed_links', 2 );
		remove_action( 'wp_head', 'feed_links_extra', 3 );

		foreach ( apply_filters( $this->prefix . '/remove_feeds/all_feeds', $this->settings['feed_types'] ) as $feed )
		{
			if ( $feed )
			{
				if ( apply_filters( $this->prefix . '/remove_feeds/type=' . $feed, true ) ) {
					add_action( 'do_feed_' . $feed, array($this, 'disable_feeds'), 1 );
				}
			}
		}
	}

	/**
	 * Disable the feeds by redirecting them to the homepage
	 *
	 * @author  ekaj
	 * @since	1.0.0
	 * @return	void
	 */
	public function disable_feeds()
	{
		wp_redirect( home_url('/'), 302 );
		die;
	}
}

$$class_name = new $class_name;
$$class_name->run();
unset($class_name);

endif;