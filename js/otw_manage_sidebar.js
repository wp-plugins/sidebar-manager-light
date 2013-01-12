jQuery(document).ready(function() {
	init_manage_page();
});
function init_manage_page(){
	
	var s_labels = jQuery( '.sitems label' ).click(  function( event ){
		event.preventDefault();
		select_sitem( this );
	} );
	
	jQuery('#col-right').find('.sitem_toggle').click(function() {
		jQuery(this).parent().find( '.inside').toggleClass('otw_closed');
	});
	
};
function select_sitem( param, force, force_value ){
	
	var label = jQuery( param );
	var block = label.parent();
	var input = block.find( 'input' );
	
	if( force ){
		if( force_value ){
			input.attr( 'checked', true );
			block.removeClass( 'sitem_notselected' );
			block.addClass( 'sitem_selected' );
		}else{
			input.attr( 'checked', false );
			block.removeClass( 'sitem_selected' );
			block.addClass( 'sitem_notselected' );
		}
	}else{
		if( !input.attr( 'checked' ) ){
			input.attr( 'checked', true );
			block.removeClass( 'sitem_notselected' );
			block.addClass( 'sitem_selected' );
		}else{
			input.attr( 'checked', false );
			block.removeClass( 'sitem_selected' );
			block.addClass( 'sitem_notselected' );
		}
		
		
		if( input.attr( 'id' ).match( /^otw_sbi_(.*)_all$/ ) ){
			input.parent().parent().find( 'label' ).each( function(){
				if( !jQuery( this ).attr( 'for' ).match( /^otw_sbi_(.*)_all$/ ) ){
					select_sitem( this, true, input.attr( 'checked' ) );
				}
			} );
		}else{
			input.parent().parent().find( 'label' ).each( function(){
				if( jQuery( this ).attr( 'for' ).match( /^otw_sbi_(.*)_all$/ ) ){
					select_sitem( this, true, false );
					return;
				}
			} );
		}
	}
}