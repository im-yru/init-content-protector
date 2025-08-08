<?php
/**
 * Uninstall Init Content Protector
 *
 * This file is executed when the plugin is deleted via WordPress admin.
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

// Option name used in settings-page.php
$option_name = defined( 'INIT_PLUGIN_SUITE_CONTENT_PROTECTOR_OPTION' )
    ? INIT_PLUGIN_SUITE_CONTENT_PROTECTOR_OPTION
    : 'init_content_protector_settings';

// Delete plugin option
delete_option( $option_name );
