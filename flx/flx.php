<?php
if ( ! defined( 'ABSPATH' ) )
{
	exit;
}

include_once ABSPATH . 'wp-admin/includes/plugin.php';

require_once HYPER_PWA_PATH . 'cfg/cfg.php';
require_once HYPER_PWA_PATH . 'lib/option.php';

class HyperPWAFlx
{
	private $home_url = '';

	private $option = NULL;


	public function __construct()
	{
		$this->home_url = home_url();

		$this->option = new HyperPWAOption();
	}

	public function __destruct()
	{
	}


	private function get_server_list()
	{
		$list = [];

		$num = wp_rand( 1, 6 );
		if ( 1 === $num )
		{
			$list = [ HYPER_PWA_FLX_SERVER_1, HYPER_PWA_FLX_SERVER_2, HYPER_PWA_FLX_SERVER_3 ];
		}
		elseif ( 2 === $num )
		{
			$list = [ HYPER_PWA_FLX_SERVER_1, HYPER_PWA_FLX_SERVER_3, HYPER_PWA_FLX_SERVER_2 ];
		}
		elseif ( 3 === $num )
		{
			$list = [ HYPER_PWA_FLX_SERVER_2, HYPER_PWA_FLX_SERVER_1, HYPER_PWA_FLX_SERVER_3 ];
		}
		elseif ( 4 === $num )
		{
			$list = [ HYPER_PWA_FLX_SERVER_2, HYPER_PWA_FLX_SERVER_3, HYPER_PWA_FLX_SERVER_1 ];
		}
		elseif ( 5 === $num )
		{
			$list = [ HYPER_PWA_FLX_SERVER_3, HYPER_PWA_FLX_SERVER_1, HYPER_PWA_FLX_SERVER_2 ];
		}
		else
		{
			$list = [ HYPER_PWA_FLX_SERVER_3, HYPER_PWA_FLX_SERVER_2, HYPER_PWA_FLX_SERVER_1 ];
		}

		return $list;
	}

	private function fetch( $url, $args )
	{
		$response = wp_remote_post( $url, $args );

		$http_code = wp_remote_retrieve_response_code( $response );
		if ( 200 !== $http_code )
		{
			return;
		}

		$body = wp_remote_retrieve_body( $response );
		$body = json_decode( $body, TRUE );
		if ( empty( $body ) || ! is_array( $body ) )
		{
			return;
		}

		return $body;
	}

	private function query( $args )
	{
		list( $server1, $server2, $server3 ) = $this->get_server_list();

		$url = $server1 . HYPER_PWA_FLX_API;
		$body = $this->fetch( $url, $args );
		if ( ! empty( $body ) )
		{
			return $body;
		}

		if ( $server1 === $server2 )
		{
			return;
		}

		$url = $server2 . HYPER_PWA_FLX_API;
		$body = $this->fetch( $url, $args );
		if ( ! empty( $body ) )
		{
			return $body;
		}

		if ( $server2 === $server3 )
		{
			return;
		}

		$url = $server3 . HYPER_PWA_FLX_API;
		$body = $this->fetch( $url, $args );
		if ( ! empty( $body ) )
		{
			return $body;
		}

		return;
	}


	public function get_pwa()
	{
		$short_name = get_bloginfo( 'name' );
		$description = get_bloginfo( 'description' );
		$name = $short_name . ( ! empty( $description ) ? ( ' – ' . $description ) : '' );
		$icons = $this->option->get_icons();
		$screenshots = $this->option->get_screenshots();

		$site_type = $this->option->get_site_type();

		$webpushr = is_plugin_active( 'webpushr-web-push-notifications/push.php' ) ? 'enable' : 'disable';
		$cleverpush = is_plugin_active( 'cleverpush/cleverpush.php' ) ? 'enable' : 'disable';

		$data = [
			'name' => $name,
			'short_name' => $short_name,
			'description' => $description,
			'icons' => $icons,
			'screenshots' => $screenshots,

			'site_type' => $site_type,

			'webpushr' => $webpushr,
			'cleverpush' => $cleverpush
		];

		$body = [
			'version' => HYPER_PWA_VERSION,
			'home_url' => $this->home_url,
			'data' => $data
		];
		$body = wp_json_encode( $body );

		$args = [
			'headers' => [ 'Content-Type' => 'application/json; charset=utf-8' ],
			'method' => 'POST',
			'data_format' => 'body',
			'body' => $body
		];

		$body = $this->query( $args );

		$body[ HYPER_PWA_REGISTER_JS ] =
			( ! empty( $body[ HYPER_PWA_REGISTER_JS ] ) && is_string( $body[ HYPER_PWA_REGISTER_JS ] ) ) ?
			trim( $body[ HYPER_PWA_REGISTER_JS ] ) : '';

		$body[ HYPER_PWA_SERVICE_WORKER_JS ] =
			( ! empty( $body[ HYPER_PWA_SERVICE_WORKER_JS ] ) && is_string( $body[ HYPER_PWA_SERVICE_WORKER_JS ] ) ) ?
			trim( $body[ HYPER_PWA_SERVICE_WORKER_JS ] ) : '';

		$body[ HYPER_PWA_MANIFEST_JSON ] =
			( ! empty( $body[ HYPER_PWA_MANIFEST_JSON ] ) && is_string( $body[ HYPER_PWA_MANIFEST_JSON ] ) ) ?
			trim( $body[ HYPER_PWA_MANIFEST_JSON ] ) : '';

		$body[ HYPER_PWA_OFFLINE_HTML ] =
			( ! empty( $body[ HYPER_PWA_OFFLINE_HTML ] ) && is_string( $body[ HYPER_PWA_OFFLINE_HTML ] ) ) ?
			trim( $body[ HYPER_PWA_OFFLINE_HTML ] ) : '';

		$body[ HYPER_PWA_UNREGISTER_JS] =
			( ! empty( $body[ HYPER_PWA_UNREGISTER_JS ] ) && is_string( $body[ HYPER_PWA_UNREGISTER_JS ] ) ) ?
			trim( $body[ HYPER_PWA_UNREGISTER_JS ] ) : '';

		$body[ HYPER_PWA_A2HS_JS ] =
			( ! empty( $body[ HYPER_PWA_A2HS_JS ] ) && is_string( $body[ HYPER_PWA_A2HS_JS ] ) ) ?
			trim( $body[ HYPER_PWA_A2HS_JS ] ) : '';

		$body[ HYPER_PWA_SETTINGS ] =
			( ! empty( $body[ HYPER_PWA_SETTINGS ] ) && is_array( $body[ HYPER_PWA_SETTINGS ] ) ) ?
			$body[ HYPER_PWA_SETTINGS ] : array();

		return $body;
	}
}
