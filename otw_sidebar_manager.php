<?php

/**
Plugin Name: Sidebar Manager Light
Plugin URI: http://otwthemes.com/?utm_source=wp.org&utm_medium=admin&utm_content=site&utm_campaign=sml
Description:  Create custom sidebars (widget areas) and replace any existing sidebar so you can display relevant content on different pages.
Author: OTWthemes.com
Version: 1.0
Author URI: http://otwthemes.com/?utm_source=wp.org&utm_medium=admin&utm_content=site&utm_campaign=sml
*/
$wp_int_items = array(
	'page'              => array( array(), __( 'Pages' ), __( 'All pages' ) )
);

global $otw_plugin_options;

$otw_plugin_options = get_option( 'otw_plugin_options' );

include_once( plugin_dir_path( __FILE__ ).'/include/otw_plugin_activation.php' );
require_once( plugin_dir_path( __FILE__ ).'/include/otw_functions.php' );

/** calls list of available sidebars
  *
  */
function otw_sml_sidebars_list(){
	if( isset( $_GET['action'] ) && $_GET['action'] == 'edit' ){
		require_once( 'include/otw_manage_sidebar.php' );
	}else{
		require_once( 'include/otw_list_sidebars.php' );
	}
}

/** calls page where to create new sidebars
  *
  */
function otw_sml_sidebars_manage(){;
	require_once( 'include/otw_manage_sidebar.php' );
}

/** delete sidebar
  *
  */
function otw_sml_sidebars_action(){
	require_once( 'include/otw_sidebar_action.php' );
}


/** plugin info
  *
  */
function otw_sml_info(){
	require_once( 'include/otw_sidebar_info.php' );
}

/** admin menu actions
  * add the top level menu and register the submenus.
  */ 
function otw_sml_admin_actions(){
	
	add_menu_page('Sidebar Manager', 'Sidebar Manager', 'manage_options', 'otw-sml', 'otw_sml_sidebars_list', plugins_url( 'otw_sml/images/application_side_boxes.png' ) );
	add_submenu_page( 'otw-sml', 'Sidebars', 'Sidebars', 'manage_options', 'otw-sml', 'otw_sml_sidebars_list' );
	add_submenu_page( 'otw-sml', 'Add Sidebar', 'Add Sidebar', 'manage_options', 'otw-sml-add', 'otw_sml_sidebars_manage' );
	add_submenu_page( 'otw-sml', 'Info', 'Info', 'manage_options', 'otw-sml-info', 'otw_sml_info' );
	add_submenu_page( __FILE__, 'Manage widget', 'Manage widget', 'manage_options', 'otw-sml-action', 'otw_sml_sidebars_action' );
}


/** include needed javascript scripts based on current page
  *  @param string
  */
function enqueue_sml_scripts( $requested_page ){

	switch( $requested_page ){
	
		case 'toplevel_page_otw-sml':
		case 'sidebar-manager_page_otw-sml-add':
				wp_enqueue_script("otw_sml_manage_sidebar", plugins_url('otw_sml/js/otw_manage_sidebar.js' ) , array( 'jquery' ), '1.1' );
			break;
	}
}

/**
 * include needed styles
 */
function enqueue_sml_styles( $requested_page ){
	wp_enqueue_style( 'otw_sml_sidebar', plugins_url('otw_sml/css/otw_sbm_admin.css'), array( 'thickbox' ), '1.1' );
}

/**
 * register admin menu 
 */
add_action('admin_menu', 'otw_sml_admin_actions');
add_action('admin_notices', 'otw_sml_admin_notice');

/**
 * include plugin js and css.
 */
add_action('admin_enqueue_scripts', 'enqueue_sml_scripts');
add_action('admin_print_styles', 'enqueue_sml_styles' );

/** 
 *call init plugin function
 */
add_action('init', 'otw_sml_plugin_init', 100 );

include_once( plugin_dir_path( __FILE__ ).'/include/otw_plugin_activation.php' );

register_activation_hook(  __FILE__,'otw_sml_plugin_activate');
register_deactivation_hook(  __FILE__,'otw_sml_plugin_deactivate');
?>
