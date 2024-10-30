<?php
if ( ! defined( 'ABSPATH' ) )
{
	exit;
}

require_once HYPER_PWA_PATH . 'cfg/cfg.php';
require_once HYPER_PWA_PATH . 'lib/transient.php';

class HyperPWAAdmin
{
	private $fields = [
		[
			'uid' => HYPER_PWA_ICON_192,
			'label' => 'App Icon',
			'tab' => 'manifest',
			'section' => 'icons',
			'type' => 'media-uploader',
			'button' => 'icon-192',
			'supplimental' => 'Should be a .png format 192x192px size image.',
			'placeholder' => '/wp-content/plugins/hyper-pwa/img/icon-192.png'
		],
		[
			'uid' => HYPER_PWA_ICON_512,
			'label' => 'Splash Screen Icon',
			'tab' => 'manifest',
			'section' => 'icons',
			'type' => 'media-uploader',
			'button' => 'icon-512',
			'supplimental' => 'Should be a .png format 512x512px size image.',
			'placeholder' => '/wp-content/plugins/hyper-pwa/img/icon-512.png'
		],
		[
			'uid' => HYPER_PWA_MASKABLE_ICON_192,
			'label' => 'Maskable App Icon',
			'tab' => 'manifest',
			'section' => 'icons',
			'type' => 'media-uploader',
			'button' => 'maskable-icon-192',
			'supplimental' => 'Should be a .png format 192x192px size image.',
			'placeholder' => '/wp-content/plugins/hyper-pwa/img/maskable-icon-192.png'
		],
		[
			'uid' => HYPER_PWA_MASKABLE_ICON_512,
			'label' => 'Maskable Splash Screen Icon',
			'tab' => 'manifest',
			'section' => 'icons',
			'type' => 'media-uploader',
			'button' => 'maskable-icon-512',
			'supplimental' => 'Should be a .png format 512x512px size image.',
			'placeholder' => '/wp-content/plugins/hyper-pwa/img/maskable-icon-512.png'
		],
		[
			'uid' => HYPER_PWA_SCREENSHOT_WIDE,
			'label' => 'Wide Screenshot',
			'tab' => 'manifest',
			'section' => 'screenshots',
			'type' => 'media-uploader',
			'button' => 'screenshot-wide',
			'supplimental' => 'Should be a .jpg format 512x384px size image.',
			'placeholder' => '/wp-content/plugins/hyper-pwa/img/screenshot-wide.jpg'
		],
		[
			'uid' => HYPER_PWA_SCREENSHOT_NARROW,
			'label' => 'Narrow Screenshot',
			'tab' => 'manifest',
			'section' => 'screenshots',
			'type' => 'media-uploader',
			'button' => 'screenshot-narrow',
			'supplimental' => 'Should be a .jpg format 384x512px size image.',
			'placeholder' => '/wp-content/plugins/hyper-pwa/img/screenshot-narrow.jpg'
		],
		[
			'uid' => HYPER_PWA_SITE_TYPE,
			'label' => 'Site Type',
			'tab' => 'service-worker',
			'section' => 'basic',
			'type' => 'radio',
			'options' => [
				'dynamic' => 'Dynamic Site',
				'static' => 'Static Site'
			],
			'default' => [
				'dynamic'
			],
			'supplimental' => 'Static would typically include sites such as blogs, small business sites, lower volume news sites, personal, photography sites, etc.  By <strong>static</strong>, we mean that the data on these WordPress sites is <strong>not changing very often</strong> (perhaps a couple of times a day).  On the flip side, we have highly dynamic sites.  These include sites such as eCommerce (WooCommerce or Easy Digital Downloads), community, membership, forums (bbPress or BuddyPress) and learning management systems (LMS).  By <strong>dynamic</strong>, we mean that the data on these WordPress sites is <strong>frequently changing</strong> (server transactions are taking place every few minutes or even every second).  This means that not all requests to the server can be served directly from cache and require additional server resources and database queries.'
		],
		[
			'uid' => HYPER_PWA_INSTALL_BUTTON,
			'label' => 'Install Button',
			'tab' => 'a2hs',
			'section' => 'notification-bar',
			'type' => 'text',
			'supplimental' => '',
			'placeholder' => 'Add to Home Screen'
		],
		[
			'uid' => HYPER_PWA_POPUP_MESSAGE_IOS_SAFARI,
			'label' => 'iOS Safari',
			'tab' => 'a2hs',
			'section' => 'popup-message',
			'type' => 'textarea',
			'supplimental' => '',
			'placeholder' => 'While viewing the website, tap'
		],
		[
			'uid' => HYPER_PWA_POPUP_MESSAGE_IOS_SAFARI_2,
			'label' => 'iOS Safari 2',
			'tab' => 'a2hs',
			'section' => 'popup-message',
			'type' => 'textarea',
			'supplimental' => '',
			'placeholder' => 'in the menu bar.  Scroll down the list of options, then tap Add to Home Screen.'
		],
		[
			'uid' => HYPER_PWA_POPUP_MESSAGE_IOS_CHROME,
			'label' => 'iOS Chrome',
			'tab' => 'a2hs',
			'section' => 'popup-message',
			'type' => 'textarea',
			'supplimental' => '',
			'placeholder' => 'Use Safari for a better experience.'
		]
	];

	private $tabs = [
		'procedure' => 'Procedure',
		'manifest' => 'Manifest',
		'service-worker' => 'Service Worker',
		'a2hs' => 'Add to Home Screen',
		'faq' => 'FAQ',
		'subscription' => 'Subscription',
		'custom-dev' => 'Custom Dev.',
		'contact-us' => 'Contact Us'
	];

	private $sections = [
		'icons' => 'Icons',
		'screenshots' => 'Screenshots',
		'basic' => 'Basic',
		'notification-bar' => 'Notification Bar',
		'popup-message' => 'Popup Message'
	];


	private $current_timestamp = 0;

	private $transient = NULL;

	private $tab = '';


	public function __construct()
	{
		$this->current_timestamp = time();

		$this->transient = new HyperPWATransient();

		$this->tab = $this->get_tab();
	}

	public function __destruct()
	{
	}


	private function get_tab()
	{
		$tab = ! empty( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : '';
		if ( 'manifest' === $tab || 'service-worker' === $tab || 'a2hs' === $tab || 'faq' === $tab || 'subscription' === $tab
			|| 'custom-dev' === $tab || 'contact-us' === $tab )
		{
			setcookie( 'hyper-pwa-admin-tab', $tab, 0, COOKIEPATH, COOKIE_DOMAIN );

			return $tab;
		}
		elseif ( empty( $tab ) && ! empty( $_COOKIE['hyper-pwa-admin-tab'] ) )
		{
			return sanitize_text_field( wp_unslash( $_COOKIE['hyper-pwa-admin-tab'] ) );
		}
		else
		{
			setcookie( 'hyper-pwa-admin-tab', 'procedure', 0, COOKIEPATH, COOKIE_DOMAIN );

			return 'procedure';
		}
	}


	private function title_callback( $matches )
	{
		return $matches[1] . '<hr>' . $matches[2];
	}

	private function precaching_callback( $matches )
	{
		return '<input' . $matches[1] . ' onclick="EnableDisablePrecachingList()"' . $matches[2] . ' />';
	}

	private function precaching_list_callback( $matches )
	{
		$precaching = get_option( HYPER_PWA_PRECACHING );
		$precaching = ! empty( $precaching[0] ) ? $precaching[0] : 'enable';

		if ( 'disable' !== $precaching )
		{
			return '<textarea' . $matches[1] . '>' . $matches[2] . '</textarea>';
		}
		else
		{
			return '<textarea' . $matches[1] . ' disabled="disabled">' . $matches[2] . '</textarea>';
		}
	}

	private function periodic_background_sync_callback( $matches )
	{
		return '<input' . $matches[1] . ' onclick="EnableDisablePeriodicBackgroundSyncList()"' . $matches[2] . ' />';
	}

	private function periodic_background_sync_list_callback( $matches )
	{
		$periodic_background_sync = get_option( HYPER_PWA_PERIODIC_BACKGROUND_SYNC );
		$periodic_background_sync = ! empty( $periodic_background_sync[0] ) ? $periodic_background_sync[0] : 'enable';

		if ( 'disable' !== $periodic_background_sync )
		{
			return '<textarea' . $matches[1] . '>' . $matches[2] . '</textarea>';
		}
		else
		{
			return '<textarea' . $matches[1] . ' disabled="disabled">' . $matches[2] . '</textarea>';
		}
	}

	private function manifest_callback( $page )
	{
		$page = preg_replace_callback( '/(<\/table>)((<h2>.+<\/h2>.*)?<table[^>]+>)/isU', [ $this, 'title_callback' ], $page );

		return $page;
	}

	private function service_worker_callback( $page )
	{
		$page = preg_replace_callback( '/(<\/table>)((<h2>.+<\/h2>.*)?<table[^>]+>)/isU', [ $this, 'title_callback' ], $page );

		$page = preg_replace_callback( '/<input( id="hyper-pwa-precaching_\d+" name="[^\"]+" type="radio" value="[^\"]+")([^>]*) \/>/i', [ $this, 'precaching_callback' ], $page );
		$page = preg_replace_callback( '/<textarea( name="[^\"]+" id="hyper-pwa-precaching-list" placeholder="[^\"]*" rows="\d+" cols="\d+")>(.*)<\/textarea>/iU', [ $this, 'precaching_list_callback' ], $page );

		$page = preg_replace_callback( '/<input( id="hyper-pwa-periodic-background-sync_\d+" name="[^\"]+" type="radio" value="[^\"]+")([^>]*) \/>/i', [ $this, 'periodic_background_sync_callback' ], $page );
		$page = preg_replace_callback( '/<textarea( name="[^\"]+" id="hyper-pwa-periodic-background-sync-list" placeholder="[^\"]*" rows="\d+" cols="\d+")>(.*)<\/textarea>/iU', [ $this, 'periodic_background_sync_list_callback' ], $page );

		return $page;
	}

	private function a2hs_callback( $page )
	{
		$page = preg_replace_callback( '/(<\/table>)((<h2>.+<\/h2>.*)?<table[^>]+>)/isU', [ $this, 'title_callback' ], $page );

		return $page;
	}


	private function echo_procedure()
	{
		echo '
	<ol>
		<li>Please do not activate other PWA plugins when you are using this one.</li>
		<li>Please enable plugin auto-update to get the latest functions.</li>
		<li>Go to <a href="?page=hyper-pwa&tab=manifest"><strong>Manifest</strong></a> tab, update the settings and save the changes.</li>
		<li>Go to <a href="?page=hyper-pwa&tab=service-worker"><strong>Service Worker</strong></a> tab, update the settings if necessary and save the changes.</li>
		<li>Go to <a href="?page=hyper-pwa&tab=a2hs"><strong>Add to Home Screen</strong></a> tab, update the settings if necessary and save the changes.</li>
		<li>Use <a href="https://www.pwabuilder.com/" target="_blank"><strong>PWABuilder</strong></a> or other audit tools to find out if your website can pass PWA validation testing.</li>
		<li>Use <a href="https://www.webpagetest.org/" target="_blank"><strong>WebPageTest</strong></a> or other performance test tools to check your webpage loading time -- PWA makes the Repeat View Loading Time much better than the First View Loading Time.</li>
		<li>Users can <a href="https://www.brandeis.edu/its/support/website-shortcut.html" target="_blank"><strong>add your website to their mobile device home screen</strong></a>, and then visit your website from these app icons.</li>
		<li>Users can even visit your website from these app icons during Offline Mode.</li>
		<li>Use <a href="https://onesignal.com/" target="_blank"><strong>OneSignal</strong></a> or <a href="https://firebase.google.com/" target="_blank"><strong>Firebase</strong></a> to send Push Notifications to these app icons, so users can get your latest information/updating message.</li>
		<li>If you have any questions, check <a href="?page=hyper-pwa&tab=faq"><strong>FAQ</strong></a> tab first.</li>
		<li>If you want to have more features of Manifest, Service Worker and/or App Capabilities, subscribe to my premium service at <a href="?page=hyper-pwa&tab=subscription"><strong>Subscription</strong></a> tab.</li>
		<li>If you want to have a custom PWA development/configuration, read <a href="?page=hyper-pwa&tab=custom-dev"><strong>Custom Dev.</strong></a> tab</li>
		<li>If you still have questions, find my email address at <a href="?page=hyper-pwa&tab=contact-us"><strong>Contact Us</strong></a> tab.</li>
	</ol>';
	}

	private function echo_manifest()
	{
		echo '
		<form method="post" action="options.php">
			';

		ob_start( [ $this, 'manifest_callback' ] );

		// settings_fields( string $option_group )
		settings_fields( 'hyper-pwa' );
		// do_settings_sections( string $page )
		do_settings_sections( $this->tab );
		// submit_button( string $text = null, string $type = 'primary', string $name = 'submit', bool $wrap = true, array|string $other_attributes = null )
		submit_button();

		ob_end_flush();

		echo '
		</form>';
	}

	private function echo_service_worker()
	{
		echo '
		<form method="post" action="options.php">
			';

		ob_start( [ $this, 'service_worker_callback' ] );

		// settings_fields( string $option_group )
		settings_fields( 'hyper-pwa' );
		// do_settings_sections( string $page )
		do_settings_sections( $this->tab );
		// submit_button( string $text = null, string $type = 'primary', string $name = 'submit', bool $wrap = true, array|string $other_attributes = null )
		submit_button();

		ob_end_flush();

		echo '
		</form>';
	}

	private function echo_a2hs()
	{
		echo '
		<form method="post" action="options.php">
			';

		ob_start( [ $this, 'a2hs_callback' ] );

		// settings_fields( string $option_group )
		settings_fields( 'hyper-pwa' );
		// do_settings_sections( string $page )
		do_settings_sections( $this->tab );
		// submit_button( string $text = null, string $type = 'primary', string $name = 'submit', bool $wrap = true, array|string $other_attributes = null )
		submit_button();

		ob_end_flush();

		echo '
		</form>';
	}

	private function echo_faq()
	{
		echo '
		<h3>Manifest</h3>
		<p><strong>Question: How to make App Icons?</strong></p>
		<p><strong>Answer:</strong> <a href="https://www.pwabuilder.com/imageGenerator" target="_blank">https://www.pwabuilder.com/imageGenerator</a></p>
		<p><strong>Question: How to make maskable App Icons?</strong></p>
		<p><strong>Answer:</strong> <a href="https://maskable.app/editor" target="_blank">https://maskable.app/editor</a></p>
		<hr>
		<h3>Service Worker</h3>
		<p><strong>Question: How to find my website Repeat View load time/speed?</strong></p>
		<p><strong>Answer:</strong> You can use following tool: <a href="https://www.webpagetest.org/" target="_blank">https://www.webpagetest.org/</a> .  In the Advanced Settings, you need to change Repeat View option from "First View Only" to "First View and Repeat View".</p>
		<hr>
		<h3>Add to Home Screen</h3>
		<p><strong>Question: How to add my website to mobile device home screen?</strong></p>
		<p><strong>Answer:</strong> <a href="https://www.brandeis.edu/its/support/website-shortcut.html" target="_blank">https://www.brandeis.edu/its/support/website-shortcut.html</a></p>
		<hr>
		<h3>Audit</h3>
		<p><strong>Question: How to validate/audit my website PWA status?</strong></p>
		<p><strong>Answer:</strong> I use Microsoft PWABuilder: <a href="https://www.pwabuilder.com/" target="_blank">https://www.pwabuilder.com/</a> .  You can google to find more tools.</p>
		<hr>
		<h3>Push Notifications</h3>
		<p><strong>Question: How to send Push Notifications?</strong></p>
		<p><strong>Answer:</strong> This plugin is compatible with OneSignal: <a href="https://onesignal.com/" target="_blank">https://onesignal.com/</a> and Firebase: <a href="https://firebase.google.com/" target="_blank">https://firebase.google.com/</a> , you can use OneSignal WordPress plugin: <a href="https://wordpress.org/plugins/onesignal-free-web-push-notifications/" target="_blank">https://wordpress.org/plugins/onesignal-free-web-push-notifications/</a> and Firebase WordPress plugin: <a href="https://wordpress.org/plugins/integrate-firebase/" target="_blank">https://wordpress.org/plugins/integrate-firebase/</a> to do Push Notifications.</p>';
	}

	private function echo_subscription()
	{
		echo '
		<p>When you use <a href="https://www.pwabuilder.com/" target="_blank"><strong>PWABuilder</strong></a> to validate your website, you will find my plugin supports all the required functions, but does not support most of the recommended and/or optional functions, such as:</p>
		<p><a href="https://docs.pwabuilder.com/#/home/pwa-intro?id=web-app-manifests" target="_blank"><strong>Manifest</strong></a></p>
		<ol>
			<li><a href="https://developer.mozilla.org/en-US/docs/Web/Manifest/description" target="_blank">Manifest has description field</a></li>
			<li><a href="https://developer.mozilla.org/en-US/docs/Web/Manifest/launch_handler" target="_blank">Manifest has launch_handler field</a></li>
			<li>Manifest specifies a default direction of text -- Direction of the text.  Values: ltr(left to right), rtl (right to left), auto.</li>
			<li><a href="https://udn.realityripple.com/docs/Web/Manifest/iarc_rating_id" target="_blank">Manifest has iarc_rating_id field</a></li>
			<li>Manifest specifies a language -- Language of the text.  Example: en (English).</li>
			<li><a href="https://developer.mozilla.org/en-US/docs/Web/Manifest/prefer_related_applications" target="_blank">Manifest properly sets prefer_related_applications field</a></li>
			<li><a href="https://developer.mozilla.org/en-US/docs/Web/Manifest/related_applications" target="_blank">Manifest has related_applications field</a></li>
			<li><a href="https://developer.chrome.com/docs/capabilities/scope-extensions" target="_blank">Manifest has scope_extensions field</a></li>
		</ol>
		<p><a href="https://docs.pwabuilder.com/#/home/sw-intro" target="_blank"><strong>Service Worker</strong></a></p>
		<ol>
			<li><a href="https://docs.pwabuilder.com/#/home/native-features?id=background-sync-overview" target="_blank">background sync</a></li>
			<li><a href="https://docs.pwabuilder.com/#/home/native-features?id=periodic-background-sync-overview" target="_blank">periodic sync</a></li>
		</ol>
		<p><a href="https://docs.pwabuilder.com/#/builder/manifest" target="_blank"><strong>App Capabilities</strong></a></p>
		<ol>
			<li><a href="https://docs.pwabuilder.com/#/builder/manifest?id=edge_side_panel-object" target="_blank">edge_side_panel</a></li>
			<li><a href="https://docs.pwabuilder.com/#/builder/manifest?id=file_handlers-array" target="_blank">file_handlers</a></li>
			<li><a href="https://docs.pwabuilder.com/#/builder/manifest?id=handle_links-string" target="_blank">handle_links</a></li>
			<li><a href="https://docs.pwabuilder.com/#/home/native-features?id=web-share-api" target="_blank">share_target</a></li>
			<li><a href="https://docs.pwabuilder.com/#/home/native-features?id=shortcuts" target="_blank">shortcuts</a></li>
			<li><a href="https://learn.microsoft.com/en-us/microsoft-edge/progressive-web-apps-chromium/how-to/widgets" target="_blank">widgets</a></li>
		</ol>
		<p>If you want your website to support any of the above functions, you can subscribe to my premium service.  <strong>The cost is 10 USD per month per website, or 100 USD per year per website.</strong>  I will add the corresponding code to the plugin according to your requirement.  Please send an email to me, so we can start the communication.</p>';
	}

	private function echo_custom_dev()
	{
		echo '
		<p>If your website is complex, such as WooCommerce, Learning Management System (LMS), Online Booking/Reservation System, and want to have a personalized/customized Manifest and/or Service Worker solution, so different pages can have different routing strategies, different resources have different recipe configuration, different accounts have different precaching list, everyday has a new periodic background sync list... I can do the custom PWA development for you.  It is a premium service.  Please send an email to me, so I can have more communication.</p>';
	}

	private function echo_contact_us()
	{
		echo '
		<p><strong>Email:</strong> rickey29@gmail.com</p>';
	}


	public function settings_callback()
	{
		echo '
<div class="wrap">
	<h2>Hyper PWA Settings</h2>';

		if ( ! empty( $_GET['settings-updated'] ) )
		{
			update_option( HYPER_PWA_TRANSIENT_TIMESTAMP, $this->current_timestamp );

			$this->transient->reset_transient();


			echo '
	<div class="notice notice-success is-dismissible">
		<p>Your settings have been updated!</p>
	</div>';
		}

		echo '
	<nav class="nav-tab-wrapper">';

		foreach ( $this->tabs as $key => $value )
		{
			echo '
		<a href="?page=hyper-pwa&tab=' . esc_attr( $key ) . '" class="nav-tab' . ( ( $key === $this->tab ) ? ' nav-tab-active' : '' ) . '">' . esc_html( $value ) . '</a>';
		}

		echo '
	</nav>
	<div class="tab-content">';

		if ( 'manifest' === $this->tab )
		{
			$this->echo_manifest();
		}
		elseif ( 'service-worker' === $this->tab )
		{
			$this->echo_service_worker();
		}
		elseif ( 'a2hs' === $this->tab )
		{
			$this->echo_a2hs();
		}
		elseif ( 'faq' === $this->tab )
		{
			$this->echo_faq();
		}
		elseif ( 'subscription' === $this->tab )
		{
			$this->echo_subscription();
		}
		elseif ( 'custom-dev' === $this->tab )
		{
			$this->echo_custom_dev();
		}
		elseif ( 'contact-us' === $this->tab )
		{
			$this->echo_contact_us();
		}
		else
		{
			$this->echo_procedure();
		}

		echo '
	</div>
</div>';
	}

	public function add_menu()
	{
		if ( ! current_user_can( 'manage_options' ) )
		{
			return;
		}

		// add_menu_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', string $icon_url = '', int $position = null )
		add_menu_page( 'Hyper PWA', 'Hyper PWA', 'manage_options', 'hyper-pwa', [ $this, 'settings_callback' ] );
	}


	public function section_callback( $args )
	{
	}

	public function field_callback( $args )
	{
		$value = get_option( $args['uid'] );
		if ( empty( $value ) )
		{
			$value = isset( $args['default'] ) ? $args['default'] : '';
		}

		if ( ! isset( $args['placeholder'] ) )
		{
			$args['placeholder'] = '';
		}

		switch ( $args['type'] )
		{
			case 'text':
			case 'password':
			case 'number':
				printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" size="64" />', esc_attr( $args['uid'] ), esc_attr( $args['type'] ), esc_attr( $args['placeholder'] ), esc_attr( $value ) );
				break;

			case 'textarea':
				printf( '<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="4" cols="64">%3$s</textarea>', esc_attr( $args['uid'] ), esc_attr( $args['placeholder'] ), esc_textarea( $value ) );
				break;

			case 'select':
			case 'multiselect':
				if ( ! empty( $args['options'] ) && is_array( $args['options'] ) )
				{
					$options_markup = '';
					foreach ( $args['options'] as $key => $label )
					{
						if ( ! empty( $value ) )
						{
							$options_markup .= sprintf( '<option value="%s" %s>%s</option>', $key, selected( $value[ array_search( $key, $value, TRUE ) ], $key, FALSE ), $label );
						}
						else
						{
							$options_markup .= sprintf( '<option value="%s">%s</option>', $key, $label );
						}
					}

					$attributes = '';
					if ( 'multiselect' === $args['type'] )
					{
						$attributes = ' multiple="multiple" ';
					}

					printf( '<select name="%1$s[]" id="%1$s" %2$s>%3$s</select>', esc_attr( $args['uid'] ), esc_attr( $attributes ), wp_kses_post( $options_markup ) );
				}
				break;

			case 'radio':
			case 'checkbox':
				if ( ! empty( $args['options'] ) && is_array( $args['options'] ) )
				{
					$options_markup = '';
					$iterator = 0;
					foreach ( $args['options'] as $key => $label )
					{
						$iterator++;
						if ( ! empty( $value ) )
						{
							$options_markup .= sprintf( '<input id="%1$s_%6$s" name="%1$s[]" type="%2$s" value="%3$s" %4$s /><label for="%1$s_%6$s"> %5$s</label><br>', esc_attr( $args['uid'] ), esc_attr( $args['type'] ), esc_attr( $key ), checked( $value[ array_search( $key, $value, TRUE ) ], $key, FALSE ), esc_html( $label ), esc_attr( $iterator ) );
						}
						else
						{
							$options_markup .= sprintf( '<input id="%1$s_%5$s" name="%1$s[]" type="%2$s" value="%3$s" /><label for="%1$s_%5$s"> %4$s</label><br>', esc_attr( $args['uid'] ), esc_attr( $args['type'] ), esc_attr( $key ), esc_html( $label ), esc_attr( $iterator ) );
						}
					}

					printf( '<fieldset>%s</fieldset>', wp_kses_post( $options_markup ) );
				}
				break;

			case 'media-uploader':
				printf( '<input id="%1$s" type="text" name="%1$s" value="%2$s" placeholder="%3$s" size="64" /> <input id="%4$s" type="button" class="button-primary" value="Choose Image" />', esc_attr( $args['uid'] ), esc_attr( $value ), esc_attr( $args['placeholder'] ), esc_attr( $args['button'] ) );
				break;

			case 'color-picker':
				printf( '<input id="%1$s" type="text" name="%1$s" value="%2$s" class="hyper-pwa-color-picker" data-default-color="#ffffff" />', esc_attr( $args['uid'] ), esc_attr( $value ) );
				break;
		}

		if ( ! empty( $args['helper'] ) )
		{
			$helper = $args['helper'];
			printf( '<span class="helper">%s</span>', esc_html( $helper ) );
		}

		if ( ! empty( $args['supplimental'] ) )
		{
			$supplimental = $args['supplimental'];
			printf( '<p class="description">%s</p>', esc_html( $supplimental ) );
		}
	}

	public function register_settings()
	{
		if ( ! current_user_can( 'manage_options' ) )
		{
			return;
		}

		foreach ( $this->fields as $field )
		{
			if ( empty( $field['tab'] ) || $this->tab !== $field['tab'] )
			{
				continue;
			}
			$tab = $field['tab'];

			if ( empty( $field['uid'] ) )
			{
				continue;
			}
			$uid = $field['uid'];

			if ( empty( $field['label'] ) )
			{
				continue;
			}
			$label = $field['label'];

			if ( empty( $field['section'] ) )
			{
				continue;
			}
			$section = $field['section'];

			$title = ! empty( $this->sections[$section] ) ? $this->sections[$section] : '';


			// add_settings_section( string $id, string $title, callable $callback, string $page )
			add_settings_section( $section, $title, [ $this, 'section_callback' ], $tab );

			// add_settings_field( string $id, string $title, callable $callback, string $page, string $section = 'default', array $args = array() )
			add_settings_field( $uid, $label, [ $this, 'field_callback' ], $tab, $section, $field );

			// register_setting( string $option_group, string $option_name, array $args = array() )
			register_setting( 'hyper-pwa', $uid );
		}
	}
}


if ( 'admin-ajax.php' === $GLOBALS['pagenow'] || 'wp-activate.php' === $GLOBALS['pagenow'] || 'wp-cron.php' === $GLOBALS['pagenow'] || 'wp-signup.php' === $GLOBALS['pagenow'] )
{
	return;
}
elseif ( 'wp-login.php' === $GLOBALS['pagenow'] )
{
	if ( ! empty( $_COOKIE['hyper-pwa-admin-tab'] ) )
	{
		$current_timestamp = time();
		setcookie( 'hyper-pwa-admin-tab', '', $current_timestamp - 1, COOKIEPATH, COOKIE_DOMAIN );
	}

	return;
}
elseif ( ! is_admin() )
{
	return;
}

$hyper_pwa_admin = new HyperPWAAdmin();

add_action( 'admin_menu', [ $hyper_pwa_admin, 'add_menu' ] );
add_action( 'admin_init', [ $hyper_pwa_admin, 'register_settings' ] );
