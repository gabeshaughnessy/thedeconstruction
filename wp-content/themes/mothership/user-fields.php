<?php 
// Add New Fields to the User Profile Pages
// get the fields for use in a template with:  the_author_meta( $meta_key, $user_id );

add_action( 'show_user_profile', 'decon_profile_fields' );
add_action( 'edit_user_profile', 'decon_profile_fields' );

function decon_profile_fields( $user ) { ?>

	<h3>Extra profile information</h3>

	<table class="form-table">

		<tr>
			<th><label for="twitter">Twitter</label></th>

			<td>
				<input type="text" name="twitter" id="twitter" value="<?php echo esc_attr( get_the_author_meta( 'twitter', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your Twitter username.</span>
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
	update_user_meta( $user_id, 'twitter', $_POST['twitter'] );
}


?>