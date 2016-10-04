var likeRequestRunning = false;
$('.btn-like-review').on('click', function(e) {
    if (likeRequestRunning) {
        return;
    }
    likeRequestRunning = true;
    $.ajax({
        url: $(this).data('urlPostLikeReview'),
        method: 'POST',
        data: {
            reviewId: $(this).data('reviewId')
        },
        success: function(msg) {
            if (msg['err']) {
                alert(msg['err']);
            } else {
                $('.btn-like-review').text(msg['likeAction']);
                $('#rv-num-likes').html(msg['htmlVal']);
            }
        },
        complete: function() {
            likeRequestRunning = false;
        },
        error: function(xhr, ajaxOptions, thrownerror) {
            alert('Error ' + xhr.status);
        }
    });
});
