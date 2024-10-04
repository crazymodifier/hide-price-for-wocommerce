<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
/**
 * undocumented class
 */
if ( !class_exists( 'Hide_Price_For_WC_Admin' ) ) {
    
    class Hide_Price_For_WC_Admin
    {
        /**
         * Register the stylesheets for the admin area.
         *
         * @since    1.0.0
         */
        public function enqueue_styles()
        {

            /**
             * This function is provided for demonstration purposes only.
             *
             * An instance of this class should be passed to the run() function
             * defined in Asdf_Loader as all of the hooks are defined
             * in that particular class.
             *
             * The Asdf_Loader will then create the relationship
             * between the defined hooks and the functions defined in this
             * class.
             */

            // wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/asdf-admin.css', array(), $this->version, 'all' );
            wp_enqueue_style( CM_HPFW_PLUGIN_NAME.'-style', plugin_dir_url( __FILE__ ) . 'assets/css/style.css', array(), CM_HPFW_PLUGIN_VER, 'all' );
        }

        /**
         * Register the JavaScript for the admin area.
         *
         * @since    1.0.0
         */
        public function enqueue_scripts()
        {

            /**
             * This function is provided for demonstration purposes only.
             *
             * An instance of this class should be passed to the run() function
             * defined in Asdf_Loader as all of the hooks are defined
             * in that particular class.
             *
             * The Asdf_Loader will then create the relationship
             * between the defined hooks and the functions defined in this
             * class.
             */

             wp_enqueue_script( CM_HPFW_PLUGIN_NAME.'-script', plugin_dir_url(__FILE__) . 'assets/js/script.js', array( 'jquery','jquery-blockui' ), CM_HPFW_PLUGIN_VER, false );

        }

        /**
         * Initialise woocommerce settings page
         * @name hideprice_extending_wc_settings
         * @author Crazy Modifier <plugins@crazymodifier.com> 
         * @link https://crazymodifier.com/
         * @param array $settings All settings of woocommerce
         * @return $settings
         **/

        public function hideprice_extending_wc_settings($settings)
        {
            if (!class_exists('Hide_Price_For_WC_Settings')) {
                include_once CM_HPFW_PLUGIN_DIR . 'admin/class-hide-price-for-wc-settings.php';
                $settings[] = new Hide_Price_For_WC_Settings();
            }
            return $settings;
        }
    }

}