<?php
/** init plugin
  *
  */
function otw_sml_plugin_init(){
	
	global $wp_registered_sidebars, $otw_replaced_sidebars, $wp_sml_int_items, $otw_sml_plugin_url;
	
	$otw_registered_sidebars = get_option( 'otw_sidebars' );
	$otw_widget_settings = get_option( 'otw_widget_settings' );
	
	if( !is_array( $otw_widget_settings ) ){
		$otw_widget_settings = array();
		update_option( 'otw_widget_settings', $otw_widget_settings );
	}
	
	otw_sml_sidebar_add_items();
	
	if( is_array( $otw_registered_sidebars ) && count( $otw_registered_sidebars ) ){
		
		foreach( $otw_registered_sidebars as $otw_sidebar_id => $otw_sidebar ){
			
			$sidebar_params = array();
			$sidebar_params['id']  = $otw_sidebar_id;
			$sidebar_params['name']  = $otw_sidebar['title'];
			$sidebar_params['description']  = $otw_sidebar['description'];
			$sidebar_params['replace']  = $otw_sidebar['replace'];
			$sidebar_params['status']  = $otw_sidebar['status'];
			if( isset( $otw_sidebar['widget_alignment'] ) ){
				$sidebar_params['widget_alignment']  = $otw_sidebar['widget_alignment'];
			}
			$sidebar_params['validfor']  = $otw_sidebar['validfor'];
			
			//collect all replacements for faster search in font end
			if( strlen( $sidebar_params['replace'] ) ){
			
				if( !isset( $otw_replaced_sidebars[ $sidebar_params['replace'] ] ) ){
					$otw_replaced_sidebars[ $sidebar_params['replace'] ] = array();
				}
				$otw_replaced_sidebars[ $sidebar_params['replace'] ][ $sidebar_params['id'] ] = $sidebar_params['id'];
				
				if( isset( $wp_registered_sidebars[ $sidebar_params['replace'] ] ) ){
					if( isset( $wp_registered_sidebars[ $sidebar_params['replace'] ]['class'] ) ){
						$sidebar_params['class'] = $wp_registered_sidebars[ $sidebar_params['replace'] ]['class'];
					}
					if( isset( $wp_registered_sidebars[ $sidebar_params['replace'] ]['before_widget'] ) ){
						$sidebar_params['before_widget'] = $wp_registered_sidebars[ $sidebar_params['replace'] ]['before_widget'];
					}
					if( isset( $wp_registered_sidebars[ $sidebar_params['replace'] ]['after_widget'] ) ){
						$sidebar_params['after_widget'] = $wp_registered_sidebars[ $sidebar_params['replace'] ]['after_widget'];
					}
					if( isset( $wp_registered_sidebars[ $sidebar_params['replace'] ]['before_title'] ) ){
						$sidebar_params['before_title'] = $wp_registered_sidebars[ $sidebar_params['replace'] ]['before_title'];
					}
					if( isset( $wp_registered_sidebars[ $sidebar_params['replace'] ]['after_title'] ) ){
						$sidebar_params['after_title'] = $wp_registered_sidebars[ $sidebar_params['replace'] ]['after_title'];
					}
				}
				
			}else{
				$sidebar_params['before_widget'] = '';
				$sidebar_params['after_widget']  = '';
			}
			
			register_sidebar( $sidebar_params );
		}
	}
	
	//apply validfor settings to all sidebars
	if( is_array( $wp_registered_sidebars ) && count( $wp_registered_sidebars ) ){
		foreach( $wp_registered_sidebars as $wp_widget_key => $wo_widget_data ){
		
			if( array_key_exists( $wp_widget_key, $otw_widget_settings ) ){
				$wp_registered_sidebars[ $wp_widget_key ]['widgets_settings'] = $otw_widget_settings[ $wp_widget_key ];
			}else{
				$wp_registered_sidebars[ $wp_widget_key ]['widgets_settings'] = array();
			}
		}
	}
	
	
	if( is_admin() ){
		require_once( plugin_dir_path( __FILE__ ).'/otw_process_actions.php' );
	}else{
		wp_register_style('otw_sbm.css', $otw_sml_plugin_url.'/css/otw_sbm.css'  );
		wp_enqueue_style('otw_sbm.css');
	}
}

function otw_sml_admin_notice(){
	$plugin_error = get_option( 'otw_sml_plugin_error' );
	
	if( $plugin_error ){
		echo '<div class="error"><p>';
		echo 'Sidebar Manager Light Plugin Error: '.$plugin_error;
		echo '</p></div>';
	}
}

/**
 * Add more items based on installed plugins etc.
 */
function otw_sml_sidebar_add_items(){
	
	global $wp_sml_int_items;
	
	//wpml
	$active_plugins = get_settings( 'active_plugins' );
	
	if( in_array( 'sitepress-multilingual-cms/sitepress.php', $active_plugins ) && function_exists( 'icl_get_languages' ) ){
		$wp_sml_int_items['wpmllanguages'] = array();
		$wp_sml_int_items['wpmllanguages'][0] = array();
		$wp_sml_int_items['wpmllanguages'][1] = __( 'WPML plugin language', 'otw_sbm' );
		$wp_sml_int_items['wpmllanguages'][2] = __( 'All WPML plugin languages', 'otw_sbm' );
	}
}

require_once( plugin_dir_path( __FILE__ ).'otw_sbm_core.php' );
?>