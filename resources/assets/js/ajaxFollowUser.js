var requestFollowRunning = false;
$('.btn-follow').on('click', function() {
    if (requestFollowRunning) {
        return;
    }
    requestFollowRunning = true;
    $.ajax({
        url: urlFollow,
        method: 'POST',
        data: {
            userId: userId
        },
        success: function(msg) {
            if (msg['err']) {
                alert(msg['err']);
                window.location.href = redirectPath;
            } else {
                $('.btn-follow').text(msg['changeAction']);
                $('.box-profile .box-info-like div:nth-child(2) mark:nth-child(2)').text(msg['num_followings']);
                $('.box-profile .box-info-like div:nth-child(3) mark:nth-child(2)').text(msg['num_followers']);
            }
        },
        complete: function() {
            requestFollowRunning = false;
        },
        error: function(xhr, ajaxOptions, thrownerror) {
            alert('Error ' + xhr.status);
            window.location.href = redirectPath;
        }
    });
});
