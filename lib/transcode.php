<?php
if ( ! defined( 'ABSPATH' ) )
{
	exit;
}

class HyperPWATranscode
{
	public function __construct()
	{
	}

	public function __destruct()
	{
	}


	private function audio_cache_callback( $matches )
	{
		$match = $matches[1];

		if ( preg_match( '/ crossOrigin=(("([^"]*)")|(\'([^\']*)\'))/i', $match ) )
		{
			return '<audio' . $match . '>';
		}

		return '<audio' . $match . ' crossOrigin="anonymous">';
	}

	private function video_cache_callback( $matches )
	{
		$match = $matches[1];

		if ( preg_match( '/ crossOrigin=(("([^"]*)")|(\'([^\']*)\'))/i', $match ) )
		{
			return '<video' . $match . '>';
		}

		return '<video' . $match . ' crossOrigin="anonymous">';
	}


	public function page_callback( $page )
	{
		if ( ! preg_match( '/<!DOCTYPE html>/i', $page ) )
		{
			return $page;
		}

		$pattern = '/<meta\b[^>]*[\s\t\r\n]+name=(("viewport")|(\'viewport\'))[^>]*\s*?\/?>/iU';
		$head = '<meta name="viewport" content="width=device-width, minimum-scale=1, initial-scale=1" />';
		if ( preg_match( $pattern, $page ) )
		{
			$page = preg_replace( $pattern, $head, $page );
		}
		else
		{
			$page = preg_replace( '/<\/head>/i', $head . "\n" . '</head>', $page );
		}

		$page = preg_replace_callback( '/<audio\b([^>]*)\s*?>/iU', [ $this, 'audio_cache_callback' ], $page );
		$page = preg_replace_callback( '/<video\b([^>]*)\s*?>/iU', [ $this, 'video_cache_callback' ], $page );

		return $page;
	}
}
