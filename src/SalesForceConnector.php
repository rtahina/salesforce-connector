<?php
/**
 * SalesForce Connector File
 *
 * @package RTahina_SalesForce_Connector
 */

namespace RTahina\SalesforceConnector;

use RTahina\SalesforceConnector\DTOs\SalesForceConfig;
use RTahina\SalesforceConnector\Services\SalesForce;

/**
 * SalesForceConnector class
 *
 * All the class's properties and methods
 *
 * @since 1.0.0
 */
class SalesForceConnector {
    /** @var self $instance  */
    private static ?SalesForceConnector $instance = null;

    // phpcs:disable Squiz.Commenting.FunctionComment.Missing
    private function __construct() {
        // phpcs:enable
    }

    // phpcs:disable Squiz.Commenting.FunctionComment.Missing
    private function __clone() {
        // phpcs:enable
    }

    // phpcs:disable Squiz.Commenting.FunctionComment.Missing
    public function __wakeup() {
        // phpcs:enable
        throw new \Exception( 'Cannot unserialize a singleton.' );
    }

    /**
     * The get_instance function.
     *
     * Return an instance of SalesForceConnector
     *
     * @return SalesForceConnector
     */
    public static function get_instance(): SalesForceConnector {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * The run function.
     *
     * Runs: hooks, admin pages, text domain...
     *
     * @return void
     */
    public function run(): void {
        $this->load_language( RTSC_TEXT_DOMAIN );
        $this->hooks();
    }

    /**
     * Loads translation file.
     *
     * Accessible to other classes to load different language files (admin and
     * front-end for example).
     *
     * @param string $domain The plugin text domain
     * @return  void
     */
    public function load_language( string $domain ): void {
        load_plugin_textdomain(
            $domain,
            false,
            RTSC_PLUGIN_PATH . 'languages'
        );
    }

    /**
     * The hooks function.
     *
     * Fires hooks
     *
     * @return void
     */
    protected function hooks(): void {
        // 1. Register a submenu page.
        add_action(
            'admin_menu',
            function () {
                add_submenu_page(
                    'tools.php',
                    'SalesForce Connector',
                    'SalesForce connection',
                    'manage_options',
                    'rtahina-salesforce-connector',
                    array( $this, 'admin_page' )
                );
            }
        );

        // Enqueue admin styles.
        add_action(
            'admin_enqueue_scripts',
            function ( $hook ) {
                if ( 'tools_page_rtahina-salesforce-connector' === $hook ) {
                    wp_enqueue_style(
                        'rtahina-salesforce-connector-admin-css',
                        plugin_dir_url( __FILE__ ) . 'Admin/style.css',
                        array(),
                        filemtime( plugin_dir_path( __FILE__ ) . 'Admin/style.css' )
                    );

                    wp_enqueue_script(
                        'rtahina-salesforce-connector-admin-js',
                        plugin_dir_url( __FILE__ ) . 'Admin/script.js',
                        array( 'jquery' ),
                        filemtime( plugin_dir_path( __FILE__ ) . 'Admin/script.js' ),
                        true
                    );
                }
            }
        );

        // Save SalesForce key action.
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

        // Display admin notices
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

        $template = plugin_dir_path( __FILE__ ) . 'Admin/settings-page.php';

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
