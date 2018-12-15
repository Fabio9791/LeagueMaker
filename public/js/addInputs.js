let i = 1;
$('#btnTag').on('click', function (e) {
    e.preventDefault();
    i++;
    $('#tags').append('<input type="text" class="form-control" name="tag' + i + '" placeholder="Competition location">')
})