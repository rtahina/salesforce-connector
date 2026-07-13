<?php
/**
 * Hook class File
 *
 * @package RTahina_SalesForce_Connector
 */

namespace RTahina\SalesforceConnector\Hooks;

use RTahina\SalesforceConnector\Contracts\HookContract;

/**
 * Hook class
 *
 * @since 1.0.0
 */
final class AssetsHook implements HookContract {
    // phpcs:disable Squiz.Commenting.FunctionComment.Missing
    private function __construct() {
        // phpcs:enable
    }
    /**
     * The action function.
     *
     * Fires hook
     *
     * @return void
     */
    public static function action(): void {
        add_action(
            'admin_enqueue_scripts',
            function ( $hook ) {
                if ( 'tools_page_rtahina-salesforce-connector' === $hook ) {
                    wp_enqueue_style(
                        'rtahina-salesforce-connector-admin-css',
                        RTSC_PLUGIN_URL . 'src/Admin/style.css',
                        array(),
                        filemtime( RTSC_PLUGIN_PATH . 'src/Admin/style.css' )
                    );

                    wp_enqueue_script(
                        'rtahina-salesforce-connector-admin-js',
                        RTSC_PLUGIN_URL . 'src/Admin/script.js',
                        array( 'jquery' ),
                        filemtime( RTSC_PLUGIN_PATH . 'src/Admin/script.js' ),
                        true
                    );
                }
            }
        );
    }
}
