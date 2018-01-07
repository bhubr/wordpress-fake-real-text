// https://stackoverflow.com/questions/1184624/convert-form-data-to-javascript-object-with-jquery#answer-1186309
function objectifyForm(formArray) {//serialize data function

  var returnArray = {};
  for (var i = 0; i < formArray.length; i++){
    returnArray[formArray[i]['name']] = formArray[i]['value'];
  }
  return returnArray;
}

(function( $ ) {
	'use strict';

	$(document).ready(function() {
		console.log($('#generate-posts'));

		$('#generate-posts').submit(function(e) {
			e.preventDefault();
			var formData = $(this).serializeArray();
			console.log(formData, objectifyForm(formData));

			var data = Object.assign(objectifyForm(formData), {
				action: 'frt_generate_posts'
			});

			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			$.post(ajaxurl, data, function(response) {
				console.log('params were', response);
			});
		});
	});

})( jQuery );
