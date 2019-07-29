let i = 1;
let y = 2;

if ($('#tagData').val() != "") {
    i = $('#tagData').val();
}
if ($('#competitorData').val() != "") {
    y = $('#competitorData').val();
}

$('#tagData').val(i);
$('#competitorData').val(y);
$('#btnTag').on('click', function (e) {
    e.preventDefault();
    if (i < 100) {
        i++;
        $('#tags').append('<input type="text" class="form-control" name="tag' + i + '" id="tag' + i + '">')
        $('#tagData').val(i);
        console.log(i);
    }
});

$('#btnTagRemove').on('click', function (e) {
    e.preventDefault();
    if (i > 1) {
        i--;
        $('#tags').children().last().remove();
    }
    $('#tagData').val(i);
    console.log(i);

});


$('#btnCompetitor').on('click', function (e) {
    e.preventDefault();
    if (y < 100) {
        y++;
        $('#competitors').append('<input type="text" class="form-control" name="competitor' + y + '" id="competitor' + y + '">')
        $('#competitorData').val(y);
        console.log(y);
    }
});

$('#btnCompetitorRemove').on('click', function (e) {
    e.preventDefault();
    if (y > 2) {
        y--;
        $('#competitors').children().last().remove();
        console.log(y);
    }
    $('#competitorData').val(y);
});