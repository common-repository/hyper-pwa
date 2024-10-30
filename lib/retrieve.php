<?php
if ( !defined( 'ABSPATH' ) )
{
	exit;
}

require_once HYPER_PWA_PATH . 'cfg/cfg.php';
require_once HYPER_PWA_PATH . 'fallback/register.php';
require_once HYPER_PWA_PATH . 'fallback/service-worker.php';
require_once HYPER_PWA_PATH . 'fallback/manifest.php';
require_once HYPER_PWA_PATH . 'fallback/offline.php';
require_once HYPER_PWA_PATH . 'fallback/unregister.php';

class HyperPWARetrieve
{
	private $register = NULL;
	private $service_worker = NULL;
	private $manifest = NULL;
	private $offline = NULL;
	private $unregister = NULL;


	public function __construct()
	{
		$this->register = new HyperPWARegister();
		$this->service_worker = new HyperPWAServiceWorker();
		$this->manifest = new HyperPWAManifest();
		$this->offline = new HyperPWAOffline();
		$this->unregister = new HyperPWAUnregister();
	}

	public function __destruct()
	{
	}


	public function get_register_js()
	{
		$page = get_transient( HYPER_PWA_REGISTER_JS );
		if ( ! empty( $page ) )
		{
			return $page;
		}

		$page = $this->register->get_register();

		return $page;
	}

	public function get_service_worker_js()
	{
		$page = get_transient( HYPER_PWA_SERVICE_WORKER_JS );
		if ( ! empty( $page ) )
		{
			return $page;
		}

		$page = $this->service_worker->get_service_worker();

		return $page;
	}

	public function get_manifest_json()
	{
		$page = get_transient( HYPER_PWA_MANIFEST_JSON );
		if ( ! empty( $page ) )
		{
			return $page;
		}

		$page = $this->manifest->get_manifest();

		return $page;
	}

	public function get_offline_html()
	{
		$page = get_transient( HYPER_PWA_OFFLINE_HTML );
		if ( ! empty( $page ) )
		{
			return $page;
		}

		$page = $this->offline->get_offline();

		return $page;
	}

	public function get_unregister_js()
	{
		$page = get_transient( HYPER_PWA_UNREGISTER_JS );
		if ( ! empty( $page ) )
		{
			return $page;
		}

		$page = $this->unregister->get_unregister();

		return $page;
	}

	public function get_a2hs_js()
	{
		$page = get_transient( HYPER_PWA_A2HS_JS );

		return $page;
	}

	public function get_settings()
	{
		$settings = get_transient( HYPER_PWA_SETTINGS );

		return $settings;
	}
}
