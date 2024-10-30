<?php
if ( !defined( 'ABSPATH' ) )
{
	exit;
}

class HyperPWAOffline
{
	private $home_url = '';


	public function __construct()
	{
		$this->home_url = home_url();
	}

	public function __destruct()
	{
	}


	public function get_offline()
	{
		$page = '<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>You are offline</title>
    <style>
      body {
        font-family: helvetica, arial, sans-serif;
        margin: 2em;
      }
      h1 {
        font-style: italic;
        color: #373fff;
      }
      p {
        margin-block: 1rem;
      }
      button {
        display: block;
      }
    </style>
  </head>
  <body>
    <h1>You are offline</h1>
    <p>Click the button below to try reloading.</p>
    <button type="button">&#x21BB; Reload</button>
    <p>Click the button below to go back to previous page.</p>
    <button onclick="history.back()">&#x2190; Back</button>
    <p>Click the button below to go to home page.</p>
    <form style="display: inline" action="' . $this->home_url . '" method="post">
      <button>&#x2302; Home</button>
    </form>
    <script>
      document.querySelector("button").addEventListener("click", () => {
        window.location.reload();
      });
      window.addEventListener(\'online\', () => {
        window.location.reload();
      });
      async function checkNetworkAndReload() {
        try {
          const response = await fetch(\'.\');
          if (response.status >= 200 && response.status < 500) {
            window.location.reload();
            return;
          }
        } catch {
        }
        window.setTimeout(checkNetworkAndReload, 2500);
      }
      checkNetworkAndReload();
    </script>
  </body>
</html>';

		return $page;
	}
}
