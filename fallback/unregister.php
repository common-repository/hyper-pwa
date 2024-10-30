<?php
if ( !defined( 'ABSPATH' ) )
{
	exit;
}

class HyperPWAUnregister
{
	public function __construct()
	{
	}

	public function __destruct()
	{
	}


	public function get_unregister()
	{
		$page = "\"use strict\";
if ('serviceWorker' in navigator) {
  navigator.serviceWorker.getRegistrations().then(function (registrations) {
    // returns installed service workers
    if (registrations.length) {
      for (let registration of registrations) {
        registration.unregister();
      }
    }
  });
}";

		return $page;
	}
}
