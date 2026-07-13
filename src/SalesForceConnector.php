<?php
/**
 * SalesForce Connector File
 *
 * @package RTahina_SalesForce_Connector
 */

namespace RTahina\SalesforceConnector;

use RTahina\SalesforceConnector\Hooks\AdminMenuHooks;
use RTahina\SalesforceConnector\Hooks\AdminNoticesHook;
use RTahina\SalesforceConnector\Hooks\AssetsHook;
use RTahina\SalesforceConnector\Hooks\SalesForceCallbackHook;
use RTahina\SalesforceConnector\Hooks\SaveSalesForceConfigHook;
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
        AdminMenuHooks::action();
        AssetsHook::action();
        SaveSalesForceConfigHook::action();
        AdminNoticesHook::action();
        SalesForceCallbackHook::action();
    }
}
