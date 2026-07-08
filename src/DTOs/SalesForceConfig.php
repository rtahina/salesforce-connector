<?php
/**
 * SalesForce Configuration
 *
 * @package RTahina_SalesForce_Connector
 */

namespace RTahina\SalesforceConnector\DTOs;

/**
 * SalesForceConfig class
 *
 * DTO for SalesForce config
 *
 * @since 1.0.0
 */
final readonly class SalesForceConfig {

// phpcs:disable Squiz.Commenting.FunctionComment.Missing
    public function __construct(
        public string $client_id = '',
        public string $consumer_key = '',
        public string $code_challenge = '',
        public string $code_verifier = ''
    ) {}
}
