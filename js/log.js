$(document).ready(function() {
    $('#formulario-login').submit(function(event) {
        event.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: 'loginproc.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.valid === true) {
                    window.location.href = 'index.php';
                } else {
                    showErrorToast('El email o la contraseña son incorrectos.');
                }
            },
            error: function() {
                showErrorToast('Ha ocurrido un error al procesar su solicitud. Intente nuevamente más tarde.');
            }
        });
    });

    $('#form-register').submit(function(e) {
        e.preventDefault();

        var form = $(this);
        var formData = new FormData(this);

        if ($('#img')[0].files.length === 0) {
            formData.delete('img');
        }

        $.ajax({
            url: 'upload-user.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                showSuccessToast('El usuario se agregó correctamente.')
                $('#registerModal').modal('hide');
                form[0].reset();
            },
            error: function(xhr, status, error) {
                if (xhr.status === 400) {
                    showErrorToast(xhr.responseJSON.error);
                } else {
                    showErrorToast(xhr.responseText);
                }
            }
        });
    });


    function showSuccessToast(message) {
        var toast = '<div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">'
        toast += '<div class="d-flex">'
        toast += '<div class="toast-body">'
        toast += message
        toast += '</div>'
        toast += '<button type="button" class="btn-close ms-auto me-2" data-bs-dismiss="toast" aria-label="Close"></button>'
        toast += '</div>'
        toast += '</div>'

        $('.toast-container').html(toast)
        $('.toast').toast('show')
    }

    function showErrorToast(message) {
        var toast = '<div class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">'
        toast += '<div class="d-flex">'
        toast += '<div class="toast-body">'
        toast += message
        toast += '</div>'
        toast += '<button type="button" class="btn-close ms-auto me-2" data-bs-dismiss="toast" aria-label="Close"></button>'
        toast += '</div>'
        toast += '</div>'

        $('.toast-container').html(toast)
        $('.toast').toast('show')
    }
});

function previewImage(input, displayTo) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#' + displayTo).attr('src', e.target.result);
            $(input).siblings('.custom-file-label').html(input.files[0].name)
        }

        reader.readAsDataURL(input.files[0]);
    }
}