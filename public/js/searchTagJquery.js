var availableTags = [];
$('#tagSearch').on('keyup', function(){
	if ($('#tagSearch').val() != '') {
		$.getJSON(
			"/en/tag/search?pattern=" + $('#tagSearch').val(),
			function( response ) {
				response['data'].forEach(function(element) {
					availableTags.push(element['label']);
					console.log(element['label']);
				});
			}
		);
	}
});
for (let i=0; i<availableTags.length-1; i++) {
	for (let y=i+1; y<availableTags.length; y++) {
		if (availableTags[y] == availableTags[i]) {
			availableTags.slice(y, 1);
			y--;
		}
	}
}
$('#tagSearch').autocomplete({ source: availableTags});
console.log(availableTags);
