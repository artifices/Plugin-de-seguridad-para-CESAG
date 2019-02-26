<?php
/*
Plugin Name: Opciones de Seguridad para centros Pureza de Maria
Plugin URI: https://www.artifices.net
Description: Opciones básicas de seguridad: límite de revisiones, no actualizaciones automaticas, no edición de ficheros, no instalación de plugins. 
* Version: 1.0.6
* Author: Jesus Cortes
* Author URI: http://www.artifices.net
License: GPLv2 or later
Text Domain: artifices
GitHub Plugin URI: https://github.com/ArtificesGIT/cesag-seguridad-plugin
*/

// Auto actualizador mediante Github

if( ! class_exists( 'cesag_Updater' ) ){
    include_once( plugin_dir_path( __FILE__ ) . 'updater.php' );
}

$updater = new cesag_Updater( __FILE__ );
$updater->set_username( 'artifices' );
$updater->set_repository( 'cesag-seguridad-plugin' );
/*
    $updater->authorize( 'abcdefghijk1234567890' ); // Your auth code goes here for private repos
*/
$updater->initialize();


// Definición de configuraciones

function constants_cesag() {

// Filtro para permitir solo al usuario artifices realizar tareas de mantenimiento.

    $current_user = wp_get_current_user();
    if ( 'cesag' != $current_user->user_login ) {
        define('DISALLOW_FILE_EDIT',true); // IMPEDIR EDICION TEMAS & PLUGINS
        define('DISALLOW_FILE_MODS',true); // IMPEDIR INSTALACION DE PLUGINS 
    } else {
        define('DISALLOW_FILE_EDIT',false); // PERMITIR A ARTIFICES EDICION TEMAS & PLUGINS
        define('DISALLOW_FILE_MODS',false); // PERMITIR A ARTIFICES INSTALACION DE PLUGINS    
    }

    // LIMITAR NUMERO DE REVISIONES EN LA BD

    if (!defined('WP_POST_REVISIONS')) {
        define('WP_POST_REVISIONS', 2);
    }
    if (!defined('WP_POST_REVISIONS')) {
        define('WP_POST_REVISIONS', false);
    }

    // Limitar subidas

     @ini_set( 'upload_max_size' , '5M' );

}
add_action('init', 'constants_cesag', 102);


// PERSONALIZAR LOGO LOGIN

function my_login_logo_style() { ?>
    <style type="text/css">
        #login h1 a, 
        .login h1 a {
            background-image: url('<?php echo plugins_url( 'logo-pureza.png', __FILE__ ) ; ?>') !important;
            height:52px;
            width:240px;
            background-size: 240px 52px;
            background-repeat: no-repeat;
            padding-bottom: 30px;
        }
    </style>
<?php }
add_action('login_enqueue_scripts', 'my_login_logo_style' );
add_filter('login_headerurl', 'custom_loginlogo_url' );
function custom_loginlogo_url($url) {
    return 'https://www.pmaria.es';
}
?>