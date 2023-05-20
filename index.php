<?php
session_start();

if(!isset($_SESSION['user_name'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Simple Music Player App</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
</head>

<body class="text-light bg-dark bg-gradient">
    <main>
    <nav class="mainnav shadow navbar-expand navbar navbar-dark bg-dark fixed-top" style="min-width: 350px">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <i class="bi bi-soundwave"></i> urMUSICplaya
                    <div style="font-size: 13px">Lorem ipsum dolor sit amet.</div>
                </a>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <img class="rounded-circle" src="<?php echo $_SESSION['user_urlimg'] ?>" style="width:40px;height:40px">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="us_name" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php echo $_SESSION['user_name'] ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end bg-dark" aria-labelledby="navbarDropdown">
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a id="logout-link" class="dropdown-item text-white" href="#"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="col-lg-12">
            <h1 class="fw-bolder text-center" id="project-title">Reproductor de Musicas</h1>
        </div>
        <div class="clear-fix my-5"></div>
        <div  class="container w-100">
            <div class="col-12">
                <div class="row">
                    
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-5">
                        <div class="col-md-12 text-center mb-3">
                            <img src="images/music-logo.jpg" alt="" id="display-img" class="img-fluid border bg-gradient bg-dark">
                        </div>
                        <h4><b id="inplay-title">---</b></h4>
                        <small class="text-muted" id="inplay-duration">--:--</small>
                        <hr>
                        <p id="inplay-artista">---</p>
                        <div class="d-flex w-100 justify-content-center mb-4">
                            <div class="mx-1">
                                <button class="btn btn-sm btn-light bg-gradient text-dark" id="prev-btn"><i class="fa fa-step-backward"></i></button>
                            </div>
                            <div class="mx-1">
                                <button class="btn btn-sm btn-light bg-gradient text-dark" id="play-btn" data-value="play"><i class="fa fa-play"></i></button>
                            </div>
                            <div class="mx-1">
                                <button class="btn btn-sm btn-light bg-gradient text-dark" id="stop-btn"><i class="fa fa-stop"></i></button>
                            </div>
                            <div class="mx-1">
                                <button class="btn btn-sm btn-light bg-gradient text-dark" id="next-btn"><i class="fa fa-step-forward"></i></button>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="mx-1">
                                <span id="currentTime">--:--</span>
                            </div>
                            <div id="range-holder" class="mx-1">
                                <input type="range" id="playBackSlider" value="0">
                            </div>
                            <div class="mx-1">
                                <span id="vol-icon"><i class="fa fa-volume-up"></i></span> <input type="range" value="25" id="volume">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 hero-area text-white mt-2">
                        <ul class="hero-nav nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="text-white nav-link active" id="pills-songs-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Lista de Canciones</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="text-white nav-link" id="pills-playlist-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Playlists</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="text-white nav-link" id="pills-playlistm-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Playlist Manager</button>
                            </li>
                        </ul>
                        <div class="tab-content overflow-auto" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                                <div class="mb-4">
                                    <div class="d-flex">
                                        <h5 class="card-title col-auto flex-grow-1 flex-shrink-1">Lista de Musicas</h5>
                                        <div class="mb-2">
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#music_modal"><i class="fa fa-plus"></i> Añadir</button>
                                        </div>
                                    </div>
                                    <div class="table-responsive p-3 bg-light text-dark">
                                        <table id="song-table" class="table table-striped align-middle">
                                            <thead>
                                            <tr>
                                                <th>portada</th>
                                                <th>Título</th>
                                                <th>Artista</th>
                                                <th>Acciones</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                                
                                 <div class="mb-4">
                                    <div class="d-flex">
                                        <h5 class="card-title col-auto flex-grow-1 flex-shrink-1">Lista de Playlists</h5>
                                        <div class="mb-2">
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#import_modal"><i class="fa fa-plus"></i> Importar</button>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#playlist_modal"><i class="fa fa-plus"></i> Añadir</button>
                                        </div>
                                    </div>
                                    <div class="table-responsive p-3 bg-light text-dark">
                                        <table id="playlist-table" class="table table-striped align-middle">
                                            <thead>
                                            <tr>
                                                <th>portada</th>
                                                <th>Título</th>
                                                <th>Acciones</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">
                                <div class="mb-4">
                                    <div class="d-flex">
                                        <div class="card-title col-auto flex-grow-1 flex-shrink-1">
                                            <img id="playplaylistimg" src="images/music-logo.jpg" alt="" class="img-thumbnail bg-gradient bg-dark mini-display-img">
                                            <h5 id="playplaylisttitle"></h5>
                                        </div>
                                        
                                    </div>
                                    <div class="table-responsive p-3 bg-light text-dark">
                                        <table id="playlistm-table" class="table table-striped align-middle">
                                            <thead>
                                            <tr>
                                                <th>portada</th>
                                                <th>Título</th>
                                                <th>Artista</th>
                                                <th>Acciones</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade text-dark" id="music_modal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa fa-music"></i> Añadir Nueva Musica</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <form action="" id="music-form">
                                <div class="form-group mb-3">
                                    <label for="title" class="control-label">Title</label>
                                    <input type="text" name="title" id="title" class="form-control form-control-sm rounded-0" required placeholder="">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="artist" class="control-label">Artista</label>
                                    <input type="text" name="artist" id="artist" class="form-control form-control-sm rounded-0" required placeholder=""></input>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="audio" class="control-label">Audio File</label>
                                    <input type="file" name="audio" id="audio" class="form-control form-control-sm rounded-0" required accept="audio/*">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="img" class="control-label">Display Image</label>
                                    <input type="file" name="img" id="img" class="form-control form-control-sm rounded-0" accept="image/*" onchange="previewImage(this, 'dImage')">
                                </div>
                                <div class="form-group mb-3 text-center">
                                    <div class="col-md-6">
                                        <img src="images/music-logo.jpg" alt="Image" class="img-fluid img-thumbnail bg-gradient bg-dark" id="dImage">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-sm rounded-0" form="music-form">Save</button>
                        <button type="button" class="btn btn-secondary btn-sm rounded-0" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade text-dark" id="playlist_modal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa fa-music"></i> Añadir Nueva Playlist</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <form action="" id="playlist-form">
                                <div class="form-group mb-3">
                                    <label for="title" class="control-label">Title</label>
                                    <input type="text" name="title" id="titleplaylist" class="form-control form-control-sm rounded-0" required placeholder="">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="img" class="control-label">Imagen de Portada</label>
                                    <input type="file" name="img" id="imgplaylist" class="form-control form-control-sm rounded-0" accept="image/*" onchange="previewImage(this, 'dImageplaylist')">
                                </div>
                                <div class="form-group mb-3 text-center">
                                    <div class="col-md-6">
                                        <img src="images/music-logo.jpg" alt="Image" class="img-fluid img-thumbnail bg-gradient bg-dark" id="dImageplaylist">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-sm rounded-0" form="playlist-form">Save</button>
                        <button type="button" class="btn btn-secondary btn-sm rounded-0" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade text-dark" id="import_modal" tabindex="-1" aria-labelledby="code_modal_label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="code_modal_label">Importar Playlist</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="import-form">
                            <div class="mb-3">
                            <label for="encrypted_code" class="form-label">Código:</label>
                            <input type="text" class="form-control" name="codigo" id="encrypted_code" placeholder="Ingrese el código" required placeholder="">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" form="import-form">Importar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade text-dark" id="update-modal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa fa-music"></i> Editar Musica</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <form action="" id="edit-form">
                                <input type="hidden" name="id">
                                <div class="form-group mb-3">
                                    <label for="title" class="control-label">Title</label>
                                    <input type="text" name="title" id="title1" class="form-control form-control-sm rounded-0" required placeholder="">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="artist" class="control-label">Artista</label>
                                    <input type="text" name="artist" id="artist1" class="form-control form-control-sm rounded-0" required placeholder=""></input>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="audio" class="control-label">Audio File</label>
                                    <input type="file" name="audio" id="audio1" class="form-control form-control-sm rounded-0"  accept="audio/*">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="img" class="control-label">Display Image</label>
                                    <input type="file" name="img" id="img1" class="form-control form-control-sm rounded-0" accept="image/*" onchange="previewImage(this, 'dImage1')">
                                </div>
                                <div class="form-group mb-3 text-center">
                                    <div class="col-md-6">
                                        <img src="images/music-logo.jpg" alt="Image" class="img-fluid img-thumbnail bg-gradient bg-dark" id="dImage1">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-sm rounded-0" form="edit-form">Save</button>
                        <button type="button" class="btn btn-secondary btn-sm rounded-0" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="toast-container top-0 end-0"></div>

        <div class="modal fade bg-gradient text-dark" id="confirm-modal" tabindex="-1" aria-labelledby="confirm-modal-label" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="confirm-modal-label">Confirmación</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					¿Está seguro de que desea eliminar este elemento?
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
					<button type="button" class="btn btn-primary" id="confirm-yes-button">Sí</button>
				</div>
				</div>
			</div>
		</div>

        <div class="modal fade text-dark" id="share_modal" tabindex="-1" aria-labelledby="share_modal_label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="share_modal_label">Enlace Para Compartir</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        
                        <p id="enlace_encriptado"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="copy_link">Copiar</button>
                    </div>
                </div>
            </div>
        </div>

    </main>
    <script src="https://kit.fontawesome.com/2493c793bb.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script type="text/javascript" src="//code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>