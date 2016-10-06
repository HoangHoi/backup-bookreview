var delCmtRequestRunning = false;
$('.comment').on('click', '#btn-delete-comment', function(event) {
    var commentId = event.target.parentNode.parentNode.parentNode.dataset['commentId'];
    var reviewId = event.target.parentNode.parentNode.parentNode.dataset['reviewId'];
    $('#btn-confirm-delete').data('comment-id', commentId);
    $('#btn-confirm-delete').data('review-id', reviewId);
});
$('#btn-confirm-delete').on('click', function() {
    if (delCmtRequestRunning)
        return;

    delCmtRequestRunning = true;
    var commentId = $('#btn-confirm-delete').data('commentId');
    $.ajax({
        url: $(this).data('urlDeleteComment'),
        method: 'POST',
        data: {
            commentId: commentId,
            reviewId: $(this).data('reviewId')
        },
        success: function(msg) {
            if (msg['err']) {
                alert(msg['err']);
            }
            if (msg['status'] == 'Yes') {
                $('#item-comment-' + commentId).remove();
                $('#rv-num-comments').html(msg['htmlComments']);
            }
        },
        complete: function() {
            delCmtRequestRunning = false;
        },
        error: function(xhr, ajaxOptions, thrownerror) {
            alert('Error ' + xhr.status);
        }
    });
});
