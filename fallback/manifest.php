<?php
if ( !defined( 'ABSPATH' ) )
{
	exit;
}

require_once HYPER_PWA_PATH . 'lib/option.php';

class HyperPWAManifest
{
	private $home_url = '';
	private $host_dir = '';

	private $option = NULL;


	public function __construct()
	{
		$this->home_url = home_url();
		$this->host_dir = preg_replace( '/^https?:\/\/[^\/]+/im', '', $this->home_url );

		$this->option = new HyperPWAOption();
	}

	public function __destruct()
	{
	}


	public function get_manifest()
	{
		$short_name = get_bloginfo( 'name' );
		$description = get_bloginfo( 'description' );
		$name = $short_name . ( ! empty( $description ) ? ( ' – ' . $description ) : '' );
		$icons = $this->option->get_icons();
		$screenshots = $this->option->get_screenshots();
		$id = $this->home_url;

		$page = '{
  "name": "' . $name . '",
  "short_name": "' . $short_name . '",';

		if ( ! empty( $description ) )
		{
			$page .= '
  "description": "' . $description . '",';
		}

		$page .= '
  "id": "' . $id . '",
  "start_url": "' . $this->host_dir . '/?src=hyper-pwa",
  "theme_color": "#ffffff",
  "background_color": "#ffffff",
  "orientation": "any",
  "display": "standalone",
  "protocol_handlers": [
    {
      "protocol": "web+pwa",
      "url": "' . $this->host_dir . '/pwa?type=%s"
    }
  ],
  "icons": [
    {
      "src": "' . $this->host_dir . $icons['icon_192'] . '",
      "sizes": "192x192",
      "type": "image/png",
      "purpose": "any"
    },
    {
      "src": "' . $this->host_dir . $icons['icon_512'] . '",
      "sizes": "512x512",
      "type": "image/png",
      "purpose": "any"
    },
    {
      "src": "' . $this->host_dir . $icons['maskable_icon_192'] . '",
      "sizes": "192x192",
      "type": "image/png",
      "purpose": "maskable"
    },
    {
      "src": "' . $this->host_dir . $icons['maskable_icon_512'] . '",
      "sizes": "512x512",
      "type": "image/png",
      "purpose": "maskable"
    }
  ],
  "display_override": [
    "window-controls-overlay"
  ],
  "screenshots": [
    {
      "src": "' . $this->host_dir . $screenshots['wide'] . '",
      "sizes": "512x384",
      "type": "image/gif",
      "form_factor": "wide",
      "label": "Wide Screenshot"
    },
    {
      "src": "' . $this->host_dir . $screenshots['narrow'] . '",
      "sizes": "384x512",
      "type": "image/gif",
      "form_factor": "narrow",
      "label": "Narrow Screenshot"
    }
  ],
  "categories": [
  ],
  "scope": "' . $this->host_dir . '/"
}';

		return $page;
	}
}
