$('.btn-follow').on('click', function() {
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
                $('.box-profile .box-info-like a:nth-child(2) p:nth-child(2)').text(msg['num_followings']);
                $('.box-profile .box-info-like a:nth-child(3) p:nth-child(2)').text(msg['num_followers']);
            }
        },
        error: function(xhr, ajaxOptions, thrownerror) {
            alert('Error ' + xhr.status);
            window.location.href = redirectPath;
        }
    });
});
