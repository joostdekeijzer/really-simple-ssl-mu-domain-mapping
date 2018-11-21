<?php
/**
 * Plugin Name: Really Simple SSL & WordPress MU Domain Mapping Fixes
 * Plugin URI: https://dkzr.nl/
 * Description: Don't redirect to SSL for non-default domains.
 * Version: 0.5
 */
if ( defined( 'DOMAIN_MAPPING' ) && DOMAIN_MAPPING ) {
	add_action( 'wp_loaded', 'rssl_mu_domain_mapping', 10 );
}

function rssl_mu_domain_mapping () {
	global $wpdb, $current_blog;

	$wpdb->dmtable = $wpdb->base_prefix . 'domain_mapping';

	$s = $wpdb->suppress_errors();

	if ( get_site_option( 'dm_no_primary_domain' ) == 1 ) {
		$domain = $wpdb->get_var( "SELECT domain FROM {$wpdb->dmtable} WHERE blog_id = '{$wpdb->blogid}' AND domain = '" . $wpdb->escape( $_SERVER[ 'HTTP_HOST' ] ) . "' LIMIT 1" );
	} else {
		// get primary domain, if we don't have one then return original url.
		$domain = $wpdb->get_var( "SELECT domain FROM {$wpdb->dmtable} WHERE blog_id = '{$wpdb->blogid}' AND active = 1 LIMIT 1" );
	}

	$wpdb->suppress_errors( $s );

	if ( $domain && $current_blog->domain && $domain != $current_blog->domain ) {
		define( 'rsssl_no_wp_redirect', 1 );
	}
}
