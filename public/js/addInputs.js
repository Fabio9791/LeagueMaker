let i = 1;
let y = 1;
$('#tagData').val(i);
$('#competitorData').val(y);
$('#btnTag').on('click', function (e) {
    e.preventDefault();
    i++;
    $('#tags').append('<input type="text" class="form-control" name="tag' + i + '" id="tag' + i + '">')
    $('#tagData').val(i);
});
$('#btnCompetitor').on('click', function (e) {
    e.preventDefault();
    y++;
    $('#competitors').append('<input type="text" class="form-control" name="competitor' + y + '" id="competitor' + y + '">')
    $('#competitorData').val(y);
});