$(document).ready(function () {
    $('#menu').metisMenu();
    $('.start-barrating').barrating({
        theme: 'fontawesome-stars',
    });
    $('.profile-header').find('.icon-edit').on('mouseover', function() {
        $(this).tooltip();
    });
    $('.profile-header').find('.icon-edit').on('click', function() {
        $('.profile').hide();
        $('.update-profile').show();
    });
    $('.alert').delay(3000).slideUp();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('.notification').delay(4000).fadeOut();
    $('.notification').on({
        mouseover: function() {
            $('.notification').stop(true, true);   
        } ,
        mouseout: function() {
            $('.notification').delay(1000).fadeOut();
        }
    });
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#preview_avatar_img').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#file_avatar").change(function () {
        readURL(this);
    });
    var options = {
        url: $('#input-autocomplete').data('url'),
        getValue: "title",
        list: {   
            match: {
              enabled: true
            }
        },
        theme: "square"
    };
    $("#input-autocomplete").easyAutocomplete(options);
});
