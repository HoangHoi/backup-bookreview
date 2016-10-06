var buyRequestRunning = false;
$('#btn-buy').on('click', function() {
    if (buyRequestRunning) {
        return;
    }
    buyRequestRunning = true;
    $.ajax({
        url: $(this).data('urlPostBuyBook'),
        method: 'POST',
        data: {
            bookId: $(this).data('bookId')
        },
        success: function(msg) {
            if (msg['err']) {
                alert(msg['err']);
            } else {
                alert(msg['success']);
            }
        },
        complete: function() {
            buyRequestRunning = false;
        },
        error: function(xhr, ajaxOptions, thrownerror) {
            alert('Error ' + xhr.status);
        }
    });
});
