jQuery(document).ready(function($){
	$('input[type=checkbox]').lc_switch();
	
	$( "#auto_rotate_speed_slider" ).slider({
		  range: "min",
		  value: $('#auto_rotate_speed').val(),
		  min: 1000,
		  max: 10000,
		  slide: function( event, ui ) {
			$( "#auto_rotate_speed" ).val( ui.value );
		  }
		});
		$( "#auto_rotate_speed" ).val( $( "#auto_rotate_speed_slider" ).slider( "value" ) );
		
		 $( "#transition_type" ).buttonset();
		 
		 $(document).on('click','#transition_type input',function(){
				$('#transition_type input').attr('checked',false);
				$(this).attr('checked',true);
		});
		 
	jQuery(document).on('click','.tfc-upload', function( event ){
		var file_frame='';
		event.preventDefault();
		var obj=$(this);
		if ( file_frame ) {
		  // Open frame
		  file_frame.open();
		  return;
		} 
		
		file_frame = wp.media.frames.file_frame = wp.media({
		  title: 'Loader Image',
		  button: {
			text: 'Select',
		  },
		  multiple: false  
		});
	
		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
		  attachment = file_frame.state().get('selection').first().toJSON();
		  obj.prev('input[type=text]').val(attachment.url);
		  obj.siblings('.tfc-upload-snap').html("<img src=\""+attachment.url+"\">");
		});
		// Finally, open the modal
		file_frame.open();
  });
	
	jQuery(document).on('click','.tfc-remove', function( event ){
		var obj=$(this);
		obj.prevAll('input[type=text]').val('');
		obj.siblings('.tfc-upload-snap').html('');
	});
});