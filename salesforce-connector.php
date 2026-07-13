<?php
/**
 * Plugin Name:     SalesForce Connector
 * Plugin URI:      https://tahina.dev/
 * Description:     The SalesForce Connector plugin allows you to connect your SalesForce instance to your WordPress website.
 * Author:          Tahina R.
 * Author URI:      https://tahina.dev/
 * Text Domain:     rtahina-salesforce-connector
 * Domain Path:     /languages
 * Version:         0.1.0
 * Requires PHP:    8.0 or higher
 *
 * @category WordPress_Plugin
 * @package  RTahina_SalesForce_Connector
 * @author   Tahina R
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @version  0.1.0
 * @link     https://tahina.dev/
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use RTahina\SalesforceConnector\SalesForceConnector;

define( 'RTSC_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'RTSC_TEXT_DOMAIN', 'rtahina-salesforce-connector' );
define( 'RTSC_NONCE_ACTION', 'rtsc_admin_action' );
define( 'RTSC_NONCE_NAME', 'rtsc_admin_nonce' );
define( 'RTSC_SALESFORCE_CALLBACK', get_site_url() . '/rtsc-salesforce-callback/' );
define( 'RTSC_SALESFORCE_ADMIN_PAGE', admin_url() . 'admin.php?page=rtahina-salesforce-connector' );

// Autoload file.
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    require_once __DIR__ . '/vendor/autoload.php';
}

/** Bootstrap the plugin. */
function rtsc_bootstrap() {
    $app = SalesForceConnector::get_instance();
    $app->run();
}

rtsc_bootstrap();

register_activation_hook(
    __FILE__,
    function () {
        flush_rewrite_rules( false );
        delete_option( 'rewrite_rules' );
    }
);

register_deactivation_hook(
    __FILE__,
    function () {
        flush_rewrite_rules( false );
        delete_option( 'rewrite_rules' );
    }
);
