<?php
/**
 * Hook class File
 *
 * @package RTahina_SalesForce_Connector
 */

namespace RTahina\SalesforceConnector\Hooks;

use RTahina\SalesforceConnector\Contracts\HookContract;
use RTahina\SalesforceConnector\Utilities;

/**
 * Hook class
 *
 * @since 1.0.0
 */
final class AdminMenuHooks implements HookContract {
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
            'admin_menu',
            function () {
                add_submenu_page(
                    'tools.php',
                    'SalesForce Connector',
                    'SalesForce connection',
                    'manage_options',
                    'rtahina-salesforce-connector',
                    function () {
                        // Verify if we are in the admin area and the current user has permission.
                        if ( is_admin() && ! Utilities::current_user_can( 'manage_options' ) ) {
                            wp_die( 'You do not have permission to access this page.' );
                        }

                        $template = RTSC_PLUGIN_PATH . 'src/Admin/settings-page.php';

                        if ( file_exists( $template ) ) {
                            include $template;
                        } else {
                            printf(
                                '<div class="notice notice-error"><p>%s <code>%s</code></p></div>',
                                esc_html( 'Admin template not found:' ),
                                esc_html( $template )
                            );
                        }
                    }
                );
            }
        );
    }

    /**
     * The admin_page function.
     *
     * Retrieve the admin page template
     *
     * @return void
     */
    public function admin_page(): void {
        // Verify if we are in the admin area and the current user has permission.
        if ( is_admin() && ! Utilities::current_user_can( 'manage_options' ) ) {
            wp_die( 'You do not have permission to access this page.' );
        }

        $template = RTSC_PLUGIN_PATH . 'src/Admin/settings-page.php';

        if ( file_exists( $template ) ) {
            include $template;
        } else {
            printf(
                '<div class="notice notice-error"><p>%s <code>%s</code></p></div>',
                esc_html( 'Admin template not found:' ),
                esc_html( $template )
            );
        }
    }
}
