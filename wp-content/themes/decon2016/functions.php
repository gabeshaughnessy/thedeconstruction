<?php
//ADVANCED CUSTOM FIELDS - INCLUDED IN THE THEME

add_filter('acf/settings/dir', 'decon_acf_settings_dir');
function decon_acf_settings_dir( $path ) {
    // update path
    $path = 'http://decon.local/wp-content/themes/decon2016/functions/acf/';
    // return
    return $path;
    
}
 

add_filter('acf/settings/save_json', 'decon_acf_json_save_point');
 
function decon_acf_json_save_point( $path ) {
    // update path
    $path = ABSPATH.'wp-content/themes/decon2016/functions/acf/acf-json';
    
    
    // return
    return $path;
    
}

add_filter('acf/settings/load_json', 'decon_acf_json_load_point');

function decon_acf_json_load_point( $paths ) {
    
    // remove original path (optional)
    unset($paths[0]);
    
    
    // append path
    $paths[] = ABSPATH.'wp-content/themes/decon2016/functions/acf/acf-json';
    
    
    // return
    return $paths;
    
}


include_once( 'functions/acf/acf.php' );

//END ACF INCLUDE

if(!is_404()){
    require_once('functions-concat.php');
}
?>