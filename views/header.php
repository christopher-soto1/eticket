<?php
error_reporting(E_ALL & ~E_WARNING & ~E_DEPRECATED);
ini_set('max_execution_time', 4000);
ini_set('memory_limit', '512M');

session_start();

// Redirigir si no hay sesión activa
if (!isset($_SESSION['usuario'])) {
    session_unset(); session_destroy();
    header("Location: " . constant('URL') . "login"); exit();
}

// Actualizar actividad sin límite de tiempo
$_SESSION['LAST_ACTIVITY'] = time();
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="<?php echo constant('URL'); ?>public/uploads/logo.png">
  <title>E-Tickets</title>

  <?php
  include_once 'models/usuariosperfil.php';

  // Solo necesitas el primer elemento
  $usuariosperfil0    = $this->usuariosperfil[0];
  $idusuario0         = $usuariosperfil0->idusuario;
  $usuario_rebsol     = $usuariosperfil0->usuario_rebsol;
  $menu               = $usuariosperfil0->menu;
  $habilitado         = $usuariosperfil0->habilitado;
  $principal          = $usuariosperfil0->principal;
  $area               = $usuariosperfil0->area;
  $permiso            = $usuariosperfil0->permiso;

  // Guardar en sesión
  $_SESSION['permiso']   = $permiso;
  $_SESSION['idusuario'] = $idusuario0;

  // Estos vienen del controlador y tienen prioridad sobre el perfil
  $permiso    = $this->permiso;
  $asignacion = $this->asignacion;

  $usuarios_permitidos = [
    'christopher.soto@iopa.cl', 'nstuardo@gmail.com', 'dimas.delmoral@iopa.cl',
    'daniel.navarrete@iopa.cl', 'marcos.huenchunir@iopa.cl',
    'luis.farias@iopa.cl',      'catalina.henriquez@iopa.cl'
  ];

  // Avatar: iniciales desde el email
  $usuario_email = $idusuario0 ?? '';
  $partes        = explode('.', explode('@', $usuario_email)[0]);
  $iniciales     = strtoupper(substr($partes[0] ?? 'U', 0, 1) . substr($partes[1] ?? '', 0, 1));
  ?>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <!-- Moment.js -->
  <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <!-- Daterangepicker -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
  <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <!-- Inter font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Bootstrap 4.6.2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <!-- AdminLTE 3.2.0 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.0/dist/sweetalert2.min.css">
  <!-- Animate.css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

  <style>
    body, p, span, div, a, button, input, select, textarea, h1, h2, h3, h4, h5, h6, label, small {
      font-family: 'Inter', sans-serif !important;
    }

    /* Ocultar navbar original de AdminLTE y reemplazar */
    .main-sidebar { left: 0 !important; }
    .content-wrapper { margin-left: 0 !important; }

    /* ── HEADER STITCH ── */
    .stitch-header {
      position: sticky;
      top: 0;
      z-index: 1050;
      width: 100%;
      height: 64px;
      background: #f9f9ff;
      border-bottom: 1px solid #e8eaf2;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 2rem;
      box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    /* Sección izquierda */
    .stitch-header .sh-left {
      display: flex;
      align-items: center;
      gap: 1.5rem;
    }
    .sh-menu-btn {
      display: flex; align-items: center; justify-content: center;
      width: 36px; height: 36px;
      border-radius: 10px;
      background: transparent;
      border: none;
      cursor: pointer;
      color: #64748b;
      transition: background 0.2s;
    }
    .sh-menu-btn:hover { background: #f1f5f9; }

    .sh-logo {
      font-size: 18px;
      font-weight: 800;
      color: #0059bb;
      letter-spacing: -0.5px;
      text-decoration: none;
      white-space: nowrap;
    }
    .sh-logo:hover { color: #0059bb; text-decoration: none; }

    .sh-nav { display: flex; align-items: center; gap: 2px; }
    .sh-nav a {
      padding: 6px 14px;
      font-size: 13px;
      font-weight: 500;
      color: #64748b;
      border-radius: 8px 8px 0 0;
      border-bottom: 2px solid transparent;
      text-decoration: none;
      transition: color 0.2s, background 0.2s;
      white-space: nowrap;
    }
    .sh-nav a:hover {
      color: #0059bb;
      background: #f1f5f9;
      text-decoration: none;
    }
    .sh-nav a.active {
      color: #0059bb;
      font-weight: 600;
      border-bottom-color: #0059bb;
    }

    /* Centro: buscador */
    .sh-search-wrap {
      flex: 1;
      max-width: 420px;
      margin: 0 2rem;
    }
    .sh-search {
      width: 100%;
      display: flex;
      align-items: center;
      gap: 8px;
      background: #ebedf9;
      border-radius: 12px;
      padding: 7px 14px;
      border: 1px solid transparent;
      transition: border 0.2s, background 0.2s;
    }
    .sh-search:focus-within {
      background: #fff;
      border-color: rgba(0,89,187,0.3);
    }
    .sh-search i { color: #94a3b8; font-size: 13px; }
    .sh-search input {
      border: none;
      background: transparent;
      outline: none;
      font-size: 13px;
      color: #1e293b;
      width: 100%;
    }
    .sh-search input::placeholder { color: #94a3b8; }

    /* Derecha */
    .stitch-header .sh-right {
      display: flex;
      align-items: center;
      gap: 4px;
    }
    .sh-icon-btn {
      display: flex; align-items: center; justify-content: center;
      width: 36px; height: 36px;
      border-radius: 10px;
      background: transparent;
      border: none;
      cursor: pointer;
      color: #64748b;
      position: relative;
      transition: background 0.2s;
      text-decoration: none;
    }
    .sh-icon-btn:hover { background: #f1f5f9; color: #0059bb; }
    .sh-icon-btn .badge-dot {
      position: absolute;
      top: 7px; right: 7px;
      width: 7px; height: 7px;
      background: #ef4444;
      border-radius: 50%;
      border: 2px solid #f9f9ff;
    }

    .sh-divider {
      width: 1px; height: 32px;
      background: #e2e8f0;
      margin: 0 8px;
    }

    /* Avatar */
    .sh-avatar {
      width: 36px; height: 36px;
      border-radius: 10px;
      background: #0059bb;
      color: #fff;
      font-size: 12px;
      font-weight: 700;
      display: flex; align-items: center; justify-content: center;
      letter-spacing: 0.5px;
      flex-shrink: 0;
    }
    .sh-user-info { text-align: right; }
    .sh-user-info .sh-user-name {
      font-size: 13px;
      font-weight: 600;
      color: #1e293b;
      line-height: 1.2;
      display: block;
    }
    .sh-user-info .sh-user-role {
      font-size: 11px;
      color: #94a3b8;
      display: block;
    }
    .sh-logout {
      display: flex; align-items: center; justify-content: center;
      width: 36px; height: 36px;
      border-radius: 10px;
      background: transparent;
      border: none;
      cursor: pointer;
      color: #94a3b8;
      text-decoration: none;
      transition: background 0.2s, color 0.2s;
    }
    .sh-logout:hover {
      background: #fef2f2;
      color: #dc2626;
      text-decoration: none;
    }

    @media (max-width: 768px) {
      .sh-search-wrap { display: none; }
      .sh-nav { display: none; }
      .sh-user-info { display: none; }
      .stitch-header { padding: 0 1rem; }
    }
  </style>
</head>

<!-- HEADER STITCH -->
<header class="stitch-header">

  <!-- IZQUIERDA: menú + logo + nav -->
  <div class="sh-left">
    <!-- Botón hamburguesa AdminLTE -->
    <button class="sh-menu-btn" data-widget="pushmenu" data-toggle="tooltip" title="Menú">
      <i class="fas fa-bars"></i>
    </button>

    <!-- Logo -->
    <a href="" class="sh-logo d-none d-md-block" onclick="reload();" data-tooltip="tooltip" title="Recargar página">
      <i class="fas fa-ticket-alt" style="font-size:15px; margin-right:6px;"></i>IOPA System
      <span style="font-weight:300; color:#64748b;"> | E-Tickets</span>
    </a>

    <!-- Nav links -->
    <nav class="sh-nav">
      <a href="" class="active" onclick="reload();">
        <i class="fas fa-ticket-alt" style="font-size:11px; margin-right:4px;"></i> Tickets
      </a>

      <?php if (isset($_SESSION['usuario']) && in_array($_SESSION['usuario'], $usuarios_permitidos) && $_SESSION['permiso'] == 'admin'): ?>
        <a href="<?= constant('URL'); ?>proyectos/verTabla" data-tooltip="tooltip" title="Ir a proyectos IOPA">
          <i class="fas fa-tasks" style="font-size:11px; margin-right:4px;"></i> Proyectos
        </a>
      <?php endif; ?>

      <?php
      include_once 'models/usuariosperfil.php';
      foreach ($this->usuariosperfil as $row) {
        $usuariosperfil = new Usuariosperfil();
        $usuariosperfil = $row;
        if ($usuariosperfil->principal == "Formularios"): ?>
          <a href="<?php echo constant('URL') . $usuariosperfil->menu; ?>/verPaginacion/1">
            <i class="fas fa-edit" style="font-size:11px; margin-right:4px;"></i>
            <?php echo $usuariosperfil->menu; ?>
          </a>
        <?php endif;
      }
      ?>
    </nav>
  </div>

  <!-- CENTRO: buscador (decorativo, puedes conectar tu lógica) -->
  <div class="sh-search-wrap">
    <!-- <div class="sh-search">
      <i class="fas fa-search"></i>
      <input type="text" placeholder="Buscar tickets, usuarios...">
    </div> -->
  </div>

  <!-- DERECHA: acciones + perfil -->
  <div class="sh-right">

    <!-- Badge de permiso -->
    <span style="font-size:10px; font-weight:700; background:#dbeafe; color:#1d4ed8;
                 padding:3px 10px; border-radius:999px; letter-spacing:0.05em; text-transform:uppercase;">
      <?= strtoupper($permiso); ?>
    </span>

    <div class="sh-divider"></div>

    <!-- Info usuario -->
    <div class="sh-user-info mr-2 d-none d-md-block">
      <span class="sh-user-name">
        <?php
        $nombre = explode('@', $usuario_email)[0];  // christopher.soto
        $nombre = str_replace('.', ' ', $nombre);   // christopher soto
        $nombre = ucwords($nombre);                 // Christopher Soto
        echo $nombre.' ('.$usuario_rebsol.')';
        ?>

      </span>
      <!-- <span class="sh-user-role"><?= $usuario_email; ?></span> -->
      <span class="sh-user-role"><?= $area; ?></span>
    </div>

    <!-- Avatar con iniciales -->
    <div class="sh-avatar" data-tooltip="tooltip" title="<?= $usuario_email; ?>">
      <?= $iniciales; ?>
    </div>

    <!-- Logout -->
    <a href="<?= constant('URL'); ?>login/salir" class="sh-logout ml-1" data-tooltip="tooltip" title="Cerrar sesión">
      <i class="fas fa-sign-out-alt"></i>
    </a>

  </div>
</header>

<body class="sidebar-mini layout-navbar-fixed sidebar-collapse sidebar-closed">