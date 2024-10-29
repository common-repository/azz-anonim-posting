<?php
/*
Plugin Name: Azz Anonim Posting
Plugin URI: http://azzrael.ru/azz-anonim-posting
Description: Allows you to add posts with images by anonymous users without registration.
From any page of your blog, where you place the form.
Author URI: http://azzrael.ru/
Version: 0.9
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Not allowed.';
	exit;
}

define( 'AZZAP_PLUGIN_DIR'  , plugin_dir_path( __FILE__ ) );
define( 'AZZAP_PLUGIN_URL'  , plugin_dir_url ( __FILE__ ) );
define( 'AZZAP_TMPL_DIR'    , AZZAP_PLUGIN_DIR.'tmpl' );
define( 'AZZAP_QUERY_VAR'    , 'azzap_query' );

$azzap_upload_dir = wp_upload_dir();
define( 'AZZAP_FILES_DIR'  , $azzap_upload_dir['basedir'].'/azzap_files/' );
define( 'AZZAP_FILES_URL'  , $azzap_upload_dir['baseurl'].'/azzap_files/' );

require_once (AZZAP_PLUGIN_DIR.'inc/class.azzaptmpl.php');
require_once (AZZAP_PLUGIN_DIR.'inc/class.azzapuser.php');
require_once (AZZAP_PLUGIN_DIR.'inc/class.azzap.uh.php');

require_once (AZZAP_PLUGIN_DIR.'inc/class.azzapconfig.php');
add_action( 'init', array( 'AzzapConfig', 'init' ) );

if(is_admin()){
    require_once(AZZAP_PLUGIN_DIR.'inc/class.azzapadmin.php');
    add_action( 'init', array( 'AzzapAdmin', 'init' ) );
}

register_activation_hook    ( __FILE__, array( 'AzzapConfig', 'install' ));
register_deactivation_hook  ( __FILE__, array( 'AzzapConfig', 'deinstall' ));

/*************************************************************************
 * Public functions
 */

function azzap_form(){
    AzzapUser::pageAddPostForm();
}