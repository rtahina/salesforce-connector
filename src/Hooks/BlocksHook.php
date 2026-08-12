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
final class BlocksHook implements HookContract {
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
        add_action( 'block_categories_all', array( self::class, 'addBlockCategory' ) );
        add_action( 'init', array( self::class, 'registerBlocks' ) );
    }

    /**
     * Callback for the 'init' filter, used to register blocks.
     *
     * @param array $categories Block categories array.
     *
     * @return array
     */
    public static function registerBlocks(): void {
        register_block_type( RTSC_PLUGIN_PATH . 'build/blocks/sf-form' );
        register_block_type( RTSC_PLUGIN_PATH . 'build/blocks/sf-form-input' );
        register_block_type( RTSC_PLUGIN_PATH . 'build/blocks/sf-form-checkbox' );
        register_block_type( RTSC_PLUGIN_PATH . 'build/blocks/sf-form-selectbox' );
    }

    /**
     * Callback for the 'block_categories_all' filter, used to add the SalesForce Blocks category.
     *
     * @param array $categories Block categories array.
     *
     * @return array
     */
    public static function addBlockCategory( array $categories ): array {
        $categories[] = array(
            'slug'  => 'rtsc-blocks',
            'title' => __( 'SalesForce Blocks', 'rtahina-salesforce-connector' ),
        );

        return $categories;
    }
}
