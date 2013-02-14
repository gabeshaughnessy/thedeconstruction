<?php 

//remove default fields for the team users
add_filter('user_contactmethods','decon_hide_profile_fields',10,1);
remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
function decon_hide_profile_fields( $contactmethods ) {


unset($contactmethods['aim']);
unset($contactmethods['jabber']);
unset($contactmethods['yim']);
return $contactmethods;
}


function decon_admin_del_options() {
   global $_wp_admin_css_colors;
   $_wp_admin_css_colors = 0;
}

add_action('admin_head', 'decon_admin_del_options');



//ocultar campos ao editar profile e ao criar novo usuario
function decon_hide_personal_options(){
echo "\n" . '<script type="text/javascript">jQuery(document).ready(function($) {

$(\'form#your-profile > h3\').hide();
$(\'form#your-profile\').show();
$(\'form#your-profile > h3:first\').hide();
$(\'#wordpress-seo\').next(\'table\').hide();
$(\'#wordpress-seo\').hide();
$(\'form#your-profile label[for=user_login]\').html("Login name");
$(\'form#your-profile label[for=first_name]\').html("Team Organizer First Name");
$(\'form#your-profile label[for=last_name]\').html("Team Organizer Last Name");
$(\'form#your-profile label[for=nickname]\').html("Team Name");
$(\'form#your-profile label[for=wp_user_avatar]\').html("Team Avatar Image");
$(\'form#your-profile > table:first\').hide();
$(\'form#your-profile label[for=url], form#your-profile input#url\').hide();
$(\'form#your-profile label[for=description], form#your-profile textarea#description, form#your-profile span.description\').hide();
$(\'form#createuser label[for=url], form#createuser input#url\').hide();
});
 
</script>' . "\n";
}
add_action('admin_head','decon_hide_personal_options');
// Add New Fields to the User Profile Pages
// get the fields for use in a template with:  the_author_meta( $meta_key, $user_id );

add_action( 'show_user_profile', 'decon_profile_fields', 1);
add_action( 'edit_user_profile', 'decon_profile_fields', 1 );

function decon_profile_fields( $user ) { ?>

	<h3>Team profile information</h3>

	<table class="form-table">
		<tr>
					<th><label for="team-name">Team Location</label></th>
		
					<td>
						<input type="text" name="team-location" id="team-location" value="<?php echo esc_attr( get_the_author_meta( 'team-location', $user->ID ) ); ?>" class="regular-text" /><br />
						<span class="description">City, State, Country</span>
					</td>
		</tr>
		
		<tr>
					<th><label for="bio">Team Bio</label></th>
		
					<td>
						<textarea  name="bio" id="bio" value="<?php echo esc_attr( get_the_author_meta( 'bio', $user->ID ) ); ?>" class="regular-text"></textarea><br />
						<span class="description">Tell us your team's story for the top of your profile page.</span>
					</td>
		</tr>
		
	
		
	</table>
<?php } 

add_action( 'personal_options_update', 'decon_save_profile_fields' );
add_action( 'edit_user_profile_update', 'decon_save_profile_fields' );

function decon_save_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
	update_user_meta( $user_id, 'bio', $_POST['bio'] );
	update_user_meta( $user_id, 'stream', $_POST['stream'] );
	update_user_meta( $user_id, 'team-location', $_POST['team-location'] );
	update_user_meta( $user_id, 'twitter', $_POST['twitter'] );
}

/**
 *	Add TinyMCE editor to the "Biographical Info" field in a user profile
 */
function decon_user_bio_visual_editor( $user ) {
	// Requires WP 3.3+ and author level capabilities
	if ( function_exists('wp_editor') && current_user_can('publish_posts') ):
	?>
	<script type="text/javascript">
	(function($){ 
		// Remove the textarea before displaying visual editor
		$('#bio').parents('tr').remove();
	})(jQuery);
	</script>
 
	<table class="form-table">
		<tr>
			<th><label for="bio"><?php _e('Team Bio'); ?></label></th>
			<td>
				<?php 
				$bio = get_user_meta( $user->ID, 'bio', true);
				wp_editor( $bio, 'bio' ); 
				?>
				<p class="description"><?php _e('Share a little background information to fill out your profile. This will be shown at the top of your team profile.'); ?></p>
			</td>
		</tr>
	</table>
	<table class="form-table">
		<tr>
			<th><label for="stream"><?php _e('Team Stream Embed Code'); ?></label></th>
			<td>
				<?php 
				$stream = get_user_meta( $user->ID, 'astream', true);
				wp_editor( $stream, 'stream', array('tinymce'=>false) ); 
				?>
				<p class="description"><?php _e('Paste the HTML embed code for your team\'s live video stream here.'); ?></p>
			</td>
		</tr>
	</table>
	<?php
	endif;
}
add_action('show_user_profile', 'decon_user_bio_visual_editor');
add_action('edit_user_profile', 'decon_user_bio_visual_editor');
 
/**
 * Remove textarea filters from description field
 */
function decon_user_bio_visual_editor_unfiltered() {
	remove_all_filters('pre_user_description');
}
add_action('admin_init','decon_user_bio_visual_editor_unfiltered');

?>