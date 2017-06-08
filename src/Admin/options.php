<?php

namespace Vendi\YubiKey\Admin;

use Vendi\Shared\utils;

class options
{
    public static $nonce_action = 'set_vendi_yubikey_options';

    public static $nonce_field = 'vendi_yubikey_options_nonce';

    public static $field_name_client_id = 'vendi_yubikey_client_id';

    public static $field_name_secret_key = 'vendi_yubikey_secret_key';

    public static $page_name = 'vendi-yubikey';

    public static function show_menu()
    {
        include VENDI_YUBIKEY_DIR . '/admin/options.php';
    }

    public static function is_valid_base_64_string( $data )
    {
        if( base64_encode( base64_decode( $data, true ) ) === $data )
        {
            return true;
        }

        return false;
    }

    public static function get_post_or_option( $name )
    {
        if( utils::is_post() )
        {
            return utils::get_post_value( $name );
        }

        /**
         * get_option @since 1.5.0
         */
        return get_option( $name );
    }

    public static function maybe_handle_post()
    {
        if( ! utils::is_post() )
        {
            return;
        }

        $nonce = utils::get_post_value( self::$nonce_field, null );

        /**
         * wp_verify_nonce @since 2.0.4
         */
        if( ! $nonce ||  ! wp_verify_nonce( $nonce, self::$nonce_action ) )
        {
            /**
             * wp_die @since 2.0.4
             */
            wp_die( 'Failed security check' );
        }

        $client_id  = utils::get_post_value( self::$field_name_client_id );
        $secret_key = utils::get_post_value( self::$field_name_secret_key );

        if( ! utils::is_integer_like( $client_id ) )
        {
            /**
             * add_settings_error @since 3.0.0
             * wp_kses            @since 1.0.0
             * __                 @since 2.1.0
             */
            add_settings_error(
                                    options::$page_name,
                                    self::$field_name_secret_key,
                                    wp_kses(
                                            __( '<em>Settings not saved!</em> The Client ID field should be a number.', 'vendi-yubikey' ),
                                            array(
                                                    'em' => array(),
                                                )
                                        ),
                                    'error' //error or updated
                            );
            return false;
        }

        if( ! self::is_valid_base_64_string( $secret_key ) )
        {
            /**
             * add_settings_error @since 3.0.0
             * wp_kses            @since 1.0.0
             * __                 @since 2.1.0
             */
            add_settings_error(
                                    options::$page_name,
                                    self::$field_name_secret_key,
                                    wp_kses(
                                            __( '<em>Settings not saved!</em> The secret key does not appear to be a valid base64 encoded string.', 'vendi-yubikey' ),
                                            array(
                                                    'em' => array(),
                                                )
                                        ),
                                    'error' //error or updated
                            );
            return false;
        }

        /**
         * update_option @since 1.0.0
         */
        update_option( self::$field_name_client_id,  $client_id,  false );
        update_option( self::$field_name_secret_key, $secret_key, false );

        /**
         * add_settings_error @since 3.0.0
         */
        add_settings_error(
                                options::$page_name,
                                self::$page_name . '-ok',
                                __( 'Your settings have been saved.', 'vendi-yubikey' ),
                                'updated' //error or updated
                        );

    }
}
