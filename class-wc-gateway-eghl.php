<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once 'eGHLPackage/vendor/autoload.php';

use Omnipay\Omnipay;

/**
 * WC eGHL Gateway Class.
 */
class WC_Gateway_Eghl extends WC_Payment_Gateway {
	/**
	 * Constructor for the gateway.
	 *
	 * @return void
	 */

	private $gateway = NULL; 

	public function __construct() {
		global $woocommerce;

		$this->id                = 'eghl';
    	$this->icon              = plugins_url( 'images/eghl.png', __FILE__ );
		$this->has_fields        = false;
		$this->order_button_text = __( 'Proceed to eGHL', 'eghl' );
		$this->method_title      = __( 'eGHL', 'eghl' );
		$this->notify_url        = add_query_arg( 'wc-api', 'WC_Gateway_Eghl', home_url( '/' ) );
		$this->callback_url      = add_query_arg( array( 'wc-api' => 'WC_Gateway_Eghl', 'callback' => 'server' ), home_url( '/' ) );

		// Load the form fields.
		$this->init_form_fields();

		// Load the settings.
		$this->init_settings();

		// Define user set variables.
		$this->merchant_code  = $this->get_option( 'merchant_code' );
		$this->merchant_key   = $this->get_option( 'merchant_key' );
		$this->description    = $this->get_option( 'description' );
		$this->title          = $this->get_option( 'title' );
		$this->description    = $this->get_option( 'description' );
		$this->testmode       = $this->get_option( 'testmode' );
		$this->ocpenabled       = $this->get_option( 'ocpenabled' );
		$this->debug          = $this->get_option( 'debug' );
		$this->invoice_prefix	= $this->get_option( 'invoice_prefix', 'WC-' );
		$this->page_timeout   = $this->get_option( 'page_timeout', '600' );

		$this->gateway = Omnipay::create('eGHL');
		$this->gateway->setMerchantId($this->merchant_code);
		$this->gateway->setMerchantPassword($this->merchant_key );
		if ( 'yes' === $this->testmode ) {
			$this->gateway->setTestMode();
		}

		// Logs
		if ( 'yes' == $this->debug ) {
			$this->log = new WC_Logger();
		}

		// Actions.
		add_action( 'woocommerce_update_options_payment_gateways_eghl', array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_receipt_eghl', array( $this, 'receipt_page' ) );
		add_action( 'woocommerce_api_wc_gateway_eghl', array( $this, 'check_eghl_response' ) );

		if ( ! $this->is_valid_for_use() ) {
			$this->enabled = false;
		}
	}

	/**
	 * Check if this gateway is enabled and available in the user's country
	 *
	 * @access public
	 * @return bool
	 */
	function is_valid_for_use() {
		if ( ! in_array( get_woocommerce_currency(), apply_filters( 'woocommerce_eghl_supported_currencies', array( 'CNY', 'IDR', 'AUD', 'BRL', 'CAD', 'MXN', 'NZD', 'HKD', 'SGD', 'USD', 'EUR', 'JPY', 'TRY', 'NOK', 'CZK', 'DKK', 'HUF', 'ILS', 'MYR', 'PHP', 'PLN', 'SEK', 'CHF', 'TWD', 'THB', 'GBP', 'RMB', 'RUB' ) ) ) ) {
			return false;
		}

		return true;
	}

	/* Admin Panel Options.*/
	public function admin_options() {

		?>
		<h3><?php _e( 'eGHL', 'eghl' ); ?></h3>
		<p><?php _e( 'eGHL works by sending the user to eGHL to enter their payment information.', 'eghl' ); ?></p>

		<?php if ( $this->is_valid_for_use() ) : ?>

			<table class="form-table">
			<?php
				// Generate the HTML For the settings form.
				$this->generate_settings_html();
			?>
			</table><!--/.form-table-->

		<?php else : ?>
			<div class="inline error"><p><strong><?php _e( 'Gateway Disabled', 'eghl' ); ?></strong>: <?php _e( 'eGHL does not support your store currency.', 'eghl' ); ?></p></div>
		<?php
			endif;
	}

	/**
	 * Initialise Gateway Settings Form Fields
	 *
	 * @access public
	 * @return void
	 */
	function init_form_fields() {

		$this->form_fields = array(
			'enabled' => array(
				'title'   => __( 'Enable/Disable', 'eghl' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable Credit Card / Direct Debit Checkout', 'eghl' ),
				'default' => 'yes'
			),
			'title' => array(
				'title'       => __( 'Title', 'eghl' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'eghl' ),
				'default'     => __( 'Credit Card / Direct Debit (via eGHL)', 'eghl' ),
				'desc_tip'    => true,
			),
			'description' => array(
				'title'       => __( 'Description', 'eghl' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'eghl' ),
				'default'     => __( 'Pay via eGHL; you can pay with your credit card.', 'eghl' )
			),
			'merchant_code' => array(
				'title'       => __( 'eGHL Merchant Service ID', 'eghl' ),
				'type'        => 'text',
				'description' => __( 'Merchant Service ID given by eGHL', 'eghl' ),
				'default'     => '',
				'desc_tip'    => true,
			),
			'merchant_key' => array(
				'title'       => __( 'eGHL Merchant Password', 'eghl' ),
				'type'        => 'password',
				'description' => __( 'Merchant Password given by eGHL', 'eghl' ),
				'default'     => '',
				'desc_tip'    => true,
			),
			'invoice_prefix' => array(
				'title'       => __( 'Invoice Prefix', 'eghl' ),
				'type'        => 'text',
				'description' => __( 'Please enter a prefix for your invoice numbers. If you use your eGHL account for multiple stores ensure this prefix is unique as eGHL will not allow orders with the same invoice number.', 'eghl' ),
				'default'     => 'WC-',
				'desc_tip'    => true,
			),
			'testing' => array(
				'title'       => __( 'Gateway Testing', 'eghl' ),
				'type'        => 'title',
				'description' => '',
			),
			'testmode' => array(
				'title'       => __( 'eGHL Test Mode', 'eghl' ),
				'type'        => 'checkbox',
				'label'       => __( 'Enable eGHL Testing', 'eghl' ),
				'default'     => 'no',
				'description' => __( 'eGHL Test Mode can be used to test payments.', 'eghl' ),
			),
			'ocpenabled' => array(
				'title'       => __( 'Enable OCP', 'eghl' ),
				'type'        => 'checkbox',
				'label'       => __( 'Enable OCP for eGHL payment', 'eghl' ),
				'default'     => 'no',
				'description' => __( 'With OCP enabled the customer credit card info will be remembered by eGHL, if the customer agrees.', 'eghl' ),
			),
			'debug' => array(
				'title'       => __( 'Debug Log', 'eghl' ),
				'type'        => 'checkbox',
				'label'       => __( 'Enable logging', 'eghl' ),
				'default'     => 'no',
				'description' => sprintf( __( 'Log eGHL events, such as IPN requests, inside <code>%s</code>', 'eghl' ), wc_get_log_file_path( 'eghl' ) )
			)
		);
	}
	
	/**
	 * Output of autosubmit form redirected to eGHL.
	 *
	 * @access public
	 * @return void
	 */
	function receipt_page( $order ) {
		$data = $this->getOrderData( $order );
		$PurchaseResponse = $this->gateway->purchase($data)->send();
		if ($PurchaseResponse->isRedirect()) {
			$PurchaseResponse->redirect();
		}
	}
	
	function write_log ( $log )  {
	  if ( is_array( $log ) || is_object( $log ) ) {
		 error_log( print_r( $log, true ) );
	  } else {
		 error_log( $log );
	  }
	}

	/**
	 * Get eGHL Args for passing to PP
	 *
	 * @access public
	 * @param mixed $order
	 * @return array
	 */
	function get_eghl_args( $order ) {

		$order_id = $order->get_id();

		$orderNumber = ltrim( $order->get_order_number(), '#' );

		if ( 'yes' == $this->debug ) {
			$this->log->add( 'eghl', 'Generating payment form for order ' . $order->get_order_number() . '. Notify URL: ' . $this->notify_url );
		}
		
		$PromoCode = '';
		
		foreach(WC()->cart->get_coupons() as $WC_Coupon){
			$PromoCode = $WC_Coupon->get_code();
			break;
		}
		
		$PaymentDesc = '';
		try{
			$product_details = array();
			$order_items = $order->get_items();
			
			foreach( $order_items as $product ) {
                $product_details[] = $product['name']." x ".$product['qty'];
			}
			
            $PaymentDesc = implode( ',', $product_details );
		}
		catch (Exception $e) {
			$this->write_log("eGHL Plugin Exception while collecting order item details: ".$e->__toString());
		}
		
		$PaymentDesc = strip_tags($PaymentDesc); // Remove any html tags
		
		if(empty($PaymentDesc)){
			$PaymentDesc = "No Description";
		}
		
		
		$maxLen = 100;
		if(mb_strlen($PaymentDesc)>$maxLen){
			$PaymentDesc = mb_substr($PaymentDesc,0,($maxLen-3)).'...';
		}
		
		if(mb_strlen($PaymentDesc)<1){
			$PaymentDesc = "Wordpress Order Number: ".trim($order->get_order_number());
		}

		// eGHL Args
		$eghl_args = array(
			'PymtMethod'          => 'ANY',
			'PaymentID'           => $order_id . '#' . $this->genPaymentID(20-(strlen($order_id)+1)),
			'OrderNumber'         => $this->invoice_prefix . ltrim( $order->get_order_number(), '#' ),
			'PaymentDesc'         => $PaymentDesc,

			'Amount'              => sprintf( "%.02f", $order->get_total() ),
			'CurrencyCode'        => strtoupper( get_woocommerce_currency() ),
			'PageTimeout'         => $this->page_timeout,
			'MerchantReturnURL'   => (isset($_SERVER['HTTPS']))?str_replace('http://','https://',str_replace( "&", ";", $this->notify_url )):str_replace( "&", ";", $this->notify_url ),
			'MerchantCallBackURL' => (isset($_SERVER['HTTPS']))?str_replace('http://','https://',str_replace( "&", ";", $this->callback_url )):str_replace( "&", ";", $this->callback_url ),

			//Customer Info
			'CustIP'              => $this->getIP(),
			'CustName'            => $order->get_billing_first_name().' '.$order->get_billing_last_name(),
			'CustPhone'           => $order->get_billing_phone(),
			'CustEmail'           => $order->get_billing_email(),
			
			//Bill Address
			'BillAddr'				=>	$order->get_billing_address_1()." ".$order->get_billing_address_2(),
			'BillPostal'			=>	$order->get_billing_postcode(),
			'BillCity'				=>	$order->get_billing_city(),
			'BillRegion'			=>	empty($order->get_billing_state())?"None":$order->get_billing_state(),
			'BillCountry'			=>	$order->get_billing_country(),
			'PromoCode'			=>	$PromoCode
		);
		
		if('yes' == $this->ocpenabled){
			$eghl_args['TokenType'] = 'OCP';
		}
		
		$eghl_args = apply_filters( 'woocommerce_eghl_args', $eghl_args );

		return $eghl_args;
	}
	
	public function getIP(){
		$IP = $_SERVER['REMOTE_ADDR'];
		if(strlen($IP) > 20){
			$IP = substr($_SERVER['REMOTE_ADDR'], 0, 20);
		}
		return $IP;
	}
	
	public function GUID($length)
	{
		$output = '';	
		$pseudoBytesLen = 64;
		
		$bytes = openssl_random_pseudo_bytes($pseudoBytesLen);
		$hex_array = str_split(bin2hex($bytes));
		
		for($i=0;$i<$length;$i++){
			$output .= $hex_array[rand ( 0 , ($pseudoBytesLen-1) )];
		}
		return $output;
	}
 
	public function genPaymentID($maxLength = 20){
		$timeStamp = time();
		$guid_len = $maxLength - strlen((string)$timeStamp);
		return $timeStamp.$this->GUID($guid_len);
	}

	/**
	 * Prepare Order data to be submitted to eGHL
	 *
	 * @access public
	 * @param mixed $order_id
	 * @return string
	 */
	function getOrderData( $order_id ) {

		$order = wc_get_order( $order_id );

		$eghl_args = $this->get_eghl_args( $order );

		return $eghl_args;
	}

	/**
	 * Process the payment and return the result
	 *
	 * @access public
	 * @param int $order_id
	 * @return array
	 */
	function process_payment( $order_id ) {

		$order = wc_get_order( $order_id );

		return array(
			'result' 	=> 'success',
			'redirect'	=> $order->get_checkout_payment_url( true )
		);

	}

	/**
	 * Verify a successful Payment!
	**/
	function check_eghl_response( $posted ){
		global $woocommerce;

		$Response = $this->gateway->completePurchase($_REQUEST)->send();

		$posted = stripslashes_deep( $_REQUEST );

		$PaymentID    = $posted['PaymentID'];
		$ServiceID    = $posted['ServiceID'];
		$OrderNumber  = $posted['OrderNumber'];
		$Amount       = $posted['Amount'];
		$CurrencyCode = $posted['CurrencyCode'];
		$TxnID        = $posted['TxnID'];
		$PymtMethod   = $posted['PymtMethod'];
		$TxnStatus    = $posted['TxnStatus'];
		$AuthCode     = (!empty($posted['AuthCode'])) ? $posted['AuthCode'] : "";
		$TxnMessage   = $posted['TxnMessage'];
		$IssuingBank  = (!empty($posted['IssuingBank'])) ? $posted['IssuingBank'] : "";
		$HashValue    = $posted['HashValue2'];
		$PromoCode  = (!empty($posted['PromoCode'])) ? $posted['PromoCode'] : "";
		$PromoOriAmt  = (!empty($posted['PromoOriAmt'])) ? $posted['PromoOriAmt'] : "";

		$order = $this->get_eghl_order( $OrderNumber, $PaymentID,  $posted);

		$URLType = 'return';
		if ( !empty( $posted['callback'] ) && $posted['callback'] == 'server' ) {
			$URLType = 'callback';
		}

		if ( $Response->isSuccessful() ) {

			// Store PP Details
			if ( ! empty( $PaymentID ) ) {
				update_post_meta( $order->get_id(), 'PaymentID', wc_clean( $PaymentID ) );
			}
			if ( ! empty( $ServiceID ) ) {
				update_post_meta( $order->get_id(), 'ServiceID', wc_clean( $ServiceID ) );
			}
			if ( ! empty( $OrderNumber ) ) {
				update_post_meta( $order->get_id(), 'OrderNumber', wc_clean( $OrderNumber ) );
			}
			if ( ! empty( $Amount ) ) {
				update_post_meta( $order->get_id(), 'Amount', wc_clean( $Amount ) );
			}
			if ( ! empty( $CurrencyCode ) ) {
				update_post_meta( $order->get_id(), 'CurrencyCode', wc_clean( $CurrencyCode ) );
			}
			if ( ! empty( $TxnID ) ) {
				update_post_meta( $order->get_id(), 'TxnID', wc_clean( $TxnID ) );
			}
			if ( ! empty( $TxnStatus ) ) {
				update_post_meta( $order->get_id(), 'TxnStatus', wc_clean( $TxnStatus ) );
			}
			if ( ! empty( $AuthCode ) ) {
				update_post_meta( $order->get_id(), 'AuthCode', wc_clean( $AuthCode ) );
			}
			if ( ! empty( $TxnMessage ) ) {
				update_post_meta( $order->get_id(), 'TxnMessage', wc_clean( $TxnMessage ) );
			}
			if ( ! empty( $IssuingBank ) ) {
				update_post_meta( $order->get_id(), 'IssuingBank', wc_clean( $IssuingBank ) );
			}
			if ( ! empty( $HashValue ) ) {
				update_post_meta( $order->get_id(), 'HashValue', wc_clean( $HashValue ) );
			}
			if ( ! empty( $PromoCode ) ) {
				update_post_meta( $order->get_id(), 'PromoCode', wc_clean( $PromoCode ) );
			}
			if ( ! empty( $PromoOriAmt ) ) {
				update_post_meta( $order->get_id(), 'PromoOriAmt', wc_clean( $PromoOriAmt ) );
			}

			if(!$order->has_status('processing')){
				$order->add_order_note( __( '['.$URLType.'] eGHL payment completed', 'eghl' ) );
				$order->payment_complete();
			}

			if ( $URLType == "callback" ) {
				echo "OK";
				exit;
			}

			$redirect_url = $this->get_return_url( $order );

		}
		elseif($Response->isPending()) {
			if ( 'yes' == $this->debug ) {
				$this->log->add( 'eghl', '['.$URLType.'] Payment status: Pending');
			}
			$order->add_order_note( __( '['.$URLType.'] eGHL payment pending', 'eghl' ) );
			if ( $URLType == "callback" ) {
				echo "OK";
				exit;
			} else {
				wc_add_notice( __( 'Your payment is still pending. ', 'eghl' ), 'error' );
			}
			
			//For removing all the items from the cart
			global $woocommerce;
			$woocommerce->cart->empty_cart();
			
			$redirect_url = get_permalink( wc_get_page_id('cart') );
		}
		else {
			if ( 'yes' == $this->debug ) {
				$this->log->add( 'eghl', '['.$URLType.'] Payment status: ' . ($TxnStatus == "0" ? "success" : "failed") );
			}

			if ( $URLType == "callback" ) {
				echo "ERROR";
				exit;
			} else {
				wc_add_notice( __( 'Payment error: ', 'eghl' ) . $TxnMessage, 'error' );
				wc_add_notice( __( 'You can procced the payment again. ', 'eghl' ), 'error' );
			}
			
			$redirect_url = get_permalink( wc_get_page_id('cart') );
		}

        if ( 'yes' == $this->debug ) {
			$this->log->add( 'eghl', '['.$URLType.'] Order ID: ' . $order->get_id() . ' | Order Number:' . $OrderNumber . ' | redirect_url:' . $redirect_url);
		}

		wp_safe_redirect( $redirect_url );
		exit;

	}

	/**
	 * get_eghl_order function.
	 *
	 * @param  string $custom
	 * @param  string $invoice
	 * @return WC_Order object
	 */
	private function get_eghl_order( $custom, $paymentID, $respParams) {
		$order_id  = str_replace( $this->invoice_prefix, '', $custom );
		
		$order_id =  explode("#", $paymentID)[0];
		$order = wc_get_order( $order_id );
		if(empty($order)){
			$order_id  = (int)$order_id;
			$order = wc_get_order( $order_id );
			if(empty($order)){
				$this->log->add( 'eghl', 'Unable to find order');
				throw new exception("unable to find order");
			}
		}

		if(
			isset($respParams['Amount']) && 
			(($order->get_total() - $respParams['Amount']) != 0)
		){
			$msg = 'Transaction amount missmatched order amount [Expected: '.$order->get_total().'] [Recieved: '.$respParams['Amount'].']';
			$this->log->add( 'eghl', $msg);
			throw new exception($msg);
		}

		if(
			explode("#", $paymentID)[0] != $order->get_id()
		){			
			$msg = 'Transaction order id missmatched order id [Expected: '.$order->get_id().'] [Recieved: '.explode("#", $paymentID)[0].']';
			$this->log->add( 'eghl', $msg);
			throw new exception($msg);
		}

		return $order;
	}

}