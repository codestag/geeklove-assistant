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
	 * Geeklove Assistant Class.
	 *
	 * @since 1.0
	 */
	class Geeklove_Assistant {

		/**
		 * Base instance var
		 *
		 * @var
		 * @since 1.0
		 */
		private static $instance;

		/**
		 * Register method for creating class instance.
		 *
		 * @since 1.0
		 */
		public static function register() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Geeklove_Assistant ) ) {
				self::$instance = new Geeklove_Assistant();
				self::$instance->define_constants();
				self::$instance->init();
				self::$instance->includes();
			}
		}

		/**
		 * Initialize plugin hooks.
		 *
		 * @since 1.0
		 */
		public function init() {
			add_action( 'admin_enqueue_scripts', array( $this, 'scripts_and_styles' ) );
		}

		/**
		 * Define constants.
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
		 * Define a constant.
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
		 * Include files for the plugin.
		 *
		 * @since 1.0
		 */
		public function includes() {
			/**
			 * Include Meta Boxes.
			 */
			require_once GA_PLUGIN_PATH . 'includes/metaboxes/stag-admin-metaboxes.php';
			if ( false === geeklove_get_thememod_value( 'geeklove_disable_seo_settings' ) ) {
				include_once GA_PLUGIN_PATH . 'includes/metaboxes/seo.php';
			}
			require_once GA_PLUGIN_PATH . 'includes/metaboxes/event-metabox.php';
			require_once GA_PLUGIN_PATH . 'includes/metaboxes/contact-metabox.php';
			require_once GA_PLUGIN_PATH . 'includes/metaboxes/gallery-metabox.php';
			require_once GA_PLUGIN_PATH . 'includes/metaboxes/page-metabox.php';
			require_once GA_PLUGIN_PATH . 'includes/metaboxes/rsvp-metabox.php';


			// Shortcodes.
			require_once IA_PLUGIN_PATH . 'includes/shortcodes/contact-form.php';

			/**
			 * Include Custom Post Types.
			 */
			require_once GA_PLUGIN_PATH . 'includes/cpt/events.php';
			require_once GA_PLUGIN_PATH . 'includes/cpt/gallery.php';
			require_once GA_PLUGIN_PATH . 'includes/cpt/guestbook.php';
			require_once GA_PLUGIN_PATH . 'includes/cpt/rsvp.php';

			/**
			 * Include Widgets.
			 */
			require_once GA_PLUGIN_PATH . 'includes/widgets/class-stag-widget.php';
			require_once GA_PLUGIN_PATH . 'includes/widgets/homepage-blog.php';
			require_once GA_PLUGIN_PATH . 'includes/widgets/homepage-countdown.php';
			require_once GA_PLUGIN_PATH . 'includes/widgets/homepage-divider.php';
			require_once GA_PLUGIN_PATH . 'includes/widgets/homepage-event.php';
			require_once GA_PLUGIN_PATH . 'includes/widgets/homepage-gallery.php';
			require_once GA_PLUGIN_PATH . 'includes/widgets/homepage-rsvp.php';
			require_once GA_PLUGIN_PATH . 'includes/widgets/homepage-tweets.php';
			require_once GA_PLUGIN_PATH . 'includes/widgets/homepage-wedding-intro.php';
			require_once GA_PLUGIN_PATH . 'includes/widgets/static-content.php';
		}

		/**
		 * Enqueue scripts and styles.
		 *
		 * @since  1.0.0
		 *
		 * @param  string $hook Page name where to enqueue the admin styles.
		 * @return void
		 */
		public function scripts_and_styles( $hook ) {
			if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
				// Scripts
				wp_enqueue_media();
				wp_register_script( 'stag-admin-metabox', GA_PLUGIN_URL  . 'assets/js/stag-admin-metabox.js', array( 'jquery', 'wp-color-picker' ) );
				wp_enqueue_script( 'stag-admin-metabox' );

				// Styles
				wp_register_style( 'stag-admin-metabox', GA_PLUGIN_URL  . 'assets/css/stag-admin-metabox.css', array( 'wp-color-picker' ), GA_VERSION );
				wp_enqueue_style( 'stag-admin-metabox' );
				wp_enqueue_style( 'wp-color-picker' );
			}
		}
	}
endif;


/**
 * Plugin Class instance.
 *
 * @since 1.0
 */
function geeklove_assistant() {
	return Geeklove_Assistant::register();
}

/**
 * Plugin activation notice.
 *
 * @since 1.0
 */
function geeklove_assistant_activation_notice() {
	echo '<div class="error"><p>';
	echo esc_html__( 'Geeklove Assistant requires Geeklove WordPress Theme to be installed and activated.', 'geeklove-assistant' );
	echo '</p></div>';
}

/**
 * Plugin activation check.
 *
 * @since 1.0
 */
function geeklove_assistant_activation_check() {
	$theme = wp_get_theme(); // gets the current theme.
	if ( 'GeekLove' === $theme->name || 'GeekLove' === $theme->parent_theme ) {
		add_action( 'after_setup_theme', 'geeklove_assistant' );
	} else {
		if ( ! function_exists( 'deactivate_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		deactivate_plugins( plugin_basename( __FILE__ ) );
		add_action( 'admin_notices', 'geeklove_assistant_activation_notice' );
	}
}

// Plugin loads.
geeklove_assistant_activation_check();
