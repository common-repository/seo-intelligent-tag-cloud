<?php
/*  
	Plugin Name: SEO Intelligent Tag Cloud
	Description: SEO Intelligent Tag Cloud is the best plug-in for list of tags in what is called a 'tag cloud'. The tag is added only when they reach a minimum number of posts attributed to him, this number is defined by the user (customizable). This helps your ranking on search engines  because they are not  generated  within  a few  pages  with content tags. Search engines will thank you!
	Version: 1.0
	Author: Dechigno (Prima Posizione Srl)
	Author URI: http://www.prima-posizione.it/

*/
define( "TITLE_PR_LONG" , "SEO Intelligent Tag Cloud");
define( "TITLE_PR_SHORT", "SEOIntelligentTagCloud");
define( "POWERED_BY"	, "Gb-rugs.com - Rugs & Carpets");
define( "LINK_ADMIN"	, "http://www.gb-rugs.com/index.asp?lang=uk");
define( "NAME_DIR"		, "seo-intelligent-tag-cloud");

/************************************************************/
/*		admin_init is triggered before any other hook 		*/
/*			when a user access the admin area.  			*/
/*		  This hook doesn't provide any parameters,			*/
/*			 so it can only be used to callback				*/
/*		  		   a specified function. 					*/
/************************************************************/
if ( ! defined( 'WP_CONTENT_URL' ) )
	define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_URL' ) )
	define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
	define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );		

require( WP_PLUGIN_DIR . '/'.NAME_DIR.'/widget.php' );
wp_enqueue_style('css',  WP_PLUGIN_URL . '/'.NAME_DIR.'/css/style.css');
function wtc_admin_init() {
	global $current_user;
	get_currentuserinfo();
	if ($current_user->user_level <  8) { //if not admin, die with message
		wp_die( __('You are not allowed to access this part of the site') );
	}
}
add_action('admin_init', 'wtc_admin_init');

// Custom Meta Box
function wtc_add_custom_box() {
	if( function_exists( 'add_meta_box' )) {
		add_meta_box( 'wtc_sidebar', __( TITLE_PR_LONG, 'myplugin_textdomain' ), 'wtc_inner_custom_box', 'post', 'side', 'high' );
	}
}
function wtc_inner_custom_box() {
	// Use nonce for verification
	echo '<p style="text-align: center" id="loading_pr"><img src="'.WP_PLUGIN_URL . '/postrelated/ajax-loader.gif" alt="Loading" /></p><div id="wtc_sidebar"><ul></ul></div>';
}
/************************************************************/
/*		This action is used to add extra submenus	 		*/
/*		  and menu options to the admin panel's 	 		*/
/*		 			menu structure.							*/
/*			It runs after the basic admin panel				*/
/*		  		menu structure is in place.					*/
/************************************************************/
function wtc_config_page() {
	if ( function_exists( 'add_submenu_page' ) )
		add_submenu_page( 'plugins.php', __(TITLE_PR_SHORT.' Configuration'), __(TITLE_PR_SHORT.' Configuration'), 'manage_options', 'postrelated', 'wtc_wp_admin' );
}
function wtc_wp_admin() {
	global $wp_version;
?>
<div class="updated"><p><strong><?php _e('Configurazione Salvata', 'postrelated' ); ?></strong></p></div>
<?php

	// Now display the options editing screen
	echo '<div class="wrap">';

	// header
	echo "<h2>" . __( 'Configurazione Plugin ' . TITLE_PR_LONG, 'postrelated' ) . "</h2>";
	if( isset( $_POST["wtc_min_post"] ) ){
		$api_key_pr = $_POST["wtc_min_post"];
		update_option('wtc_min_post', $api_key_pr);
	}
	
	// options form
	?>
	<form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
        <p>Configurazione <?php echo TITLE_PR_LONG?>.</p>
        <p><?php _e('Numero minimo di post per visualizzare il Tag all\'interno del Widget:', 'wtc_min_post' ); ?>
			<input type="text" name="wtc_min_post" value="<?php echo get_option('wtc_min_post'); ?>" size="2">
		</p>
		<p class="submit">
			<input type="submit" name="Submit" value="<?php _e('Salva', 'wtc_submit' ) ?>" />
		</p>
	</form>
</div>
<?php
}
    
/************************************************************/
/*  This action admin_menu is used to add extra submenus 	*/
/*		   and menu options to the admin panel's	 		*/
/*		 	menu structure. It runs after the				*/
/*				 basic admin panel menu 			 		*/
/*			     structure is in place. 			 		*/
/************************************************************/
global $wp_version;
if ( substr( $wp_version, 0 ,3 ) >= '2.7') {
	require_once(ABSPATH . '/wp-includes/pluggable.php');
	//add_action('admin_menu', 'wtc_add_custom_box');
}
//add_action( 'admin_menu', 'wtc_config_page' );


/* Function that registers our widget. */
function wtc_load_widgets() {
	register_widget( 'WpTagCloud' );
}
add_action( 'widgets_init', 'wtc_load_widgets' );

/************************************************************/
/*			The function register_activation_hook 	 		*/
/*		  		(introduced in WordPress 2.0)	 			*/
/*		 	  registers a plugin function to be				*/
/*			   run when the plugin is activated. 			*/
/************************************************************/
function wtc_activate() {}

/************************************************************/
/*			The function register_deactivation_hook	 		*/
/*		  		(introduced in WordPress 2.0)	 			*/
/*		 	  registers a plugin function to be				*/
/*			   run when the plugin is deactivated. 			*/
/************************************************************/

function wtc_deactivate() {}

/************************************************************/
/*	  The wp_footer action is triggered near the </body> 	*/
/*	     tag of the user's template by the wp_footer()	 	*/
/*		  function. Although this is theme-dependent,		*/
/*		    it is one of the most essential theme  			*/
/*		   hooks, so it is fairly widely supported. 		*/
/************************************************************/
function wtc_footer(){
	echo '<p style="text-align: center; font-size:10px">Powered by <a href="'.LINK_ADMIN.'" target="_blank">'.POWERED_BY.'</a></p>';
}
add_action( 'wp_footer', 'wtc_footer' );
add_action( 'wtc_active', 'wtc_activate' );
add_action( 'wtc_deactive', 'wtc_deactivate' );
register_activation_hook(__FILE__, 'wtc_activate');
register_deactivation_hook(__FILE__, 'wtc_deactivate');
?>