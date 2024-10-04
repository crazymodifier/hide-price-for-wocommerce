<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
/**
 * undocumented class
 */
class Hide_Price_For_WC_Settings extends WC_Settings_Page
{
	/**
	 * Construct Class
	 * Extend the woocommerce settings
	 * @name __construct
	 * @author Crazy Modifier <plugins@crazymodifier.com> 
	 * @link https://crazymodifier.com/
	 **/
	public function __construct()
	{
		$this->id    = 'crazymodifier-hide-price';
		$this->label = __('Hide Price', 'hide-price-for-woocommerce');

		// add tab
		add_filter('woocommerce_settings_tabs_array', array($this, 'add_settings_page'), 20);

		// show settings
		add_action('woocommerce_settings_' . $this->id, array($this, 'output'));

		// show sections
		add_action('woocommerce_sections_' . $this->id, array($this, 'output_sections'));

		// save settings
		add_action('woocommerce_settings_save_' . $this->id, array($this, 'save'));
	}

	/**
	 * Get Section of woocommerce setting page
	 * @name get_sections
	 * @author Crazy Modifier <plugins@crazymodifier.com> 
	 * @link https://crazymodifier.com/
	 * @return $sections
	 **/
	public function get_sections()
	{
		if (file_exists(ABSPATH . 'wp-admin/includes/user.php')) {
			include_once ABSPATH . 'wp-admin/includes/user.php';
		}
		$roles 			= get_editable_roles();

		$assigned_roles = array();

		foreach ($roles as $role_name => $role_info) {
			$initial_assigned_roles = array($role_name => $role_info['name']);
			$assigned_roles 		= array_merge($assigned_roles, $initial_assigned_roles);
		}
		$sections = array(
			'' 	=> __('General', 'hide-price-for-woocommerce'),
			'byrole' 	=> __('Hide Price By Roles', 'hide-price-for-woocommerce'),
			'messages' 	=> __('Messages', 'hide-price-for-woocommerce')
		);
		return apply_filters('woocommerce_get_sections_' . $this->id, $sections);
	}

	/**
	 * Get settings for hide price for woocommerce
	 * @name get_settings_for_messages_section
	 * @author Crazy Modifier <plugins@crazymodifier.com> 
	 * @link https://crazymodifier.com/
	 * @return Array $settings
	 **/

	function get_settings_for_messages_section()
	{
		global $current_section;
		
		$settings = array(
			// General Section
			'top-label' => array(
				'name'     => __('Messages', 'hide-price-for-woocommerce'),
				'type'     => 'title',
				'desc'		=> __('Enable the feature of hide price for woocommerce', 'hide-price-for-woocommerce'),
				'id'       => 'hide-price-messages-tab'
			)
		);
		return apply_filters('woocommerce_get_settings_' . $this->id, $settings, $current_section);
	}

	/**
	 * Get settings for hideprice by role
	 * @name get_settings_for_byrole_section
	 * @author Crazy Modifier <plugins@crazymodifier.com> 
	 * @link https://crazymodifier.com/
	 * @return $settings
	 **/

	function get_settings_for_byrole_section()
	{
		global $current_section;
		if (file_exists(ABSPATH . 'wp-admin/includes/user.php')) {
			include_once ABSPATH . 'wp-admin/includes/user.php';
		}
		$roles 			= get_editable_roles();

		$assigned_roles = array();

		foreach ($roles as $role_name => $role_info) {
			$initial_assigned_roles = array($role_name => $role_info['name']);
			$assigned_roles 		= array_merge($assigned_roles, $initial_assigned_roles);
		}
		$settings = array(
			// General Section
			'top-label' => array(
				'name'     => __('Hide Price By Role', 'hide-price-for-woocommerce'),
				'type'     => 'title',
				'desc'		=> __('Enable the feature of hide price ', 'hide-price-for-woocommerce'),
				'id'       => 'hide-price-byrole-tab'
			),
			'status-hide-price-by-role' => array(
				'name'     => __('Enable Hide Price By Role', 'hide-price-for-woocommerce'),
				'type'     => 'checkbox',
				'desc'		=> __('Enable the feature of hide price by role', 'hide-price-for-woocommerce'),
				'id'		=> 'status-hide-price-by-role',
				'desc_tip'	=> __('Price will be hidden according to the Role of User', 'hide-price-for-woocommerce'),
			),
			'roles' => array(
				'title' 		=>	 __('Select User Roles', 'hide-price-for-woocommerce'),
				'type' 			=> 	'multiselect',
				'id'			=>	'hide-price-by-role',
				'class' 		=> 	'wc-enhanced-select',
				'options' 		=> 	$assigned_roles,
				'desc_tip' 		=>  true,
				'custom_attributes' => [
					'data-placeholder' => __('Select User Roles', 'hide-price-for-woocommerce'),
				],
				'description' 	=> 	__('Select user roles to show the price', 'hide-price-for-woocommerce'),
			),
			array(
				'type' 	=> 'sectionend',
				'id' 	=> 'hide-price-byrole-form-group',
			),
		);
		return apply_filters('woocommerce_get_settings_' . $this->id, $settings, $current_section);
	}

	/**
	 * Get settings for hideprice until login
	 * @name get_settings_for_untillogin_section
	 * @author Crazy Modifier <plugins@crazymodifier.com> 
	 * @link https://crazymodifier.com/
	 * @return $settings
	 **/
	function get_settings_for_untillogin_section()
	{
		global $current_section;
		$settings = array(
			// General Section
			'top-label' => array(
				'name'     => __('Hide Price Until Login', 'hide-price-for-woocommerce'),
				'type'     => 'title',
				'desc'		=> __('Enable the feature of hide price ', 'hide-price-for-woocommerce'),
				'id'       => 'hide-price-untillogin-tab'
			),
			'status-hide-price-until-login' => array(
				'name'     => __('Enable Hide Price Until Login', 'hide-price-for-woocommerce'),
				'type'     => 'checkbox',
				'desc'		=> __('Enable the feature of hide price until login', 'hide-price-for-woocommerce'),
				'id'		=> 'status-hide-price-until-login',
				'desc_tip'	=> __('Price will be hidden until login', 'hide-price-for-woocommerce'),
			),
			'login-button'	=> array(
				'title'	=> __('Select Button type', 'hide-price-for-woocommerce'),
				'desc'	=> __('This setting for your desired registration/login method.', 'hide-price-for-woocommerce'),
				'id'	=> 'hide-price-login-button',
				'type'	=> 'select',
				'options' => array(
					'hideprice_login_button'	=> __("Login Button", 'hide-price-for-woocommerce'),
					'hideprice_custom_text'	=> __("Custom Text", 'hide-price-for-woocommerce'),
					'hideprice_login_diasble' => __("None", 'hide-price-for-woocommerce')
				),
				'desc_tip'        => true,
			),
			'custom-text-value'	=> array(
				'title'	=> __('Custom Text', 'hide-price-for-woocommerce'),
				'desc'	=> __('This setting for your desired text', 'hide-price-for-woocommerce'),
				'id'	=> 'hide-price-login-custom-text',
				'class' => 'hide conditional-feild',
				'type'	=> 'text',
				'row_class' => 'login-types',
				'default' => __('Login to see price', 'hide-price-for-woocommerce'),
			),
			'form-type'	=> array(
				'title'	=> __('Select Method', 'hide-price-for-woocommerce'),
				'desc'	=> __('This setting for your desired registration/login method.', 'hide-price-for-woocommerce'),
				'id'	=> 'hide-price-form-type',
				'type'	=> 'select',
				'options' => array(
					'hideprice_form_modal'	=> __("Popup Login Form (Plugin's Default)", 'hide-price-for-woocommerce'),
					'hideprice_custom_url'	=> __("Redirect to URL", 'hide-price-for-woocommerce'),
					'hideprice_form_diasble' => __("Default", 'hide-price-for-woocommerce')
				),
				'desc_tip'        => true,
			),
			'custom-redirect-url'	=> array(
				'title'	=> __('Custom Redirect URL', 'hide-price-for-woocommerce'),
				'desc'	=> __('This setting for your desired redirect url', 'hide-price-for-woocommerce'),
				'id'	=> 'custom-redirect-url',
				'class' => 'hide conditional-feild',
				'type'	=> 'text',
				'row_class' => 'login-methods'
			),
			array(
				'type' 	=> 'sectionend',
				'id' 	=> 'hide-price-untillogin-form-group',
			),
		);
		return apply_filters('woocommerce_get_settings_' . $this->id, $settings, $current_section);
	}

	/**
	 * Get general settings for hideprice 
	 * @name get_settings_for_default_section
	 * @author Crazy Modifier <plugins@crazymodifier.com> 
	 * @link https://crazymodifier.com/
	 * @return $settings
	 **/
	function get_settings_for_default_section()
	{
		global $current_section;
		$settings = array(
			// General Section
			'top-label' => array(
				'name'     => __('General Settings', 'hide-price-for-woocommerce'),
				'type'     => 'title',
				'desc'		=> __('Enable the feature of Hide Price for WooCommerce', 'hide-price-for-woocommerce'),
				'id'       => 'hpfwc-general-tab'
			),
			'status-hpfwc' => array(
				'name'     => __('Enable Hide Price', 'hide-price-for-woocommerce'),
				'type'     => 'checkbox',
				'desc'		=> __('Enable the feature of hide price ', 'hide-price-for-woocommerce'),
				'id'		=> 'status-hpfwc',
				'desc_tip'	=> __('Enable the feature of hide price', 'hide-price-for-woocommerce'),
			),
			array(
				'type' 	=> 'sectionend',
				'id' 	=> 'hpfwc-general-group',
			),
			array(
				'type'     => 'title',
				'id'       => 'hpfwc-display-tab'
			),
			'display-type-hpfwc'	=> array(
				'title'	=> __('Display Type', 'hide-price-for-woocommerce'),
				'desc'	=> __('Choose your display type to show customers.', 'hide-price-for-woocommerce'),
				'id'	=> 'hpfwc-display-type',
				'type'	=> 'select',
				'options' => array(
					'none' => __("None", 'hide-price-for-woocommerce'),
					'button'	=> __("Login Button (Plugin's Default)", 'hide-price-for-woocommerce'),
					'custom-text'	=> __("Custom Text", 'hide-price-for-woocommerce'),
					'info-notice' => __("Info Notice", 'hide-price-for-woocommerce')
				),
				'desc_tip'        => true,
				'default' => 'button',
				'row_class' => 'availability'
			),
			'custom-text-hpfwc'	=> array(
				'title'	=> __('Custom Text', 'hide-price-for-woocommerce'),
				'desc'	=> __('This setting for your desired text at the place of price', 'hide-price-for-woocommerce'),
				'id'	=> 'hpfwc-custom-text',
				'class' => 'conditional-field',
				'type'	=> 'text',
				'default' => 'Login to see price'
			),
			array(
				'type' 	=> 'sectionend',
				'id' 	=> 'hpfwc-display-group',
			),
			array(
				'type'     => 'title',
				'id'       => 'hpfwc-form-tab'
			),
			'form-type-hpfwc'	=> array(
				'title'	=> __('Register/Login Method', 'hide-price-for-woocommerce'),
				'desc'	=> __('This setting for your desired registration/login method.', 'hide-price-for-woocommerce'),
				'id'	=> 'hpfwc-form-type',
				'type'	=> 'select',
				'options' => array(
					'form-modal'	=> __("Popup Login Form (Plugin's Default)", 'hide-price-for-woocommerce'),
					'custom-url'	=> __("Redirect to URL", 'hide-price-for-woocommerce'),
					'diasble' => __("WordPress Default", 'hide-price-for-woocommerce')
				),
				'desc_tip'        => true,
				'default' => 'form-modal',
				'row_class' => 'availability'
			),
			'custom-url-hpfwc'	=> array(
				'title'	=> __('Custom Redirect URL', 'hide-price-for-woocommerce'),
				'desc'	=> __('This setting for your desired redirect url', 'hide-price-for-woocommerce'),
				'id'	=> 'hpfwc-custom-url',
				'class' => 'conditional-field',
				'type'	=> 'text',
				'default' => site_url('my-account')
			),
			array(
				'type' 	=> 'sectionend',
				'id' 	=> 'hpfwc-form-group',
			),
		);
		return apply_filters('woocommerce_get_settings_' . $this->id, $settings, $current_section);
	}

	/**
	 * Display all the output fields
	 * @name output
	 * @author Crazy Modifier <plugins@crazymodifier.com> 
	 * @link https://crazymodifier.com/
	 * @param string $current_section Current section ID
	 **/
	public function output($current_section = '')
	{
		global $current_section;

		$settings = $this->get_settings_for_section($current_section);
		WC_Admin_Settings::output_fields($settings);
	}

	/**
	 * Save settings
	 * @name save
	 * @author Crazy Modifier <plugins@crazymodifier.com> 
	 * @link https://crazymodifier.com/
	 **/
	public function save()
	{
		global $current_section;
		$settings = $this->get_settings_for_section($current_section);
		WC_Admin_Settings::save_fields($settings);
	}
}
