<?php

use Vendi\YubiKey\Admin\options;

if( ! defined( 'ABSPATH' ) || ! is_admin() || ! current_user_can( 'manage_options' ) )
{
    die( __( 'You do not have permission to perform this action', 'vendi-yubikey' ) );
}

options::maybe_handle_post();
?>
<div class="wrap">

    <h1><?php _e( 'Vendi YubiKey Global Options', 'vendi-yubikey' );?></h1>

    <?php settings_errors(); ?>

    <p>
        <?php

        echo wp_kses(
                        __( 'You can get your Yubico Client ID and Secret Key from <a href="https://upgrade.yubico.com/getapikey/" target="_blank">Yubico&rsquo;s website.</a>', 'vendi-yubikey' ),
                        array(
                                'a' => array(
                                                'href' => array(),
                                                'target' => array(),
                                            ),
                            )
                );
        ?>
    </p>

    <form name="yubikey" method="post">

        <?php wp_nonce_field( options::$nonce_action, options::$nonce_field ); ?>

        <table class="form-table">

            <tr>
                <th scope="row"><label for="<?php echo options::$field_name_client_id ?>"><?php _e('Yubico Client ID','vendi-yubikey');?></label></th>
                <td>
                    <input
                        type="text"
                        class="code"
                        name="<?php echo options::$field_name_client_id ?>"
                        id="<?php echo options::$field_name_client_id ?>"
                        value="<?php echo esc_attr( options::get_post_or_option( options::$field_name_client_id ) ) ?>"
                        size="40"
                         />
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="<?php echo options::$field_name_secret_key ?>"><?php _e('Yubico Secret Key','vendi-yubikey');?></label></th>
                <td>
                    <input
                        type="text"
                        class="code"
                        name="<?php echo options::$field_name_secret_key ?>"
                        id="<?php echo options::$field_name_secret_key ?>"
                        value="<?php echo esc_attr( options::get_post_or_option( options::$field_name_secret_key ) ) ?>"
                        size="40"
                         />
                </td>
            </tr>

        </table>

        <p class="submit">

            <input type="submit" name="Submit" value="<?php _e('Save Changes', 'vendi-yubikey' ) ?>" />

        </p>
    </form>
</div>
