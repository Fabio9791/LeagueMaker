$('#tagSearch').on('keyup', function(){
	$('#searchResults div ul').html('');
	let availableTags = [];
	if ($('#tagSearch').val() != '') {
		$.getJSON(
			"/en/tag/search?pattern=" + $('#tagSearch').val(),
			function( response ) {
				console.log(response);
				$('#searchResults').css("display", "none");
				response['data'].forEach(function(element) {
					$('#searchResults div ul').append('<li>'+element['label']+'</li>');
					availableTags.push(element['label']);
					$('#searchResults').css("display", "block");
					$('#tagSearchButton').autocomplete({ source: availableTags})
				});
			}
		);
	} else {
		$('#searchResults').css("display", "none");
	}
});
