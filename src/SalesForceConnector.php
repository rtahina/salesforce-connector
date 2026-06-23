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
    private function __wakeup() {
        // phpcs:enable
    }

    /**
     * The get_instance function.
     *
     * Return an instance of SalesForceConnector
     *
     * @return self.
     */
    public static function get_instance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * The run function.
     *
     * Runs: hooks, admin pages, text domain...
     *
     * @return void.
     */
    public function run() {
        //...
    }
}