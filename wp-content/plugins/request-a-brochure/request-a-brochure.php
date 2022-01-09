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
    wp_localize_script('rab-js-admin', 'SERVER_DATA', array(
        "rest_url" => get_rest_url(null, '/rab/v1'),
    ));
    wp_enqueue_script('rab-js-admin');

    require_once('inc/admin-page.php');
    return rab_admin_page_html();
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
    wp_enqueue_script('rab-js');

    return '';
}
add_shortcode('rab-form', 'rab_form_shortcode');


/**
 * [ASSETS REGISTRATION]
 */
function rab_assets_registration()
{
    // initialize the api
    require_once('inc/api/rab-router.php');
    new RAB_Router();

    // user-facing script
    wp_register_script('rab-js', plugins_url('assets/js/rab.min.js', __FILE__), array(), '1.0', true);
    // admin script
    wp_register_script('rab-js-admin', plugins_url('assets/js/rab-admin.min.js', __FILE__), array(), '1.0', true);
}
add_action('init', 'rab_assets_registration');

?>