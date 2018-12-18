$('#tagSearchButton').on('click', function(){
	$('#searchResults div ul').html('');
	if ($('#tagSearch').val() != '') {
		$.getJSON(
			"/en/tag/search?pattern=" + $('#tagSearch').val(),
			function( response ) {
				console.log(response);
				$('#searchResults').css("display", "none");
				response['data'].forEach(function(element) {
					$('#searchResults formComp ul').append('<li>'+element['label']+'</li>');
					$('#searchResults').css("display", "block");
				});
			}
		);
	} else {
		$('#searchResults').css("display", "none");
	}
});
