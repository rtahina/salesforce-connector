<?php
/**
 * The admin page file
 *
 * @package RTahina_SalesForce_Connector
 */

namespace RTahina\SalesforceConnector;

use RTahina\SalesforceConnector\Services\SalesForce;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$rtsc_code       = '';
$rtsc_state      = '';
$rtsc_salesforce = new SalesForce();
$rtsc_config     = $rtsc_salesforce->get_config();

$rtsc_tab = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'connect';

// Revoke a tocken.
if ( isset( $_POST['rtsc_revoke_sf_token'] ) && wp_verify_nonce( $_POST['rtsc_revoke_sf_token'], 'revoke-salesforce-token' )
) {
    $rtsc_salesforce->revoke();
    wp_safe_redirect( $_POST['_wp_http_referer'] );
    exit();
}

$rtsc_token_info = $rtsc_salesforce->get_token_info();
?>

<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    
    <nav class="nav-tab-wrapper">  
        <a 
            href="<?php echo esc_url( RTSC_SALESFORCE_ADMIN_PAGE ); ?>&tab=connect" 
            class="nav-tab 
            <?php
            if ( 'connect' === $rtsc_tab ) :
                ?>
                nav-tab-active<?php endif; ?>"
        >
            <?php _e( 'Connect', 'rtahina-salesforce-connector' ); ?>
        </a>  
        <a 
            href="<?php echo esc_url( RTSC_SALESFORCE_ADMIN_PAGE ); ?>&tab=keys" 
            class="nav-tab 
            <?php
            if ( 'keys' === $rtsc_tab ) :
                ?>
                nav-tab-active<?php endif; ?>">
            <?php _e( 'Key & Secret', 'rtahina-salesforce-connector' ); ?>
        </a>  
    </nav> 
    
    <div class="rtsc_tab-content">
        <?php if ( 'connect' === $rtsc_tab ) { ?>
            <?php if ( '' === $rtsc_config->client_id ) : ?>
                <p>The SalesForce configuration is missing. Please, go to the <a href="<?php echo esc_url( admin_url( RTSC_SALESFORCE_ADMIN_PAGE ) ); ?>&tab=keys">Key & Secret</a> tab and fill up the required fields.</p>
            <?php endif; ?>    
            <?php if ( false === SalesForce::isValidTokenInfo( $rtsc_token_info ) ) : ?>
                <p><?php _e( 'Connect to SalesForce to be able to use the plugin', 'rtahina-salesforce-connector' ); ?></p>
                <p>
                    <a href="<?php echo esc_url( $rtsc_salesforce->login_url() ); ?>">Login to SalesForce</a>
                </p>
            <?php else : ?>
                <p><?php _e( 'Currently connected on :', 'rtahina-salesforce-connector' ); ?> 
                    <pre><?php _e( 'Instance URL:', 'rtahina-salesforce-connector' ); ?><?php echo esc_html( $rtsc_token_info['instance_url'] ); ?></pre>
                    <pre><?php _e( 'Issued at:', 'rtahina-salesforce-connector' ); ?> <?php echo esc_html( $rtsc_token_info['issued_at'] ); ?></pre>
                    <pre><?php _e( 'Token:', 'rtahina-salesforce-connector' ); ?> <?php echo esc_html( $rtsc_token_info['access_token'] ); ?></pre>
                </p>
                <p>
                    <form action="" method="post">
                        <?php wp_nonce_field( 'rtsc_revoke-salesforce-token', 'rtsc_revoke-salesforce-token-nonce' ); ?>
                        <input type="submit" name="revoke" class="button button-primary" value="Revoke connection" />
                    </form>
                </p>
            <?php endif; ?>
        <?php } elseif ( 'keys' === $rtsc_tab ) { ?>
            <p><?php _e( 'Enter the SalesForce Client ID and the Consumer Secret', 'rtahina-salesforce-connector' ); ?></p>
            <form action="<?php echo esc_url( admin_url( RTSC_SALESFORCE_ADMIN_PAGE ) ); ?>&tab=keys" method="POST">
                <?php wp_nonce_field( 'rtsc_save-salesfoce-config', 'rtsc_save-salesfoce-config-nonce' ); ?>
                <div class="row">
                    <label for="rtsc-sf-client-id">
                        <?php _e( 'SalesForce Client ID', 'rtahina-salesforce-connector' ); ?>
                        <textarea id="rtsc-sf-client-id" name="rtsc-sf-client-id"><?php echo esc_html( $rtsc_config->client_id ); ?></textarea>
                    </label>
                </div>
                <div class="row">
                    <label for="rtsc-sf-client-secret">
                        <?php _e( 'SalesForce Client Secret', 'rtahina-salesforce-connector' ); ?>
                        <textarea id="rtsc-sf-client-secret" name="rtsc-sf-client-secret"><?php echo esc_html( $rtsc_config->client_secret ); ?></textarea>
                    </label>
                </div>
                <div class="row">
                    <label for="rtsc-sf-code-challenge">
                        <?php _e( 'Code Challenge', 'rtahina-salesforce-connector' ); ?>
                        <textarea id="rtsc-sf-code-challenge" name="rtsc-sf-code-challenge"><?php echo esc_html( $rtsc_config->code_challenge ); ?></textarea>
                    </label>
                </div>
                <div class="row">
                    <label for="rtsc-sf-code-verifier">
                        <?php _e( 'Code Verifier', 'rtahina-salesforce-connector' ); ?>
                        <textarea id="rtsc-sf-code-verifier" name="rtsc-sf-code-verifier"><?php echo esc_html( $rtsc_config->code_verifier ); ?></textarea>
                    </label>
                </div>
                <input type="submit" name="rtsc-sf-save-keys" class="button button-primary" value="Save Configuration">
            </form>
            <p><small class="error"><?php _e( 'All fields are required', 'rtahina-salesforce-connector' ); ?></small></p>
        <?php } ?>
    </div>
</div>
