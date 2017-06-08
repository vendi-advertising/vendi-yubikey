<?php
/*
Plugin Name: Vendi Yubikey
Description: YubiKey authentication (hardware-base two-factor/multi-factor authentication) for Wordpress.
Plugin URI: https://www.vendiadvertising.com/
Author: Vendi Advertising (Chris Haas)
Version: 1.0.0
Text Domain: vendi-yubikey
Domain Path: /languages
*/

define( 'VENDI_YUBIKEY_FILE', __FILE__ );
define( 'VENDI_YUBIKEY_DIR',  dirname( __FILE__ ) );
define( 'VENDI_YUBIKEY_URL',  plugin_dir_url( __FILE__ ) );

require_once VENDI_YUBIKEY_DIR . '/includes/autoload.php';

require_once VENDI_YUBIKEY_DIR . '/includes/hooks.php';
