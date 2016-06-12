<?php

//ADVANCED CUSTOM FIELDS - INCLUDED IN THE THEME

add_filter('acf/settings/save_json', 'decon_acf_json_save_point');
 
function decon_acf_json_save_point( $path ) {
    
    // update path
    $path = get_stylesheet_directory() . '/functions/acf/acf-json';
    
    
    // return
    return $path;
    
}

add_filter('acf/settings/load_json', 'decon_acf_json_load_point');

function decon_acf_json_load_point( $paths ) {
    
    // remove original path (optional)
    unset($paths[0]);
    
    
    // append path
    $paths[] = get_stylesheet_directory() . '/functions/acf/acf-json';
    
    
    // return
    return $paths;
    
}

include_once( get_stylesheet_directory() . '/functions/acf/acf.php' );


?>