<?php

// Set-up Hooks
add_action('admin_init', 'affiliately_init' );
add_action('admin_menu', 'affiliately_add_options_page');

// Init plugin options to white list our options
function affiliately_init(){
	register_setting( 'affiliately_plugin_options', 'affiliately_options', 'affiliately_validate_options' );
}

// Add menu page
function affiliately_add_options_page() {
	add_options_page('Affiliately Settings', 'Affiliately Settings', 'manage_options', 'affiliately-settings', 'affiliately_render_form');
}

// Render the Plugin options form
function affiliately_render_form() {
?>
	<div class="wrap">
		
		<!-- Display Plugin Icon, Header, and Description -->
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>Affiliately Settings</h2>
		<p>Fill in the form below with your affiliate ID and select the merchant (marketplace).</p>

		<!-- Beginning of the Plugin Options Form -->
		<form method="post" action="options.php">
			<?php settings_fields('affiliately_plugin_options'); ?>
			<?php $options = get_option('affiliately_options'); ?>

			<!-- Table Structure Containing Form Controls -->
			<!-- Each Plugin Option Defined on a New Table Row -->
			<table class="form-table">
			  
			  <tr>
					<th scope="row">Merchant (marketplace)</th>
					<td>
						<select name='affiliately_options[marketplace]'>
							<option value='com' <?php selected('com', $options['marketplace']); ?>>amazon.com</option>
							<option value='uk' <?php selected('uk', $options['marketplace']); ?>>amazon.co.uk</option>
							<option value='cb' <?php selected('cb', $options['marketplace']); ?>>clickbank.com</option>
						</select>
						<span style="color:#666666;margin-left:2px;">Select the merchant where you have the affiliate account.</span>
					</td>
				</tr>

				<tr>
					<th scope="row">Your affiliate ID</th>
					<td>
						<input type="text" size="57" name="affiliately_options[affiliate_id]" value="<?php echo $options['affiliate_id']; ?>" />
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">Affiliate links settings</th>
					<td>
						<label><input name="affiliately_options[show_tooltip]" type="checkbox" value="1" <?php if (isset($options['show_tooltip'])) { checked('1', $options['show_tooltip']); } ?> /> Show a tooltip <em>(it will display the title of the product & an image, when the mouse if over the affiliate link)</em></label><br />

						<label><input name="affiliately_options[use_double_underline]" type="checkbox" value="1" <?php if (isset($options['use_double_underline'])) { checked('1', $options['use_double_underline']); } ?> /> Use double underline </label><br />
						
						<label><input name="affiliately_options[new_window]" type="checkbox" value="1" <?php if (isset($options['new_window'])) { checked('1', $options['new_window']); } ?> /> Open links in new window </label>
					</td>
				</tr>

				<tr><td colspan="2"><div style="margin-top:10px;"></div></td></tr>
				<tr valign="top" style="border-top:#dddddd 1px solid;">
					<th scope="row">Database options</th>
					<td>
						<label><input name="affiliately_options[default_options_db]" type="checkbox" value="1" <?php if (isset($options['default_options_db'])) { checked('1', $options['default_options_db']); } ?> /> Restore defaults upon plugin deactivation/reactivation</label>
						<br /><span style="color:#666666;margin-left:2px;">Only check this if you want to reset plugin settings upon Plugin reactivation</span>
					</td>
				</tr>
			</table>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>

		<p style="margin-top:15px;">
			<p style="font-style: italic;font-weight: bold;color: #26779a;">If you want to see stats like number of affiliate links created & CTR, please go to <a href="http://www.affiliate.ly/">http://www.affiliate.ly/</a> and sign in with your Facebook account.<p>
		</p>

	</div>
<?php	
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function affiliately_validate_options($input) {
	$input['affiliate_id'] =  wp_filter_nohtml_kses($input['affiliate_id']); // Sanitize textbox input (strip html tags, and escape characters)
	if (trim($input['affiliate_id']) == '') {
	  add_settings_error( "affiliately_options", "affiliate_id", "The affiliate ID can't be empty", $type = 'error' );
	}
	return $input;
}

add_filter( 'plugin_action_links', 'affiliately_plugin_action_links', 10, 2 );
// Display a Settings link on the main Plugins page
function affiliately_plugin_action_links( $links, $file ) {
  if ( $file == plugin_basename( dirname(__FILE__).'/affiliately.php' ) ) {
		$links[] = '<a href="options-general.php?page=affiliately-settings">'.__('Settings').'</a>';
	}
	return $links;
}

?>
