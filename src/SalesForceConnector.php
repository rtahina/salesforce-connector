<?php
/**
 * SalesForce Connector File
 *
 * @package RTahina_SalesForce_Connector
 */

namespace RTahina\SalesforceConnector;

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
