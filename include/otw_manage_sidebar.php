<?php
/** Create/edit otw sidebar
  *
  */
	global $wp_registered_sidebars, $validate_messages, $wp_int_items;
	
	$otw_sidebar_values = array(
		'sbm_title'              =>  '',
		'sbm_description'        =>  '',
		'sbm_replace'            =>  '',
		'sbm_status'             =>  'active',
		'sbm_widget_alignment'   =>  'vertical'
	);
	
	$otw_sidebar_id = '';
	
	$page_title = __( 'Create New Sidebar' );

	if( isset( $_GET['sidebar'] ) ){
		
		$otw_sidebar_id = $_GET['sidebar'];
		$otw_sidebars = get_option( 'otw_sidebars' );
		
		if( is_array( $otw_sidebars ) && isset( $otw_sidebars[ $otw_sidebar_id ] ) && ( $otw_sidebars[ $otw_sidebar_id ]['replace'] != '' ) ){
			
			$otw_sidebar_values['sbm_title'] = $otw_sidebars[ $otw_sidebar_id ]['title'];
			$otw_sidebar_values['sbm_description'] = $otw_sidebars[ $otw_sidebar_id ]['description'];
			$otw_sidebar_values['sbm_replace'] = $otw_sidebars[ $otw_sidebar_id ]['replace'];
			$otw_sidebar_values['sbm_status'] = $otw_sidebars[ $otw_sidebar_id ]['status'];
			if( isset( $otw_sidebars[ $otw_sidebar_id ]['widget_alignment'] ) ){
				$otw_sidebar_values['sbm_widget_alignment'] = $otw_sidebars[ $otw_sidebar_id ]['widget_alignment'];
			}
			$otw_sidebar_values['sbm_validfor'] = $otw_sidebars[ $otw_sidebar_id ]['validfor'];
			$page_title = __( 'Edit Sidebar' );
		}
	}
	//apply post values
	if( isset( $_POST['otw_action'] ) ){
		foreach( $otw_sidebar_values as $otw_field_key => $otw_field_default_value ){
			if( isset( $_POST[ $otw_field_key ] ) ){
				$otw_sidebar_values[ $otw_field_key ] = $_POST[ $otw_field_key ];
			}
		}
	}

	foreach( $wp_int_items as $wp_item_type => $wp_item_data ){
		$wp_int_items[ $wp_item_type ][0] = otw_get_wp_items( $wp_item_type );
	}
	
/** set class name of each item block
  *  @param array
  *  @return void
  */
function otw_sidebar_block_class( $item_type, $sidebar_data ){
	
	if( isset( $_POST['otw_action'] ) ){
		if( !isset( $_POST[ 'otw_sbi_'.$item_type ] ) || !count( $_POST[ 'otw_sbi_'.$item_type ] ) ){
			echo ' open';
		}
	}else{
		if( !isset( $sidebar_data['sbm_validfor'][ $item_type ] ) || !count( $sidebar_data['sbm_validfor'][ $item_type ] ) ){
			echo ' open';
		}
	}
}
/** set html ot each item row
  *  @param string 
  *  @param string 
  *  @param string
  *  @param array
  *  @return void
  */
function otw_sidebar_item_attributes( $tag, $item_type, $item_id, $sidebar_data, $item_data ){

	$attributes = '';
	
	switch( $tag ){
		case 'p':
				$attributes_array = array();
				if( isset( $_POST['otw_action'] ) ){
					if( isset( $_POST[ 'otw_sbi_'.$item_type ][ $item_id ] ) || isset( $_POST[ 'otw_sbi_'.$item_type ][ 'all' ] ) ){
						$attributes_array['class'][] = 'sitem_selected';
					}else{
						$attributes_array['class'][] = 'sitem_notselected';
					}
				}else{
					if( isset( $sidebar_data['sbm_validfor'][ $item_type ]['all'] ) ){
						$attributes_array['class'][] = 'sitem_selected';
					}elseif( isset( $sidebar_data['sbm_validfor'][ $item_type ][ $item_id ] ) ){
						$attributes_array['class'][] = 'sitem_selected';
					}else{
						$attributes_array['class'][] = 'sitem_notselected';
					}
				}
				if( isset( $attributes_array['class'] ) ){
					$attributes .= ' class="'.implode( ' ', $attributes_array['class'] ).'"';
				}
			break;
		case 'c':
				if( isset( $_POST['otw_action'] ) ){
					if( isset( $_POST[ 'otw_sbi_'.$item_type ][ $item_id ] )  || isset( $_POST[ 'otw_sbi_'.$item_type ][ 'all' ] ) ){
						$attributes .= ' checked="checked"';
					}
				}else{
					if( isset( $sidebar_data['sbm_validfor'][ $item_type ]['all'] ) ){
						$attributes .= ' checked="checked"';
					}elseif( isset( $sidebar_data['sbm_validfor'][ $item_type ][ $item_id ] ) ){
						$attributes .= ' checked="checked"';
					}
				}
			break;
		case 'ap':
				if( isset( $_POST['otw_action'] ) ){
					if( isset( $_POST[ 'otw_sbi_'.$item_type ][ $item_id ] ) ){
						$attributes .= ' class="all sitem_selected"';
					}else{
						$attributes .= ' class="all sitem_notselected"';
					}
				}else{
					if( isset( $sidebar_data['sbm_validfor'][ $item_type ][ $item_id ] ) ){
						$attributes .= ' class="all sitem_selected"';
					}else{
						$attributes .= ' class="all sitem_notselected"';
					}
				}
			break;
		case 'ac':
				if( isset( $_POST['otw_action'] ) ){
					if( isset( $_POST[ 'otw_sbi_'.$item_type ][ $item_id ] ) ){
						$attributes .= ' checked="checked"';
					}
				}else{
					if( isset( $sidebar_data['sbm_validfor'][ $item_type ][ $item_id ] ) ){
						$attributes .= ' checked="checked"';
					}
				}
			break;
		case 'l':
				if( isset( $item_data->_sub_level ) && $item_data->_sub_level ){
					$attributes .= ' style="margin-left: '.( $item_data->_sub_level * 20 ).'px"';
				}
			break;
	}
	echo $attributes;
}
?>
<div class="updated"><p>Check out the <a href="http://otwthemes.com/online-documentation-sidebar-manager-light/?utm_source=wp.org&utm_medium=admin&utm_content=docs&utm_campaign=sml">Online documentation</a> for this plugin<br /><br /> 
Upgrade to the full version of <a href="http://otwthemes.com/product/sidebar-widget-manager-for-wordpress/?utm_source=wp.org&utm_medium=admin&utm_content=upgrade&utm_campaign=sml">Sidebar and Widget Manager</a> | <a href="http://otwthemes.com/demos/sidebar-widget-manager/?utm_source=wp.org&utm_medium=admin&utm_content=upgrade&utm_campaign=sml">Demo site</a> | <a href="http://www.youtube.com/watch?v=WT9UK1eX4C8">Video overview</a><br /><br />
Follow on <a href="http://twitter.com/OTWthemes">Twitter</a> | <a href="http://www.facebook.com/pages/OTWthemes/250294028325665">Facebook</a> | <a href="http://www.youtube.com/OTWthemes">YouTube</a> | <a href="https://plus.google.com/117222060323479158835/about">Google+</a></p></div>
<div class="wrap">
	<div id="icon-edit" class="icon32"><br/></div>
	<h2>
		<?php echo $page_title; ?>
		<a class="button add-new-h2" href="admin.php?page=otw-sml">Back To Available Sidebars</a>
	</h2>
	<?php if( isset( $validate_messages ) && count( $validate_messages ) ){?>
		<div id="message" class="error">
			<?php foreach( $validate_messages as $v_message ){
				echo '<p>'.$v_message.'</p>';
			}?>
		</div>
	<?php }?>
	<div class="form-wrap" id="poststuff">
		<form method="post" action="" class="validate">
			<input type="hidden" name="otw_sml_action" value="manage_otw_sidebar" />
			<?php wp_original_referer_field(true, 'previous'); wp_nonce_field('otw-sbm-manage'); ?>

			<div id="post-body">
				<div id="post-body-content">
					<div id="col-right">
						<?php if( is_array( $wp_int_items ) && count( $wp_int_items ) ){?>
						
							<?php foreach( $wp_int_items as $wp_item_type => $wp_item_data ){?>
							
								<div class="meta-box-sortables">
									<div class="postbox">
										<div title="<?php _e('Click to toggle')?>" class="handlediv sitem_toggle"><br></div>
										<h3 class="hndle sitem_header"><span><?php echo $wp_item_data[1]?></span></h3>
										
										<div class="inside sitems<?php otw_sidebar_block_class( $wp_item_type, $otw_sidebar_values )?>">
											
											<p<?php otw_sidebar_item_attributes( 'ap', $wp_item_type, 'all', $otw_sidebar_values, array() )?>>
												<input type="checkbox" id="otw_sbi_<?php echo $wp_item_type?>_all" name="otw_sbi_<?php echo $wp_item_type?>[all]"<?php otw_sidebar_item_attributes( 'ac', $wp_item_type, 'all', $otw_sidebar_values, array() )?> value="all" /><label for="otw_sbi_<?php echo $wp_item_type?>_all"><a href="javascript:;"><?php echo $wp_item_data[2]?></a></label>
											</p>
											
											<?php if( is_array( $wp_item_data[0] ) && count( $wp_item_data[0] ) ){?>
											
												<?php foreach( $wp_item_data[0] as $wpItem ) {?>
													<p<?php otw_sidebar_item_attributes( 'p', $wp_item_type, otw_wp_item_attribute( $wp_item_type, 'ID', $wpItem ), $otw_sidebar_values, $wpItem )?>>
														<input type="checkbox" id="otw_sbi_<?php echo $wp_item_type?>_<?php echo otw_wp_item_attribute( $wp_item_type, 'ID', $wpItem ) ?>"<?php otw_sidebar_item_attributes( 'c', $wp_item_type, otw_wp_item_attribute( $wp_item_type, 'ID', $wpItem ), $otw_sidebar_values, array() )?> value="<?php echo otw_wp_item_attribute( $wp_item_type, 'ID', $wpItem ) ?>" name="otw_sbi_<?php echo $wp_item_type?>[<?php echo otw_wp_item_attribute( $wp_item_type, 'ID', $wpItem ) ?>]" /><label for="otw_sbi_<?php echo $wp_item_type?>_<?php echo otw_wp_item_attribute( $wp_item_type, 'ID', $wpItem ) ?>"<?php otw_sidebar_item_attributes( 'l', $wp_item_type, otw_wp_item_attribute( $wp_item_type, 'ID', $wpItem ), $otw_sidebar_values, $wpItem )?> ><a href="javascript:;"><?php echo otw_wp_item_attribute( $wp_item_type, 'TITLE', $wpItem ) ?></a></label>
													</p>	
												<?php }?>
											<?php }else{ echo '&nbsp;'; }?>
										</div>
									</div>
								</div>
								
							<?php }?>
							
						<?php } ?>
					</div>
					<div id="col-left">
						<div class="form-field form-required">
							<label for="sbm_title"><?php _e( 'Sidedabar title' );?></label>
							<input type="text" id="sbm_title" value="<?php echo $otw_sidebar_values['sbm_title']?>" tabindex="1" size="30" name="sbm_title"/>
							<p><?php _e( 'The name is how it appears on your site.' );?></p>
						</div>
						<?php if( is_array( $wp_registered_sidebars ) && count( $wp_registered_sidebars ) ){?>
						<div class="form-field">
							<label for="sbm_replace"><?php _e( 'Replace Existing SideBar' );?></label>
							<select id="sbm_replace" tabindex="3" style="width: 270px;" name="sbm_replace">
								<?php foreach( $wp_registered_sidebars as $lp_wp_sidebar_id => $lp_wp_sidebar ){?>
									<?php if( !preg_match( '/^otw\-sidebar\-/', $lp_wp_sidebar_id ) && ( $otw_sidebar_id != $lp_wp_sidebar_id ) ){?>
										<?php
											$selected = '';
											if( $otw_sidebar_values['sbm_replace'] == $lp_wp_sidebar_id ){
												$selected = ' selected="selected"';
											}
										?>
										<option value="<?php echo $lp_wp_sidebar_id?>"<?php echo $selected?>><?php echo $lp_wp_sidebar['name']?></option>
									<?php }?>
									
								<?php }?>
							</select>
							<p><?php _e( 'Replace existing sidebar. It will be replaced only for selected templates from the right column.' );?></p>
						</div>
						<?php }?>
						<div class="form-field">
							<label for="sbm_description"><?php _e( 'Description' )?></label>
							<textarea id="sbm_description" name="sbm_description" tabindex="4" rows="3" cols="10"><?php echo $otw_sidebar_values['sbm_description']?></textarea>
							<p><?php _e( 'Short description for your reference at the admin panel.')?></p>
						</div>
						<p class="submit">
							<input type="submit" value="<?php _e( 'Save Sidebar') ?>" name="submit" class="button"/>
						</p>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>