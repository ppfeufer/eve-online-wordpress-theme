jQuery(document).ready(function($) {
	/**
	 * Check all upload sections for uploaded files
	 */
	$('code.uploaded-file-url').each(function() {
		if($(this).html().trim() !== '') {
			$(this).css('display', 'inline-block');
		} // END if($(this).html().trim() !== '')
	});

	$('img.uploaded-image').each(function() {
		if($(this).attr('src').trim() !== '') {
			$(this).css('display', 'block');
		} // END if($(this).html().trim() !== '')
	});

	// Upload attachment
	$('.upload, .image img, .url code').click(function(e) {
		e.preventDefault();

		var send_attachment_bkp = wp.media.editor.send.attachment;
//		var data_id = $(this).attr('id');
		var data_id = $(this).data('field-id');

		wp.media.editor.send.attachment = function(props, attachment) {
			var current = '[data-id="' + data_id + '"]';

			if(attachment.sizes && attachment.sizes.thumbnail && attachment.sizes.thumbnail.url) {
				$(current + ' .image img').attr('src', attachment.sizes.thumbnail.url);
				$(current + ' .image img').css('display', 'block');
			} // END if(attachment.sizes && attachment.sizes.thumbnail && attachment.sizes.thumbnail.url)

			$(current + ' .url code').html(attachment.url).show();
			$(current + ' .attachment_id').val(attachment.id);
			$(current + ' .remove').show();
			$(current + ' .upload').hide();

			wp.media.editor.send.attachment = send_attachment_bkp;
		};

		wp.media.editor.open();

		return false;
	});

	// Remove attachment
	$('.remove').click(function(e) {
		e.preventDefault();

		var data_id = $(this).parent().attr('data-id');
		var current = '[data-id="' + data_id + '"]';

		$(current + ' .url code').html('').hide();
		$(current + ' .attachment_id').val('');
		$(current + ' .image img').attr('src', '');
		$(current + ' .image img').css('display', 'none');
		$(current + ' .remove').hide();
		$(current + ' .upload').show();

//		console.log(data_id);
	});

	// Add color picker to fields
	if($('.colorpicker').length) {
		$('.colorpicker').wpColorPicker();
	} // END if($('.colorpicker').length)

	// Nav click toggle
	if($('.nav-tab').length) {
		$('.nav-tab').click(function(e) {
			e.preventDefault();

			var id = $(this).attr('href').substr(1);

			$('.tab-content').hide();
			$('#' + id).show();

			$('.nav-tab').removeClass('nav-tab-active');
			$(this).addClass('nav-tab-active');
		});
	} // END if($('.nav-tab').length)
});