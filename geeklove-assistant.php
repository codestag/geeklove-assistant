<?php
/**
 * Plugin Name: Geeklove Assistant
 * Plugin URI: https://github.com/Codestag/geeklove-assistant
 * Description: A plugin to assist Geeklove theme in adding widgets.
 * Author: Codestag
 * Author URI: https://codestag.com
 * Version: 1.0
 * Text Domain: geeklove-assistant
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package Geeklove
 */


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Geeklove_Assistant' ) ) :
	/**
	 *
	 * @since 1.0
	 */
	class Geeklove_Assistant {

		/**
		 *
		 * @since 1.0
		 */
		private static $instance;

		/**
		 *
		 * @since 1.0
		 */
		public static function register() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Geeklove_Assistant ) ) {
				self::$instance = new Geeklove_Assistant();
				self::$instance->init();
				self::$instance->define_constants();
				self::$instance->includes();
			}
		}

		/**
		 *
		 * @since 1.0
		 */
		public function init() {
			add_action( 'enqueue_assets', 'plugin_assets' );
		}

		/**
		 *
		 * @since 1.0
		 */
		public function define_constants() {
			$this->define( 'GA_VERSION', '1.0' );
			$this->define( 'GA_DEBUG', true );
			$this->define( 'GA_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
			$this->define( 'GA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

		/**
		 *
		 * @param string $name
		 * @param string $value
		 * @since 1.0
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 *
		 * @since 1.0
		 */
		public function includes() {
			require_once GA_PLUGIN_PATH . 'includes/widgets/homepage-blog.php';
			require_once GA_PLUGIN_PATH . 'includes/widgets/homepage-countdown.php';
			require_once GA_PLUGIN_PATH . 'includes/widgets/homepage-divider.php';
			require_once GA_PLUGIN_PATH . 'includes/widgets/homepage-event.php';
			require_once GA_PLUGIN_PATH . 'includes/widgets/homepage-gallery.php';
			require_once GA_PLUGIN_PATH . 'includes/widgets/homepage-rsvp.php';
			require_once GA_PLUGIN_PATH . 'includes/widgets/homepage-tweets.php';
			require_once GA_PLUGIN_PATH . 'includes/widgets/homepage-wedding-intro.php';
			require_once GA_PLUGIN_PATH . 'includes/widgets/static-content.php';

			require_once GA_PLUGIN_PATH . 'includes/updater/updater.php';
		}
	}
endif;


/**
 *
 * @since 1.0
 */
function geeklove_assistant() {
	return Geeklove_Assistant::register();
}

/**
 *
 * @since 1.0
 */
function geeklove_assistant_activation_notice() {
	echo '<div class="error"><p>';
	echo esc_html__( 'Geeklove Assistant requires Geeklove WordPress Theme to be installed and activated.', 'geeklove-assistant' );
	echo '</p></div>';
}

/**
 *
 *
 * @since 1.0
 */
function geeklove_assistant_activation_check() {
	$theme = wp_get_theme(); // gets the current theme
	if ( 'Geeklove' == $theme->name || 'Geeklove' == $theme->parent_theme ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			add_action( 'after_setup_theme', 'geeklove_assistant' );
		} else {
			ink_assistant();
		}
	} else {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		add_action( 'admin_notices', 'geeklove_assistant_activation_notice' );
	}
}

// Theme loads.
geeklove_assistant_activation_check();
