<style>
    .btn-vertical {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .btn-image {
        margin-bottom: 10px;
    }

    .content-wrapper-1 {
        background-color: #3c8dbc; /* Cambia este valor al código de color azul que prefieras */
    }

    .btn-primary {
        background-color: #ffffff; /* Cambia este valor al código de color blanco que prefieras */
        border-color: #0a0a0a; /* Cambia este valor al código de color blanco que prefieras */
        color: #000000;
        margin: 20px;
        margin-left: 150px;
        margin-right: 150px;
    }

    .btn-shadow {
        box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.75) !important;
    }

</style>

<!-- Formulario 1 -->
<?php
    include 'modalAgregarFormulario1.php';
    include 'modalAgregarFormulario2.php';
    include 'modalAgregarFormulario3.php';
    include 'modalAgregarFormulario4.php';
    include 'modalAgregarFormulario5.php';
    include 'modalAgregarFormulario6.php';
    include 'modalAgregarFormulario7.php';
?>

<div class="content-wrapper">

    <section class="content-header">

        <h1>
            Diagramas de control
        </h1>

        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Calidad</li>
        </ol>

    </section>

    <section class="content">

        <div class="box">

            <div class="box-header with-border">

                <div class="content-wrapper-1">

                    <section class="content">

                        <div class="row">
                            <!-- Primera fila -->
                            <div class="col-md-4 d-flex justify-content-center">
                                <button class="btn btn-primary btn-lg rounded-circle p-2  btn-shadow btn-vertical" data-toggle="modal" data-target="#modalAgregarFormularioXR">
                                    <img src="vistas/modulos/imagenes/carta-x-r.png" class="img-fluid btn-image" style="width: 100px; height: 100px;">
                                    Carta X-R
                                </button>
                            </div>

                            <div class="col-md-4 d-flex justify-content-center">
                                <button class="btn btn-primary btn-lg rounded-circle p-2 btn-shadow btn-vertical" data-toggle="modal" data-target="#modalAgregarFormularioXS">
                                    <img src="vistas/modulos/imagenes/carta-x-s.png" class="img-fluid btn-image" style="width: 100px; height: 100px;">
                                    Carta X-s
                                </button>
                            </div>

                            <div class="col-md-4 d-flex justify-content-center">
                                <button class="btn btn-primary btn-lg rounded-circle p-2 btn-shadow btn-vertical" data-toggle="modal" data-target="#modalAgregarFormularioXRM">
                                    <img src="vistas/modulos/imagenes/carta-x-rm.png" class="img-fluid btn-image" style="width: 100px; height: 100px;">
                                    Carta X-Rm
                                </button>
                            </div>

                            <!-- Segunda fila -->
                            <div class="col-md-4 d-flex justify-content-center">
                                <button class="btn btn-primary btn-lg rounded-circle p-2 btn-shadow btn-vertical" data-toggle="modal" data-target="#modalAgregarFormularioP">
                                    <img src="vistas/modulos/imagenes/carta-p.png" class="img-fluid btn-image" style="width: 100px; height: 100px;">
                                    Carta P
                                </button>
                            </div>

                            <div class="col-md-4 d-flex justify-content-center">
                                <button class="btn btn-primary btn-lg rounded-circle p-2 btn-shadow btn-vertical" data-toggle="modal" data-target="#modalAgregarFormularioNP">
                                    <img src="vistas/modulos/imagenes/carta-np.png" class="img-fluid btn-image" style="width: 100px; height: 100px;">
                                    Carta np
                                </button>
                            </div>

                            <div class="col-md-4 d-flex justify-content-center">
                                <button class="btn btn-primary btn-lg rounded-circle p-2 btn-shadow btn-vertical" data-toggle="modal" data-target="#modalAgregarFormularioC">
                                    <img src="vistas/modulos/imagenes/carta-c.png" class="img-fluid btn-image" style="width: 100px; height: 100px;">
                                    Carta c
                                </button>
                            </div>

                            <!-- Tercera fila -->
                            <div class="col-md-4"></div>

                            <div class="col-md-4 d-flex justify-content-center">
                                <button class="btn btn-primary btn-lg  p-2 mx-auto btn-shadow btn-vertical" data-toggle="modal" data-target="#modalAgregarFormularioU">
                                    <img src="vistas/modulos/imagenes/carta-u.png" class="img-fluid btn-image" style="width: 100px; height: 100px;">
                                    Carta u
                                </button>
                            </div>

                            <div class="col-md-4"></div>

                        </div>

                    </section>

                </div>

            </div>

        </div>

    </section>

</div>
