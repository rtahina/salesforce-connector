<?php
/**
 * Hook class File
 *
 * @package RTahina_SalesForce_Connector
 */

namespace RTahina\SalesforceConnector\Hooks;

use RTahina\SalesforceConnector\Contracts\HookContract;
use RTahina\SalesforceConnector\DTOs\SalesForceConfig;
use RTahina\SalesforceConnector\Services\SalesForce;

/**
 * Hook class
 *
 * @since 1.0.0
 */
final class SaveSalesForceConfigHook implements HookContract {
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
            'admin_init',
            function () {
                if (
                ! isset( $_POST['rtsc-sf-save-keys'] ) ||
                ! isset( $_GET['page'] ) || 'rtahina-salesforce-connector' !== $_GET['page']
                ) {
                    return;
                }

                check_admin_referer( 'rtsc_save-salesfoce-config', 'rtsc_save-salesfoce-config-nonce' );

                if ( ! current_user_can( 'manage_options' ) ) {
                    wp_die( __( 'Insufficient permissions.', 'rtahina-salesforce-connector' ) );
                }

                $errors          = array();
                $rtsc_sf_service = new SalesForce();

                $rtsc_client_id      = sanitize_text_field( wp_unslash( $_POST['rtsc-sf-client-id'] ) ) ?? '';
                $rtscclient_secret   = sanitize_text_field( wp_unslash( $_POST['rtsc-sf-client-secret'] ) ) ?? '';
                $rtsc_code_challenge = sanitize_text_field( wp_unslash( $_POST['rtsc-sf-code-challenge'] ) ) ?? '';
                $rtsc_code_verifier  = sanitize_text_field( wp_unslash( $_POST['rtsc-sf-code-verifier'] ) ) ?? '';

                if ( empty( $rtsc_client_id ) ) {
                    $errors['rtsc-sf-client-id'] = __( 'Client ID field is required.', 'rtahina-salesforce-connector' );
                }

                if ( empty( $rtscclient_secret ) ) {
                    $errors['rtsc-sf-client-secret'] = __( 'Client Secret field is required.', 'rtahina-salesforce-connector' );
                }

                if ( empty( $rtsc_code_challenge ) ) {
                    $errors['rtsc-sf-code-challenge'] = __( 'Code Challenge field is required.', 'rtahina-salesforce-connector' );
                }

                if ( empty( $rtsc_code_verifier ) ) {
                    $errors['rtsc-sf-code-verifier'] = __( 'Code Verifier field is required.', 'rtahina-salesforce-connector' );
                }

                if ( ! empty( $errors ) ) {
                    set_transient( 'rtsc_sf_config_form_errors_' . get_current_user_id(), $errors, 45 );
                    wp_safe_redirect(
                        add_query_arg(
                            array(
                                'page' => 'rtahina-salesforce-connector',
                                'tab'  => 'keys',
                            ),
                            admin_url( 'admin.php' )
                        )
                    );
                    exit;
                }

                // Valid — save data, then redirect with a success flag.
                $rtsc_config = new SalesForceConfig( $rtsc_client_id, $rtscclient_secret, $rtsc_code_challenge, $rtsc_code_verifier );
                $rtsc_sf_service->save_config( $rtsc_config );

                set_transient( 'rtsc_sf_config_form_success_' . get_current_user_id(), true, 45 );
                wp_safe_redirect(
                    add_query_arg(
                        array(
                            'page' => 'rtahina-salesforce-connector',
                            'tab'  => 'keys',
                        ),
                        admin_url( 'admin.php' )
                    )
                );

                exit;
            }
        );
    }
}
