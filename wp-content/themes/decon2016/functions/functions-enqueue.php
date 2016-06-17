<?php
/*
functions.php extension - enqueue
automatically compiled into functions-concat.php
*/


add_action('wp_enqueue_scripts', 'decon_theme_enqueue');
//add_action('admin_enqueue_scripts', 'decon_admin_enqueue');

$decon_style_dir_stats = stat(get_template_directory().'/css');
$css_version = $decon_style_dir_stats['mtime'];

$decon_script_dir_stats = stat(get_template_directory().'/js');
$js_version = $decon_script_dir_stats['mtime'];

function decon_theme_enqueue() {

    global $css_version, $js_version;
    $decon_template_dir = get_template_directory_uri();


    // Register Stylesheets

    wp_register_style('app-css', $decon_template_dir.'/css/app.min.css', array(), $css_version);
   
    // Register Javascript
    wp_deregister_script( 'jquery' );

    wp_register_script('app-js', $decon_template_dir.'/js/app.min.js', false, $js_version);
    wp_register_script('patterns-js', $decon_template_dir.'/js/patterns.min.js', false, $js_version);


    // Enqueue files

   
    wp_enqueue_script('app-js', false, array(), $js_version);
    wp_enqueue_script('patterns-js', true, array('app-js'), $js_version);
    wp_enqueue_style('app-css', false, array(), $css_version);
   
    // Pricing Page repeats product pattern from home page
    if(is_page_template('template-pages/page-pricing.php')){
        wp_enqueue_script('app-homepage-js', false, array(), $js_version);
        wp_enqueue_style('app-homepage-css', false, array(), $css_version);
    }
}



function decon_admin_enqueue(){


    global $css_version, $js_version;
    $decon_template_dir = get_template_directory_uri();

    //register
    wp_register_script('decon-admin-js', $decon_template_dir.'/js/admin.min.js', false, $js_version);
    wp_register_style('admin-css', $decon_template_dir.'/css/admin.min.css', array(), $css_version);

    //enqueue
    wp_enqueue_script('decon-admin-js', false, array('jquery'), $js_version);
    wp_enqueue_style('admin-css', false, array(), $css_version);
}



if(!is_admin()){
    function decon_deferred_scripts($tag, $handle){

        if($handle !== 'app-js'){
            $tag = str_replace(' src=', ' defer="defer" src=', $tag);
        }
        return $tag;
    }
    add_filter('script_loader_tag', 'decon_deferred_scripts', 10, 2);

}
?>
