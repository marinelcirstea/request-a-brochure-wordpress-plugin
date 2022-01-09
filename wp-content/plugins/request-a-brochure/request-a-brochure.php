<?php
/**
 * Plugin Name: Request a Brochure
 * Plugin Description: Request a Brochure
 * Author: Marinel Cirstea
 * Author URI: https://www.callmejohn.co.uk
 * Version: 1.0
 * Text Domain: request-a-brochure
 * Domain Path: /languages/
 */
?>
<?php

/**
 * [ACTIVATION]
 * create the brochure list table and the brochure request table
 * if they don't exist
 */
function rab_activate()
{
    require_once('inc/rab-service.php');
    $rs = new RAB_Service();
    $rs->create_brochures_table();
    $rs->create_brochure_request_table();
}
register_activation_hook(__FILE__, 'rab_activate');


/**
 * [UNINSTALL]
 * delete the brochure list table and the brochure request table
 */
function rab_uninstall()
{
    require_once('inc/rab-service.php');
    $rs = new RAB_Service();
    $rs->drop_brochures_table();
    $rs->drop_brochure_requests_table();
}
register_uninstall_hook(__FILE__, 'rab_uninstall');

/**
 * [ADMIN MENU PAGE]
 */
function rab_admin_page()
{
    require_once('inc/admin-page.php');
    new RAB_AdminPage();
}


/**
 * [ADMIN MENU SETUP]
 */
function rab_admin_menu_setup()
{
    add_menu_page(
        "Request a Brochure",
        "RAB Plugin",
        "manage_options",
        "request-a-brochure",
        "rab_admin_page",
        "dashicons-media-spreadsheet",
        3
    );
}
add_action('admin_menu', 'rab_admin_menu_setup');

/**
 * [SHORTCODE]
 */
function rab_form_shortcode()
{
    return '';
}
add_shortcode('rab-form', 'rab_form_shortcode');


/**
 * [ASSETS REGISTRATION]
 */
function rab_assets_registration()
{
}

?>