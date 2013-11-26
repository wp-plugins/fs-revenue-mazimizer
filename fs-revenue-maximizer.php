<?php
/*
Plugin Name: FS Revenue Maximizer
Plugin URI: http://www.flamescorpion.com
Description: Adds your adsense or any other ads inside your content ( after the first or second pharagraph ), enabling you to increase your revenue 10 times. 
Author: Lucian Apostol
Version: 1.0.1
Author URI: http://www.flamescorpion.com
*/


add_action( 'admin_menu', 'fsrm_plugin_menu' );
add_action( 'admin_init', 'fsrm_register_settings' );


function fsrm_register_settings() { // whitelist options
  register_setting( 'fsrm-settings', 'fsrm-pharagraph' );
  register_setting( 'fsrm-settings', 'fsrm-adcode' );
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
		Put ad code here: <br /> <textarea name="fsrm-adcode" cols="50" rows="5"><?php echo get_option('fsrm-adcode'); ?></textarea>



<?php
	submit_button('Save');
	echo '</form></div>';
}



//Start the adding


 add_filter( 'the_content', 'fsrm_ad_placement' );


function fsrm_ad_placement($content) {

	$adcode = get_option('fsrm-adcode');
	$pid = get_option('fsrm-pharagraph');



	global $adadded;
	if(!$adadded && is_main_query()) {
		$para = explode("</p>", $content, $pid+1);
		// var_dump($para); // debug
		$content = $para[0].'</p>';
		if($pid == "1") $content .= '<p>'. $adcode .'</p>';
		if($pid == "2") { 
			$content .= $para[1].'</p>';
			$content .= '<p>'. $adcode .'</p>';
		}
		if (!empty($para[$pid])) {
   	 		$content .= $para[$pid];
		}
		
		$adadded = 1;
	}
	

	return $content;
}







?>