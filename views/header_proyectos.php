<?php
session_start();
$nombre_usuario = $_SESSION['nombre_usuario'] ?? 'Invitado';
$permiso = $this->permiso;
/* echo __DIR__; */
require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="<?php echo constant('URL'); ?>public/uploads/logo.png">

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">

  <!-- Font Awesome 5.15.4 (compatible con AdminLTE 3.2.0) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css">

  <!-- Ionicons (solo si usas los íconos "ion") -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <!-- Bootstrap 4.6.2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

  <!-- AdminLTE 3.2.0 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">

  <!-- SweetAlert2 CSS (opcional para alertas) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.0/dist/sweetalert2.min.css">

  <!-- Animate.css (opcional si usas animaciones) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

  <!-- JQUERY -->
  <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.min.css">

  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/2.1.4/js/dataTables.min.js"></script>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

  
  
</head>

<body class="hold-transition layout-top-nav">
<div class="wrapper">

  <input type="hidden" value="<?php echo MINIATURA_PATH_PROD; ?>"> <!-- Servidor -->
  <input type="hidden" value="<?php echo MINIATURA_PATH_LOCAL; ?>"> <!-- Local -->

  <input type="hidden" value="<?php echo LOGO_PATH_PROD; ?>"> <!-- Servidor -->
  <input type="hidden" value="<?php echo LOGO_PATH_LOCAL; ?>"> <!-- Local -->

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
    <div class="container">

      <a href="" class="navbar-brand">
        <img src="<?php echo constant('URL'); ?>public/uploads/Logo_IOPA.png" alt="IOPA Logo" style="width: 100px;height: 45px;"> <!-- Produccion -->

      <!-- <a href="" class="navbar-brand">
        <img src="" alt="IOPA Logo" style="width: 100px;height: 45px;"> 
      </a> -->

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Left links -->
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav">
          <li class="nav-item">
                  <a href="<?php echo constant('URL'); ?>correo/verPaginacion/1" class="nav-link">Volver a E-Tickets</a> <!-- Produccion -->
                  <!-- <a href="" class="nav-link">Inicio</a>  --><!-- Local -->
            
          </li>
          <!-- Agrega más ítems si quieres -->
        </ul>
      </div>

      <!-- Right links -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item d-flex align-items-center">
          <span class="mr-2 font-weight-bold text-dark">
            <i class="fas fa-user"></i>
            <?php echo $_SESSION["usuario"]; ?>
          </span>

          <!-- <form action="C:\xampp\htdocs\informe_powerb\logout.php" method="post" class="d-inline"> -->
                  <form action="" method="post" class="d-inline">
                  <form action="" method="post" class="d-inline">
            <!-- <button class="btn btn-outline-danger btn-sm" type="submit">
              <i class="fas fa-sign-out-alt"></i> Salir
            </button> -->
            <!-- <button class="btn btn-outline-danger btn-sm" type="button" onclick="<?= constant('URL'); ?>login/salir">
              <i class="fas fa-sign-out-alt"></i> Salir
            </button> -->
            <button class="btn btn-outline-danger btn-sm" type="button" onclick="window.location.href='<?= constant('URL'); ?>login/salir'">
                <i class="fas fa-sign-out-alt"></i> Salir
            </button>

          </form>
        </li>
      </ul>
    </div>
  </nav>

</div>
