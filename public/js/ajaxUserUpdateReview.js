$('#submit-update-review').on('click', function(event) {
    $.ajax({
        url: $(this).data('urlPutEditReview'),
        method: 'PUT',
        data: {
            content: tinyMCE.get('rv-content').getContent()
        },
        success: function(msg) {
            if (msg['err']) {
                alert(msg['err']);
            } else {
                $('#review-content').html(msg['content']);
                alert(msg['success']);
            }
        },
        error: function(xhr, ajaxOptions, thrownerror) {
            alert('Error ' + xhr.status);
        }
    });
});
