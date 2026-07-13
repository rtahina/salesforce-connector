<?php
/**
 * SalesForce Connector File
 *
 * @package RTahina_SalesForce_Connector
 */

namespace RTahina\SalesforceConnector\Services;

use RTahina\SalesforceConnector\DTOs\SalesForceConfig;

/**
 * SalesForce class
 *
 * All the class's properties and methods
 *
 * @since 1.0.0
 */
class SalesForce {

    protected const RTSC_TOKEN_OPTION             = '_rtsc_salesforce_token';
    protected const RTSC_CONFIG_OPTION            = '_rtsc_salesforce_config';
    protected const RTSC_SALESFORCE_AUTH_ENDPOINT = 'https://login.salesforce.com/services/oauth2';

    /**
     * Saves the data from Salesforce
     *
     * @param string $data JSon format string
     * @return void
     */
    public function save_token( string $data ): void {
        update_option( $this::RTSC_TOKEN_OPTION, $data );
    }

    /**
     * Get token
     *
     * @param string $code Authorization code from Salesforce
     * @return string|false $response Json format response on success or false on failure
     */
    public static function get_token( string $code = '' ): mixed {

        if ( $code !== '' ) { // get initial token
            return ( new self() )->get_initial_token( $code );
        } else {
            return get_option( self::RTSC_TOKEN_OPTION );
        }
    }

    /**
     * Get inital token
     *
     * @param string $code Authorization code from Salesforce
     * @return string|false $response Json format response on success or false on failure
     */
    protected function get_initial_token( string $code ): mixed {
        $config = $this->get_config();
        $params = array(
            'grant_type'    => 'authorization_code',
            'code'          => $code,
            'client_id'     => $config->client_id,
            'client_secret' => $config->client_secret,
            'redirect_uri'  => RTSC_SALESFORCE_CALLBACK,
            'code_verifier' => $config->code_verifier,
        );
        $params = http_build_query( $params );

        $headers = array(
            'Content-Type : application/x-www-form-urlencoded',
            'Accept: application/json',
            'content-length: ' . strlen( $params ),
        );

        $response      = wp_remote_post(
            $this::RTSC_SALESFORCE_AUTH_ENDPOINT . '/token',
            array(
                'method'  => 'POST',
                'timeout' => 120,
                'headers' => $headers,
                'body'    => $params,
            )
        );
        $response_code = wp_remote_retrieve_response_code( $response );

        if ( 200 !== $response_code ) {
            return false;
        }

        $body     = wp_remote_retrieve_body( $response );
        $response = json_decode( $body );

        if ( isset( $response->access_token ) ) {
            // Add time.
            $response->time = current_time( 'mysql', true );
            $token_json     = json_encode( $response );
            $this->save_token( $token_json );

            return $body;
        }

        return false;
    }

    public function login_url(): string {
        $config = $this->get_config();
        $arr    = array();
        $params = array(
            'response_type'         => 'code',
            'state'                 => RTSC_SALESFORCE_CALLBACK,
            'client_id'             => $config->client_id,
            'redirect_uri'          => RTSC_SALESFORCE_CALLBACK,
            'scope'                 => 'api+refresh_token',
            'code_challenge_method' => 'S256',
            'code_challenge'        => $config->code_challenge,
        );

        foreach ( $params as $key => $val ) {
            $arr[] = $key . '=' . $val;
        }

        $params = '?' . join( '&', $arr );

        $url = $this::RTSC_SALESFORCE_AUTH_ENDPOINT . '/authorize' . $params;

        return $url;
    }

    public function get_token_info(): mixed {
        $token_data_json = ( new self() )->get_token();
        if ( ( new self() )->isJSON( $token_data_json ) ) {
            $token_data = json_decode( $token_data_json );
            return array(
                'instance_url' => $token_data->instance_url ?? '',
                'issued_at'    => $token_data->time ?? '',
                'access_token' => $token_data->access_token ?? '',
            );
        }

        return false;
    }

    public static function refresh_token(): mixed {

        $token_info = ( new self() )->get_token();

        if ( ! ( new self() )->isJSON( $token_info ) ) {
            return false;
        }

        $data          = json_decode( $token_info );
        $refresh_token = $data->refresh_token;
        $config        = ( new self() )->get_config();

        $body = array(
            'client_id'     => $config->client_id,
            'client_secret' => $config->client_secret,
            'redirect_uri'  => RTSC_SALESFORCE_CALLBACK,
            'grant_type'    => 'refresh_token',
            'refresh_token' => $refresh_token,
        );
        $body = http_build_query( $body );

        $headers = array(
            'Content-Type : application/x-www-form-urlencoded',
            'Accept: application/json',
            'content-length: ' . strlen( $body ),
        );

        $endpoint = self::RTSC_SALESFORCE_AUTH_ENDPOINT . '/token';

        $response = wp_remote_post(
            $endpoint,
            array(
                'method'  => 'POST',
                'timeout' => 120,
                'headers' => $headers,
                'body'    => $body,
            )
        );

        if ( is_wp_error( $response ) ) {
            return false;
        }

        $body     = wp_remote_retrieve_body( $response );
        $response = json_decode( $body );

        if ( ! isset( $response->access_token ) && '' === $response->access_token ) {
            return false;
        }

        $response->time          = current_time( 'mysql', true );
        $response->refresh_token = $refresh_token;

        $json_info = json_encode( $response );

        ( new self() )->save_token( $json_info );

        return $json_info;
    }

    public static function revoke() {

        $token_info = ( new self() )->refresh_token();

        if ( ! ( new self() )->isJSON( $token_info ) ) {
            return false;
        }

        $data = json_decode( $token_info );

        $body = array(
            'token' => $data->access_token,
        );

        $body = http_build_query( $body );

        $headers = array(
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: application/json',
            'content-length: ' . strlen( $body ),
        );

        $endpoint = $data->instance_url . '/services/oauth2/revoke';

        $response = wp_remote_post(
            $endpoint,
            array(
                'method'  => 'POST',
                'timeout' => 120,
                'headers' => $headers,
                'body'    => $body,
            )
        );

        if ( is_wp_error( $response ) ) {
            return false;
        }

        $body = wp_remote_retrieve_body( $response );

        ( new self() )->save_token( '' );
    }

    public static function isJSON( $string ) {
        return is_string( $string ) && is_array( json_decode( $string, true ) ) ? true : false;
    }

    public static function isValidTokenInfo( $token ): bool {
        if ( is_array( $token ) ) {
            if ( isset( $token['access_token'] ) ) {
                if ( trim( $token['access_token'] ) !== '' ) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Saves the salesforce config
     *
     * @param SalesForceConfig $config SalesForce configuration object
     * @return bool
     */
    public function save_config( SalesForceConfig $config ): bool {
        return update_option( $this::RTSC_CONFIG_OPTION, $config );
    }

    /**
     * Gets the salesforce config
     *
     * @return SalesForceConfig|bool
     */
    public function get_config(): SalesForceConfig|bool {
        $config = get_option( $this::RTSC_CONFIG_OPTION );

        if ( false === $config ) {
            return new SalesForceConfig();
        }

        return $config;
    }
}
