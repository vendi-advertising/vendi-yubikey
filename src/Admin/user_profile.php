<?php

namespace Vendi\YubiKey\Admin;

use Vendi\Shared\utils;
use Vendi\YubiKey\yubikey_utils;
use Yubikey\Validate;

class user_profile
{
    private static $_instance = array();

    public static $field_name_yubikey_enable = 'vendi_yubikey_enable';

    public static $field_name_yubikey_id_1 = 'vendi_yubikey_id_1';

    public static $field_value_yes = 'yes';

    public static $field_value_no = 'no';

    // public static $field_name_secret_key = 'vendi_yubico_secret_key';

    public static $page_name = 'vendi-yubikey';

    public static function maybe_handle_post( $user_id )
    {
        if( ! utils::is_post() )
        {
            return;
        }

        $enable = utils::get_post_value( self::$field_name_yubikey_enable . '_post' );

        //Only the first twelve characters are the user's key
        $key_1  = substr( trim( utils::get_post_value( self::$field_name_yubikey_id_1   . '_post' ) ), 0, 12 );

        update_user_option( $user_id, self::$field_name_yubikey_enable, $enable  );
        update_user_option( $user_id, self::$field_name_yubikey_id_1,   $key_1  );
    }

    public static function get_instance( )
    {
        if( ! self::$_instance )
        {
            self::$_instance = new self( );
        }
        return self::$_instance;
    }

    public function get_post_or_user_option_or_default_by_user_id( $user_id, $name, $default = '' )
    {
        global $user_id;

        if( utils::is_post() )
        {
            return utils::get_post_value( $name );
        }

        /**
         * get_user_option @since 2.0.0
         */
        $ret = get_user_option( $name, $user_id );
        if( false !== $ret )
        {
            return $ret;
        }

        return $default;
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

        add_action( 'edit_user_profile', array( $this, 'show_user_profile_edit' ) );
        add_action( 'show_user_profile', array( $this, 'show_user_profile_edit' ) );

        add_action( 'edit_user_profile_update', array( $this, 'maybe_handle_post' ), 10, 1 );
        add_action( 'personal_options_update',  array( $this, 'maybe_handle_post' ), 10, 1 );

    }

    public function show_user_profile_edit()
    {
        include VENDI_YUBIKEY_DIR . '/admin/profile.php';
    }
}
