console.log('%cHi dear DEVELOPER 😉 Please send us your reviews. \nThanks 🙏🌹🌹🙏  \nsupport@giliapps.com', 'color: #f3f2ea; font-size: larger; font-weight: bold; padding: 8px;');

$(document).ready(function () {
    /**
     * Enable bootstrap toast with options
     */
    $('.toast').toast({ delay: 4000 });

    /**
     * Enable tooltips everywhere
     */
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    /**
     * Send form data with Ajax for all forms
     */
    $('body').on('click', '.form-button', function (event) {
        var element_id = $(this).attr('id');
        element_id = element_id.replace('-submit', '');
        
        var method_type = 'POST';
        if (element_id.indexOf('update') > -1) method_type = 'PUT';

        $.ajax({
            url: api_address + '/' + element_id.replace('-', '/'),
            data: { formData: $('#' + element_id).serialize() },
            type: method_type,
            dataType: 'JSON',
            beforeSend: function () {
                $('.progress').css('top', '56px');
            },
            complete: function () {
                $('.progress').css('top', '51px');
            },
            success: function (result) {
                if (result['status'] == 'OK') {
                    window.location.replace('/');
                } else {
                    $('.toast').toast('show');
                    $('.toast-body').text(result['message']);
                }
            },
            error: function (xhr, status, error) {
                // alert("responseText: " + xhr.responseText);
                $('.toast').toast('show');
                $('.toast-body').text(result['message']);
            }
        });
    });

    /**
     * Enable using Enter key in forms to trigger click on button
     */
    $('body').on('keypress', 'form', function (event) {
        if (event.which == 13) $('.form-button').click();
    });

    /**
     * Send form data with Ajax for all forms
     */
    $('body').on('click', '.form-delete-button', function (event) {
        var element_id = $(this).attr('id');

        $.ajax({
            url: api_address + '/blog/delete/' + element_id,
            type: 'DELETE',
            dataType: 'JSON',
            beforeSend: function () {
                $('.progress').css('top', '56px');
            },
            complete: function () {
                $('.progress').css('top', '51px');
            },
            success: function (result) {
                if (result['status'] == 'OK') {
                    window.location.replace('/');
                } else {
                    $('.toast').toast('show');
                    $('.toast-body').text(result['message']);
                }
            },
            error: function (xhr, status, error) {
                // alert("responseText: " + xhr.responseText);
                $('.toast').toast('show');
                $('.toast-body').text(result['message']);
            }
        });
    });
});