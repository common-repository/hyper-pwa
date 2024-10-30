<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
{
	exit;
}

$options = wp_load_alloptions();
foreach ( $options as $key => $value )
{
	if ( preg_match( '/^hyper[-_]pwa[-_]/i', $key ) )
	{
		delete_option( $key );
	}
}
