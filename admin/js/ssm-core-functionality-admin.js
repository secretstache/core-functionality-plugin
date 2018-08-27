(function( $ ) {

	$('#upload-image-button').click(function() {
		var send_attachment = wp.media.editor.send.attachment;
		var button = $(this);
		wp.media.editor.send.attachment = function(props, attachment) {
  
			var url = attachment.url;
			var origWidth = attachment.width;
			var origHeight = attachment.height;
			var orientation;
			var defaultLogo = login_logo.url;
  
			console.log( 'Width: ' + origWidth );
			console.log( 'Height: ' + origHeight );
  
			if ( origWidth > origHeight ) {
				orientation = 'landscape';
				// console.log(orientation);
			} else {
				orientation = 'portrait';
				// console.log(orientation);
			}
  
			if ( orientation == 'landscape' && origWidth >= 290 ) {
				w = 290;
				h = w * (origHeight / origWidth);
				width = w.toString() + 'px';
				height = h.toString() + 'px';
				// console.log('landscape > 290');
			} else if ( orientation == 'landscape' && origWidth < 290 ) {
				width = origWidth.toString() + 'px';
				height = origHeight.toString() + 'px';
				// console.log('landscape < 290');
			} else if ( orientation == 'portrait' && origWidth >= 125 ) {
				w = 125;
				h = w * (origHeight / origWidth);
				width = w.toString() + 'px';
				height = h.toString() + 'px';
				// console.log( 'portrait > 125' );
			} else if ( orientation == 'portrait' && origWidth < 125 ) {
				width = origWidth.toString() + 'px';
				height = origHeight.toString() + 'px';
				// console.log('portrait < 125');
			}
  
			$('#ssm-core-login-logo').attr('value', url);
			$('#logo-preview').attr('src', url);
			$('#logo-preview').css({'width': width, 'height': height});
			$('#ssm-core-login-logo-width').attr('value', width);
			$('#ssm-core-login-logo-height').attr('value', height);
  
			wp.media.editor.send.attachment = send_attachment;
  
	  };
	  wp.media.editor.open(button);
	  return false;
	});
  
	// The "Remove" button (remove the value from input type='hidden')
	$('#remove-image-button').click(function() {
	  var answer = confirm('Are you sure?');
	  if (answer == true) {
  
		var defaultLogo = login_logo.url;
  
		$('#ssm-core-login-logo').attr('value', '');
		$('#logo-preview').attr('src', defaultLogo);
		$('#logo-preview').css({'width': '230px', 'height': 'auto'});
		$('#ssm-core-login-logo-width').attr('value', '');
		$('#ssm-core-login-logo-height').attr('value', '');
	  }
	  return false;
	});

	$(document).on( 'click', '.ssm_module', function(e) {
		
		if (e.target.tagName == 'INPUT') {

			var slug = $(this).data('module-slug');

			if ( $(this).find('input').attr('checked') == undefined ) {

				$('#ssm_functions .ssm_function.' + slug).find('input').each(function() {
					$(this).prop('checked', false);
				});

			} else {

				$('#ssm_functions .ssm_function.' + slug).find('input').each(function() {
					$(this).prop('checked', true);
				});

			}

		}
	});

	$(document).on( 'change', '.ssm_function :checkbox', function(e) {
		
		var slug = $(this).parents('.ssm_function').data('module-slug');

		if ( $(this).parents('.ssm_function').find('input:checked' ).length == 0 ) {
			$('.ssm_module.' + slug + ' input').prop('checked', false);
		} else {
			$('.ssm_module.' + slug + ' input').prop('checked', true);
		}

	});

})( jQuery );
