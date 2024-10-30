<?php
/*
Plugin Name: Hyper PWA
Plugin URI:  https://wordpress.org/plugins/hyper-pwa/
Description: Provide Manifest and Service Worker, convert WordPress into Progressive Web Apps (PWA).
Version:     4.1.0
Author:      Rickey Gu
Author URI:  https://flexplat.com
License:     GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain: hyper-pwa
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) )
{
	exit;
}

define( 'HYPER_PWA_PATH', plugin_dir_path( __FILE__ ) );

include_once ABSPATH . 'wp-admin/includes/plugin.php';

require_once HYPER_PWA_PATH . 'cfg/cfg.php';
require_once HYPER_PWA_PATH . 'lib/transient.php';
require_once HYPER_PWA_PATH . 'lib/retrieve.php';
require_once HYPER_PWA_PATH . 'lib/transcode.php';
require_once HYPER_PWA_PATH . 'lib/option.php';


class HyperPWA
{
	private $home_url = '';
	private $base_pattern = '';

	private $page_url = '';

	private $current_timestamp = 0;

	private $transient = NULL;
	private $retrieve = NULL;
	private $transcode = NULL;
	private $option = NULL;


	public function __construct()
	{
		$this->home_url = home_url();
		$this->base_pattern = str_replace( [ '/', '.' ], [ '\/', '\.' ], $this->home_url );

		$parts = wp_parse_url( $this->home_url );
		$this->page_url = $parts['scheme'] . '://' . $parts['host'] . add_query_arg( [] );

		$this->current_timestamp = time();

		$this->transient = new HyperPWATransient();
		$this->retrieve = new HyperPWARetrieve();
		$this->transcode = new HyperPWATranscode();
		$this->option = new HyperPWAOption();
	}

	public function __destruct()
	{
	}


	private function get_page_type()
	{
		global $wp_query;

		$page_type = '';
		if ( $wp_query->is_page )
		{
			$page_type = is_front_page() ? 'front' : 'page';
		}
		elseif ( $wp_query->is_home )
		{
			$page_type = 'home';
		}
		elseif ( $wp_query->is_single )
		{
			$page_type = ( $wp_query->is_attachment ) ? 'attachment' : 'single';
		}
		elseif ( $wp_query->is_category )
		{
			$page_type = 'category';
		}
		elseif ( $wp_query->is_tag )
		{
			$page_type = 'tag';
		}
		elseif ( $wp_query->is_tax )
		{
			$page_type = 'tax';
		}
		elseif ( $wp_query->is_archive )
		{
			if ( $wp_query->is_day )
			{
				$page_type = 'day';
			}
			elseif ( $wp_query->is_month )
			{
				$page_type = 'month';
			}
			elseif ( $wp_query->is_year )
			{
				$page_type = 'year';
			}
			elseif ( $wp_query->is_author )
			{
				$page_type = 'author';
			}
			else
			{
				$page_type = 'archive';
			}
		}
		elseif ( $wp_query->is_search )
		{
			$page_type = 'search';
		}
		elseif ( $wp_query->is_404 )
		{
			$page_type = 'notfound';
		}

		return $page_type;
	}


	public function plugins_loaded()
	{
		if ( preg_match( '/^' . $this->base_pattern . HYPER_PWA_REGISTER_JS_PATTERN . '/i', $this->page_url ) )
		{
			$page = $this->retrieve->get_register_js();

			header( 'Content-Type: text/javascript; charset=UTF-8' );
			echo $page;

			exit();
		}
		elseif ( preg_match( '/^' . $this->base_pattern . HYPER_PWA_SERVICE_WORKER_JS_PATTERN . '/i', $this->page_url ) )
		{
			$page = $this->retrieve->get_service_worker_js();

			header( 'Content-Type: text/javascript; charset=UTF-8' );
			echo $page;

			exit();
		}
		elseif ( preg_match( '/^' . $this->base_pattern . HYPER_PWA_MANIFEST_JSON_PATTERN . '/i', $this->page_url ) )
		{
			$page = $this->retrieve->get_manifest_json();

			header( 'Content-Type: application/json' );
			echo $page;

			exit();
		}
		elseif ( preg_match( '/^' . $this->base_pattern . HYPER_PWA_OFFLINE_HTML_PATTERN . '/i', $this->page_url ) )
		{
			$page = $this->retrieve->get_offline_html();

			header( 'Content-Type: text/html; charset=UTF-8' );
			echo $page;

			exit();
		}
		elseif ( preg_match( '/^' . $this->base_pattern . HYPER_PWA_UNREGISTER_JS_PATTERN . '/i', $this->page_url ) )
		{
			$page = $this->retrieve->get_unregister_js();

			header( 'Content-Type: text/javascript; charset=UTF-8' );
			echo $page;

			exit();
		}
		elseif ( preg_match( '/^' . $this->base_pattern . HYPER_PWA_A2HS_JS_PATTERN . '/i', $this->page_url ) )
		{
			$page = $this->retrieve->get_a2hs_js();

			header( 'Content-Type: text/javascript; charset=UTF-8' );
			echo $page;

			exit();
		}
		else
		{
			$this->transient->update_transient();
		}
	}


	public function after_setup_theme()
	{
		ob_start( [ $this->transcode, 'page_callback' ] );
	}

	public function shutdown()
	{
		if ( ! ob_get_length() )
		{
			return;
		}

		ob_end_flush();
	}


	public function wp_enqueue_scripts()
	{
		wp_enqueue_script( 'hyper-pwa-register', $this->home_url . '/' . HYPER_PWA_REGISTER_JS, array(), HYPER_PWA_VERSION, TRUE );

		$page = $this->retrieve->get_a2hs_js();
		if ( empty( $page ) )
		{
			return;
		}

		wp_enqueue_script( 'hyper-pwa-a2hs', $this->home_url . '/' . HYPER_PWA_A2HS_JS, array(), HYPER_PWA_VERSION, TRUE );
		wp_enqueue_style( 'hyper-pwa-a2hs', plugins_url( 'css/a2hs.css', __FILE__ ), array(), HYPER_PWA_VERSION );
	}

	public function admin_enqueue_scripts()
	{
		wp_enqueue_script( 'hyper-pwa-unregister', $this->home_url . '/' . HYPER_PWA_UNREGISTER_JS, array(), HYPER_PWA_VERSION, TRUE );

		wp_enqueue_media();
		wp_enqueue_script( 'hyper-pwa-media-uploader', plugins_url( 'js/media-uploader.js', __FILE__ ), array( 'jquery' ), HYPER_PWA_VERSION, TRUE );
	}


	public function wp_head()
	{
		$page_type = $this->get_page_type();

		$head = '<link rel="manifest" href="' . $this->home_url . '/' . HYPER_PWA_MANIFEST_JSON . '" />
<meta name="theme-color" content="#ffffff" />
<meta name="hyper-pwa-page-type" content="' . $page_type . '" />' . "\n";

		echo $head;
	}

	public function wp_footer()
	{
		$page = $this->retrieve->get_a2hs_js();
		if ( empty( $page ) )
		{
			return;
		}

		$icons = $this->option->get_icons();
		$install_button = $this->option->get_install_button();
		$popup_messages = $this->option->get_popup_messages();

		$footer = '<div id="hyper-pwa-notification-bar" class="hyper-pwa-hidden hyper-pwa-notification-bar" style="display:flex;">
	<div>
		<img class="hyper-pwa-icon" src="' . $this->home_url . $icons['icon_192'] . '" width="48" height="48">
	</div>
	<div style="flex:1;">
		<button id="hyper-pwa-install-button" class="hyper-pwa-install-button">
			' . $install_button . '
		</button>
	</div>
	<div>
		<button id="hyper-pwa-close-button" class="hyper-pwa-close-button">
			<svg width="24px" height="24px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
				<path d="M0 0h24v24H0V0z" fill="none"></path>
				<path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"></path>
			</svg>
		</button>
	</div>
</div>
<div id="hyper-pwa-popup-window-ios-safari" class="hyper-pwa-popup-window">
	<div id="hyper-pwa-popup-text-ios-safari" class="hyper-pwa-popup-text">
		' . $popup_messages['ios_safari'] . '
		<svg width="24px" height="24px" style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" enable-background="new 0 0 50 50">
			<path fill="dodgerblue" d="M30.3 13.7L25 8.4l-5.3 5.3-1.4-1.4L25 5.6l6.7 6.7z"/>
			<path fill="dodgerblue" d="M24 7h2v21h-2z"/>
			<path fill="dodgerblue" d="M35 40H15c-1.7 0-3-1.3-3-3V19c0-1.7 1.3-3 3-3h7v2h-7c-.6 0-1 .4-1 1v18c0 .6.4 1 1 1h20c.6 0 1-.4 1-1V19c0-.6-.4-1-1-1h-7v-2h7c1.7 0 3 1.3 3 3v18c0 1.7-1.3 3-3 3z"/>
		</svg>
		' . $popup_messages['ios_safari_2'] . '
	</div>
</div>
<div id="hyper-pwa-popup-window-ios-chrome" class="hyper-pwa-popup-window">
	<div id="hyper-pwa-popup-text-ios-chrome" class="hyper-pwa-popup-text">
		' . $popup_messages['ios_chrome'] . '
	</div>
</div>' . "\n";

		echo $footer;
	}


	public function script_loader_tag( $tag, $handle, $src )
	{
		if ( 'hyper-pwa-register' === $handle || 'hyper-pwa-unregister' === $handle )
		{
			$tag = preg_replace( '/<script\b([^>]+)><\/script>/i', '<script type=\'module\'${1} async></script>', $tag );
		}

		return $tag;
	}

	public function settings_link( $links )
	{
		$url = get_admin_url();
		$url .= 'admin.php';

		$url = add_query_arg( 'page', 'hyper-pwa', $url );
		$url = esc_url( $url );

		$link = '<a href="' . $url . '">' . __( 'Settings', 'hyper-pwa' ) . '</a>';
		array_push( $links, $link );

		return $links;
	}


	public function register_activation_hook()
	{
		add_option( HYPER_PWA_TRANSIENT_TIMESTAMP, $this->current_timestamp );
		add_option( HYPER_PWA_VERSION_ID, HYPER_PWA_VERSION );

		$this->transient->reset_transient();

		setcookie( 'hyper-pwa-admin-tab', '', $this->current_timestamp - 1, COOKIEPATH, COOKIE_DOMAIN );
		setcookie( 'hyper-pwa-a2hs-disable', '', $this->current_timestamp - 1, COOKIEPATH, COOKIE_DOMAIN );
	}

	public function register_deactivation_hook()
	{
		delete_option( HYPER_PWA_TRANSIENT_TIMESTAMP );
		delete_option( HYPER_PWA_VERSION_ID );

		$this->transient->remove_transient();

		setcookie( 'hyper-pwa-admin-tab', '', $this->current_timestamp - 1, COOKIEPATH, COOKIE_DOMAIN );
		setcookie( 'hyper-pwa-a2hs-disable', '', $this->current_timestamp - 1, COOKIEPATH, COOKIE_DOMAIN );
	}
}


if ( is_plugin_active( 'flx-woo/flx-woo.php' ) )
{
	return;
}

include_once HYPER_PWA_PATH . 'admin/admin.php';

if ( 'admin-ajax.php' === $GLOBALS['pagenow'] || 'wp-activate.php' === $GLOBALS['pagenow'] || 'wp-cron.php' === $GLOBALS['pagenow'] || 'wp-login.php' === $GLOBALS['pagenow'] || 'wp-signup.php' === $GLOBALS['pagenow'] )
{
	return;
}

$hyper_pwa = new HyperPWA();

add_action( 'plugins_loaded', [ $hyper_pwa, 'plugins_loaded' ] );
if ( ! is_admin() )
{
	add_action( 'after_setup_theme', [ $hyper_pwa, 'after_setup_theme' ] );
	add_action( 'shutdown', [ $hyper_pwa, 'shutdown' ] );

	add_action( 'wp_enqueue_scripts', [ $hyper_pwa, 'wp_enqueue_scripts' ] );

	add_action( 'wp_head', [ $hyper_pwa, 'wp_head' ] );
	add_action( 'wp_footer', [ $hyper_pwa, 'wp_footer' ] );
}
else
{
	add_action( 'admin_enqueue_scripts', [ $hyper_pwa, 'admin_enqueue_scripts' ] );
}

add_filter( 'script_loader_tag', [ $hyper_pwa, 'script_loader_tag' ], 10, 3 );
add_filter( 'plugin_action_links_hyper-pwa/hyper-pwa.php', [ $hyper_pwa, 'settings_link' ] );

register_activation_hook( __FILE__, [ $hyper_pwa, 'register_activation_hook' ] );
register_deactivation_hook( __FILE__, [ $hyper_pwa, 'register_deactivation_hook' ] );
