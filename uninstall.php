<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

delete_option( 'ACFFA_icon_data' );
delete_option( 'ACFFA_current_version' );

$timestamp = wp_next_scheduled( 'ACFFA_refresh_latest_icons' );

if ( $timestamp ) {
	wp_unschedule_event( $timestamp, 'ACFFA_refresh_latest_icons' );
}
