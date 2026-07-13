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
final class AdminNoticesHook implements HookContract {
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
            'admin_notices',
            function () {
                $screen = get_current_screen();
                if ( ! $screen || strpos( $screen->id, 'rtahina-salesforce-connector' ) === false ) {
                    return;
                }

                $user_id = get_current_user_id();

                $errors = get_transient( 'rtsc_sf_config_form_errors_' . $user_id );
                if ( $errors && is_array( $errors ) ) {
                    foreach ( $errors as $error ) {
                        printf(
                            '<div class="notice notice-error is-dismissible"><p>%s</p></div>',
                            esc_html( $error )
                        );
                    }
                    delete_transient( 'rtsc_sf_config_form_errors_' . $user_id );
                }

                if ( get_transient( 'rtsc_sf_config_form_success_' . $user_id ) ) {
                    echo '<div class="notice notice-success is-dismissible"><p>' .
                    esc_html__( 'Settings saved successfully.', 'rtahina-salesforce-connector' ) .
                    '</p></div>';
                    delete_transient( 'rtsc_sf_config_form_success_' . $user_id );
                }
            }
        );
    }
}
