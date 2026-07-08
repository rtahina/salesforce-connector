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

$rtsc_tab = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'connect';
if ( isset( $_POST['rtsc_revoke_sf_token'] ) && wp_verify_nonce( $_POST['rtsc_revoke_sf_token'], 'revoke-salesforce-token' )
) {
    $rtsc_salesforce->revoke();
    wp_safe_redirect( $_POST['_wp_http_referer'] );
    exit();
}

// Parameters from SalesForce.
if ( ! empty( $_GET['code'] ) && ! empty( $_GET['state'] ) ) {
    $rtsc_code  = sanitize_key( wp_unslash( $_GET['code'] ) );
    $rtsc_state = sanitize_key( wp_unslash( $_GET['state'] ) );

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
                        <?php wp_nonce_field( 'revoke-salesforce-token', 'rtsc_revoke_sf_token' ); ?>
                        <input type="submit" name="revoke" class="button button-primary" value="Revoke connection" />
                    </form>
                </p>
            <?php endif; ?>
        <?php } elseif ( 'keys' === $rtsc_tab ) { ?>
            <p>Enter the SalesForce Client ID and the Consumer Secret</p>
            <form action="<?php echo esc_url( admin_url( RTSC_SALESFORCE_ADMIN_PAGE ) ); ?>&tab=keys" method="POST">
                <label for="rtsc-sf-client-id">
                    SalesForce Client ID
                    <input type="text" id="rtsc-sf-client-id" name="rtsc-sf-client-id">
                </label>
                <label for="rtsc-sf-consumer-key">
                    SalesForce Consumer Key
                    <input type="text" id="rtsc-sf-consumer-key" name="rtsc-sf-consumer-key">
                </label>
                <input type="submit" class="button button-primary" value="Save keys">
            </form>
        <?php } ?>
    </div>
</div>
