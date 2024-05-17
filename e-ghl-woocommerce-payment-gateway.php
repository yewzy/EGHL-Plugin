<?php
/*
Plugin Name: WooCommerce eGHL Payment Gateways
Description: Add eGHL Gateways for WooCommerce.
Version: 1.0.5
Author:  eGHL
Author URI: http://e-ghl.com/
License: GPLv2
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//Additional links on the plugin page
add_filter( 'plugin_row_meta', 'eghl_register_plugin_links', 10, 2 );
function eghl_register_plugin_links( $links, $file ) {
	$base = plugin_basename(__FILE__);
	if ($file == $base) {
		$links[] = '<a href="http://www.e-ghl.com/" target="_blank">' . __( 'Visit plugin site', 'eghl' ) . '</a>';
	}
	return $links;
}

/* WooCommerce fallback notice. */
function woocommerce_eghl_fallback_notice()
{
	echo '<div class="error"><p>' . sprintf( __( 'WooCommerce eGHL Payment Gateways depends on WooCommerce.', 'eghl' ), '<a href="http://wordpress.org/extend/plugins/woocommerce/">WooCommerce</a>' ) . '</p></div>';
}

/* Load functions. */
function eghl_payment_gateway_load()
{
	if ( ! class_exists( 'WC_Payment_Gateway' ) )
	{
		add_action( 'admin_notices', 'woocommerce_eghl_fallback_notice' );
		return;
	}

	function wc_add_gateway( $methods )
	{
		$methods[] = 'WC_Gateway_Eghl';
		return $methods;
	}
	add_filter( 'woocommerce_payment_gateways', 'wc_add_gateway' );

	// Include the WooCommerce eGHL Payment Gateways classes.
	require_once plugin_dir_path( __FILE__ ) . 'class-wc-gateway-eghl.php';
}
add_action( 'plugins_loaded', 'eghl_payment_gateway_load', 0 );

/* Adds custom settings url in plugins page. */
function eghl_action_links( $links )
{
	$settings = array(
		'settings' => sprintf(
			'<a href="%s">%s</a>',
			admin_url( 'admin.php?page=wc-settings&tab=checkout&section=wc_gateway_eghl' ),
			__( 'Settings', 'eghl' )
		),
		'support' => sprintf(
			'<a href="%s">%s</a>',
			'http://support.woothemes.com/',
			__( 'Support', 'eghl' )
		),
		'docs' => sprintf(
			'<a href="%s">%s</a>',
			'http://docs.woothemes.com/',
			__( 'Docs', 'eghl' )
		),
	);

	return array_merge( $settings, $links );
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'eghl_action_links' );

?>