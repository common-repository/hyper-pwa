<?php
if ( !defined( 'ABSPATH' ) )
{
	exit;
}

require_once HYPER_PWA_PATH . 'cfg/cfg.php';

class HyperPWARegister
{
	private $home_url = '';
	private $host_dir = '';


	public function __construct()
	{
		$this->home_url = home_url();
		$this->host_dir = preg_replace( '/^https?:\/\/[^\/]+/im', '', $this->home_url );
	}

	public function __destruct()
	{
	}


	public function get_register()
	{
		$page = "\"use strict\";
import {Workbox} from 'https://storage.googleapis.com/workbox-cdn/releases/7.1.0/workbox-window.prod.mjs';
if ('serviceWorker' in navigator) {
  const wb = new Workbox('" . $this->host_dir . "/" . HYPER_PWA_SERVICE_WORKER_JS . "');
  wb.addEventListener('activated', (event) => {
    if (!event.isUpdate) {
    }
  });
  wb.addEventListener('waiting', (event) => {
  });
  wb.addEventListener('message', (event) => {
    if (event.data.type === 'CACHE_UPDATED') {
      const {updatedURL} = event.data.payload;
    }
  });
  wb.addEventListener('activated', (event) => {
    const urlsToCache = [
    ];
    wb.messageSW({
      type: 'CACHE_URLS',
      payload: {urlsToCache}
    });
  });
  wb.register();
  const swVersion = await wb.messageSW({type: 'GET_VERSION'});
}";

		return $page;
	}
}
