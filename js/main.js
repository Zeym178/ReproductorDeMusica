$(document).ready(function() {
    var tableactual;
    var playlistactual = 0;
    var audio = new Audio();
    var playbackSlider = $('#playBackSlider')[0];
    var currentSongIndex = -1;

    var table = $('#song-table').DataTable({
        "paging": false,
        "ordering": false,
        "scrollY": "300px",
        "scrollCollapse": true,
        "scroller": true,
        "language": {
            "info": "",
            "infoEmpty": "",
            "infoFiltered": ""
        },
        ajax: {
            url: 'get-songs.php',
            type: 'POST',
            data: '',
            dataSrc: ''
        },
        rowId: 'id',
        columns: [{
                data: 'urlimg',
                render: function(data, type, row, meta) {
                    if (data) return '<img src="' + data + '" alt="" class="img-thumbnail bg-gradient bg-dark mini-display-img">';
                    else return '<img src="images/music-logo.jpg" alt="" class="img-thumbnail bg-gradient bg-dark mini-display-img">';
                },
                createdCell: function(cell, cellData, rowData, rowIndex, colIndex) {
                    $(cell).css({
                        'width': '3.5rem',
                        'height': '3.5rem',
                        'object-fit': 'cover',
                        'object-position': 'center center',
                        'padding': '0.8em'
                    });
                }
            },
            { data: 'title', },
            { data: 'artist', },
            {
                targets: -1,
                data: null,
                defaultContent: '<button class="btn btn-primary play-button"><i class="fa-solid fa-play" style="color: #ffffff;"></i></button> <button class="btn btn-primary edit-button"><i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i></button> <button class="btn btn-primary addplaylist-button">ToPlaylist</button> <button class="btn btn-danger delete-button"><i class="fa-solid fa-trash" style="color: #ffffff;"></i></button>'
            }
        ]
    });

    var tableplaylist = $('#playlist-table').DataTable({
        "paging": false,
        "ordering": false,
        "scrollY": "300px",
        "scrollCollapse": true,
        "scroller": true,
        "language": {
            "info": "",
            "infoEmpty": "",
            "infoFiltered": ""
        },
        ajax: {
            url: 'get-playlist.php',
            type: 'POST',
            data: '',
            dataSrc: ''
        },
        rowId: 'id',
        columns: [{
                data: 'urlimg',
                render: function(data, type, row, meta) {
                    if (data) return '<img src="' + data + '" alt="" class="img-thumbnail bg-gradient bg-dark mini-display-img">';
                    else return '<img src="images/music-logo.jpg" alt="" class="img-thumbnail bg-gradient bg-dark mini-display-img">';
                },
                createdCell: function(cell, cellData, rowData, rowIndex, colIndex) {
                    $(cell).css({
                        'width': '3.5rem',
                        'height': '3.5rem',
                        'object-fit': 'cover',
                        'object-position': 'center center',
                        'padding': '0.8em'
                    });
                }
            },
            { data: 'titulo', },
            {
                targets: -1,
                data: null,
                defaultContent: '<button class="btn btn-primary play-button"><i class="fa-solid fa-play" style="color: #ffffff;"></i></button> <button class="btn btn-danger delete-button"><i class="fa-solid fa-trash" style="color: #ffffff;"></i></button> <button class="btn btn-primary share-button"><i class="fa-solid fa-share" style="color: #ffffff;"></i></button>'
            }
        ]
    });

    $('#playlist-table tbody').on('click', '.play-button', function() {
        if ($.fn.DataTable.isDataTable('#playlistm-table')) {
            $('#playlistm-table').DataTable().destroy();
        }

        var button = $(this);
        var id = button.closest('tr').attr('id');
        var urlimg = button.closest('tr').find('img').attr('src');
        var title = button.closest('tr').find('td:eq(1)').text();

        var tableplaylistm = $('#playlistm-table').DataTable({
            "paging": false,
            "ordering": false,
            "scrollY": "300px",
            "scrollCollapse": true,
            "scroller": true,
            "language": {
                "info": "",
                "infoEmpty": "",
                "infoFiltered": ""
            },
            ajax: {
                url: 'get-playlistm.php',
                type: 'POST',
                data: { id: id },
                dataSrc: '',
            },
            rowId: 'id',
            columns: [{
                    data: 'urlimg',
                    render: function(data, type, row, meta) {
                        if (data) return '<img src="' + data + '" alt="" class="img-thumbnail bg-gradient bg-dark mini-display-img">';
                        else return '<img src="images/music-logo.jpg" alt="" class="img-thumbnail bg-gradient bg-dark mini-display-img">';
                    },
                    createdCell: function(cell, cellData, rowData, rowIndex, colIndex) {
                        $(cell).css({
                            'width': '3.5rem',
                            'height': '3.5rem',
                            'object-fit': 'cover',
                            'object-position': 'center center',
                            'padding': '0.8em'
                        });
                    }
                },
                { data: 'title', },
                { data: 'artist', },
                {
                    targets: -1,
                    data: null,
                    defaultContent: '<button class="btn btn-primary play-button"><i class="fa-solid fa-play" style="color: #ffffff;"></i></button> <button class="btn btn-danger delete-button"><i class="fa-solid fa-trash" style="color: #ffffff;"></i></button>'
                }
            ],
            initComplete: function(settings, json) {
                playlistactual = id;
                $('#playlistm-table tbody .play-button:first').trigger('click');
                $('#pills-playlistm-tab').trigger('click');
                $('#playplaylistimg').attr('src', urlimg);
                $('#playplaylisttitle').html(title);
            }
        });

    });

    $('#playlist-table tbody').on('click', '.share-button', function() {
        var id = $(this).closest('tr').attr('id');
        $.ajax({
            type: "POST",
            url: "encriptar.php",
            data: { id: id },
            success: function(response) {
                var enlaceEncriptado = response;
                $("#enlace_encriptado").text(enlaceEncriptado);
                $("#share_modal").modal("show");

                $("#copy_link").click(function() {
                    navigator.clipboard.writeText(enlaceEncriptado);
                    showSuccessToast("El enlace ha sido copiado al portapapeles.");
                });
            }
        });
    });

    $('#import-form').submit(function(event) {
        event.preventDefault();
        var form = $(this);
        var formData = new FormData(this);

        $.ajax({
            url: "desencriptar.php",
            method: "POST",
            data: formData,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    tableplaylist.ajax.reload();
                    $('#import_modal').modal('hide');
                    showSuccessToast('La playlist se importo correctamente');
                    form[0].reset();
                } else {
                    showErrorToast(response.message);
                }
            },
            error: function() {
                showErrorToast('Codigo Invalido');
            }
        });
    });

    $('#song-table_length').addClass('d-none');

    $('#song-table tbody, #playlistm-table tbody').on('click', '.play-button', function() {
        var tableaux = $(this).closest('table').DataTable();
        tableactual = tableaux;
        var data = tableactual.row($(this).parents('tr')).data();
        audio.src = data.url;
        audio.volume = $('#volume').val() / 100;
        playmusic(data);
        currentSongIndex = tableactual.row($(this).parents('tr')).index();
    });

    $('#song-table tbody').on('click', '.addplaylist-button', function() {
        if (playlistactual) {
            var song_id = $(this).closest('tr').attr('id');
            $.ajax({
                url: 'upload-playlistsong.php',
                type: 'POST',
                data: { playlist_id: playlistactual, song_id: song_id },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showSuccessToast('La canción se agrego a la playlist');
                        if ($.fn.DataTable.isDataTable('#playlistm-table')) {
                            $('#playlistm-table').DataTable().ajax.reload();
                        }
                    } else {
                        showErrorToast(response.message);
                    }
                },
                error: function() {
                    showErrorToast('Error al agregar la cancion.');
                }
            });
        } else {
            showErrorToast('No esta seleccionada una playlist');
        }
    });

    $('#song-table tbody').on('click', '.delete-button', function() {
        var button = $(this);
        $('#confirm-modal').modal('show');
        $('#confirm-yes-button').off('click').on('click', function() {
            var id = button.closest('tr').attr('id');
            $.ajax({
                url: 'delete-song.php',
                type: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showSuccessToast('La canción se eliminó correctamente');
                        table.ajax.reload();
                        if ($.fn.DataTable.isDataTable('#playlistm-table')) {
                            $('#playlistm-table').DataTable().ajax.reload();
                        }
                    } else {
                        showErrorToast(response.message);
                    }
                },
                error: function() {
                    showErrorToast('Error al eliminar la canción.');
                }
            });
            $('#confirm-modal').modal('hide');
        });
    });

    $('#song-table tbody').on('click', '.edit-button', function() {
        var data = table.row($(this).parents('tr')).data();
        $('#update-modal').modal('show');
        $('#edit-form [name="id"]').val(data.id);
        $('#edit-form [name="title"]').val(data.title);
        $('#edit-form [name="artist"]').val(data.artist);
        $('#edit-form [name="audio"]').val('');
        $('#edit-form [name="img"]').val('');
        $('#dImage1').attr('src', data.urlimg);
    });

    $('#playlist-table tbody').on('click', '.delete-button', function() {
        var button = $(this);
        $('#confirm-modal').modal('show');
        $('#confirm-yes-button').off('click').on('click', function() {
            var id = button.closest('tr').attr('id');
            $.ajax({
                url: 'delete-playlist.php',
                type: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showSuccessToast('La playlist se eliminó correctamente');
                        $('#playlist-table').DataTable().ajax.reload();
                        if ($.fn.DataTable.isDataTable('#playlistm-table')) {
                            $('#playlistm-table').DataTable().ajax.reload();
                        }
                    } else {
                        showErrorToast(response.message);
                    }
                },
                error: function() {
                    showErrorToast('Error al eliminar la playlist.');
                }
            });
            $('#confirm-modal').modal('hide');
        });
    });

    $('#playlistm-table tbody').on('click', '.delete-button', function() {
        var button = $(this);
        $('#confirm-modal').modal('show');
        $('#confirm-yes-button').off('click').on('click', function() {
            var id = button.closest('tr').attr('id');
            $.ajax({
                url: 'delete-playlistsong.php',
                type: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showSuccessToast('La cancion se eliminó de la playlist');
                        $('#playlistm-table').DataTable().ajax.reload();
                    } else {
                        showErrorToast(response.message);
                    }
                },
                error: function() {
                    showErrorToast('Error al eliminar la cancion.');
                }
            });
            $('#confirm-modal').modal('hide');
        });
    });

    $('#edit-form').submit(function(event) {
        event.preventDefault();
        var form = $(this);
        var formData = new FormData(this);

        $.ajax({
            url: 'update-song.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    table.ajax.reload();
                    $('#update-modal').modal('hide');
                    showSuccessToast('La canción se ha actualizado correctamente.');
                } else {
                    showErrorToast(response.message);
                }
            },
            error: function() {
                showErrorToast('Error al actualizar la canción.');
            }
        });
    });

    $('#logout-link').on('click', function(event) {
        event.preventDefault();

        $.ajax({
            url: 'logout.php',
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                window.location.href = 'login.php';
            },
            error: function(xhr, status, error) {
                console.error('Error al cerrar la sesión: ' + error);
            }
        });
    });

    $('#play-btn').click(function() {
        if (audio.paused) {
            audio.play();
            $('#play-btn').html('<i class="fa fa-pause"></i>');
        } else {
            audio.pause();
            $('#play-btn').html('<i class="fa fa-play"></i>');
        }
    });

    $('#stop-btn').click(function() {
        audio.pause();
        audio.currentTime = 0;
        $('#play-btn').html('<i class="fa fa-play"></i>');
    });

    $('#prev-btn').click(function() {
        if (currentSongIndex) currentSongIndex--;
        var data = tableactual.row(currentSongIndex).data();
        audio.src = data.url;
        playmusic(data);
    });

    $('#next-btn').click(function() {
        if (tableactual.rows().count() - 1 > currentSongIndex) currentSongIndex++;
        var data = tableactual.row(currentSongIndex).data();
        audio.src = data.url;
        playmusic(data);
    });

    audio.addEventListener('loadedmetadata', function() {
        playbackSlider.max = audio.duration;
        $('#song-duration').text(formatTime(audio.duration));
        $('#inplay-duration').text(formatTime(audio.duration));
    });

    audio.addEventListener('timeupdate', function() {
        playbackSlider.value = audio.currentTime;
        $('#currentTime').text(formatTime(audio.currentTime));
    });

    audio.addEventListener('ended', function() {
        currentSongIndex++;
        var data = tableactual.row(currentSongIndex).data();
        audio.src = data.url;
        playmusic(data);
    });

    function formatTime(seconds) {
        var minutes = Math.floor(seconds / 60);
        var seconds = Math.floor(seconds % 60);
        return (minutes < 10 ? '0' : '') + minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
    }

    playbackSlider.addEventListener('input', function() {
        audio.currentTime = playbackSlider.value;
    });

    $('#volume').on('input', function() {
        audio.volume = $(this).val() / 100;
    });

    $('#music-form').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var formData = new FormData(this);

        if ($('#img')[0].files.length === 0) {
            formData.delete('img');
        }

        $.ajax({
            url: 'upload-music.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                showSuccessToast('La canción se agregó correctamente.')
                $('#music_modal').modal('hide');
                console.log(response);
                $('#song-table').DataTable().ajax.reload();
                form[0].reset();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    $('#playlist-form').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var formData = new FormData(this);

        if ($('#imgplaylist')[0].files.length === 0) {
            formData.delete('img');
        }

        $.ajax({
            url: 'upload-playlist.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                showSuccessToast('La playlist se agregó correctamente.')
                $('#playlist_modal').modal('hide');
                console.log(response);
                $('#playlist-table').DataTable().ajax.reload();
                form[0].reset();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    $('#playlistm-form').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var formData = new FormData(this);

        if ($('#imgplaylist')[0].files.length === 0) {
            formData.delete('img');
        }

        $.ajax({
            url: 'upload-playlist.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                showSuccessToast('La playlist se agregó correctamente.')
                $('#playlist_modal').modal('hide');
                console.log(response);
                $('#playlist-table').DataTable().ajax.reload();
                form[0].reset();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    //otrooooos
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

    function playmusic(data) {
        audio.play();
        $('#play-btn').html('<i class="fa fa-pause"></i>');
        $('#display-img').attr('src', data.urlimg);
        $('#inplay-title').html(data.title);
        $('#inplay-artista').html(data.artist);
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