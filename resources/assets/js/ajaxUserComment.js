var commentRequestRunning = false;
$('#icon-submit-comment').click(function() {
    if (commentRequestRunning) {
        return;
    }
    commentRequestRunning = true;
    $.ajax({
        url: $('#icon-submit-comment').data('urlPostAddComment'),
        method: 'POST',
        data: {
            reviewId: $('#icon-submit-comment').data('reviewId'),
            content: $('#comment-content').val()
        },
        success: function(msg, xhr) {
            if (msg['err']) {
                alert(msg['err']);
            } else {
                $('#comment-content').val('');
                $('.comment').append(msg['htmlValue']);
                $('#rv-num-comments').html(msg['htmlComments']);
            }
        },
        complete: function() {
            commentRequestRunning = false;
        },
        error: function(xhr, ajaxOptions, thrownerror) {
            alert('Error ' + xhr.status);
        }
    });
});
