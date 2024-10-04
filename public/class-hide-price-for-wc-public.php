<?php

/**
 * undocumented class
 */

if( !class_exists( 'Hide_Price_For_WC_Public' ) ){

	class Hide_Price_For_WC_Public
	{
		
		private $role_status;
		/**
		 * Register the stylesheets for the public area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {

			wp_enqueue_style( CM_HPFW_PLUGIN_NAME.'-style', CM_HPFW_PLUGIN_URL . 'public/assets/css/style.css', array(), CM_HPFW_PLUGIN_VER, 'all' );

		}

		/**
		 * Register the JavaScript for the public area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {
			
			wp_enqueue_script( CM_HPFW_PLUGIN_NAME.'-script', CM_HPFW_PLUGIN_URL . 'public/assets/js/script.js', array( 'jquery','jquery-blockui' ), CM_HPFW_PLUGIN_VER, false );

			wp_localize_script(CM_HPFW_PLUGIN_NAME.'-script','hpfwcAjaxData',[
				'security' => wp_create_nonce('hidepriceforwoocommerce-security'),
				'ajaxUrl' => admin_url('admin-ajax.php')
			]);

		}
		
		function init(){
			$available_methods = $this->is_hide_price_enable_methods();

			if(!empty($available_methods)){
			
				// Set login methods
				if(in_array('byrole', $available_methods) && is_user_logged_in()){
					$invalid_roles = get_option('hide-price-by-role',[]);
					$user = wp_get_current_user();

					$check = array_intersect($invalid_roles, $user->roles);
					if(!empty($check)){
						$this->role_status = false;
						add_filter( 'woocommerce_get_price_html', '__return_empty_string', 99 );
						add_filter( 'woocommerce_get_variation_price_html',  '__return_empty_string', 99 );
						$this->hide_price_actions();
					}
				}
				else{
					if( !is_user_logged_in() ){
						$this->do_hide_price_enabled();
						$this->hide_price_actions();
					}
				}
			}
		}

		
		function hide_price_actions(){
			
			

			// Customisations on cart page item table
			add_filter( 'woocommerce_cart_item_price',  [ $this, 'replace_price_by_text' ], 99 );
			add_filter( 'woocommerce_cart_item_subtotal',  [ $this, 'replace_price_by_text' ], 99 );

			// Customisations on cart page total calculation
			add_filter( 'woocommerce_cart_totals_order_total_html',  [ $this, 'replace_price_by_text' ], 99 );
			add_filter( 'woocommerce_cart_totals_taxes_total_html',  [ $this, 'replace_price_by_text' ], 99 );
			add_filter( 'woocommerce_cart_totals_order_total_html',  [ $this, 'replace_price_by_text' ], 99 );
			add_filter( 'woocommerce_cart_totals_coupon_html',  [ $this, 'replace_price_by_text' ], 99 );
			add_filter( 'woocommerce_cart_totals_fee_html',  [ $this, 'replace_price_by_text' ], 99 );
			add_filter( 'woocommerce_cart_shipping_method_full_label',  [ $this, 'replace_price_by_text' ], 99 );

			add_filter( 'woocommerce_cart_subtotal',  [ $this, 'replace_price_by_text' ], 99 );
			add_filter( 'woocommerce_get_formatted_order_total',  [ $this, 'replace_price_by_text' ], 99 );
			add_filter( 'woocommerce_get_order_item_totals',  [ $this, 'replace_price_by_text' ], 99 );
			// Checkout page 
			add_filter( 'woocommerce_order_button_html', [ $this, 'remove_place_order_button'] );
		}

		/**
		 * undocumented function summary
		 *
		 * Undocumented function long description
		 *
		 * @return string
		 * @throws conditon
		 **/
		public function replace_price_by_text()
		{
			$price_value = get_option( 'replace_price_by_text', '--' );

			$price_value = apply_filters('hpfwc_replace_price_by_text' , $price_value );

			return $price_value;
		}

		/**
		 * undocumented function summary
		 *
		 * Undocumented function long description
		 *
		 * @param string $button Place order button at checkout page
		 * @return string
		 **/
		public function remove_place_order_button($button)
		{
			if( !$this->role_status ){
				ob_start();
				?>
					<ul class="woocommerce-error" role="alert">
						<li><?php esc_html_e( 'You don\'t have enough permission to place this order.',  'hide-price-for-woocommerce')?></li>
					</ul>
				<?php

				$button = ob_get_clean();
			}

			return $button;
		}


		/**
		 * Check hipeprice enabled or not
		 * @name is_hide_price_enable
		 * @author Crazy Modifier <plugins@crazymodifier.com> 
		 * @link https://crazymodifier.com/
		 * @return $methods[]
		 **/
		
		function is_hide_price_enable_methods(){
			$methods = [];
			$check = get_option( 'status-hpfwc' , 0);
			if('yes' != $check){
				return $methods;
			}
			else{
				$methods[] = 'enabled';
			}
			
			if('yes' === get_option('status-hide-price-by-role', 0))
			{
				$methods[] = 'byrole';
			}
			return $methods;
		}
		
		
		function do_hide_price_enabled(){
			$login_type= get_option( 'hpfwc-display-type' , 0);
			// print_r($login_type); die;
			switch($login_type){
				case 'button':
					add_filter('woocommerce_get_price_html',[$this, 'hide_price_show_login_button'],15,2);
					add_filter( 'woocommerce_get_variation_price_html', [$this, 'hide_price_show_login_button'],15,2);
					break;
				case 'custom-text':
					add_filter('woocommerce_get_price_html',[$this, 'hide_price_show_custom_text'],15,2);
					add_filter( 'woocommerce_get_variation_price_html', [$this, 'hide_price_show_custom_text'],15,2);
					break;
				case 'info-notice' :
					add_filter('woocommerce_get_price_html','__return_empty_string',15);
					add_filter( 'woocommerce_get_variation_price_html',  '__return_empty_string', 99 );
					break;
				default:
					add_filter('woocommerce_get_price_html','__return_empty_string',15);
					add_filter( 'woocommerce_get_variation_price_html',  '__return_empty_string', 99 );
					break;
			}
		}
		
		function hide_price_show_login_button($price, $product){
			$output = '';
			$method = get_option('hpfwc-form-type', 'form-modal');

			switch($method){
				case 'custom-url':
					$redirect_url = get_option('custom-redirect-url', wp_login_url());
					$output = sprintf('<a class="hp-button-default" href="%s">%s</a>',esc_url($redirect_url), esc_html__( 'Login', 'hide-price-for-woocommerce' ) );;
					break;
				case 'form-modal' :
					wp_enqueue_style( CM_HPFW_PLUGIN_NAME.'-modal-style', CM_HPFW_PLUGIN_URL . 'public/assets/css/modal.css', array(), CM_HPFW_PLUGIN_VER, 'all' );
					add_action('wp_footer', [$this, 'get_hideprice_form_modal']);
					$output = sprintf('<a class="hp-button-default hideprice-modal-btn" href="%s">%s</a>','#', esc_html__( 'Login', 'hide-price-for-woocommerce' ) );
					break;
				default :
					$output = sprintf('<a class="" href="%s">%s</a>',wp_login_url(), esc_html__( 'Login', 'hide-price-for-woocommerce' ) );;
					break;
			}


			return $output;
			
		}
		function hide_price_show_custom_text($price, $product){
			
			$text = get_option('hpfwc-custom-text', esc_html__('Login to see price', 'hide-price-for-woocommerce'));
			$method = get_option('hpfwc-form-type', 'form-modal');

			switch($method){
				case 'custom-url':
					$redirect_url = get_option('custom-redirect-url', wp_login_url());

					$output = sprintf('<a href="%s">%s</a>',esc_url($redirect_url),$text);
					break;
				case 'form-modal' :
					wp_enqueue_style( CM_HPFW_PLUGIN_NAME.'-modal-style', CM_HPFW_PLUGIN_URL . 'public/assets/css/modal.css', array(), CM_HPFW_PLUGIN_VER, 'all' );
					add_action('wp_footer', [$this, 'get_hideprice_form_modal']);
					$output = sprintf('<a class="hideprice-modal-btn" href="%s">%s</a>','#',$text);
					break;
				default :
					$output = sprintf('<a class="" href="%s">%s</a>',wp_login_url(),$text);;
					break;
			}
			return $output;
		}
		
		function get_hideprice_form_modal(){
			include 'partials/hide-price-for-wc-login-form.php';
		}


		/**
		 * undocumented function summary
		 *
		 * Undocumented function long description
		 *
		 * @param Type $var Description
		 * @return type
		 * @throws conditon
		 **/
		public function do_login_via_ajax()
		{
			if( !isset( $_POST['security'] ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ) , 'hidepriceforwoocommerce-security') ) {
				wp_send_json_error(['msg'=> __( 'Nonce Failure' , 'hide-price-for-woocommerce')], 403);
			}
			if( isset($_POST['data']) ){
				parse_str(sanitize_text_field(wp_unslash($_POST['data'])), $data);
			}
			$user = wp_signon(array(
				'user_login' => isset( $data['log'] ) ? sanitize_text_field($data['log']) : '',
				'user_password' => isset( $data['pwd'] ) ? sanitize_text_field($data['pwd']) : '',
				'remember' => true
			), false);
			
			if (is_wp_error($user)) {
				wp_send_json_error(['msg'=>$user->get_error_message()], 200);
			} else {
				wp_send_json_success(['msg'=>'Login successfully'], 200);
			}
		}
	}

}
