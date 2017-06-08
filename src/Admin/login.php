<?php

namespace Vendi\YubiKey\Admin;

use Vendi\Shared\utils;
use Yubikey\Validate;
use Vendi\YubiKey\yubikey_utils;
use Vendi\YubiKey\Admin\{options, user_profile};

class login
{
    private static $_instance = array();

    public static function get_instance( )
    {
        if( ! self::$_instance )
        {
            self::$_instance = new self( );
        }
        return self::$_instance;
    }

    public function register_hooks()
    {
        if( ! yubikey_utils::are_yubio_settings_registered() )
        {
            return;
        }

        if( ! yubikey_utils::is_system_sane() )
        {
            return;
        }

        add_action( 'login_form',           array( $this, 'show_login_form' ) );
        add_filter( 'wp_authenticate_user', array( $this, 'authenticate' ) );
    }

    public function show_login_form()
    {
        $label_for_field = __( 'Leave this field blank if a system administrator did not assign you a physical YubiKey.', 'vendi-yubikey' );

        echo '<p>
                <label>' . esc_html( $label_for_field ) . '<br />
                    <input type="text" name="otp" id="otp" class="input" size="20" autocomplete="no" />
                </label>
            </p>';
    }

    public function authenticate( $user )
    {
        if( is_wp_error( $user ) )
        {
            return $user;
        }

        $client_id  = get_option( options::$field_name_client_id,  false );
        $secret_key = get_option( options::$field_name_secret_key, false );

        // Get user specific settings
        $enabled       = get_user_option( user_profile::$field_name_yubikey_enable, $user->ID );
        $yubikey_id_1  = get_user_option( user_profile::$field_name_yubikey_id_1,   $user->ID );

        $otp = utils::get_post_value( 'otp' );

        if( ! $enabled )
        {
            return $user;
        }

        if( ! $otp )
        {
            return new \WP_Error( 'vendi-yubikey-missing-otp', __( 'The OTP value is required for this user.', 'vendi-yubikey' ) );
        }

        $client = new Validate( $secret_key, $client_id );

        $response = false;
        try{
            $response = $client->check( $otp )->success();
        }
        catch( \Exception $e )
        {
            if( $e instanceOf \InvalidArgumentException )
            {
                return new \WP_Error( 'vendi-yubikey-unknown-error', __( 'An error occurred while logging in: ' . esc_html( $e->getMessage() ), 'vendi-yubikey' ), $e );
            }

            return new \WP_Error( 'vendi-yubikey-unknown-error', __( 'An unknown error occurred while logging in.', 'vendi-yubikey' ), $e );

        }

        if( $response )
        {
            return $user;
        }

        return new \WP_Error( 'vendi-yubikey-invalid-otp', __( 'In correct value for OTP.', 'vendi-yubikey' ), $e );

    }

}
