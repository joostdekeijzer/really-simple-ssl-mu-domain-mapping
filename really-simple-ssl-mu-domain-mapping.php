<?php
/**
 * Plugin Name: Really Simple SSL & WordPress MU Domain Mapping
 * Version: 1.0
 * Description: Don't redirect to SSL for non-default domains.
 * Author: Joost de Keijzer
 * Author URI: https://dkzr.nl
 * Plugin URI: https://dkzr.nl
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
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
