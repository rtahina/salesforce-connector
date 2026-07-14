<?php
/**
 * Hook class File
 *
 * @package RTahina_SalesForce_Connector
 */

namespace RTahina\SalesforceConnector\Hooks;

use RTahina\SalesforceConnector\Contracts\HookContract;
use RTahina\SalesforceConnector\Services\SalesForce;

/**
 * Hook class
 *
 * @since 1.0.0
 */
final class SalesForceCallbackHook implements HookContract {
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
        // 1. Register the rewrite rule for the SalesForce callback page
        add_action(
            'init',
            function () {
                add_rewrite_rule(
                    '^rtsc-salesforce-callback/?$',
                    'index.php?rtsc_salesforce_callback=1',
                    'top'
                );
            }
        );

        add_filter(
            'query_vars',
            function ( $vars ) {
                $vars[] = 'rtsc_salesforce_callback';
                return $vars;
            }
        );

        add_action(
            'template_redirect',
            function () {
                if ( get_query_var( 'rtsc_salesforce_callback' ) ) {
                    // Parameters from SalesForce upon authoriation code request.
                    if ( isset( $_GET['code'] ) && isset( $_GET['state'] ) ) {
                        $rtsc_salesforce = new SalesForce();
                        $rtsc_code       = sanitize_text_field( wp_unslash( $_GET['code'] ) );
                        $rtsc_state      = sanitize_text_field( wp_unslash( $_GET['state'] ) );

                        if ( RTSC_SALESFORCE_CALLBACK === $rtsc_state ) {
                            $rtsc_salesforce->get_token( $rtsc_code );
                        }
                    }
                    status_header( 200 );
                    nocache_headers();
                    wp_safe_redirect(
                        add_query_arg(
                            array(
                                'page' => 'rtahina-salesforce-connector',
                                'tab'  => 'connect',
                            ),
                            admin_url( 'admin.php' )
                        )
                    );
                    exit;
                }
            }
        );
    }
}
