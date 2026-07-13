<?php
/**
 * HookContract File
 *
 * @package RTahina_SalesForce_Connector
 */

namespace RTahina\SalesforceConnector\Contracts;

/**
 * HookContract class
 *
 * @since 1.0.0
 */
interface HookContract {
    public static function action(): void;
}
