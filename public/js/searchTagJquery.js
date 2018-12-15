$('#tagSearchButton').on('click', function(){

	$.getJSON(
		"/tag/search?pattern=" + $('#tagSearch').val(),
		function( response ) {console.log(response);
			response.data.forEach(function(element) {
				$('#searchResults').append('<li>'+element.name+'</li>');
			});
		}
	);

});