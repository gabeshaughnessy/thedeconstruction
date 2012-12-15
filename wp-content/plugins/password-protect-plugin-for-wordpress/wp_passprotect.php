<?php
/*
Plugin Name: Password Protect Plugin for WordPress
Description: This plugin will force all visitors to login before accessing your wordpress site. It also adds a message on the login screen explaining that login is required.
Version: 0.8.1.0
Author: David Marcucci
Author URI: http://david.marcucci.org/wppassprotect
License: GPL2
*/


function password_protected() {
	if ( (!is_user_logged_in() and !is_feed()) or  (!is_user_logged_in() and is_feed() and !get_option("wppassprotect_public_feed")=="checked")) {
		auth_redirect();
  }
}

function password_protected_notice() {
	//echo ("<p>Notice: This site requires all users to login before accessing its content. If you do not have a login you may register for one using the register link below.</p><br>");
	echo("<p class='message'>");
	echo(get_option('login_screen_message'));
	echo("</p>");
}

function wppassprotect_plugin_menu() {
  add_options_page('Password Protect Options', 'Password Protect Plugin', 'administrator', 'wppassprotect', 'wppassprotect_options_page');
}


// wppassprotect_options_page() displays the page content for the Test Options submenu
function wppassprotect_options_page() {

    // variables for the field and option names 
    $opt_name = 'login_screen_message';
	$opt_name2 = 'wppassprotect_public_feed';
    $hidden_field_name = 'wppassprotect_submit_hidden';
    $data_field_name = 'wppassprotect_login_screen_message';
	$data_field_name2 = 'wppassprotect_public_feed';

    // Read in existing option value from database
    $opt_val = get_option( $opt_name );
	$opt_val2 = get_option( $opt_name2 );

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
        $opt_val = $_POST[ $data_field_name ];
		$opt_val2 = $_POST[ $data_field_name2 ];

        // Save the posted value in the database
        update_option( $opt_name, $opt_val );
        update_option( $opt_name2, $opt_val2 );

        // Put an options updated message on the screen

?>
<div class="updated"><p><strong><?php _e('Options saved.', 'wppassprotect_domain' ); ?></strong></p></div>
<?php

    }

    // Now display the options editing screen

    echo '<div class="wrap">';

    // header

    echo "<h2>" . __( 'Password Protect Plugin for WordPress', 'wppassprotect_domain' ) . "</h2>";

    // options form
    
    ?>

<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

<p><?php _e("Allow public feed (do not require login for feeds when checked): ", 'wppassprotect_domain' ); ?>
<input type="checkbox" name="<?php echo $data_field_name2; ?>" value="checked" <?php echo $opt_val2; ?> ><br><br>
<?php _e("Login Screen Message:", 'wppassprotect_domain' ); ?><br>
<textarea name="<?php echo $data_field_name; ?>" rows="10" cols="40"><?php echo $opt_val; ?></textarea>
</p><hr />

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options', 'wppassprotect_domain' ) ?>" />
</p>

</form>
</div>

<?php
 
}



add_action('template_redirect', 'password_protected');
add_action('do_feed', 'password_protected');

if (get_option('login_screen_message')!=null){
	add_action('login_message', 'password_protected_notice');
	}

add_action('admin_menu', 'wppassprotect_plugin_menu');
?>