<?php

use Vendi\YubiKey\Admin\user_profile;

global $user_id;

//TODO: perms check here
if( ! defined( 'ABSPATH' ) || ! is_admin() || ! current_user_can( 'edit_user', $user_id ) )
{
    die( __( 'You do not have permission to perform this action', 'vendi-yubikey' ) );
}

$enabled = user_profile::get_post_or_user_option_or_default_by_user_id( $user_id, user_profile::$field_name_yubikey_enable, user_profile::$field_value_no );

?>
<h2><?php _e( 'Vendi YubiKey Personal Options', 'vendi-yubikey' );?></h2>

<table class="form-table">
    <tbody>
        <tr>
            <th scope="row" style="vertical-align: top;"><?php _e( 'Enable YubiKey authentication?', 'vendi-yubikey' ); ?></th>
            <td>
                <label for="<?php echo user_profile::$field_name_yubikey_enable . '_' . user_profile::$field_value_no; ?>">
                <input
                    type="radio"
                    name="<?php echo user_profile::$field_name_yubikey_enable . '_post'; ?>"
                    id="<?php echo user_profile::$field_name_yubikey_enable . '_' . user_profile::$field_value_no; ?>"
                    value="no"
                    <?php
                    if( $enabled === user_profile::$field_value_no )
                    {
                        echo 'checked="checked"';
                    }
                    ?>
                    />
                    <span><?php _e( 'No', 'vendi-yubikey' ); ?></span>
                </label>

                <label for="<?php echo user_profile::$field_name_yubikey_enable . '_' . user_profile::$field_value_yes; ?>">
                <input
                    type="radio"
                    name="<?php echo user_profile::$field_name_yubikey_enable . '_post'; ?>"
                    id="<?php echo user_profile::$field_name_yubikey_enable . '_' . user_profile::$field_value_yes; ?>"
                    value="yes"
                    <?php
                    if( $enabled === user_profile::$field_value_yes )
                    {
                        echo 'checked="checked"';
                    }
                    ?>
                    />
                    <span><?php _e( 'Yes', 'vendi-yubikey' ); ?></span>
                </label>
            </td>
        </tr>

        <tr>
            <th scope="row" style="vertical-align: top;"><label for="<?php echo user_profile::$field_name_yubikey_id_1; ?>"><?php _e( 'Server key', 'vendi-yubikey' ); ?></label></th>
            <td>
                <input
                    type="text"
                    name="<?php echo user_profile::$field_name_yubikey_id_1 . '_post'; ?>"
                    id="<?php echo user_profile::$field_name_yubikey_id_1; ?>"
                    value="<?php echo esc_attr( user_profile::get_post_or_user_option_or_default_by_user_id( $user_id, user_profile::$field_name_yubikey_id_1 ) ); ?>"
                    class="code"
                    />
            </td>
        </tr>
    </tbody>
</table>
