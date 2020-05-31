$(document).ready(function () {
    /**
     * Start summernote if needed
     */
    if ($('#body').length) {
        $('#body').summernote({
            tabsize: 4,
            height: 100
        });
    }

    /**
     * Enable bootstrap toast with options
     */
    $('.toast').toast({delay: 4000});

    /**
     * Enable tooltips everywhere
     */
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    /**
     * Send form data with Ajax for all forms
     *
     * Consider a route for your form like /blog/create; now use blog-create as an ID
     * for form and blog-create-submit for it's button. Form's buttons
     * need to have constant form-button class.
     */
    $('body').on('click', '.form-button', function(event) {
        let element_id = $(this).attr('id');
        element_id = element_id.replace('-submit', '');

        /*
        let method_type = 'POST';
        if (element_id.indexOf('update') > -1) method_type = 'PUT';
         */

        let formData = new FormData($('form').get(0));

        $.ajax({
            url: api_address + '/' + element_id.replace('-', '/'),
            data: formData,
            type: 'POST', // method_type,
            dataType: 'JSON',
            cache: false,
            processData: false,
            contentType: false,
            beforeSend() {
                $('.progress').css('top', '56px');
            },
            complete() {
                $('.progress').css('top', '51px');
            },
            success(result) {
                if (result['status'] === 'OK') {
                    window.location.replace('/');
                } else {
                    $('.toast').toast('show');
                    $('.toast-body').text(result['message']);
                }
            },
            error(xhr, status, error) {
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
        if (event.which === 13) $('.form-button').click();
    });

    /**
     * Send form data with Ajax for all forms
     *
     * Consider a route for your form like /blog/delete/{slug}; now use
     * blog-delete-{slug} as an ID for this button. Buttons
     * need to has constant form-delete-button class.
     */
    $('body').on('click', '.form-delete-button', function (event) {
        let element_id = $(this).attr('id');

        $.ajax({
            url: api_address + '/blog/delete/' + element_id,
            type: 'DELETE',
            dataType: 'JSON',
            beforeSend() {
                $('.progress').css('top', '56px');
            },
            complete() {
                $('.progress').css('top', '51px');
            },
            success(result) {
                if (result['status'] === 'OK') {
                    window.location.replace('/');
                } else {
                    $('.toast').toast('show');
                    $('.toast-body').text(result['message']);
                }
            },
            error(xhr, status, error) {
                // alert("responseText: " + xhr.responseText);
                $('.toast').toast('show');
                $('.toast-body').text(result['message']);
            }
        });
    });
});