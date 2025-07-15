<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>IOPA | Ingreso</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

  <!-- Font Awesome (compatible con AdminLTE 3) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css">

  <!-- icheck bootstrap (opcional para checkbox bonito) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css">

  <!-- AdminLTE CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">

  <!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="hold-transition login-page">

<style>
  /* Arreglo definitivo para evitar que login-box salte cuando aparece SweetAlert */
  body.login-page,
  .login-page .login-box {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .swal2-container {
    z-index: 10000 !important;
  }

  .login-box {
    margin: 0 !important;
    transform: none !important;
    transition: none !important;
    width: 420px; /* puedes ajustar este valor según tu gusto */
    max-width: 90%;
  }

  .card-header .h1 {
  font-weight: bold;
  font-size: 2rem;
}
</style>




<div class="login-box">
  <!-- Logo / Encabezado -->
  <div class="card card-outline card-primary" style="width: 90%;">
    <div class="card-header text-center">
      <a href="#" class="h1"><b>IOPA</b> System</a>
    </div>

    <div class="card-body">
      <p class="login-box-msg">Inicia sesión para comenzar</p>

      <!-- FORMULARIO -->
      <form action="<?php echo constant('URL'); ?>login/verificar" method="POST">
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Correo" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="password" name="pass" class="form-control" placeholder="Contraseña" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-lock"></span></div>
          </div>
        </div>

        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember" name="remember" checked>
              <label for="remember">Recordarme</label>
            </div>
          </div>
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
          </div>
        </div>
      </form>

      <p class="mb-1 mt-3">
        <a href="#">¿Olvidaste tu contraseña?</a>
      </p>
    </div>
  </div>
</div>

<?php
  session_start();
  if (isset($_SESSION['login_error'])) {
    $login_error = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
?>
<script>
  window.onload = function () {
    Swal.fire({
      icon: 'error',
      title: '¡Error!',
      text: '<?php echo $login_error; ?>',
      confirmButtonColor: '#3085d6'
    });
  };
</script>
<?php } ?>





<!-- JS: jQuery + Bootstrap + AdminLTE -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
</body>
</html>
