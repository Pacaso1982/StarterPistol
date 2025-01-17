<?php
add_action('admin_menu', 'wp_cosmick_alerts_options');

function wp_cosmick_alerts_options() {

    add_options_page('Control WP alerts Settings', 'Control WP alerts', 'manage_options', 'cosmick-wp-alerts', 'wp_cosmick_alerts_options_page');
    add_action('admin_init', 'register_wp_cosmick_alerts_options_settings');
}

function register_wp_cosmick_alerts_options_settings() {
    register_setting('cosmick_alerts_options_settings', 'cosmick_alerts_options', 'validate_checkbox');
}

function wp_cosmick_alerts_options_page() {
        
    $cosmick_alerts_options = get_option('cosmick_alerts_options', get_default_cosmick_alerts_options() );
    
    if(empty($cosmick_alerts_options)){
        $cosmick_alerts_options = get_default_cosmick_alerts_options();
    }
    
    ?>
    <div class="wrap">
        <h1>Control WP alerts Settings by Cosmick</h1>

        <form method="post" action="options.php">
            <?php settings_fields('cosmick_alerts_options_settings'); ?>
            <?php do_settings_sections('cosmick_alerts_options_settings'); ?>
            <table class="form-table">
                 <tr valign="top">
                    <th scope="row">Disable WP core update</th>
                    <td><input type="checkbox" <?php checked(1, $cosmick_alerts_options['wpcore']); ?> name="cosmick_alerts_options[wpcore]" value="1" /></td>
                </tr>                
                <tr valign="top">
                    <th scope="row">Disable Theme Update</th>
                    <td><input type="checkbox" <?php checked(1, $cosmick_alerts_options['theme']); ?> name="cosmick_alerts_options[theme]" value="1" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Disable Plugin Update</th>
                    <td><input type="checkbox" <?php checked(1, $cosmick_alerts_options['plugin']); ?> name="cosmick_alerts_options[plugin]" value="1" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Disable pingbacks</th>
                    <td><input type="checkbox" <?php checked(1, $cosmick_alerts_options['xmlrpc']); ?> name="cosmick_alerts_options[xmlrpc]" value="1" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Disable Debug Email</th>
                    <td><input type="checkbox" <?php checked(1, $cosmick_alerts_options['wpdebug']); ?> name="cosmick_alerts_options[wpdebug]" value="1" /></td>
                </tr>               
            </table>    
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function get_default_cosmick_alerts_options(){
    return  array(
                'wpcore'    => 1,
                'theme'     => 1,
                'plugin'    => 1,
                'xmlrpc'    => 1,
                'wpdebug'   => 1
            );
}

function validate_checkbox( $options ){
    $array = array_fill_keys(array_keys( get_default_cosmick_alerts_options() ), 0);
    $options_final = wp_parse_args($options, $array);
    
    return $options_final;
}