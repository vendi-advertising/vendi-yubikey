<?php

namespace Vendi\YubiKey;

use Vendi\YubiKey\Admin\options;

class yubikey_utils
{
    private function __construct()
    {

    }

    public static function is_system_sane()
    {
        if( ! function_exists('curl_init') )
        {
            return false;
        }

        if( ! function_exists( 'hash_hmac' ) )
        {
            return false;
        }

        return true;
    }

    public static function are_yubio_settings_registered()
    {
        if( ! get_option( options::$field_name_client_id, false ) )
        {
            return false;
        }

        if( ! get_option( options::$field_name_secret_key, false ) )
        {
            return false;
        }

        return true;
    }
}
