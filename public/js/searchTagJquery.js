
var availableTags = [];
$.getJSON(
		"/en/tag/search?pattern=" + $('#tagSearch').val(),
		function( response ) {
			response['data'].forEach(function(element) {
				if (availableTags.indexOf(element['label']) == -1 ) {
					availableTags.push(element['label']);
				}
			});
		}
	);
$('#tagSearch').autocomplete({ source: availableTags});
console.log(availableTags);

