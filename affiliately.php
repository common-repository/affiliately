<?php
/**
 * @package Affiliately
 */
/*
Plugin Name: Affiliately
Plugin URI: http://affiliate.ly/
Description: Affiliately converts keywords in your blog posts into affiliate links (containing your affiliate ID). It also follows users that leave your blog and displays a top frame with your affiliate links on the destination website. Go to <a href="options-general.php?page=affiliately-settings">Affiliately settings</a> page to set your affiliate ID and marketplace.
Version: 1.0.0
Author: Affiliately
Author URI: http://affiliate.ly/
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

define('AFFILIATELY_VERSION', '1.0.0');

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo "Hi there! I'm just a plugin, not much I can do when called directly.";
	exit;
}

if ( is_admin() ) require_once dirname( __FILE__ ) . '/admin.php';

add_action('wp_footer', 'affiliately_wp_footer');

register_activation_hook(__FILE__, 'affiliately_add_defaults');
register_uninstall_hook(__FILE__, 'affiliately_delete_plugin_options');

// Add Affiliately JS code at the bottom of the page 
function affiliately_wp_footer() {
  $options = get_option('affiliately_options');
  
  if (trim($options['affiliate_id']) != '') {
    $preview          = $options['show_tooltip'] == '1' ? 'true' : 'false';
    $doubleUnderline  = $options['use_double_underline'] == '1' ? 'true' : 'false';
    $newWindow        = $options['new_window'] == '1' ? 'true' : 'false';
    
    echo <<<AFFILIATELY_SCRIPT
<script type="text/javascript">
  var _affiliately = {
    aaID: '{$options['affiliate_id']}', aaMarketplace: '{$options['marketplace']}',
    preview: {$preview}, newWindow: {$newWindow}, doubleUnderline: {$doubleUnderline}
  };
  (function() {
    var e = document.createElement('script'); e.type = 'text/javascript'; e.async = true;
    e.src = 'http://js.affiliately.com/show_links.min.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(e, s);
  })();
</script>

AFFILIATELY_SCRIPT;
	}
}

// Define default option settings
function affiliately_add_defaults() {
	$tmp = get_option('affiliately_options');
  if (($tmp['chk_default_options_db'] == '1') || (!is_array($tmp))) {
		delete_option('affiliately_options'); // so we don't have to reset all the 'off' checkboxes too! (don't think this is needed but leave for now)
		update_option('affiliately_options', array(	
		  "affiliate_id" => "",
  		"marketplace" => "com",
    	"show_tooltip" => "1",
    	"use_double_underline" => "1",
    	"new_window" => "0",
    	"default_options_db" => ""
		));
	}
}

// Delete options table entries ONLY when plugin deactivated AND deleted
function affiliately_delete_plugin_options() {
	delete_option('affiliately_options');
}

?>
