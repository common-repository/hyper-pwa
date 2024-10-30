<?php
if ( ! defined( 'ABSPATH' ) )
{
	exit;
}

require_once HYPER_PWA_PATH . 'cfg/cfg.php';

class HyperPWAOption
{
	private $home_url = '';
	private $base_pattern = '';


	public function __construct()
	{
		$this->home_url = home_url();
		$this->base_pattern = str_replace( [ '/', '.' ], [ '\/', '\.' ], $this->home_url );
	}

	public function __destruct()
	{
	}


	public function get_icons()
	{
		$icon_192 = get_option( HYPER_PWA_ICON_192 );
		$icon_192 = ! empty( $icon_192 ) ?
			preg_replace( '/^' . $this->base_pattern . '/i', '', $icon_192 ) :
			'/wp-content/plugins/hyper-pwa/img/icon-192.png';

		$icon_512 = get_option( HYPER_PWA_ICON_512 );
		$icon_512 = ! empty( $icon_512 ) ?
			preg_replace( '/^' . $this->base_pattern . '/i', '', $icon_512 ) :
			'/wp-content/plugins/hyper-pwa/img/icon-512.png';

		$maskable_icon_192 = get_option( HYPER_PWA_MASKABLE_ICON_192 );
		$maskable_icon_192 = ! empty( $maskable_icon_192 ) ?
			preg_replace( '/^' . $this->base_pattern . '/i', '', $maskable_icon_192 ) :
			'/wp-content/plugins/hyper-pwa/img/maskable-icon-192.png';

		$maskable_icon_512 = get_option( HYPER_PWA_MASKABLE_ICON_512 );
		$maskable_icon_512 = ! empty( $maskable_icon_512 ) ?
			preg_replace( '/^' . $this->base_pattern . '/i', '', $maskable_icon_512 ) :
			'/wp-content/plugins/hyper-pwa/img/maskable-icon-512.png';

		$icons = [
			'icon_192' => $icon_192,
			'icon_512' => $icon_512,
			'maskable_icon_192' => $maskable_icon_192,
			'maskable_icon_512' => $maskable_icon_512
		];

		return $icons;
	}

	public function get_screenshots()
	{
		$wide = get_option( HYPER_PWA_SCREENSHOT_WIDE );
		$wide = ! empty( $wide ) ?
			preg_replace( '/^' . $this->base_pattern . '/i', '', $screenshot_wide ) :
			'/wp-content/plugins/hyper-pwa/img/screenshot-wide.jpg';

		$narrow = get_option( HYPER_PWA_SCREENSHOT_NARROW );
		$narrow = ! empty( $narrow ) ?
			preg_replace( '/^' . $this->base_pattern . '/i', '', $screenshot_narrow ) :
			'/wp-content/plugins/hyper-pwa/img/screenshot-narrow.jpg';

		$screenshots = [
			'wide' => $wide,
			'narrow' => $narrow
		];

		return $screenshots;
	}

	public function get_site_type()
	{
		$site_type = get_option( HYPER_PWA_SITE_TYPE );
		$site_type = ! empty( $site_type ) && 'static' === $site_type[0] ? 'static' : 'dynamic';

		return $site_type;
	}

	public function get_install_button()
	{
		$install_button = get_option( HYPER_PWA_INSTALL_BUTTON );
		if ( empty( $install_button ) )
		{
			$install_button = 'Add to Home Screen';
		}

		return $install_button;
	}

	public function get_popup_messages()
	{
		$ios_safari = get_option( HYPER_PWA_POPUP_MESSAGE_IOS_SAFARI );
		if ( empty( $ios_safari ) )
		{
			$ios_safari = 'While viewing the website, tap';
		}

		$ios_safari_2 = get_option( HYPER_PWA_POPUP_MESSAGE_IOS_SAFARI_2 );
		if ( empty( $ios_safari_2 ) )
		{
			$ios_safari_2 = 'in the menu bar.  Scroll down the list of options, then tap Add to Home Screen.';
		}

		$ios_chrome = get_option( HYPER_PWA_POPUP_MESSAGE_IOS_CHROME );
		if ( empty( $ios_chrome ) )
		{
			$ios_chrome = 'Use Safari for a better experience.';
		}

		$popup_messages = [
			'ios_safari' => $ios_safari,
			'ios_safari_2' => $ios_safari_2,
			'ios_chrome' => $ios_chrome
		];

		return $popup_messages;
	}
}
