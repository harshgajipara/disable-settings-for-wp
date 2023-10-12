<?php
// If uninstall is not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete plugin options from the database
delete_option('disable_right_click_enabled');
delete_option('show_topbar_panel');
