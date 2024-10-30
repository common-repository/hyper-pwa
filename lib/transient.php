<?php
if ( ! defined( 'ABSPATH' ) )
{
	exit;
}

require_once HYPER_PWA_PATH . 'cfg/cfg.php';
require_once HYPER_PWA_PATH . 'flx/flx.php';

class HyperPWATransient
{
	private $current_timestamp = 0;

	private $flx = NULL;


	public function __construct()
	{
		$this->current_timestamp = time();

		$this->flx = new HyperPWAFlx();
	}

	public function __destruct()
	{
	}


	public function reset_transient()
	{
		$body = $this->flx->get_pwa();

		if ( ! empty( $body[ HYPER_PWA_REGISTER_JS ] ) )
		{
			set_transient( HYPER_PWA_REGISTER_JS, $body[ HYPER_PWA_REGISTER_JS ], HYPER_PWA_TRANSIENT_EXPIRATION );
		}

		if ( ! empty( $body[ HYPER_PWA_SERVICE_WORKER_JS ] ) )
		{
			set_transient( HYPER_PWA_SERVICE_WORKER_JS, $body[ HYPER_PWA_SERVICE_WORKER_JS ], HYPER_PWA_TRANSIENT_EXPIRATION );
		}

		if ( ! empty( $body[ HYPER_PWA_MANIFEST_JSON ] ) )
		{
			set_transient( HYPER_PWA_MANIFEST_JSON, $body[ HYPER_PWA_MANIFEST_JSON ], HYPER_PWA_TRANSIENT_EXPIRATION );
		}

		if ( ! empty( $body[ HYPER_PWA_OFFLINE_HTML ] ) )
		{
			set_transient( HYPER_PWA_OFFLINE_HTML, $body[ HYPER_PWA_OFFLINE_HTML ], HYPER_PWA_TRANSIENT_EXPIRATION );
		}

		if ( ! empty( $body[ HYPER_PWA_UNREGISTER_JS ] ) )
		{
			set_transient( HYPER_PWA_UNREGISTER_JS, $body[ HYPER_PWA_UNREGISTER_JS ], HYPER_PWA_TRANSIENT_EXPIRATION );
		}

		if ( ! empty( $body[ HYPER_PWA_A2HS_JS ] ) )
		{
			set_transient( HYPER_PWA_A2HS_JS, $body[ HYPER_PWA_A2HS_JS ], HYPER_PWA_TRANSIENT_EXPIRATION );
		}

		if ( ! empty( $body[ HYPER_PWA_SETTINGS ] ) )
		{
			set_transient( HYPER_PWA_SETTINGS, $body[ HYPER_PWA_SETTINGS ], HYPER_PWA_TRANSIENT_EXPIRATION );
		}
	}

	public function remove_transient()
	{
		delete_transient( HYPER_PWA_REGISTER_JS );
		delete_transient( HYPER_PWA_SERVICE_WORKER_JS );
		delete_transient( HYPER_PWA_MANIFEST_JSON );
		delete_transient( HYPER_PWA_OFFLINE_HTML );
		delete_transient( HYPER_PWA_UNREGISTER_JS );
		delete_transient( HYPER_PWA_A2HS_JS );
		delete_transient( HYPER_PWA_SETTINGS );
	}

	public function update_transient()
	{
		$transient_timestamp = get_option( HYPER_PWA_TRANSIENT_TIMESTAMP );
		$version = get_option( HYPER_PWA_VERSION_ID );
		if ( HYPER_PWA_TRANSIENT_SCHEDULE_RECURRENCE > $this->current_timestamp - (int)$transient_timestamp && HYPER_PWA_VERSION === $version )
		{
			return;
		}

		update_option( HYPER_PWA_TRANSIENT_TIMESTAMP, $this->current_timestamp );
		update_option( HYPER_PWA_VERSION_ID, HYPER_PWA_VERSION );

		$this->reset_transient();
	}
}
