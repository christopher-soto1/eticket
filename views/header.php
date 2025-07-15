<?php
error_reporting(E_ALL & ~E_WARNING & ~E_DEPRECATED);
$permiso = $this->permiso;
$asignacion = $this->asignacion;
?>
<!DOCTYPE html>
<html>

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
  
  
</head>

<body>

<nav class="navbar navbar-expand navbar-dark bg-primary">
  <!-- Left navbar: Push menu + Inicio -->
  <ul class="navbar-nav">
    <!-- Botón menú (hamburguesa) -->
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>

    <!-- Link fijo a Inicio -->
    <li class="nav-item d-none d-sm-inline-block" style="margin-left: 30px;">
      <a href="#" class="nav-link">
      <i class="fas fa-chart-bar"></i> IOPA System: E-Tickets
      </a>
    </li>

    <!-- Formularios -->
    <?php
    include_once 'models/usuariosperfil.php';
    foreach ($this->usuariosperfil as $row) {
      $usuariosperfil = new Usuariosperfil();
      $usuariosperfil = $row;
      if ($usuariosperfil->principal == "Formularios") { ?>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="<?php echo constant('URL') . $usuariosperfil->menu; ?>/verPaginacion/1" class="nav-link">
            <i class="fas fa-edit"></i> <?php echo $usuariosperfil->menu; ?>
          </a>
        </li>
    <?php }
    }
    ?>
  </ul>

  <!-- Right navbar -->
  <ul class="navbar-nav ml-auto">
    <!-- Dropdown: Tablas -->
    <!-- <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown">
        <i class="fas fa-table"></i> Tablas
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <?php
        foreach ($this->usuariosperfil as $row) {
          $usuariosperfil = new Usuariosperfil();
          $usuariosperfil = $row;
          if ($usuariosperfil->principal == "Tablas") { ?>
            <a class="dropdown-item" href="<?php echo constant('URL') . $usuariosperfil->menu; ?>/verPaginacion/1">
              <i class="fas fa-angle-right mr-2"></i><?php echo $usuariosperfil->menu; ?>
            </a>
          <?php }
        }
        ?>
      </div>
    </li> -->

    <!-- Usuario -->
    <li class="nav-item">
      <a class="nav-link" href="#">
        <i class="fas fa-user-circle"></i> 
        <?php echo $_SESSION["usuario"]; ?> : <?php echo strtoupper($permiso); ?>
      </a>
    </li>

    <!-- Logout -->
    <li class="nav-item">
      <a class="nav-link" href="<?php echo constant('URL'); ?>">
        <i class="fas fa-sign-out-alt"></i> Salir
      </a>
    </li>
  </ul>
</nav>

<!-- jQuery (debe ir primero) -->
<!-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> -->

<!-- Bootstrap 4.6.2 -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script> -->

<!-- AdminLTE 3.2.0 -->
<!-- <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script> -->

<!-- SweetAlert2 (opcional si usas alertas) -->
<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
<script>
  var rol = <?php echo json_encode($permiso); ?>;
  var usuarioID = <?php echo json_encode($asignacion); ?>;
</script>

<!-- jQuery 3.6.4 -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Bootstrap 4.6.2 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- AdminLTE 3.2.0 -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Variables JS desde PHP -->
<script>
  var rol = <?php echo json_encode($permiso); ?>;
  var usuarioID = <?php echo json_encode($asignacion); ?>;
</script>
<!-- LOLITO -->
</body>

</html>