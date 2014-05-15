<?php
/*
Plugin Name: FS Revenue Maximizer
Plugin URI: http://www.flamescorpion.com
Description: Adds your adsense or any other ads inside your content ( after the first or second pharagraph ), enabling you to increase your revenue 10 times. 
Author: Lucian Apostol
Version: 1.2
Author URI: http://www.flamescorpion.com
*/


add_action( 'admin_menu', 'fsrm_plugin_menu' );
add_action( 'admin_init', 'fsrm_register_settings' );


function fsrm_register_settings() { // whitelist options
  register_setting( 'fsrm-settings', 'fsrm-pharagraph' );
  register_setting( 'fsrm-settings', 'fsrm-alignment' );
  register_setting( 'fsrm-settings', 'fsrm-adcode' );
  register_setting( 'fsrm-settings', 'fsrm-tryagain' );
}



function fsrm_plugin_menu() {
	add_options_page( 'FS Revenue Maximizer', 'FS Revenue Maximizer', 'manage_options', 'fs-revenue-mazimizer', 'fsrm_plugin_options' );
}


function fsrm_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	
	echo '<div class="wrap">';
?>		<?php screen_icon(); ?>
		<h2>FS Revenue Mazimizer</h2>
		<br /><br />
		<form method="post" action="options.php"> 
<?php
		settings_fields( 'fsrm-settings' );
		do_settings_sections('fsrm-settings_display');
		
?>

		Where ad will be displayed: <select name="fsrm-pharagraph">
			<option value="1" <?php if(get_option('fsrm-pharagraph')=='1') echo "selected"; ?> >After first pharagraph</option>
			<option value="2"<?php if(get_option('fsrm-pharagraph')=='2') echo "selected"; ?>>After second pharagraph</option>
		</select><br /><br />
		Alignment: <select name="fsrm-alignment">
			<option value="" <?php if(get_option('fsrm-alignment')=='') echo "selected"; ?> > None</option>
			<option value="center" <?php if(get_option('fsrm-alignment')=='center') echo "selected"; ?> >Center</option>
		</select><br /><br />
		Put ad code here: <br /> <textarea name="fsrm-adcode" cols="70" rows="15"><?php echo get_option('fsrm-adcode'); ?></textarea><br /><br />
		Compatibility feature: <select name="fsrm-tryagain">
			<option value="1" <?php if(get_option('fsrm-tryagain')=='1') echo "selected"; ?> >First option</option>
			<option value="2"<?php if(get_option('fsrm-tryagain')=='2') echo "selected"; ?>>Second option</option>
		</select><br />
		Some plugins or theme features might prevent this plugin from working correctly. If the ad does not appear on your post pages with the default option ( First Option ), then select "Second Option". Be aware that selecting Second Option might display the ad in the first 2 articles on the homepage and can cause adsense policy violations. Do not select Second Option unless First option does not work. 		
		<br /><br />



<?php
	submit_button('Save');
	echo '</form></div>';
}



//Start the adding


 add_filter( 'the_content', 'fsrm_ad_placement' );


function fsrm_ad_placement($content) {

	$adcode = get_option('fsrm-adcode');
	$pid = get_option('fsrm-pharagraph');
	$tryagain = get_option('fsrm-tryagain');
	$alignment = get_option('fsrm-alignment');
	
	
	
	global $adadded;
	global $next;
	
	
	
	if(!$adadded && is_main_query()) {
		
		if($next) $adadded = 1;
		$next = 1;		
		
		if($tryagain <= 1) $adadded = 1;
		
				
		if($adadded) {
		$para = explode("</p>", $content, $pid+1);
		// var_dump($para); // debug
		$content = $para[0].'</p>';
		if($pid == "1") $content .= '<p style="text-align:'. $alignment .';" >'. $adcode .'</p>';
		if($pid == "2") { 
			$content .= $para[1].'</p>';
			$content .= '<p style="align:'. $alignment .';margin: auto;" >'. $adcode .'</p>';
		}
		if (!empty($para[$pid])) {
   	 		$content .= $para[$pid];
		}
		}
		

	}
	

	return $content;
}







?>