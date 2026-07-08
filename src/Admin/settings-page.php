<?php
/**
 * The admin page file
 *
 * @package RTahina_SalesForce_Connector
 */

namespace RTahina\SalesforceConnector;

use RTahina\SalesforceConnector\DTOs\SalesForceConfig;
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

// Parameters from SalesForce upon authoriation code request.
if ( isset( $_GET['code'] ) && isset( $_GET['state'] ) ) {
    $rtsc_code  = sanitize_text_field( wp_unslash( $_GET['code'] ) );
    $rtsc_state = sanitize_text_field( wp_unslash( $_GET['state'] ) );

    if ( admin_url( RTSC_SALESFORCE_ADMIN_PAGE ) === $rtsc_state ) {
        $rtsc_salesforce->get_token( $rtsc_code );
    }
}

$rtsc_token_info = $rtsc_salesforce->get_token_info();
?>

<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

    <nav class="nav-tab-wrapper">  
        <a 
            href="<?php echo esc_url( admin_url( RTSC_SALESFORCE_ADMIN_PAGE ) ); ?>&tab=connect" 
            class="nav-tab 
            <?php
            if ( 'connect' === $rtsc_tab ) :
                ?>
                nav-tab-active<?php endif; ?>"
        >
            Connect
        </a>  
        <a 
            href="<?php echo esc_url( admin_url( RTSC_SALESFORCE_ADMIN_PAGE ) ); ?>&tab=keys" 
            class="nav-tab 
            <?php
            if ( 'keys' === $rtsc_tab ) :
                ?>
                nav-tab-active<?php endif; ?>">
            Key & Secret    
        </a>  
    </nav> 
    
    <div class="rtsc_tab-content">
        <?php if ( 'connect' === $rtsc_tab ) { ?>
            <?php if ( false === SalesForce::isValidTokenInfo( $rtsc_token_info ) ) : ?>
                <p>Connect to SalesForce to be able to use the plugin</p>
                <p>
                    <a href="<?php echo esc_url( $rtsc_salesforce->login_url() ); ?>">Login to SalesForce</a>
                </p>
            <?php else : ?>
                <p>Currently connected on : 
                    <pre>Instance URL: <?php echo esc_html( $rtsc_token_info['instance_url'] ); ?></pre>
                    <pre>Issued at: <?php echo esc_html( $rtsc_token_info['issued_at'] ); ?></pre>
                    <pre>Token: <?php echo esc_html( $rtsc_token_info['access_token'] ); ?></pre>
                </p>
                <p>
                    <form action="" method="post">
                        <?php wp_nonce_field( 'rtsc_revoke-salesforce-token', 'rtsc_revoke-salesforce-token-nonce' ); ?>
                        <input type="submit" name="revoke" class="button button-primary" value="Revoke connection" />
                    </form>
                </p>
            <?php endif; ?>
        <?php } elseif ( 'keys' === $rtsc_tab ) { ?>
            <p>Enter the SalesForce Client ID and the Consumer Secret</p>
            <form action="<?php echo esc_url( admin_url( RTSC_SALESFORCE_ADMIN_PAGE ) ); ?>&tab=keys" method="POST">
                <?php wp_nonce_field( 'rtsc_save-salesfoce-config', 'rtsc_save-salesfoce-config-nonce' ); ?>
                <div class="row">
                    <label for="rtsc-sf-client-id">
                        SalesForce Client ID*
                        <textarea id="rtsc-sf-client-id" name="rtsc-sf-client-id"><?php echo esc_html( $rtsc_config->client_id ); ?></textarea>
                    </label>
                </div>
                <div class="row">
                    <label for="rtsc-sf-client-secret">
                        SalesForce Client Secret*
                        <textarea id="rtsc-sf-client-secret" name="rtsc-sf-client-secret"><?php echo esc_html( $rtsc_config->client_secret ); ?></textarea>
                    </label>
                </div>
                <div class="row">
                    <label for="rtsc-sf-code-challenge">
                        Code Challenge*
                        <textarea id="rtsc-sf-code-challenge" name="rtsc-sf-code-challenge"><?php echo esc_html( $rtsc_config->code_challenge ); ?></textarea>
                    </label>
                </div>
                <div class="row">
                    <label for="rtsc-sf-code-verifier">
                        Code Verifier*
                        <textarea id="rtsc-sf-code-verifier" name="rtsc-sf-code-verifier"><?php echo esc_html( $rtsc_config->code_verifier ); ?></textarea>
                    </label>
                </div>
                <input type="submit" name="rtsc-sf-save-keys" class="button button-primary" value="Save keys">
            </form>
        <?php } ?>
    </div>
</div>
