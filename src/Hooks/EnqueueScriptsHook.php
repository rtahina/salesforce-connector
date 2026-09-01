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
final class EnqueueScriptsHook implements HookContract {
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
            'wp_enqueue_scripts',
            function () {
                wp_enqueue_script(
                    'rtsc-public-js',
                    RTSC_PLUGIN_URL . 'src/Assets/script.js',
                    array(),
                    filemtime( RTSC_PLUGIN_PATH . 'src/Assets/script.js' ),
                    true
                );

                wp_localize_script(
                    'rtsc-public-js',
                    'rtscplugin',
                    array(
                        'siteurl' => site_url(),
                        'ajaxurl' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce( 'rtsc-nonce' ),
                    )
                );
            }
        );
    }
}
