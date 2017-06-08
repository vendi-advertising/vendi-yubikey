<?php

use Vendi\YubiKey\Admin\{login, options, user_profile};

add_action(
            'admin_menu',
            function()
            {
                add_options_page( 'Vendi YubiKey', 'Vendi YubiKey', 'manage_options', options::$page_name, array( 'Vendi\YubiKey\Admin\options', 'show_menu' ) );
            }
        );

login::get_instance()->register_hooks();
user_profile::get_instance()->register_hooks();
