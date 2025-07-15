<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
ini_set('max_execution_time', 4000);  // 300 segundos = 5 minutos
ini_set('memory_limit', '512M');
//error_reporting(E_ALL & ~E_WARNING & ~E_DEPRECATED);
?>
<!DOCTYPE html>
<html>

<head>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="<?php echo constant('URL'); ?>public/uploads/logo-eticket.png">
  <title>E-Tickets</title>
  <?php
  include_once 'models/usuariosperfil.php';
  $correoModel = new CorreoModel();
  foreach ($this->usuariosperfil as $row) {
    $usuariosperfil = new Usuariosperfil();
    $usuariosperfil = $row;
    $idusuario = $usuariosperfil->id_usuario;
    $menu = $usuariosperfil->menu;
    $habilitado = $usuariosperfil->habilitado;
    $principal = $usuariosperfil->principal;
    //$permiso = $usuariosperfil->permiso;
  }
  // Accede al primer elemento del array (aunque todos los elementos contienen el mismo valor de idusuario)
  $usuariosperfil0 = $this->usuariosperfil[0];
  $idusuario0 = $usuariosperfil0->idusuario;
  $menu = $usuariosperfil0->menu;
  $habilitado = $usuariosperfil0->habilitado;
  $principal = $usuariosperfil0->principal;
  $permiso = $usuariosperfil0->permiso;

  $_SESSION['permiso'] = $permiso;
  $_SESSION['idusuario'] = $idusuario0;

  $permiso = $this->permiso;
  $asignacion = $this->asignacion;
  ?>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

  <!-- Moment.js (requerido por daterangepicker) -->
  <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

  <!-- Daterangepicker -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <style>
    .main-sidebar {
      left: 0 !important;
    }

    .content-wrapper {
      margin-left: 0 !important;
      margin-top: -35 !important;

    }
  </style>
</head>

<body class="sidebar-mini layout-navbar-fixed sidebar-collapse sidebar-closed">
  <div class="wrapper">

    <?php require 'views/header.php' ?>
    <?php
    $correoModel = new CorreoModel();
    //CONTADORES ADMIN
    $noAsignados = $correoModel->getTicketsNoAsignados();
    $asignados = $correoModel->getTicketsAsignados();
    $finalizados = $correoModel->getTicketsFinalizados();
    $enProgresoAdmin = $correoModel->getTicketsEnProgreso();
    $realizados = $correoModel->getTicketsRealizados();

    //CONTADORES USUARIO
    $asignadosUsuario = $correoModel->getTicketsAsignadosUsuario($idusuario0);
    $enProgresoUsuario = $correoModel->getTicketsEnProgresoUsuario($idusuario0);
    $realizadoUsuario = $correoModel->getTicketsRealizadoUsuario($idusuario0);
    $finalizadosUsuario = $correoModel->getTicketsFinalizadosUsuario($idusuario0);
    ?>

    <div class="content-wrapper" style="margin-top: -35;">

      <style>
        /* Centra el contenedor de filtros */
        .card {
          max-width: 1100px;
          /* Antes era 800px */
          margin: 0 auto;
          padding: 20px;
        }

        /* Ajusta el espacio para el botón de sincronizar */
        .d-flex.justify-content-end {
          margin-top: 20px;
        }

        /* Se asegura de que el botón de sincronizar se vea destacado */
        #btnSincronizar {
          /* font-weight: bold;
          background-color:rgb(59, 134, 219);
          color: white; */
        }

        /* Opcionalmente, se puede añadir un fondo de color para destacarlo más */
        #btnSincronizar:hover {
          /*background-color:rgb(17, 179, 71);*/
        }

        .form-control {
          padding: 8px 10px;
          /* Menor que el padding normal de Bootstrap */
          font-size: 14px;
          /* Opcional: letra un poquito más pequeña */
        }

        .bg-purple {
          background-color: #6f42c1 !important;
          /* Un tono morado similar a Bootstrap's purple */
          color: #ffffff !important;
        }

        /* .card-purple {
          background-color: #6f42c1 !important;
          color: white;
        } */
        /* Ocultar inputs y mostrar solo íconos por defecto cuando sidebar está colapsado */
        body.sidebar-mini.sidebar-collapse .main-sidebar .filtro-input {
          display: none !important;
        }

        body.sidebar-mini.sidebar-collapse .main-sidebar .filtro-icono {
          display: inline-block !important;
          /* muestra los iconos en el side bard cuando esta minimizado */
          font-size: 1.3rem;
        }

        /* Al pasar el mouse sobre el sidebar, mostrar inputs y ocultar íconos */
        body.sidebar-mini.sidebar-collapse .main-sidebar:hover .filtro-input {
          display: block !important;
        }

        body.sidebar-mini.sidebar-collapse .main-sidebar:hover .filtro-icono {
          display: none !important;
        }

        /* Oculta los botones cuando el sidebar está colapsado */
        body.sidebar-mini.sidebar-collapse .main-sidebar .filtro-boton {
          display: none !important;
        }

        /* Muestra los botones al pasar el mouse sobre el sidebar */
        body.sidebar-mini.sidebar-collapse .main-sidebar:hover .filtro-boton {
          display: flex !important;
        }

        /* Oculta el label cuando el sidebar está colapsado */
        body.sidebar-mini.sidebar-collapse .main-sidebar .sidebar-etickets-label {
          display: none !important;
        }

        /* Muestra el label al hacer hover en el sidebar */
        body.sidebar-mini.sidebar-collapse .main-sidebar:hover .sidebar-etickets-label {
          display: block !important;
        }

        /* Ocultar el ícono cuando el sidebar está colapsado y en hover */
        body.sidebar-mini.sidebar-collapse .main-sidebar:hover .sidebar-etickets-icon {
          display: none !important;
        }

        /* Mostrar el label solo en hover */
        body.sidebar-mini.sidebar-collapse .main-sidebar .sidebar-etickets-label {
          display: none !important;
        }

        body.sidebar-mini.sidebar-collapse .main-sidebar:hover .sidebar-etickets-label {
          display: block !important;
        }


        /* ESTILOS SIDEBAR */
        /* Fondo general y textos */
        .main-sidebar {
          background: linear-gradient(180deg, #0056b3 0%, #007bff 100%);
          color: white;
          font-family: 'Segoe UI', sans-serif;
        }

        /* Título central */
        .sidebar-etickets-label {
          display: block;
          text-align: center;
          font-size: 1.1rem;
          font-weight: bold;
          margin-bottom: 15px;
          color: #f8f9fa;
        }

        /* Inputs y selects */
        .filtro-input input,
        .filtro-input select {
          background-color: #f1f1f1;
          border: none;
          border-radius: 4px;
          padding: 4px 8px;
          font-size: 0.9rem;
        }

        /* Labels */
        .filtro-input label,
        .form-group label {
          font-size: 0.85rem;
          color: #e2e6ea;
        }

        /* Íconos */
        .filtro-icono {
          /* color: #cce5ff; */
          color: #ffffff;
          font-size: 1rem;
        }

        /* Botones */
        .filtro-boton .btn-primary {
          background-color: #28a745;
          border: none;
        }

        .filtro-boton .btn-secondary {
          background-color: #6c757d;
          border: none;
        }

        .filtro-boton .btn:hover {
          opacity: 0.9;
        }

        /* Botón sincronizar */
        #btnSincronizar {
          /* background-color: #ffc107; */
          border: none;
          /* font-weight: bold; */
        }

        /* Hover efecto sidebar */
        .main-sidebar:hover .filtro-icono {
          color: #ffffff;
        }

        /* Transiciones suaves */
        .filtro-input input,
        .filtro-input select,
        .filtro-boton .btn,
        #btnSincronizar {
          transition: all 0.2s ease-in-out;
        }

        .content-wrapper {
          margin-top: -45px !important;
          /* ahora sí se aplicará */
        }

        /* oculta icono de barras desde boton hamburguesa desde dispositivos mobiles */
        body.ocultar-icono-etickets .sidebar-etickets-icon {
          display: none !important;
        }

        /* Ocultar el botón hamburguesa por defecto */
        /* Ocultar el botón hamburguesa por defecto */
        nav .nav-item .nav-link[data-widget="pushmenu"] {
          /* display: none; */
        }

        /* Mostrar el botón hamburguesa en dispositivos táctiles */
        body.touch-device nav .nav-item .nav-link[data-widget="pushmenu"] {
          display: block;
        }

        /* Compacta inputs/selects dentro del sidebar */
        .main-sidebar .form-group {
          margin-bottom: 4px !important;
        }

        /* Etiquetas más pequeñas y pegadas */
        .main-sidebar .filtro-input label {
          margin-bottom: 2px !important;
          font-size: 0.75rem !important;
          line-height: 1.1 !important;
        }

        /* Inputs y selects compactos */
        .main-sidebar .form-control-sm {
          padding: 2px 6px !important;
          height: auto !important;
          font-size: 0.75rem !important;
          line-height: 1.2 !important;
          margin-bottom: 4px !important;
        }

        /* Reduce espacio entre ícono y campos */
        .filtro-icono {
          margin-right: 5px !important;
          font-size: 0.9rem !important;
        }
      </style>



      <!-- TITULO -->
      <br>
      <div style="text-align: center; margin-top: 30px;">
        <h2
          style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 34px; color: #2c3e50; font-weight: 600; text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1); letter-spacing: 1px;">
          E-Tickets
        </h2>
      </div>


      <!-- CONTADORES -->
      <?php if ($permiso == 'admin') { ?>
        <section class="content">
          <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row justify-content-center">

              <!-- SIN ASIGNAR -->
              <div class="col-sm-2 col-6">
                <div class="small-box bg-info">
                  <div class="inner">
                    <h3><?php echo $noAsignados; ?></h3>

                    <p>Sin asignar</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-stats-bars" style="font-size: 50px; top: 10px;"></i>
                  </div>
                  <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
                </div>
              </div>

              <!-- ASIGNADOS A USUARIOS -->
              <div class="col-sm-2 col-6">
                <div class="small-box bg-primary">
                  <div class="inner" style="max-height: 112px;"> <!-- mantiene los contadores del mismo tamaño -->
                    <h3><?php echo $asignados; ?></h3>

                    <p>Asignados a usuarios</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-person-add" style="font-size: 50px; top: 10px;"></i>
                  </div>
                </div>
              </div>

              <!-- EN PROGRESO -->
              <div class="col-sm-2 col-6">
                <div class="small-box bg-warning">
                  <div class="inner">
                    <h3 style="color: white;"><?php echo $enProgresoAdmin; ?></h3>

                    <p style="color: white;">En progreso</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-stats-bars" style="font-size: 50px; top: 10px;"></i>
                  </div>
                  <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
                </div>
              </div>

              <!-- REALIZADOS -->
              <div class="col-sm-2 col-6">
                <div class="small-box bg-purple">
                  <div class="inner">
                    <h3><?php echo $realizados; ?></h3>
                    <p>Realizados</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-eye" style="font-size: 50px; top: 10px;"></i>
                  </div>
                </div>
              </div>

              <!-- FINALIZADOS -->
              <div class="col-sm-2 col-6">
                <div class="small-box bg-success">
                  <div class="inner">
                    <h3><?php echo $finalizados; ?></h3>

                    <p>Finalizados</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-pie-graph" style="font-size: 50px; top: 10px;"></i>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </section>
      <?php } else { ?>
        <section class="content">
          <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row justify-content-center">

              <!-- ASIGNADOS -->
              <div class="col-sm-2 col-6">
                <div class="small-box bg-info">
                  <div class="inner">
                    <h3><?php echo $asignadosUsuario; ?></h3>
                    <p>Asignados</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-stats-bars" style="font-size: 50px; top: 10px;"></i>
                  </div>
                </div>
              </div>

              <!-- EN PROGRESO -->
              <div class="col-sm-2 col-6">
                <div class="small-box bg-warning">
                  <div class="inner">
                    <h3 style="color: white;"><?php echo $enProgresoUsuario; ?></h3>
                    <p style="color: white;">En progreso</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-person-add" style="font-size: 50px; top: 10px;"></i>
                  </div>
                </div>
              </div>

              <!-- REALIZADOS -->
              <div class="col-sm-2 col-6">
                <div class="small-box bg-purple">
                  <div class="inner">
                    <h3><?php echo $realizadoUsuario; ?></h3>
                    <p>Realizados</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-eye" style="font-size: 50px; top: 10px;"></i>
                  </div>
                </div>
              </div>

              <!-- FINALIZADOS -->
              <div class="col-sm-2 col-6">
                <div class="small-box bg-success">
                  <div class="inner">
                    <h3><?php echo $finalizadosUsuario; ?></h3>
                    <p>Finalizados</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-pie-graph" style="font-size: 50px; top: 10px;"></i>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </section>
      <?php } ?>

      <!-- Main Sidebar Container -->
      <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #007bff; color: white;">
        <!-- Sidebar -->
        <div class="sidebar px-2">
          <!-- Filtros -->
          <div style="mt-5" class="">
            <i class="fas fa-chart-bar sidebar-etickets-icon" style="margin-left: 20px; margin-top: 20px;"></i>
            <label class="sidebar-etickets-label" style="margin-top: 20px; text-align: center;">Sistema de filtros<br>
              E-Tickets</label>
          </div>

          

          <hr style="border: none; border-top: 1px solid white;">


          <div class="mt-2">
            <!-- FECHA DE INICIO -->
            <div class="form-group d-flex align-items-center filtro-item">
              <i class="fas fa-calendar-alt me-2 filtro-icono"
                style="display: none; margin-left: 20px;margin-bottom: 20px;"></i>
              <div class="filtro-input w-100">
                <label for="fecha_inicio"><b>Fecha de Inicio</b></label>
                <input type="date" class="form-control form-control-sm mb-2" id="fecha_inicio" name="fecha_inicio">
              </div>
            </div>

            <!-- Fecha de Fin -->
            <div class="form-group filtro-fecha-fin">
              <label class="filtro-input" for="fecha_fin">Fecha de Fin</label>
              <i class="fas fa-calendar-alt filtro-icono" title="Fecha de Fin"
                style="display: none;margin-left: 20px;margin-bottom: 20px;"></i>
              <input type="date" class="form-control form-control-sm mb-2 filtro-input" id="fecha_fin" name="fecha_fin">
            </div>

            <!-- Usuario Asignado -->
            <?php if ($permiso == 'admin') { ?>
              <!-- Usuario Asignado -->
              <div class="form-group filtro-usuario-asignado">
                <label class="filtro-input" for="usuario_asignado">Usuario Asignado</label>
                <i class="fas fa-user filtro-icono" title="Usuario Asignado"
                  style="display: none;margin-left: 20px;margin-bottom: 20px;"></i>
                <select style="height: 35px;" class="form-control form-control-sm mb-2 filtro-input" id="usuario_asignado"
                  name="usuario_asignado">
                  <option value="0">Seleccionar usuario</option>
                  <?php foreach ($this->usuariosAsignables as $usuario): ?>
                    <option value="<?php echo $usuario->idusuario; ?>">
                      <?php echo $usuario->idusuario; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            <?php } ?>

            <!-- Estado -->
            <div class="form-group filtro-estado">
              <label class="filtro-input" for="estado">Estado</label>
              <i class="fas fa-tasks filtro-icono" title="Estado"
                style="display: none;margin-left: 20px;margin-bottom: 20px;"></i>
              <select style="height: 35px;" class="form-control form-control-sm mb-2 filtro-input" id="estado"
                name="estado">
                <option value="0">Seleccionar estado</option>
                <?php if ($permiso == 'admin') { ?>
                  <option value="1">Sin asignar</option>
                <?php } ?>
                <option value="2">Asignado</option>
                <option value="4">En progreso</option>
                <option value="6">Realizado</option>
                <option value="3">Finalizado</option>
                <?php if ($permiso == 'admin') { ?>
                  <option value="5">Eliminado</option>
                <?php } ?>
              </select>
            </div>

            <!-- Correo de origen -->
            <div class="form-group filtro-correo-origen">
              <label class="filtro-input" for="correo_origen">Correo de origen</label>
              <i class="fas fa-envelope filtro-icono" title="Correo de origen"
                style="display: none;margin-left: 20px;margin-bottom: 20px;"></i>
              <input type="text" placeholder="'gonzales' o 'gonzales@iopa.cl'"
                class="form-control form-control-sm mb-2 filtro-input" id="correo_origen" name="correo_origen">
            </div>

            <!-- Buscar por ID -->
            <div class="form-group filtro-id-ticket">
              <label class="filtro-input" for="id_ticket">ID Ticket</label>
              <i class="fas fa-hashtag filtro-icono" title="ID del ticket"
                style="display: none; margin-left: 20px; margin-bottom: 20px;"></i>
              <input type="text" placeholder="'R-123' o 'r-123' o '123'"
                    class="form-control form-control-sm mb-2 filtro-input" id="id_ticket" name="id_ticket">
            </div>

            <div class="form-group filtro-asunto">
              <label class="filtro-input" for="asunto">Asunto</label>
              <i class="fas fa-envelope-open-text filtro-icono" title="Asunto"
                style="display: none;margin-left: 20px;margin-bottom: 20px;"></i>
              <input type="text" placeholder="'Toner' o 'Re: Toner'"
                class="form-control form-control-sm mb-2 filtro-input" id="asunto" name="asunto">
            </div>
                
            <!-- Multirespuesta -->
            <div class="form-group filtro-multirespuesta">
              <label class="filtro-input" for="multirespuesta">Tipo de correo</label>
              <i class="fas fa-random filtro-icono" title="Multirespuesta"
                style="display: none;margin-left: 20px;margin-bottom: 20px;"></i>
              <select style="height: 35px;" class="form-control form-control-sm mb-3 filtro-input" id="multirespuesta"
                      name="multirespuesta">
                <option value="0">Seleccionar opción</option>
                <option value="1">Respuesta</option>
                <option value="2">Principal</option>
              </select>
            </div>

            <!-- Días desde la creación -->
            <div class="form-group filtro-dias-creacion">
              <label class="filtro-input" for="dias_creacion">Días desde la creación</label>
              <i class="fas fa-clock filtro-icono" title="Días desde la creación"
                style="display: none;margin-left: 20px;margin-bottom: 20px;"></i>
              <select style="height: 35px;" class="form-control form-control-sm mb-3 filtro-input" id="dias_creacion"
                name="dias_creacion">
                <option value="0">Seleccionar días</option>
                <option value="hoy">Hoy</option>
                <option value="1">Hace 1 día</option>
                <option value="2">Hace 2 días</option>
                <option value="3">Hace 3 días</option>
                <option value="5">Hace 5 días</option>
                <option value="mas_de_5">Más de 5 días</option>
              </select>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-between filtro-boton">
              <button class="btn btn-primary btn-sm w-50 me-1" onclick="filtrarCards();">Filtrar</button>
              <button style="margin-left: 5px;" class="btn btn-secondary btn-sm w-50" id="limpiar_filtros">Limpiar y
                recargar</button>
            </div>

            <hr style="border: none; border-top: 1px solid white;">

            <div class="form-group d-flex align-items-center filtro-item">
              <i class="fas fa-sync-alt me-2 filtro-icono" style="display: none; margin-left: 20px;"></i>
            </div>

            <div class="d-flex justify-content-between mb-2 filtro-boton">
              <button id="btnSincronizar" class="btn btn-light btn-sm w-100 me-1">Sincronizar E-Tickets</button>
            </div>

            <hr style="border: none; border-top: 1px solid white;">

            <div class="form-group d-flex align-items-center filtro-item">
              <i class="fas fa-chart-line me-2 filtro-icono" style="display: none; margin-left: 20px;"></i>
            </div>

            <div class="d-flex justify-content-between mb-2 filtro-boton">
              <button id="btnEstadisticas" class="btn btn-light open-estadisticas btn-sm w-100 me-1" data-toggle="modal"
                data-target="#modalEstadisticas">
                Estadísticas
              </button>
            </div>


<div id="respuestaEnvio" class="mt-3 text-info"></div>

          </div>

          <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
      </aside>

      <!-- FILTROS ANTIGUOS: display none-->
      <div class="card card-primary" style="display: none;max-width: 800px; margin: 0 auto; padding: 20px;">
        <div class="card-header text-center">
          <h3 class="card-title">Filtros de Búsqueda</h3>
        </div>
        <div class="card-body">
          <div class="row">
            <!-- Filtro por rango de fechas -->
            <div class="col-md-4 col-12 mb-1">
              <div class="form-group">
                <label for="fecha_inicio">Fecha de Inicio</label>
                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio"
                  placeholder="Seleccionar fecha de inicio">
              </div>
            </div>
            <div class="col-md-4 col-12 mb-1">
              <div class="form-group">
                <label for="fecha_fin">Fecha de Fin</label>
                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin"
                  placeholder="Seleccionar fecha de fin">
              </div>
            </div>

            <!-- Usuario asignado (solo admin) -->
            <?php if ($permiso == 'admin') { ?>
              <div class="col-md-4 col-12 mb-1">
                <div class="form-group">
                  <label for="usuario_asignado">Usuario Asignado</label>
                  <select class="form-control select2" id="usuario_asignado" name="usuario_asignado" style="width: 100%;">
                    <option value="0">Seleccionar usuario</option>
                    <?php foreach ($this->usuariosAsignables as $usuario): ?>
                      <option value="<?php echo $usuario->idusuario; ?>">
                        <?php echo $usuario->idusuario; ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            <?php } ?>

            <!-- Estado -->
            <div class="col-md-4 col-12 mb-1">
              <div class="form-group">
                <label for="estado">Estado</label>
                <select class="form-control" id="estado" name="estado">
                  <option value="0">Seleccionar estado</option>
                  <?php if ($permiso == 'admin') { ?>
                    <option value="1">Sin asignar</option> <?php } ?>
                  <option value="2">Asignado</option>
                  <option value="4">En progreso</option>
                  <option value="6">Realizado</option>
                  <option value="3">Finalizado</option>
                  <?php if ($permiso == 'admin') { ?>
                    <option value="5">Eliminado</option> <?php } ?>
                </select>
              </div>
            </div>

            <!-- Correo de origen -->
            <div class="col-md-4 col-12 mb-1">
              <div class="form-group">
                <label for="correo_origen">Correo de origen</label>
                <input type="text" class="form-control" id="correo_origen" name="correo_origen"
                  placeholder="Correo de origen" autocomplete="off">
              </div>
            </div>

            <!-- Nuevo Filtro: Días desde creación -->
            <div class="col-md-4 col-12 mb-1">
              <div class="form-group">
                <label for="dias_creacion">Días desde la creación</label>
                <select id="dias_creacion" name="dias_creacion" class="form-control">
                  <option value="0">Seleccionar días de creación</option>
                  <option value="hoy">Hoy</option>
                  <option value="1">Hace 1 día</option>
                  <option value="2">Hace 2 días</option>
                  <option value="3">Hace 3 días</option>
                  <option value="5">Hace 5 días</option>
                  <option value="mas_de_5">Más de 5 días</option>
                </select>
              </div>
            </div>

          </div>

          <div class="d-flex justify-content-between align-items-center mt-1 flex-wrap">

            <!-- Botones de filtrar y limpiar -->
            <div>
              <button id="btnSincronizar" class="btn btn-light">Sincronizar E-Tickets</button>
            </div>
            <div class="d-flex gap-2">
              <button type="button" class="btn btn-primary" onclick="filtrarCards();">Filtrar</button>
              <button style="margin-left: 2px;" type="button" class="btn btn-secondary" id="limpiar_filtros">Limpiar
                Filtros</button>
            </div>
          </div>

        </div>
      </div>
      <!-- FILTROS ANTIGUOS: display none-->


      <!-- Rescata permisos para la consulta -->
      <meta id="permiso" data-permiso="<?php echo $permiso; ?>">
      <meta id="asignacion" data-asignacion="<?php echo $idusuario0; ?>">

      <script>
        var permiso = document.getElementById("permiso").getAttribute("data-permiso");
        var asignacion = document.getElementById("asignacion").getAttribute("data-asignacion");
      </script>


      <br>
      <!-- PAGINADOR SUPERIOR -->
      <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
          <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">

              <!-- Ir a la primera página -->
              <?php if ($this->paginaactual > 1): ?>
                <li class="page-item">
                  <a class="page-link text-info btn-paginacion" href="#" data-pagina="1" title="Primera página">
                    <i class="fas fa-angle-double-left"></i>
                  </a>
                </li>
              <?php endif; ?>

              <!-- Botón Anterior -->
              <li class="page-item <?php echo $this->paginaactual <= 1 ? 'disabled' : ''; ?>">
                <a class="page-link text-info btn-paginacion" href="#"
                  data-pagina="<?php echo $this->paginaactual - 1; ?>">
                  <i class="fas fa-angle-left"></i>
                </a>
              </li>

              <?php
              $total_paginas = $this->paginas;
              $pagina_actual = $this->paginaactual;
              $visible = 5;

              $mitad = floor($visible / 2);
              $start = max(1, $pagina_actual - $mitad);
              $end = min($start + $visible - 1, $total_paginas);

              if ($end - $start + 1 < $visible) {
                $start = max(1, $end - $visible + 1);
              }

              for ($i = $start; $i <= $end; $i++):
                ?>
                <li class="page-item <?php echo $pagina_actual == $i ? 'active' : ''; ?>">
                  <a class="page-link btn-paginacion" href="#" data-pagina="<?php echo $i; ?>">
                    <?php echo $i; ?>
                  </a>
                </li>
              <?php endfor; ?>

              <!-- Botón Siguiente -->
              <li class="page-item <?php echo $pagina_actual >= $total_paginas ? 'disabled' : ''; ?>">
                <a class="page-link text-info btn-paginacion" href="#" data-pagina="<?php echo $pagina_actual + 1; ?>">
                  <i class="fas fa-angle-right"></i>
                </a>
              </li>

              <!-- Ir a la última página -->
              <?php if ($pagina_actual < $total_paginas): ?>
                <li class="page-item">
                  <a class="page-link text-info btn-paginacion" href="#" data-pagina="<?php echo $total_paginas; ?>"
                    title="Última página">
                    <i class="fas fa-angle-double-right"></i>
                  </a>
                </li>
              <?php endif; ?>

            </ul>
          </nav>
        </div>
      </div>

      <?php
      $registros_mostrados = count($this->correo); // lo que estás mostrando en esta página
      $total = $this->total_registros;

      $inicio = ($this->paginaactual - 1) * $this->registros_por_pagina + 1;
      $fin = $inicio + $registros_mostrados - 1;
      ?>
      <div class="text-center text-muted mt-2">
        Mostrando <?php echo $registros_mostrados; ?> registro<?php echo $registros_mostrados == 1 ? '' : 's'; ?> de <?php echo $total; ?>
      </div>


      <!-- CARDS -->
      <div id="container-full">
        <?php
        $correos = $this->correo;
        $estadisticas = $this->estadisticas;
        $historial = $this->historial;


        include 'views/correo/cards.php';
        ?>
      </div>

      <br>
      <!-- PAGINADOR INFERIOR -->
      <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
          <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">

              <!-- Ir a la primera página -->
              <?php if ($this->paginaactual > 1): ?>
                <li class="page-item">
                  <a class="page-link text-info btn-paginacion" href="#" data-pagina="1" title="Primera página">
                    <i class="fas fa-angle-double-left"></i>
                  </a>
                </li>
              <?php endif; ?>

              <!-- Botón Anterior -->
              <li class="page-item <?php echo $this->paginaactual <= 1 ? 'disabled' : ''; ?>">
                <a class="page-link text-info btn-paginacion" href="#"
                  data-pagina="<?php echo $this->paginaactual - 1; ?>">
                  <i class="fas fa-angle-left"></i>
                </a>
              </li>

              <?php
              $total_paginas = $this->paginas;
              $pagina_actual = $this->paginaactual;
              $visible = 5;

              $mitad = floor($visible / 2);
              $start = max(1, $pagina_actual - $mitad);
              $end = min($start + $visible - 1, $total_paginas);

              if ($end - $start + 1 < $visible) {
                $start = max(1, $end - $visible + 1);
              }

              for ($i = $start; $i <= $end; $i++):
                ?>
                <li class="page-item <?php echo $pagina_actual == $i ? 'active' : ''; ?>">
                  <a class="page-link btn-paginacion" href="#" data-pagina="<?php echo $i; ?>">
                    <?php echo $i; ?>
                  </a>
                </li>
              <?php endfor; ?>

              <!-- Botón Siguiente -->
              <li class="page-item <?php echo $pagina_actual >= $total_paginas ? 'disabled' : ''; ?>">
                <a class="page-link text-info btn-paginacion" href="#" data-pagina="<?php echo $pagina_actual + 1; ?>">
                  <i class="fas fa-angle-right"></i>
                </a>
              </li>

              <!-- Ir a la última página -->
              <?php if ($pagina_actual < $total_paginas): ?>
                <li class="page-item">
                  <a class="page-link text-info btn-paginacion" href="#" data-pagina="<?php echo $total_paginas; ?>"
                    title="Última página">
                    <i class="fas fa-angle-double-right"></i>
                  </a>
                </li>
              <?php endif; ?>

            </ul>
          </nav>
        </div>
      </div>

      

      <!-- MODAL DINAMICO DETALLE -->
      <div class="modal fade" id="modalDetalle" tabindex="-1" role="dialog" aria-labelledby="modalDetalleLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document"> <!-- modal-lg para variar tamaño -->
          <div class="modal-content">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title" id="modalDetalleLabel">Detalles del Ticket</h5>
            </div>
            <div class="modal-body" id="modalDetalleBody">
              <!-- Contenido dinámico aquí -->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>

      <!-- MODAL DINAMICO CONTENIDO -->
      <div class="modal fade" id="modalContenido" tabindex="-1" role="dialog" aria-labelledby="modalContenidoLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title" id="modalContenidoLabel">Contenido del Ticket</h5>
            </div>
            <div class="modal-body" id="modalContenidoBody" style="max-height: 70vh; overflow-y: auto;">
              <!-- Contenido dinámico aquí -->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>

      <!-- MODAL ASIGNAR ASIGNACION -->
      <div class="modal fade" id="modalAsignacion" tabindex="-1" role="dialog" aria-labelledby="modalAsignacionLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title" id="modalAsignacionLabel">Asignación del ticket #</h5>
            </div>
            <div class="modal-body" id="modalAsignacionBody">
            </div>
            <div class="modal-footer" id="modalAsignacionFooter">
              <button type="button" class="btn btn-light" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>

      <!-- MODAL EDITAR ESTADO -->
      <div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title" id="modalEditarLabel">Estado del Ticket</h5>
            </div>
            <div class="modal-body" id="modalEditarBody">
              <!-- Contenido dinámico desde JS -->
            </div>
            <div class="modal-footer" id="modalEditarFooter">
            </div>
          </div>
        </div>
      </div>

      <!-- MODAL CAMBIAR ESTADO (usuarios no admin) -->
      <div class="modal fade" id="modalCambiarEstado" tabindex="-1" role="dialog" aria-labelledby="modalCambiarLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title" id="modalCambiarLabel"></h5>
            </div>
            <div class="modal-body" id="modalCambiarBody">
              <!-- Contenido dinámico desde JS -->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-bs-dismiss="modal" data-dismiss="modal">Cerrar</button>
              <button type="button" class="btn btn-success guardar-cambio-estado">Guardar</button>
            </div>
          </div>
        </div>
      </div>

      <!-- MODAL DINAMICO ESTADÍSTICAS -->
      <div class="modal fade" id="modalEstadisticas" tabindex="-1" aria-labelledby="modalEstadisticasLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title" id="modalEstadisticasLabel">Estadísticas de usuarios</h5>
            </div>
            <div class="modal-body" id="modalEstadisticasBody" style="max-height: 70vh; overflow-y: auto;">
              <!-- Contenido dinámico de estadísticas aquí -->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>

      <!-- MODAL DINAMICO HISTORIAL -->
      <div class="modal fade" id="modalHistorial" tabindex="-1" role="dialog" aria-labelledby="modalHistorialLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document"> <!-- modal-lg para variar tamaño -->
          <div class="modal-content">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title" id="modalHistorialLabel">historial del Ticket</h5>
            </div>
            <div class="modal-body" id="modalHistorialBody">
              <!-- Contenido dinámico aquí -->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>

      <!-- MODAL DINÁMICO HILO -->
      <div class="modal fade" id="modalHilo" tabindex="-1" role="dialog" aria-labelledby="modalHiloLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document"> <!-- modal-xl para mostrar múltiples iframes -->
          <div class="modal-content">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title" id="modalHiloLabel">Hilo de respuestas del Ticket</h5>
            </div>
            <div class="modal-body" id="modalHiloBody" style="max-height: 70vh; overflow-y: auto;">
              <!-- Aquí se cargarán dinámicamente los iframes de cada respuesta -->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>

      <script>

        if (window.matchMedia("(pointer: coarse)").matches) {
          // Dispositivo con pantalla táctil
          //console.log("Pantalla táctil detectada");
        } else {
          // Dispositivo sin pantalla táctil
          //console.log("Pantalla no táctil detectada");
        }

        $(document).ready(function () {
          const correos = <?php echo json_encode($this->correo); ?>;
          const usuarios = <?php echo json_encode($this->asignaciones); ?>;
          var estadisticas = <?php echo json_encode($this->estadisticas); ?>;
          var estadisticasEnProgreso = <?php echo json_encode($this->estadisticasEnProgreso); ?>;

          //console.log("estadisticasEnProgreso");
          //console.log(estadisticasEnProgreso);


          // CONTENIDO
          $('.open-contenido-modal').on('click', function () {
            const uid = $(this).data('id');
            const correo = correos.find(c => c.uid == uid);

            // HILO EN CADENA
            const correoRespuesta = <?php echo json_encode($this->correoRespuesta); ?>;
            const message_id = $(this).data('message-id'); // message_id del correo principal

            // ------------ DEBUG ------------
            //console.log("UID clickeado:", uid);
            //console.log("Message ID clickeado:", message_id);
            const payload = {
              UID_clickeado: uid,
              Message_ID_clickeado: message_id
            };
            console.log("CONTENIDO - HILO: ",payload);
            // ------------ DEBUG ------------

            const hiloDescendente = [];

            function buscarRespuestas(messageIdPadre) {
              const hijos = correoRespuesta.filter(c =>
                c.in_reply_to === messageIdPadre &&
                c.multirespuesta == 1 &&
                !hiloDescendente.some(r => r.uid == c.uid) // evitar duplicados
              );

              for (const hijo of hijos) {
                hiloDescendente.push(hijo);
                buscarRespuestas(hijo.message_id); // recursivamente buscar más respuestas
              }
            }

            buscarRespuestas(message_id);

            // Ordenar el hilo por fecha de envío de más antigua a más reciente
            hiloDescendente.sort((a, b) => new Date(a.fecha_envio) - new Date(b.fecha_envio));

            let ultimaRespuesta = null;
            if (hiloDescendente.length > 0) {
              ultimaRespuesta = hiloDescendente[hiloDescendente.length - 1]; // la más reciente ahora sí por fecha
            }

            const soporteCorreo = 'soporte@iopa.cl';
            // Para el primer correo (sin hilo)
            const fusionCorreoPrincipal = [correo.correo_origen, correo.correo_destino]
              .filter(email => email && email.toLowerCase() !== soporteCorreo)
              .join(', ');

            // Para el último correo del hilo (si hay respuestas)
            let fusionUltimaRespuesta = '';
            if (ultimaRespuesta) {
              fusionUltimaRespuesta = [ultimaRespuesta.correo_origen, ultimaRespuesta.correo_destino]
                .filter(email => email && email.toLowerCase() !== soporteCorreo)
                .join(', ');
            }



            // 2. Construir HTML, empezando por el correo principal
            $('[data-tooltip="tooltip"]').tooltip();
            

            if (correo) {
                let html = `
                  <div style="margin-bottom: 40px; padding: 20px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); background-color: #f9f9f9;">`;
                  if (hiloDescendente.length !== 0) {
                    html += `<em style="color: #555; display: block; margin-bottom: 20px;">
                            Las respuestas asociadas a este ticket están ordenadas cronológicamente: la respuesta #1 es la más antigua y la ultima respuesta es la más reciente.
                          </em>`;
                  }
                  html += `
                    <h5 style="margin-bottom: 10px; color: #555;">
                      Correo principal
                    </h5>
                    <p><strong>Asunto Ticket #${correo.uid}:</strong> ${correo.asunto}</p>
                        <p style="margin-top: -20px;"><strong>Respuesta de:</strong> ${correo.correo_origen}</p>
                        <p style="margin-top: -20px;"><strong>Dirigido a:</strong> ${correo.correo_destino}</p>
                        <p style="margin-top: -20px;"><strong>CC:</strong> ${correo.cc}</p>
                        <p style="margin-top: -20px;">
                          <strong>Fecha de recepción:</strong> ${correo.fecha_envio}
                          <i class="text-success" data-tooltip="tooltip" title="Tiempo transcurrido desde su creación en la base de datos.">ⓘ</i>
                        </p>
                    <iframe 
                      src="/eticket/public/correos_html/${correo.uid}.html" 
                      width="100%" 
                      height="600px" 
                      style="border: 1px solid #ddd; border-radius: 8px;" 
                      frameborder="0"
                      onerror="this.parentNode.innerHTML='<p class=\\'text-danger\\'>No se pudo cargar el contenido.</p>'">
                    </iframe>
                  </div>
                `;

                if (hiloDescendente.length === 0) {
                    html += `
                    <div class="mt-4 p-3 border rounded bg-light shadow-sm" style="border-left: 5px solid #0d6efd;">
                      <h6 class="text-primary mb-3">Respuesta al usuario</h6>
                      <button id="btnResponderUsuarioFinal" class="btn btn-sm btn-outline-primary mb-3">Redactar respuesta</button>
                    `;
                  

                  html += `
                    <!-- AREA SI NO HAY RESPUESTAS (PRIMERA RESPUESTA) -->
                    <div id="textareaResponderSinHilo" style="display: none;">
                      <div class="form-group">
                        <label class="form-label" style="color: black;">Respuesta:</label>
                        <textarea class="form-control mb-2" rows="4" placeholder="Escribe una respuesta para el usuario..."></textarea>
                      </div>

                      <div class="alert alert-info small mb-0">
                        Esta será la primera respuesta del ticket.<hr>
                        ${correo.correo_origen === 'soporte@iopa.cl' 
                          ? '<strong>Autorrespuesta detectada. El correo será enviado a: </strong>' + fusionCorreoPrincipal
                          : '<strong>Esta respuesta será enviada a: </strong>' + correo.correo_origen} <br>
                        <strong>Con copia a: </strong>${correo.cc ? correo.cc :  'N/A'} <br>
                        <strong>Con asunto: </strong>${correo.asunto}
                        <hr>
                        <strong>Datos del ticket al que se esta respondiendo:</strong><br>
                        <strong>UID:</strong> ${correo.uid} <br>
                        <strong>Correo de origen:</strong> ${correo.correo_origen}
                        <br><strong>Correo de destino:</strong> ${correo.correo_destino || 'No especificado'}
                        ${correo.cc ? `<br><strong>CC:</strong> <em>${correo.cc}</em>` : ''}
                        <br><strong>Asunto:</strong> <em>${correo.asunto}</em>
                        <br><strong>Fecha de envío:</strong> ${correo.fecha_envio}
                        <hr>
                        <strong>References:</strong> ${correo.references || 'N/A'}
                        <hr>
                        <button 
                          class="btn btn-primary btn-sm mt-3 btn-enviar-respuesta" 
                          data-uid="${correo.uid}"
                          data-correo-origen="${correo.correo_origen}"
                          data-correo-destino="${correo.correo_destino || 'No especificado'}"
                          data-cc="${correo.cc || ''}"data-asunto="${correo.asunto}"
                          data-fecha="${correo.fecha_envio}"
                          data-message-id="${correo.message_id}"
                          data-in-reply-to="${correo.in_reply_to}"
                          data-fusion="${fusionCorreoPrincipal}"
                          data-references="${correo.references || 'N/A'}">Enviar</button>
                      </div>
                    </div>
                  </div>`;

                }
                else{
                  
                    html += `
                    <div class="mt-4 p-3 border rounded bg-light shadow-sm" style="border-left: 5px solid #0d6efd;">
                      <h6 class="text-primary mb-3">Respuesta al usuario</h6>
                      <button id="btnResponderUsuarioFinal" class="btn btn-sm btn-outline-primary mb-3">Redactar respuesta</button>
                    `;

                  html += `
                    <!-- AREA SI EXISTEN RESPUESTAS PREVIAS -->
                    <div id="textareaResponderConHilo" style="display: none;">
                      <div class="form-group">
                        <label class="form-label" style="color: black;">Respuesta:</label>
                        <textarea class="form-control mb-2" rows="4" placeholder="Escribe una respuesta para continuar con el hilo..."></textarea>
                      </div>

                      <div class="alert alert-info small mb-0">
                        Esta respuesta se enviará automáticamente como continuación del ticket.<hr> 
                        ${ultimaRespuesta.correo_origen === 'soporte@iopa.cl' 
                          ? '<strong>Autorrespuesta detectada. El correo será enviado a: </strong>' + fusionUltimaRespuesta
                          : '<strong>Esta respuesta será enviada a: </strong>' + fusionUltimaRespuesta} <br>
                        <strong>Con copia a: </strong>${ultimaRespuesta?.cc ? ultimaRespuesta?.cc : 'N/A'} <br>
                        <strong>Con asunto: </strong>${ultimaRespuesta?.asunto}
                        <hr>
                        <strong>Datos del ticket que se está respondiendo:</strong><br>
                        <strong>UID:</strong> ${ultimaRespuesta?.uid} <br>
                        <strong>Correo de origen:</strong> ${ultimaRespuesta?.correo_origen}
                        <br><strong>Correo de destino:</strong> ${ultimaRespuesta?.correo_destino}
                        ${ultimaRespuesta?.cc ? `<br><strong>CC:</strong> <em>${ultimaRespuesta.cc}</em>` : ''}
                        <br><strong>Asunto:</strong> <em>${ultimaRespuesta?.asunto}</em>
                        <br><strong>Fecha de envío:</strong> ${ultimaRespuesta?.fecha_envio}
                        <hr>
                        <strong>References:</strong> ${ultimaRespuesta?.references || 'N/A'}
                        <hr>
                        <button 
                          class="btn btn-primary btn-sm mt-3 btn-enviar-respuesta" 
                          data-uid="${ultimaRespuesta.uid}" 
                          data-correo-origen="${ultimaRespuesta.correo_origen}"
                          data-correo-destino="${ultimaRespuesta.correo_destino || 'No especificado'}" 
                          data-cc="${ultimaRespuesta.cc || ''}"data-asunto="${ultimaRespuesta.asunto}" 
                          data-fecha="${ultimaRespuesta.fecha_envio}" 
                          data-message-id="${ultimaRespuesta.message_id}"
                          data-in-reply-to="${ultimaRespuesta.in_reply_to}"
                          data-fusion="${fusionUltimaRespuesta}"
                          data-references="${ultimaRespuesta.references || 'N/A'}">Enviar
                        </button>
                      </div>
                    </div>

                  </div>
                `;
                }
                

                if (hiloDescendente.length === 0) {
                  html += `
                    <div class="text-center p-4" style="color: #666;">
                      <i class="bi bi-info-circle" style="font-size: 2rem;"></i>
                      <p class="mt-2 mb-0">Este correo no tiene respuestas asociadas.</p>
                    </div>
                  `;
                } 
                else {
                  
                  hiloDescendente.reverse();
                  hiloDescendente.forEach((correoRespuesta, index) => {
                    const respuestaNumero = hiloDescendente.length - index;
                    html += `
                      <div style="margin-bottom: 40px; padding: 20px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); background-color: #f9f9f9;">
                      
                        <h5 style="margin-bottom: 10px; color: #555;">
                          Respuesta Nro. #${respuestaNumero}
                        </h5>
                        <p><strong>Asunto Ticket #${correoRespuesta.uid}:</strong> ${correoRespuesta.asunto}</p>
                        <p style="margin-top: -20px;"><strong>Respuesta de:</strong> ${correoRespuesta.correo_origen}</p>
                        <p style="margin-top: -20px;"><strong>Dirigido a:</strong> ${correoRespuesta.correo_destino}</p>
                        <p style="margin-top: -20px;"><strong>CC:</strong> ${correoRespuesta.cc}</p>
                        <p style="margin-top: -20px;">
                          <strong>Fecha de recepción:</strong> ${correoRespuesta.fecha_envio}
                          <i class="text-success" data-tooltip="tooltip" title="Tiempo transcurrido desde su creación en la base de datos.">ⓘ</i>
                        </p>
                        <iframe 
                          src="/eticket/public/correos_html/${correoRespuesta.uid}.html" 
                          width="100%" 
                          height="600px" 
                          style="border: 1px solid #ddd; border-radius: 8px;" 
                          frameborder="0"
                          onerror="this.parentNode.innerHTML='<p class=\\'text-danger\\'>No se pudo cargar el contenido.</p>'">
                        </iframe>
                      </div>
                    `;
                  });
                }

                $('#modalContenidoLabel').text('Contenido de Ticket #' + correo.uid);
                $('#modalContenidoBody').html(html);

                
                // ---------------  TEXT AREA RESPUESTA AL USUARIO ---------------
                $('#btnResponderUsuarioFinal').on('click', function () {
                  if (hiloDescendente.length > 0) {
                    $('#textareaResponderConHilo').toggle();
                    $('#textareaResponderSinHilo').hide();
                  } else {
                    $('#textareaResponderSinHilo').toggle();
                    $('#textareaResponderConHilo').hide();
                  }
                });
                // ---------------  FIN TEXT RESPUESTA AL USUARIO ---------------
            }

          });

          // DETALLE
          $('.open-detalle-modal').on('click', function () {
            const uid = $(this).data('id');
            const correo = correos.find(c => c.uid == uid);

            if (correo) {
              $('#modalDetalleLabel').text('Detalles de Ticket #' + correo.uid);
              $('#modalDetalleBody').html(`
                <h5><strong>Estado:</strong> ${correo.estado == 1 ? 'No asignado' : (correo.estado == 2 ? 'Asignado' : 'Finalizado')}</h5>
                <h5><strong>Origen:</strong> ${correo.correo_origen}</h5>
                <h5><strong>Destino:</strong> <span style="word-break: break-all;">${correo.correo_destino ? correo.correo_destino : 'No posee'}</span></h5>
                <h5><strong>Asunto:</strong> <span style="word-break: break-all;">${correo.asunto ? correo.asunto : 'No posee'}</span></h5>
                <h5><strong>Fecha:</strong> ${correo.fecha_envio}</h5>
                <hr class="detalle">
                <h5><strong>Identificador único:</strong> <span style="word-break: break-all;">${correo.message_id ? correo.message_id : 'No posee'}</span></h5>
                <h5><strong>Correo respuesta:</strong> ${correo.multirespuesta == 1 ? 'Sí' : 'No'}</h5>
                <h5><strong>En respuesta a:</strong> <span style="word-break: break-all;">${correo.in_reply_to ? correo.in_reply_to : 'No aplica'}</span></h5>
              `);
            }
          });

          // ASIGNACION
          $('.open-asignacion-modal').on('click', function () {
            const uid = $(this).data('id');
            const fecha_envio = $(this).data('fecha');
            const asunto = $(this).data('asunto');
            const correo = correos.find(c => c.uid == uid);


            if (correo) {
              const opciones = usuarios.map(u => `
                <option value="${u.idusuario}" ${correo.asignado == u.idusuario ? 'selected' : ''}>${u.idusuario}</option>
              `).join('');

              $('#modalAsignacionLabel').text('Asignación del ticket #' + correo.uid);
              $('#modalAsignacionBody').html(`
                <form>
                  <label for="selectUsuario-${correo.uid}">Asignar a:</label>
                  <select id="selectUsuario-${correo.uid}" class="form-control"
                          data-fecha="${fecha_envio}" 
                          data-asunto="${asunto}">
                    <option value="0" ${correo.asignado == null ? 'selected' : ''}>Seleccionar usuario</option>
                    ${opciones}
                  </select>
                  <br>
                  <em>Se enviará una notificación al usuario que se seleccione, indicando la asignación de este ticket.</em>
                </form>
                
                    `);

              $('#modalAsignacion .modal-footer').html(`
                <button type="button" class="btn btn-light" data-dismiss="modal">Cerrar</button>
                <button 
                  type="button" 
                  class="btn btn-success guardar-asignacion" 
                  data-uid="${correo.uid}"
                  data-fecha="${fecha_envio}" 
                  data-asunto="${asunto}"
                  data-estado-actual="${correo.estado}">
                  Guardar
                </button>
              `);

            }
          });

          // ESTADO
          $('.open-editar-modal').on('click', function () {
            const uid = $(this).data('id');
            const correo = correos.find(c => c.uid == uid);
            const asignado = $(this).data('asignado');
            const estado_actual = $(this).data('estado-actual');

            if (correo) {
              const estados = {
                1: 'No asignado',
                2: 'Asignado',
                4: 'En progreso',
                6: 'Realizado',
                3: 'Finalizado',
                5: 'Eliminado'
              };

              //const options = Object.entries(estados).map(([val, label]) => {
              //// Si el correo está asignado, solo permitir estos estados
              //if (asignado && asignado.trim() !== '') {
              //  // Mostrar solo 2 (Asignado), 3 (Finalizado), 4 (En progreso), 6 (Realizado)
              //  if (![2, 3, 4, 6].includes(parseInt(val))) return '';
              //}

              const options = Object.entries(estados).map(([val, label]) => `
          <option value="${val}" ${correo.estado == val ? 'selected' : ''}>${label}</option>
        `).join('');

              $('#modalEditarLabel').text('Estado del Ticket #' + uid);
              $('#modalEditarBody').html(`
          <form id="editarEstadoForm">
            <label for="selectEstado-${correo.uid}">Nuevo estado:</label> 
            <i class="fas fa-info-circle text-info ml-2" 
            data-html="true"  
            data-tooltip="tooltip" 
            title="Cambiar el estado manualmente puede alterar el flujo normal del ticket.<br>Úsalo con criterio administrativo.">
            </i>
            <select id="selectEstado-${correo.uid}" class="form-control">
              ${options}
            </select>
            <div id="textareaContainer" class="mt-3" style="display: none;">
              <label for="comentarioEstado">Comentario:</label>
              <textarea id="comentarioEstado" class="form-control" rows="3" placeholder="Escribe un comentario..."></textarea>
              <em>El comentario será enviado en forma de respuesta automática al correo de origen: <strong>${correo.correo_origen}</strong></em>
            </div>
            <div id="textareaContainerDesarrollador" class="mt-3" style="display: none;">
              <label for="comentarioEstadoDesarrollador">Comentario del responsable:</label>
              <textarea id="comentarioEstadoDesarrollador" class="form-control" rows="3" placeholder="Escribe un comentario..."></textarea>
              <em>El comentario será registrado como información para el(los) usuario(s) final(es) asociado(s) a: <strong>${correo.correo_origen}</strong></em>
            </div>
            <input type="hidden" id="editarUid" value="${correo.uid}">
          </form>
          
        `);
              $('[data-tooltip="tooltip"]').tooltip(); // Para inicializar los tooltip dinamicamente

              $('#modalEditar .modal-footer').html(`
          <button type="button" class="btn btn-light" data-bs-dismiss="modal" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-success guardar-editar-estado" data-estado-actual="${estado_actual}" data-asignado="${asignado}" data-uid="${correo.uid}" data-asunto='${correo.asunto}' data-fecha='${correo.fecha_envio}' data-correo-origen='${correo.correo_origen}'>Guardar</button>
        `);

              // ---------------  TEXT AREA FINALIZADO ---------------
              $(`#selectEstado-${correo.uid}`).on('change', function () {
                const selectedValue = $(this).val();
                if (selectedValue == "3") { // Finalizado
                  $('#textareaContainer').show();
                } else {
                  $('#textareaContainer').hide();
                }
              });

              // Si ya está seleccionado "Finalizado" al abrir el modal, mostramos el textarea VALIDAR
              if (correo.estado == 3) {
                $('#textareaContainer').show();
              }
              // ---------------  FIN TEXT AREA FINALIZADO ---------------

              // ---------------  TEXT AREA DESARROLLADOR ---------------
              $(`#selectEstado-${correo.uid}`).on('change', function () {
                const selectedValue = $(this).val();
                if (selectedValue == "6") { // Finalizado
                  $('#textareaContainerDesarrollador').show();
                } else {
                  $('#textareaContainerDesarrollador').hide();
                }
              });

              // Si ya está seleccionado "Finalizado" al abrir el modal, mostramos el textarea VALIDAR
              if (correo.estado == 6) {
                $('#textareaContainerDesarrollador').show();
              }
              // ---------------  FIN TEXT AREA DESARROLLADOR ---------------

              new bootstrap.Modal(document.getElementById('modalEditar')).show();
            }
          });

          // CAMBIAR ESTADO (para usuarios sin privilegios)
          $('.open-cambiar-modal').on('click', function () {
            const uid = $(this).data('id');
            const correo = correos.find(c => c.uid == uid);

            if (correo) {
              const estados = {
                4: 'En progreso',
                6: 'Realizado'
              };

              const options = Object.entries(estados).map(([val, label]) => `
          <option value="${val}" ${correo.estado == val ? 'selected' : ''}>${label}</option>
        `).join('');

              $('#modalCambiarLabel').text('Actualizar Estado del Ticket #' + uid);
              $('#modalCambiarBody').html(`
          <form>
            <label for="selectEstadoCambiar-${uid}">Nuevo estado:</label>
            <select id="selectEstadoCambiar-${uid}" class="form-control">
              ${options}
            </select>
            <input type="hidden" id="cambiarUid" value="${uid}">
          </form>
        `);

              //new bootstrap.Modal(document.getElementById('modalCambiarEstado')).show();
            }
          });

          // ESTADISTICAS
          $('.open-estadisticas').on('click', function () {
            if (estadisticas.length === 0) {
              $('#modalEstadisticasBody').html('<p class="text-muted">No hay estadísticas disponibles.</p>');
              return;
            }

            let tabla = `
                <div class="table-responsive">
                  <table class="table table-striped table-bordered table-hover">
                    <p>Detalles generales de tickets: </p>
                    <thead class="thead text-center">
                      <tr>
                        <th class="bg-primary text-white">Usuario</th>
                        <th class="bg-primary text-white">Asignado</th>
                        <th class="bg-warning text-white">En progreso</th>
                        <th class="bg-purple text-white" style="background-color: #6f42c1;">Realizado</th>
                        <th class="bg-success text-white">Finalizado</th>
                      </tr>
                    </thead>
                    <tbody>
              `;

            estadisticas.forEach(item => {
              tabla += `
                  <tr class="text-center">
                    <td style="word-break: break-all;">${item.usuario}</td>
                    <td>${item.asignado}</td>
                    <td>${item.en_progreso}</td>
                    <td>${item.realizado}</td>
                    <td>${item.finalizado}</td>
                  </tr>
                `;
            });

            tabla += `
                    </tbody>
                  </table>
                </div>
              `;

            // Añadir la segunda tabla para las estadísticas en progreso
            if (estadisticasEnProgreso.length > 0) {
              tabla += `
                  <div class="table-responsive mt-4">
                    <table class="table table-striped table-bordered table-hover">
                      <p>Detalles de tickets en progreso: </p>
                      <thead class="thead text-center">
                        <tr>
                          <th class="bg-warning text-white">Ticket</th>
                          <th class="bg-warning text-white">Usuario</th>
                          <th class="bg-warning text-white">Asunto</th>
                          <th class="bg-warning text-white">Fecha de recepción</th>
                          <th class="bg-warning text-white">Tiempo transcurrido</th>
                        </tr>
                      </thead>
                      <tbody>
                `;

              estadisticasEnProgreso.forEach(item => {
                tabla += `
                    <tr class="text-center">
                      <td style="word-break: break-all;">#${item.uid}</td>
                      <td>${item.asignado}</td>
                      <td style="word-break: break-all;">${item.asunto}</td>
                      <td>${item.fecha_recepcion}</td>
                      <td>${item.tiempo_transcurrido}</td>
                    </tr>
                  `;
              });

              tabla += `
                      </tbody>
                    </table>
                  </div>
                `;
            } else {
              tabla += `
                  <p class="text-muted mt-3">No hay estadísticas de tickets en progreso disponibles.</p>
                `;
            }

            $('#modalEstadisticasBody').html(tabla);
          });

          // HISTORIAL
          $('.open-historial').on('click', function () {
            var historial = <?php echo json_encode($this->historial); ?>;
            //console.log(historial);
            const uid = $(this).data('id');
            const registros = historial.filter(c => c.uid == uid);

            if (registros.length > 0) {
              $('#modalHistorialLabel').text('Historial de Ticket #' + uid);

              let contenido = `
      <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
        <table class="table table-bordered table-sm table-hover">
          <thead class="thead-light">
            <tr>
              <th>#</th>
              <th>Usuario</th>
              <th>Acción</th>
              <th>Detalle</th>
              <th>Fecha</th>
            </tr>
          </thead>
          <tbody>
    `;

              registros.forEach((registro, index) => {
                contenido += `
          <tr>
            <td>${index + 1}</td>
            <td>${registro.usuario}</td>
            <td>${registro.accion}</td>
            <td>${registro.detalle}</td>
            <td>${registro.fecha}</td>
          </tr>
        `;
              });

              contenido += `
          </tbody>
        </table>
      </div>
    `;

              $('#modalHistorialBody').html(contenido);
            } else {
              $('#modalHistorialLabel').text('Historial de Ticket #' + uid);
              $('#modalHistorialBody').html('<p>No se encontraron registros en el historial.</p>');
            }

          });

          // HILO
          $('.open-hilo').on('click', function () {
            const correoRespuesta = <?php echo json_encode($this->correoRespuesta); ?>;
            const uid = $(this).data('uid'); // uid del correo principal
            const message_id = $(this).data('message-id'); // message_id del correo principal

            // ------------ DEBUG ------------
            //console.log("UID clickeado:", uid);
            //console.log("Message ID clickeado:", message_id);
            const payload = {
              UID_clickeado: uid,
              Message_ID_clickeado: message_id
            };
            //console.log("HILO: ",payload);
            // ------------ DEBUG ------------

            const hiloDescendente = [];

            function buscarRespuestas(messageIdPadre) {
              const hijos = correoRespuesta.filter(c =>
                c.in_reply_to === messageIdPadre &&
                c.multirespuesta == 1 &&
                !hiloDescendente.some(r => r.uid == c.uid) // evitar duplicados
              );

              for (const hijo of hijos) {
                hiloDescendente.push(hijo);
                buscarRespuestas(hijo.message_id); // recursivamente buscar más respuestas
              }
            }

            buscarRespuestas(message_id);

            // 2. Construir HTML, empezando por el correo principal
            $('[data-tooltip="tooltip"]').tooltip();

            let html = '';

            if (hiloDescendente.length === 0) {
              html = `
    <div class="text-center p-4" style="color: #666;">
      <i class="bi bi-info-circle" style="font-size: 2rem;"></i>
      <p class="mt-2 mb-0">Este correo no tiene respuestas asociadas.</p>
    </div>
  `;
            } 
            else {
              hiloDescendente.reverse();
              hiloDescendente.forEach((correo, index) => {
                const respuestaNumero = hiloDescendente.length - index;
                html += `
                  <div style="margin-bottom: 40px; padding: 20px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); background-color: #f9f9f9;">
                    <h5 style="margin-bottom: 10px; color: #555;">
                      Respuesta Nro. #${respuestaNumero}
                    </h5>
                    <p><strong>Asunto Ticket #${correo.uid}:</strong> ${correo.asunto}</p>
                    <p style="margin-top: -20px;"><strong>Respuesta de:</strong> ${correo.correo_origen}</p>
                    <p style="margin-top: -20px;">
                      <strong>Fecha de recepción:</strong> ${correo.fecha_envio}
                      <i class="text-success" data-tooltip="tooltip" title="Tiempo transcurrido desde su creación en la base de datos.">ⓘ</i>
                    </p>
                    <iframe 
                      src="/eticket/public/correos_html/${correo.uid}.html" 
                      width="100%" 
                      height="600px" 
                      style="border: 1px solid #ddd; border-radius: 8px;" 
                      frameborder="0"
                      onerror="this.parentNode.innerHTML='<p class=\\'text-danger\\'>No se pudo cargar el contenido.</p>'">
                    </iframe>
                  </div>
                `;

              });
            }


            $('[data-tooltip="tooltip"]').tooltip();
            // 3. Mostrar el modal con el hilo completo
            $('#modalHiloLabel').text(`Hilo del Ticket #${uid}`);
            $('#modalHiloBody').html(html);
            $('#modalHilo').modal('show');
          });


        });

        // ----- RESPONDER AL USUARIO -----
        $(document).on('click', '.btn-enviar-respuesta', function () {
          const uid = $(this).data('uid');
          const correoOrigen = $(this).data('correo-origen');
          const correoDestino = $(this).data('correo-destino');
          const cc = $(this).data('cc');
          const asunto = $(this).data('asunto');
          const fecha = $(this).data('fecha');
          const references = $(this).data('references');
          const message_id = $(this).data('message-id');
          const in_reply_to = $(this).data('in-reply-to');
          const fusion = $(this).data('fusion');
          var id_usuario = "<?php echo $_SESSION['idusuario']; ?>";

          // Detectar qué textarea está visible
          const textareaSinHiloVisible = $('#textareaResponderSinHilo').is(':visible');
          const textareaConHiloVisible = $('#textareaResponderConHilo').is(':visible');

          // Obtener el texto dependiendo de cuál está visible
          let textoRespuesta = '';
          if (textareaSinHiloVisible) {
            textoRespuesta = $('#textareaResponderSinHilo textarea').val().trim();
            if (textoRespuesta === '') {
              Swal.fire({
                icon: 'warning',
                title: 'Campo vacío',
                text: 'Por favor, escribe una respuesta para el usuario (sin hilo).',
                confirmButtonText: 'Entendido'
              });
              return;
            }
          } else if (textareaConHiloVisible) {
            textoRespuesta = $('#textareaResponderConHilo textarea').val().trim();
            if (textoRespuesta === '') {
              Swal.fire({
                icon: 'warning',
                title: 'Campo vacío',
                text: 'Por favor, escribe una respuesta para el usuario (con hilo).',
                confirmButtonText: 'Entendido'
              });
              return;
            }
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'No se encontró un área de respuesta activa.',
              confirmButtonText: 'OK'
            });
            return;
          }
          
          const payload = {
            uid: uid,
            correo_origen: correoOrigen,
            correo_destino: correoDestino,
            cc: cc,
            asunto: asunto,
            fecha_envio: fecha,
            references: references,
            texto_respuesta: textoRespuesta, // <-- Se agrega aquí la respuesta escrita
            message_id: message_id,
            in_reply_to: in_reply_to,
            id_usuario: id_usuario,
            fusion: fusion
          };

          console.log("RESPONDER AL USUARIO:", payload);

          $.ajax({
          url: "<?php echo constant('URL'); ?>correo/enviarRespuestaUsuario",
          method: 'POST',
          data: payload,
          success: function (respuesta) {
            if (respuesta === true || respuesta === 'true' || respuesta === '1' || respuesta === 1) {
              Swal.fire({
                icon: 'success',
                title: 'Respuesta enviada',
                text: 'La respuesta fue enviada correctamente, Sincroniza los E-Tickets para ver tu respuesta en el hilo.',
                confirmButtonText: 'OK'
              });
              // Opcional: Limpiar textarea
              $('#textareaResponderSinHilo textarea, #textareaResponderConHilo textarea').val('');
              $('#textareaResponderSinHilo, #textareaResponderConHilo').hide();
            } 
            else {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo enviar la respuesta. Intenta nuevamente.',
                confirmButtonText: 'OK'
              });
            }
          },
          error: function () {
            Swal.fire({
              icon: 'error',
              title: 'Error del servidor',
              text: 'No se pudo contactar al servidor.',
              confirmButtonText: 'OK'
            });
          }
        });


        });
        // ----- FIN RESPONDER AL USUARIO -----



        // ----- GUARDAR ASIGNACION (no modificar listener) -----
        document.addEventListener('click', function (e) {
          if (e.target && e.target.classList.contains('guardar-asignacion')) {
            var usuario = "<?php echo $_SESSION['idusuario']; ?>";
            const uid = e.target.getAttribute('data-uid');
            const estado_actual = e.target.getAttribute('data-estado-actual');
            const select = document.getElementById('selectUsuario-' + uid);
            const fecha_envio = e.target.getAttribute('data-fecha');
            const asunto = e.target.getAttribute('data-asunto');
            var pagina = <?php echo $pagina_actual; ?>;

            if (parseInt(estado_actual) === 5) {
              Swal.fire({
                icon: 'warning',
                title: 'No se puede asignar',
                html: 'Este ticket está eliminado o cerrado, para reabrirlo debes cambiarlo de estado a <strong>Sin asignar</strong>.',
                confirmButtonColor: '#3085d6',
              });
              return; // corta la ejecución
            }

            if (!select) {
              console.error("No se encontró el select para uid: " + uid);
              return;
            }

            const idusuario = select.value;

            if (idusuario == "0") return alert("Selecciona un usuario válido");

            const payload = {
              uid: uid,
              idusuario: idusuario,
              fecha_envio: fecha_envio,
              asunto: asunto,
              pagina: pagina,
              usuario: usuario
            };

            console.log("GUARDAR ASIGNACION (no modificar listener): ", payload);

            fetch('<?= constant("URL"); ?>correo/asignar', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
              },
              body: JSON.stringify({
                uid,
                idusuario,
                fecha_envio,
                asunto,
                usuario
              })
            })
              .then(response => response.json())
              .then(data => {
                console.log("Respuesta del backend:", data);
                if (data.success) {
                  Swal.fire({
                    title: '¡Éxito!',
                    text: data.message + (data.correo_enviado ? ', se notificó al usuario.' : ''),
                    icon: 'success',
                    confirmButtonText: 'Cerrar'
                  }).then(() => {
                    //location.reload();
                    filtrarCards(pagina);
                  });
                } else {
                  Swal.fire({
                    title: 'Error',
                    text: data.message,
                    icon: 'error',
                    confirmButtonText: 'Cerrar'
                  });
                }
              })
              .catch(error => {
                console.error("Error en la asignación:", error);
                Swal.fire({
                  title: 'Error',
                  text: 'Hubo un problema de conexión, no se pudo guardar la asignacion, por favor intente nuevamente.',
                  icon: 'error',
                  confirmButtonText: 'Cerrar'
                });
              });
          }
        }
        );
        // ----- GUARDAR ASIGNACION (no modificar listener) -----


        // ------ CERRAR MODAL ASIGNACION -----
        document.addEventListener('click', function (e) {

          if (e.target && e.target.classList.contains('guardar-asignacion')) {
            const uid = e.target.getAttribute('data-uid');
            const select = document.getElementById('selectUsuario-' + uid);
            if (!select) return alert("No se encontró el select.");
            const idusuario = select.value;
            if (idusuario == "0") return alert("Selecciona un usuario válido");
            // Acá podés hacer tu fetch/AJAX al backend
            //console.log("UID:", uid, "Usuario asignado:", idusuario);
            // Cierra el modal manualmente
            $('#modalAsignacion').modal('hide');
          }
        });
        // ------ CERRAR MODAL ASIGNACION -----


        // -----  ESTADO (ADMIN) -----
        document.addEventListener('click', function (e) {
          if (e.target && e.target.classList.contains('guardar-editar-estado')) {
            const uid = e.target.getAttribute('data-uid');
            var select = document.getElementById(`selectEstado-${uid}`);
            var nuevoEstado = select ? select.value : null;
            const comentario = document.getElementById('comentarioEstado') ? document.getElementById('comentarioEstado').value.trim() : null;
            const comentarioDesarrollador = document.getElementById('comentarioEstadoDesarrollador') ? document.getElementById('comentarioEstadoDesarrollador').value.trim() : null;
            var idusuario = "<?php echo $_SESSION['idusuario']; ?>";
            var asunto = e.target.getAttribute('data-asunto');
            var fecha_envio = e.target.getAttribute('data-fecha');
            var correo_origen = e.target.getAttribute('data-correo-origen');
            var rol = <?php echo json_encode($permiso); ?>;
            var pagina = <?php echo $pagina_actual; ?>;
            var asignado = e.target.getAttribute('data-asignado');
            var nuevoEstadoPalabra = '';
            var estado_actualPalabra = '';
            let estado_actual = e.target.getAttribute('data-estado-actual');

            estado_actual = parseInt(estado_actual, 10);
            nuevoEstado = parseInt(nuevoEstado, 10);


            if (comentarioDesarrollador && comentarioDesarrollador.includes("'")) {
              Swal.fire({
                  icon: 'warning',
                  title: 'Comentario inválido',
                  text: "El comentario no puede contener comillas simples ('). Reemplazalo por comillas dobles"
              });
              return;
            }

            if (estado_actual) {
              if (estado_actual === 1) { estado_actualPalabra = 'Sin asignar'; } //
              if (estado_actual === 2) { estado_actualPalabra = 'Asignado'; } //
              if (estado_actual === 3) { estado_actualPalabra = 'Finalizado'; }
              if (estado_actual === 4) { estado_actualPalabra = 'En progreso'; } //
              if (estado_actual === 5) { estado_actualPalabra = 'Eliminado'; }
              if (estado_actual === 6) { estado_actualPalabra = 'Realizado'; } //
            }

            if (nuevoEstado) {
              if (nuevoEstado === 1) { nuevoEstadoPalabra = 'Sin asignar'; } //
              if (nuevoEstado === 2) { nuevoEstadoPalabra = 'Asignado'; } //
              if (nuevoEstado === 3) { nuevoEstadoPalabra = 'Finalizado'; }
              if (nuevoEstado === 4) { nuevoEstadoPalabra = 'En progreso'; } //
              if (nuevoEstado === 5) { nuevoEstadoPalabra = 'Eliminado'; }
              if (nuevoEstado === 6) { nuevoEstadoPalabra = 'Realizado'; } //
            }

            const estadosQueRequierenAsignacion = [1, 2, 4, 6];
            if (estado_actual != 5) {
              if (!asignado && estadosQueRequierenAsignacion.includes(parseInt(nuevoEstado))) {
                Swal.fire({
                  icon: 'warning',
                  title: 'Asignación requerida',
                  html: `No puedes cambiar el estado de este ticket a <strong>${nuevoEstadoPalabra}</strong> porque aún no está asignado a ningún usuario.<br><br>
                      Los siguientes estados <strong>requieren que el ticket esté previamente asignado</strong>:<br>
                      <ul style="text-align: left; margin-top: 10px;">
                        <li><strong>Sin asignar</strong></li>
                        <li><strong>Asignado</strong></li>
                        <li><strong>En progreso</strong></li>
                        <li><strong>Realizado</strong></li>
                      </ul>
                      Por favor, asigna este ticket a un usuario del sistema antes de continuar.`,
                  confirmButtonText: 'Entendido',
                  confirmButtonColor: '#3085d6'
                });
                return;
              }
            }
            if (parseInt(estado_actual) === 5 && parseInt(nuevoEstado) !== 1) {
              Swal.fire({
                icon: 'warning',
                title: 'Acción inválida',
                html: `
                  Este ticket se encuentra en estado <strong>Eliminado</strong>.<br><br>
                  Solo puedes cambiar su estado a <strong>Sin asignar</strong> para reactivarlo.<br><br>
                  <strong>Nota:</strong> al cambiar el estado a <em>Sin asignar</em>, la asignación anterior del usuario será eliminada del sistema.
                `,
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#3085d6'
              });
              return;
            }

            const payload = {
              uid: uid,
              comentario: comentario,
              idusuario_logueado: idusuario,
              asunto: asunto,
              fecha_envio: fecha_envio,
              correo_origen: correo_origen,
              rol: rol,
              pagina: pagina,
              estado_actual: estado_actual,
              estado_actualPalabra: estado_actualPalabra,
              nuevoEstado: nuevoEstado,
              nuevoEstadoPalabra: nuevoEstadoPalabra,
              comentarioDesarrollador: comentarioDesarrollador

            };
            console.log("ESTADO (ADMIN): ", payload);

            if (!uid || !nuevoEstado || nuevoEstado === "0") {
              alert("Selecciona un estado válido");
              return;
            }

            // ----------- VALIDACION ESTADO REALIZADO: 3 -----------
            // Si el estado es "Finalizado" y no se ha agregado un comentario, avisar al usuario
            if (nuevoEstado == "3" && (!comentario || comentario.trim() === "")) {
              Swal.fire({
                icon: 'warning',
                title: 'Comentario requerido',
                text: 'Por favor, agrega un comentario para el estado Finalizado.',
                confirmButtonText: 'Entendido'
              });
              return;
            }
            // ----------- VALIDACION ESTADO REALIZADO: 3 -----------

            // ----------- VALIDACION ESTADO REALIZADO: 6 -----------
            // Si el estado es "Finalizado" y no se ha agregado un comentario, avisar al usuario
            if (nuevoEstado == "6" && (!comentarioDesarrollador || comentarioDesarrollador.trim() === "")) {
              Swal.fire({
                icon: 'warning',
                title: 'Comentario de desarrollador requerido',
                text: 'Por favor, ingresa un comentario para el estado "Realizado". Este comentario ayudará a brindar contexto al usuario final. Si no se requiere, simplemente escribe "realizado".',
                confirmButtonText: 'Entendido'
              });
              return;
            }
            // ----------- VALIDACION ESTADO REALIZADO: 6 -----------

            fetch('<?= constant("URL"); ?>correo/cambiarEstado', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
              },
              body: ` uid=${encodeURIComponent(uid)}
                      &estado=${encodeURIComponent(nuevoEstado)}
                      &comentario=${encodeURIComponent(comentario)}
                      &comentarioDesarrollador=${encodeURIComponent(comentarioDesarrollador)}
                      &idusuario=${encodeURIComponent(idusuario)}
                      &asunto=${encodeURIComponent(asunto)}
                      &fecha_envio=${encodeURIComponent(fecha_envio)}
                      &correo_origen=${encodeURIComponent(correo_origen)}
                      &rol=${encodeURIComponent(rol)}
                      &nuevoEstado=${encodeURIComponent(nuevoEstado)}
                      &nuevoEstadoPalabra=${encodeURIComponent(nuevoEstadoPalabra)}
                      &estado_actual=${encodeURIComponent(estado_actual)}
                      &estado_actualPalabra=${encodeURIComponent(estado_actualPalabra)}`
            })
              .then(response => response.text())
              .then(data => {
                console.log("Respuesta:", data);

                if (data.includes("Estado actualizado")) {
                  Swal.fire({
                    title: '¡Éxito!',
                    text: data, // Muestra el mensaje completo que venga del servidor
                    icon: 'success',
                    confirmButtonText: 'Cerrar'
                  }).then(() => {
                    filtrarCards(pagina);
                  });
                } else {
                  Swal.fire({
                    title: 'Error',
                    text: data, // Muestra el error que venga del servidor
                    icon: 'error',
                    confirmButtonText: 'Cerrar'
                  });
                }
              })
              .catch(error => {
                console.error("Error en el cambio de estado:", error);
                Swal.fire({
                  title: 'Error',
                  text: 'Hubo un problema de conexión, no se pudo guardar el estado del ticket, por favor intente nuevamente.',
                  icon: 'error',
                  confirmButtonText: 'Cerrar'
                });
              });
          }
        });
        // -----  ESTADO (ADMIN) -----


        // ----- GUARDAR ESTADO DE TICKET (NO ADMIN) -----
        document.querySelector('.guardar-cambio-estado').addEventListener('click', function () {
          const uid = document.getElementById('cambiarUid').value;
          const select = document.getElementById(`selectEstadoCambiar-${uid}`);
          const nuevoEstado = select ? select.value : null;
          var rol = <?php echo json_encode($permiso); ?>;
          var pagina = <?php echo $pagina_actual; ?>;

          if (uid && nuevoEstado) {
            fetch(`<?= constant('URL'); ?>correo/cambiarEstado`, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
              },
              body: `uid=${uid}&estado=${nuevoEstado}&rol=${rol}`
            })
              .then(response => response.text())
              .then(data => {
                console.log("Respuesta:", data);

                if (data.includes("Estado actualizado")) {
                  Swal.fire({
                    title: '¡Éxito!',
                    text: data,
                    icon: 'success',
                    confirmButtonText: 'Cerrar'
                  }).then(() => {
                    $('#modalCambiarEstado').modal('hide');
                    //location.reload();
                    filtrarCards(pagina);
                  });
                } else {
                  Swal.fire({
                    title: 'Error en estado',
                    text: 'Hubo un problema al actualizar el estado del ticket.',
                    icon: 'error',
                    confirmButtonText: 'Cerrar'
                  });
                }
              })
              .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                  title: 'Error',
                  text: 'Hubo un problema de conexión, no se pudo guardar el estado del ticket, por favor intente nuevamente.',
                  icon: 'error',
                  confirmButtonText: 'Cerrar'
                });
              });
          }
        });
        // ----- GUARDAR ESTADO DE TICKET (NO ADMIN) -----




        // ----- FUNCION DE FILTRADO -----
        function filtrarCards(pagina = 1) {
          //EXTRACION DE DATOS
          var estadoSelect = document.getElementById("estado");
          var usuarioSelect = document.getElementById("usuario_asignado");
          var fechaInicioInput = document.getElementById("fecha_inicio");
          var fechaFinInput = document.getElementById("fecha_fin");
          var correoOrigenInput = document.getElementById("correo_origen");
          var asuntoInput = document.getElementById("asunto");
          var idTicketInput = document.getElementById("id_ticket");
          var multirespuestaSelect = document.getElementById("multirespuesta");
          var diasSelect = document.getElementById("dias_creacion");


          //VALIDACION DE DATOS
          var fechaInicio = fechaInicioInput.value.trim() || null;
          var fechaFin = fechaFinInput.value.trim() || null;
          var usuarioAsignado = usuarioSelect && usuarioSelect.value !== "0" ? usuarioSelect.value.trim() : null;
          var estado = estadoSelect && estadoSelect.value !== "0" ? estadoSelect.value.trim() : null;
          var correoOrigen = correoOrigenInput && correoOrigenInput.value.trim() !== "" ? correoOrigenInput.value.trim() : null;
          var asunto = asuntoInput && asuntoInput.value.trim() !== "" ? asuntoInput.value.trim() : null;
          var id_ticket = idTicketInput && idTicketInput.value.trim() !== "" ? idTicketInput.value.trim() : null;
          var multirespuesta = multirespuestaSelect ? multirespuestaSelect.value.trim() : null;
          var dias_creacion = diasSelect && diasSelect.value !== "0" ? diasSelect.value.trim() : null;

          const payload = {
            fechaInicio: fechaInicio,
            fechaFin: fechaFin,
            usuarioAsignado: usuarioAsignado,
            estado: estado,
            correoOrigen: correoOrigen,
            dias_creacion: dias_creacion,
            id_ticket: id_ticket,
            multirespuesta: multirespuesta,
            asunto: asunto
          };

          console.log("FUNCION DE FILTRADO:", payload);

          if (fechaInicio && fechaFin && new Date(fechaInicio) > new Date(fechaFin)) {
            Swal.fire({
              icon: "error",
              title: "Rango de fechas inválido",
              text: "La fecha de inicio no puede ser mayor que la fecha de fin.",
              confirmButtonText: "Entendido"
            })
            fechaInicioInput.value = "";
            fechaFinInput.value = ""
            return;
          }

          //PETICION AJAX
          $.ajax({
            url: "<?php echo constant('URL'); ?>correo/verPaginacion/" + pagina,
            type: "POST",
            data: {
              fecha_inicio: fechaInicio,
              fecha_fin: fechaFin,
              usuario_asignado: usuarioAsignado,
              estado: estado,
              permiso: permiso,
              asignacion: asignacion,
              correo_origen: correoOrigen,
              dias_creacion: dias_creacion,
              id_ticket: id_ticket,
              multirespuesta: multirespuesta,
              asunto: asunto
            },
            success: function (html) {
              document.open();
              document.write(html); //rompe el flujo de eventos, manejar
              document.close();
              setTimeout(() => {
                if (fechaInicio) $('input[name="fecha_inicio"]').val(fechaInicio);
                if (fechaFin) $('input[name="fecha_fin"]').val(fechaFin);
                if (usuarioAsignado) { $('select[name="usuario_asignado"]').val(usuarioAsignado); }
                if (estado) { $('select[name="estado"]').val(estado); }
                if (correoOrigen) { $('input[name="correo_origen"]').val(correoOrigen); }
                if (asunto) { $('input[name="asunto"]').val(asunto); }
                if (id_ticket) { $('input[name="id_ticket"]').val(id_ticket); }
                if (multirespuesta) { $('select[name="multirespuesta"]').val(multirespuesta); }
                if (dias_creacion) { $('select[name="dias_creacion"]').val(dias_creacion); }
              }, 100);
              //$("#container-full").html(html); 
            },
            error: function (xhr, status, error) {
              console.error("Error al filtrar:", error);
            }
          });

        }
        // ----- FUNCION DE FILTRADO -----

        // ----- FUNCION DE LIMPIADO CONTROLADO DE UID (solo R, E, guión y números) -----
        $(document).on('input', '#id_ticket', function () {
          console.log("LIMPIADO");
          let valor = $(this).val();

          // Eliminar todo lo que no sea r, e, números o guión
          let limpio = valor.replace(/[^reRE0-9\-]/g, '');

          $(this).val(limpio);
        });



        // ----- FUNCION DE LIMPIADO DE TEXTO PARA EL FILTRO DE ID -----

        // ----- VALIDACION PARA FILTROS USUARIO Y ESTADO -----
        $(document).ready(function () {
          // Cuando cambia el estado
          $('#estado').on('change', function () {
            const estadoSeleccionado = $(this).val();

            if (estadoSeleccionado === "1" || estadoSeleccionado === "0") {
              $('#usuario_asignado').val("0");

              // Si es select2, actualizá con trigger
              if ($('#usuario_asignado').hasClass('select2-hidden-accessible')) {
                $('#usuario_asignado').trigger('change');
              }
            }
          });

          // Cuando cambia el usuario asignado
          $('#usuario_asignado').on('change', function () {
            if ($(this).val() !== "") {
              $('#estado').val("0");

              // Si es select2, actualizá con trigger
              if ($('#estado').hasClass('select2-hidden-accessible')) {
                $('#estado').trigger('change');
              }
            }
          });
        });
        // ----- VALIDACION PARA FILTROS USUARIO Y ESTADO -----


        // ----- BORRAR FILTROS -----
        $(document).on('click', '#limpiar_filtros', function () {
          $('#fecha_inicio').val('');
          $('#fecha_fin').val('');
          $('#usuario_asignado').val('0');
          $('#estado').val('0');
          $('#dias_creacion').val('0');
          $('#correo_origen').val('');
          $('#asunto').val('');
          $('#id_ticket').val('');
          location.reload();
        });
        // ----- BORRAR FILTROS -----


        // ----- ELIMINAR -----
        $(document).ready(function () {
          $(document).on('click', '.eliminar', function () {
            const uid = $(this).data('id'); // Obtener el UID del ticket

            Swal.fire({
              title: '¿Estás seguro?',
              text: `Esta acción eliminará el ticket #${uid} de forma permanente.`,
              icon: 'warning',
              showCancelButton: true,
              confirmButtonText: 'Sí, eliminar',
              cancelButtonText: 'No, cancelar'
            }).then((result) => {
              if (result.isConfirmed) {
                // Si el usuario confirma, enviar el UID al backend para eliminar el ticket
                eliminarTicket(uid);
              }
            });
          });

          // Función para eliminar el ticket
          function eliminarTicket(uid) {
            // Hacer el request al backend para eliminar el ticket
            $.ajax({
              url: "<?php echo constant('URL'); ?>correo/eliminar", // Llamamos al controlador para eliminar
              method: "POST",
              data: { uid: uid }, // Enviar el UID para eliminar el ticket
              success: function (response) {
                if (response === 'success') {
                  Swal.fire(
                    '¡Eliminado!',
                    `El ticket #${uid} ha sido eliminado correctamente.`,
                    'success'
                  ).then(() => {
                    // Recargar la página después de la eliminación
                    location.reload();
                  });
                } else {
                  Swal.fire(
                    'Error',
                    `Hubo un problema al eliminar el ticket #${uid}, por favor intenta nuevamente.`,
                    'error'
                  );
                }
              },
              error: function (xhr, status, error) {
                console.error("Error al eliminar ticket:", error);
                Swal.fire(
                  'Error',
                  `Hubo un problema de conexión al intentar eliminar el ticket #${uid}.`,
                  'error'
                );
              }
            });
          }
        });
        // ----- ELIMINAR -----


        // ----- SINCRONIZAR E-TICKETS -----
        $('#btnSincronizar').on('click', function (e) {
          e.preventDefault();
          var pagina = <?php echo $pagina_actual; ?>;

          Swal.fire({
            title: '¿Estás seguro de que quieres sincronizar?',
            text: 'Esto podría tardar un momento...',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, sincronizar',
            cancelButtonText: 'Cancelar'
          }).then((result) => {
            if (result.isConfirmed) {
              Swal.fire({
                title: 'Sincronizando correos...',
                text: 'Por favor, no cierres esta ventana.',
                allowOutsideClick: false,
                didOpen: () => {
                  Swal.showLoading();
                }
              });

              $.ajax({
                url: '<?= constant("URL") ?>correo/obtenerCorreos',
                method: 'POST',
                success: function (response) {
                  console.log(response);
                  Swal.fire({
                    icon: 'success',
                    title: '¡Sincronización completa!',
                    text: 'La sincronización ha finalizado exitosamente.',
                  }).then(() => {
                    // Recargar la página después de cerrar el modal
                    //location.reload();
                    filtrarCards(pagina);
                  });
                },
                error: function (xhr, status, error) {
                  console.log(xhr); // Verifica la respuesta del servidor aquí
                  Swal.fire({
                    icon: 'error',
                    title: 'Error al sincronizar',
                    text: 'Hubo un problema durante la sincronización de correos.',
                  });
                }
              });


            }
          });
        });
        // ----- SINCRONIZAR E-TICKETS -----

        // ----- ACTIVADOR DE LAS CARDS AL HACER CLICK EN EL PAGINADOR -----
        $(document).on('click', '.btn-paginacion', function (e) {
          e.preventDefault();
          var pagina = $(this).data('pagina');
          if (!$(this).parent().hasClass('disabled')) {
            filtrarCards(pagina);
          }
        });
        // ----- ACTIVADOR DE LAS CARDS AL HACER CLICK EN EL PAGINADOR -----


        // ----- TOOLTIP -----
        $(function () {
          $('[data-tooltip="tooltip"]').tooltip({ trigger: 'hover' });
        });

        // ----- TOOLTIP -----

        // ----- SPAM -----
        $(document).on('click', '.spam', function () {
          const uid = $(this).data('id');
          const idusuario = "<?php echo $_SESSION['idusuario']; ?>";
          const correo_origen = $(this).data('correo-origen');
          var pagina = <?php echo $pagina_actual; ?>;

          const payload = {
            uid: uid,
            idusuario: idusuario,
            correo_origen: correo_origen,
            pagina: pagina
          };

          console.log("SPAM:", payload);

          Swal.fire({
            title: '¿Marcar como spam?',
            html: "Los tickets actuales y nuevos, provenientes de: <b>" + correo_origen + "</b>, no se visualizarán más en el sitio web si confirmas esta acción.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, marcar como spam',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
          }).then((result) => {
            if (result.isConfirmed) {

              // Aquí va el AJAX para enviar los datos
              $.ajax({
                url: '<?= constant("URL") ?>correo/marcarSpam',  // URL de la función PHP que procesará el spam
                method: 'POST',
                data: {
                  uid: uid,
                  idusuario: idusuario,
                  correo_origen: correo_origen
                },
                success: function (response) {
                  console.log("Respuesta del servidor:", response);
                  Swal.fire({
                    icon: 'success',
                    title: 'Correo marcado como spam',
                    text: 'Este remitente ya no será visible en la plataforma.',
                  }).then(() => {
                    //location.reload(); // Recargar la página para ver los cambios
                    filtrarCards(pagina);
                  });
                },
                error: function (xhr, status, error) {
                  console.error("Error al marcar como spam:", error);
                  Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo marcar el correo como spam.',
                  });
                }
              });
            }
          });
        });
        // ----- SPAM -----

        // ----- FUNCION PARA OCULTAR ICONO DESDE BOTON HAMBURGUESA -----
        document.addEventListener('DOMContentLoaded', function () {
          const toggleBtn = document.querySelector('[data-widget="pushmenu"]');

          toggleBtn.addEventListener('click', function () {
            document.body.classList.toggle('ocultar-icono-etickets');
          });
        });
        // ----- FUNCION PARA OCULTAR ICONO DESDE BOTON HAMBURGUESA -----

        function detectTouchDevice() {
          const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;

          if (isTouchDevice) {
            document.body.classList.add('touch-device');
          } else {
            document.body.classList.remove('touch-device');
          }
        }

        // Ejecuta la función cuando el DOM esté completamente cargado
        document.addEventListener('DOMContentLoaded', detectTouchDevice);

        //
        $('#responder-hilo').on('click', function (e) {
          e.preventDefault();

          Swal.fire({
            title: '¿Enviar respuesta automática?',
            text: 'Se enviará una respuesta al hilo del correo seleccionado.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, enviar',
            cancelButtonText: 'Cancelar'
          }).then((result) => {
            if (result.isConfirmed) {
              Swal.fire({
                title: 'Enviando respuesta...',
                text: 'Por favor, espera un momento.',
                allowOutsideClick: false,
                didOpen: () => {
                  Swal.showLoading();
                }
              });

              $.ajax({
                url: 'correo/envioAutomatico',
                type: 'POST',
                data: {
                  ejecutar_envio_estatico: true
                },
                success: function (response) {
                  console.log(response);

                  if (response.toLowerCase().includes('correo enviado correctamente')) {
                    Swal.fire({
                      icon: 'success',
                      title: '¡Respuesta enviada!',
                      text: response
                    }).then(() => {
                      console.log("REALIZADO");
                    });
                  } else {
                    Swal.fire({
                      icon: 'warning',
                      title: 'Envío finalizado con advertencia',
                      text: response
                    });
                  }
                },
                error: function (xhr) {
                  console.error(xhr);
                  Swal.fire({
                    icon: 'error',
                    title: 'Error al enviar',
                    text: 'Ocurrió un problema al intentar enviar la respuesta automática.'
                  });
                }
              });
            }
          });
        });


      </script>

    </div> <!-- content-wrapper -->

    <?php require 'views/footer.php' ?>

  </div> <!-- wrapper -->

</body>

</html>