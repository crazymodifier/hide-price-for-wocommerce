<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
?>
<div class="hideprice-modal-wrap" id="hideprice-modal">
    <div class="hideprice-modal-content">
        <span class="hideprice-modal-close close-button"><span>&times;</span></span>
        <div class="hideprice-modal-header">
            <div class="hideprice-modal-tab-nav">
                <h3>Login</h3>
            </div>
        </div>
        <div class="hideprice-modal-body">
            <div id="hideprice-login-wrapper" class="hideprice-modal-tab-content-item">
                <?php 
                $args = [
                    'form_id' => 'hideprice-login-form',
                    'required_username' => true,
                    'required_password' => true,
                    'echo'            => true,
                ];
                wp_login_form($args);                       
                ?>
                <div class="hideprice-login-subtext">
                    <?php
                    echo sprintf('<span><a href="%s">%s</a></span>', esc_url(wp_lostpassword_url()), esc_html__('Lost your password?','hide-price-for-woocommerce'));
                    echo sprintf('<span>%s %s %s</span>',esc_html__('Don\'t have account', 'hide-price-for-woocommerce'), wp_register('','',false),esc_html__('now' , 'hide-price-for-woocommerce'));
                    ?>
                </div>
            </div>
        </div>
        <div class="hideprice-modal-footer">

        </div>
    </div>
</div>