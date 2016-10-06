var cancelRequestRunning = false;
$('.cancel-request').on('click', function(e) {
    var cf = confirm($(this).data('messageConfirm'));
    if (cf == true) {
        if (cancelRequestRunning) {
            return;
        }
        cancelRequestRunning = true;
        var bookId = $(this).data('bookId');
        $.ajax({
            url: $(this).data('urlCancelRequest'),
            method: 'POST',
            data: {
                bookId: bookId
            },
            success: function(msg) {
                if (msg['err']) {
                    confirm(msg['err']);
                } else {
                    confirm(msg['success']);
                    $('#item-request-' + bookId).remove();
                }
            },
            complete: function() {
                cancelRequestRunning = false;
            },
            error: function(xhr, ajaxOptions, thrownerror) {
                alert('Error ' + xhr.status);
            }
        });
    }
});
