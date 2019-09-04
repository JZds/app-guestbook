$("#commentInput").keyup(function () {
    if ($('#commentInput').val().trim().length === 0) {
        $('#submitComment').hide();
        $('#cancelComment').hide();
    } else {
        $('#submitComment').show();
        $('#cancelComment').show();
    }
});

$('#cancelComment').click(function(){
    $('#commentInput').val('');
    $('#submitComment').hide();
    $('#cancelComment').hide();
});