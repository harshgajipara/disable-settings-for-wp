<?php
/*
 * Plugin Name:       Disable Settings For WP
 * Plugin URI:        https://wordpress.org/plugins/disable-settings-for-wp/
 * Description:       A plugin for disabling right-click and Hide admin bar menu.
 * Version:           1.0.0
 * Author:            Harsh Gajipara
 * Author URI:        https://harshgajipara.github.io/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       disable-settings-for-wp
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
Add a new submenu under the "Settings" menu
*/
function disable_settings_add_submenu() {
    add_submenu_page(
        'options-general.php',   // Parent menu slug
        'Disable Settings',   // Page title
        'Disable Settings',   // Menu title
        'manage_options',        // Capability required to access the page
        'disable-settings',    // Page slug
        'disable_settings_render_settings' // Callback function to render the settings page
    );
}
add_action('admin_menu', 'disable_settings_add_submenu');

/*
Register plugin settings
*/
function disable_settings_register_settings() {
    register_setting('disable-right-click-settings-group', 'disable_right_click_enabled');
    register_setting('disable-right-click-settings-group', 'show_topbar_panel');
}
add_action('admin_init', 'disable_settings_register_settings');

/*
Render the settings page
*/
function disable_settings_render_settings() {
    ?>
    <div class="wrap">
        <h1>Disable Right Click Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('disable-right-click-settings-group'); ?>
            <?php do_settings_sections('disable-right-click-settings-group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Enable Right Click Disabling</th>
                    <td>
                        <label for="disable_right_click_enabled">
                            <input type="checkbox" id="disable_right_click_enabled" name="disable_right_click_enabled" value="1" <?php checked(get_option('disable_right_click_enabled'), 1); ?>>
                            Enable
                        </label>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Hide Topbar Panel</th>
                    <td>
                        <label for="show_topbar_panel">
                            <input type="checkbox" id="show_topbar_panel" name="show_topbar_panel" value="1" <?php checked(get_option('show_topbar_panel'), 1); ?>>
                            Hide
                        </label>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

/*
Add the right-click disabling script if the option is enabled
*/
function disable_right_click_script() {
    if (get_option('disable_right_click_enabled') == 1) {
         echo '<script>
            document.addEventListener("contextmenu", function(e) {
                e.preventDefault();
            });

            document.addEventListener("keydown", function(e) {
                if (e.keyCode == 123) {
                    e.preventDefault();
                }
            });
        </script>';
    }
}
add_action('wp_footer', 'disable_right_click_script');

/*
Hide the admin bar
*/
if (get_option('show_topbar_panel') == 1) {
    add_filter('show_admin_bar', '__return_false');
}