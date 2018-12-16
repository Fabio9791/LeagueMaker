$('#competitionSearchButton').on('click', function(){

	$.getJSON(
		"/competition/search?pattern=" + $('#competitionSearch').val(),
		function( response ) {console.log(response);
			response.data.forEach(function(element) {
				$('#searchResults').append('<li>'+element.name+'</li>');
			});
		}
	);
});
