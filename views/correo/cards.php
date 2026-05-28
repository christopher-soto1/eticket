<?php
$permiso = $this->permiso;
$asignacion = $this->asignacion;

$correoModel = new CorreoModel();
//CONTADORES ADMIN
$noAsignados = $correoModel->getTicketsNoAsignados();
$asignados = $correoModel->getTicketsAsignados();
$finalizados = $correoModel->getTicketsFinalizados();
$enProgresoAdmin = $correoModel->getTicketsEnProgreso();
$realizados = $correoModel->getTicketsRealizados();

//CONTADORES USUARIO
$noAsignadosUsuario = $correoModel->getTicketsNoAsignadosUsuario($asignacion);
$asignadosUsuario = $correoModel->getTicketsAsignadosUsuario($asignacion);
$enProgresoUsuario = $correoModel->getTicketsEnProgresoUsuario($asignacion);
$realizadoUsuario = $correoModel->getTicketsRealizadoUsuario($asignacion);
$finalizadosUsuario = $correoModel->getTicketsFinalizadosUsuario($asignacion);

?>
<!-- CARDS -->
<script>
  var rol = <?php echo json_encode($permiso); ?>;
  var usuarioID = <?php echo json_encode($asignacion); ?>;
  var correos = <?php echo json_encode($this->correo); ?>;
  var correoRespuesta = <?php echo json_encode($this->correoRespuesta); ?>;

  

  //PARAR MOSTRAR PERMISOS DEL USUARIO
  console.log("Rol:", rol);
  console.log("ID usuario:", usuarioID);
</script>

<!-- CONTADORES -->

      <style>
          .glass-counter {
              border-radius: 15px;
              transition: all 0.3s ease;
              border: none;
              overflow: hidden;
              box-shadow: 0 4px 6px rgba(0,0,0,0.1);
          }
          .glass-counter:hover {
              transform: translateY(-5px);
              box-shadow: 0 8px 15px rgba(0,0,0,0.2);
          }
          .card-icon-bg {
              position: absolute;
              right: -10px;
              bottom: -10px;
              font-size: 4rem;
              opacity: 0.2;
              color: #fff;
              transform: rotate(-15deg);
          }
      </style>
      <!-- STYLE DE CONTADORES -->
      <style>
        .stats-bar {
          display: flex;
          justify-content: center; 
          gap: 12px;
          padding: 12px 20px;
          background: #f8fafc;
          overflow-x: auto;
          border-bottom: 1px solid #e2e8f0;
          flex-shrink: 0;
          -ms-overflow-style: none;
          scrollbar-width: none;
          margin-top: -48px; /* no borrar */
        }
        .stats-bar::-webkit-scrollbar { display: none; }

        .stat-chip {
          display: flex;
          align-items: center;
          gap: 10px;
          padding: 8px 16px;
          background: #ffffff;
          border-radius: 12px;
          border: 1px solid #e2e8f0;
          box-shadow: 0 1px 3px rgba(0,0,0,0.06);
          white-space: nowrap;
          cursor: pointer;
          transition: box-shadow 0.2s ease, transform 0.2s ease;
          text-decoration: none;
        }
        .stat-chip:hover {
          box-shadow: 0 4px 10px rgba(0,0,0,0.12);
          transform: translateY(-2px);
        }
        .stat-dot {
          width: 8px;
          height: 8px;
          border-radius: 50%;
          flex-shrink: 0;
        }
        .stat-label {
          font-size: 11px;
          font-weight: 700;
          color: #64748b;
          text-transform: uppercase;
          letter-spacing: 0.05em;
        }
        .stat-count {
          font-size: 15px;
          font-weight: 900;
          color: #0f172a;
        }
        .content {
          padding-top: 0 !important;
          margin-top: 0 !important;
        }

        .content-wrapper {
          padding-top: 0 !important;
        }
      </style>
      <!-- STYLE DE CONTADORES -->

      <!-- STYLE DE CARDS -->
       <style>
        /* --- Ticket Card Moderna --- */
        .ticket-card-modern {
          background: #ffffff;
          border-radius: 16px;
          border: 1px solid rgba(0,0,0,0.07);
          box-shadow: 0 4px 24px rgba(24,28,35,0.04);
          transition: box-shadow 0.3s ease, transform 0.3s ease;
          overflow: hidden;
          margin-bottom: 1.25rem;
        }
        /* .ticket-card-modern:hover {
          box-shadow: 0 8px 40px rgba(0,89,187,0.08);
          transform: translateY(-1px);
        } */
        .ticket-card-body {
          padding: 1.5rem;
        }
        /* Badge de estado */
        .ticket-badge {
          display: inline-block;
          padding: 3px 12px;
          border-radius: 999px;
          font-size: 10px;
          font-weight: 700;
          text-transform: uppercase;
          letter-spacing: 0.06em;
        }
        .badge-sin-asignar  { background: #e0f2fe; color: #0369a1; }
        .badge-asignado     { background: #dbeafe; color: #1d4ed8; }
        .badge-en-progreso  { background: #fef3c7; color: #b45309; }
        .badge-realizado    { background: #ede9fe; color: #6d28d9; }
        .badge-finalizado   { background: #dcfce7; color: #15803d; }
        .badge-eliminado    { background: #fee2e2; color: #b91c1c; }
        /* Borde lateral de color según estado */
        .ticket-card-modern.estado-1 { border-left: 4px solid #0ea5e9; }
        .ticket-card-modern.estado-2 { border-left: 4px solid #3b82f6; }
        .ticket-card-modern.estado-3 { border-left: 4px solid #22c55e; }
        .ticket-card-modern.estado-4 { border-left: 4px solid #f59e0b; }
        .ticket-card-modern.estado-5 { border-left: 4px solid #ef4444; }
        .ticket-card-modern.estado-6 { border-left: 4px solid #8b5cf6; }
        /* Info grid */
        .ticket-info-grid {
          display: grid;
          grid-template-columns: repeat(2, 1fr);
          gap: 12px 20px;
          margin-top: 1rem;
        }
        @media (min-width: 768px) {
          .ticket-info-grid { grid-template-columns: repeat(4, 1fr); }
        }
        .ticket-info-item {
          display: flex;
          align-items: center;
          gap: 10px;
          min-width: 0;
        }
        .ticket-info-icon {
          width: 32px;
          height: 32px;
          border-radius: 8px;
          background: #f1f5f9;
          display: flex;
          align-items: center;
          justify-content: center;
          flex-shrink: 0;
          color: #0059bb;
          font-size: 16px;
        }
        .ticket-info-label {
          font-size: 10px;
          font-weight: 700;
          text-transform: uppercase;
          letter-spacing: 0.05em;
          color: #94a3b8;
          margin-bottom: 2px;
        }
        .ticket-info-value {
          font-size: 13px;
          font-weight: 500;
          color: #1e293b;
          white-space: nowrap;
          overflow: hidden;
          text-overflow: ellipsis;
          max-width: 160px;
        }
        /* Sidebar */
        .ticket-sidebar {
          border-top: 1px solid rgba(0,0,0,0.06);
          padding-top: 1.25rem;
          margin-top: 1.25rem;
        }
        @media (min-width: 992px) {
          .ticket-layout { display: flex; gap: 1.5rem; }
          .ticket-main   { flex: 1; min-width: 0; }
          .ticket-sidebar {
            width: 260px;
            flex-shrink: 0;
            border-top: none;
            border-left: 1px solid rgba(0,0,0,0.06);
            padding-top: 0;
            padding-left: 1.5rem;
            margin-top: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
          }
        }
        .ticket-time-label {
          font-size: 10px;
          font-weight: 700;
          text-transform: uppercase;
          letter-spacing: 0.06em;
          color: #94a3b8;
        }
        .ticket-time-value {
          font-size: 13px;
          font-weight: 600;
          color: #1e293b;
          margin-top: 2px;
        }
        /* Botones acción primaria */
        .btn-ticket-primary {
          flex: 1;
          background: #0059bb;
          color: #fff;
          border: none;
          border-radius: 8px;
          padding: 8px 0;
          font-size: 12px;
          font-weight: 700;
          cursor: pointer;
          transition: filter 0.2s;
        }
        .btn-ticket-primary:hover { filter: brightness(1.1); color: #fff; }
        .btn-ticket-secondary {
          flex: 1;
          background: #f1f5f9;
          color: #475569;
          border: none;
          border-radius: 8px;
          padding: 8px 0;
          font-size: 12px;
          font-weight: 700;
          cursor: pointer;
          transition: background 0.2s;
        }
        .btn-ticket-secondary:hover { background: #e2e8f0; color: #1e293b; }
        /* Botones acción secundaria (outline) */
        .btn-ticket-outline {
          flex: 1;
          background: transparent;
          color: #64748b;
          border: 1px solid #e2e8f0;
          border-radius: 6px;
          padding: 6px 4px;
          font-size: 10px;
          font-weight: 700;
          cursor: pointer;
          display: flex;
          align-items: center;
          justify-content: center;
          gap: 4px;
          transition: background 0.2s;
        }
        .btn-ticket-outline:hover { background: #f8fafc; }
        .btn-ticket-outline.danger:hover { background: #fef2f2; color: #dc2626; border-color: #fca5a5; }
        /* Footer de la card */
        .ticket-footer {
          border-top: 1px solid rgba(0,0,0,0.05);
          padding: 12px 1.5rem;
          display: flex;
          justify-content: space-between;
          align-items: center;
          background: #fafafa;
        }
        .btn-ticket-link {
          background: none;
          border: none;
          color: #0059bb;
          font-size: 12px;
          font-weight: 600;
          cursor: pointer;
          display: flex;
          align-items: center;
          gap: 5px;
          padding: 0;
        }
        .btn-ticket-link:hover { text-decoration: underline; color: #0059bb; }
        /* Asunto */
        .ticket-subject {
          font-size: 16px;
          font-weight: 700;
          color: #0f172a;
          margin: 8px 0 0 0;
          line-height: 1.4;
        }
        .ticket-uid {
          font-size: 12px;
          font-weight: 500;
          color: #94a3b8;
          margin-left: 8px;
        }
      </style>
      <!-- STYLE DE CARDS -->

      <!-- STYLE DE PAGINADOR -->
      <!-- STYLE DE PAGINADOR -->
       
      <section class="content" style="padding-top: 0; margin-top: 0;">
        <div class="container-fluid">

          <div class="stats-bar">

            <div class="stat-chip btn-detalle"
              data-toggle="modal"
              data-target="#modalGeneral"
              data-tipo="sin_asignar"
              data-permiso="<?=$permiso?>"
              data-usuario="<?=$asignacion?>"
              data-titulo="Tickets Sin Asignar"
              data-color="bg-info">
              <div class="stat-dot" style="background:#17a2b8;"></div>
              <span class="stat-label">Sin Asignar</span>
              <span class="stat-count"><?= ($permiso == 'admin') ? $noAsignados : $noAsignadosUsuario; ?></span>
            </div>

            <div class="stat-chip btn-detalle"
              data-toggle="modal"
              data-target="#modalGeneral"
              data-tipo="asignados"
              data-permiso="<?=$permiso?>"
              data-usuario="<?=$asignacion?>"
              data-titulo="Tickets Asignados"
              data-color="bg-primary">
              <div class="stat-dot" style="background:#007bff;"></div>
              <span class="stat-label">Asignados</span>
              <span class="stat-count"><?= ($permiso == 'admin') ? $asignados : $asignadosUsuario; ?></span>
            </div>

            <div class="stat-chip btn-detalle"
              data-toggle="modal"
              data-target="#modalGeneral"
              data-tipo="progreso"
              data-permiso="<?=$permiso?>"
              data-usuario="<?=$asignacion?>"
              data-titulo="Tickets en Progreso"
              data-color="bg-warning">
              <div class="stat-dot" style="background:#ffc107;"></div>
              <span class="stat-label">En Progreso</span>
              <span class="stat-count"><?= ($permiso == 'admin') ? $enProgresoAdmin : $enProgresoUsuario; ?></span>
            </div>

            <div class="stat-chip btn-detalle"
              data-toggle="modal"
              data-target="#modalGeneral"
              data-tipo="realizados"
              data-permiso="<?=$permiso?>"
              data-usuario="<?=$asignacion?>"
              data-titulo="Tickets Realizados"
              data-color="bg-purple">
              <div class="stat-dot" style="background:#6f42c1;"></div>
              <span class="stat-label">Realizados</span>
              <span class="stat-count"><?= ($permiso == 'admin') ? $realizados : $realizadoUsuario; ?></span>
            </div>

            <div class="stat-chip btn-detalle"
              data-toggle="modal"
              data-target="#modalGeneral"
              data-tipo="finalizados"
              data-permiso="<?=$permiso?>"
              data-usuario="<?=$asignacion?>"
              data-titulo="Tickets Finalizados"
              data-color="bg-success">
              <div class="stat-dot" style="background:#28a745;"></div>
              <span class="stat-label">Finalizados</span>
              <span class="stat-count"><?= ($permiso == 'admin') ? $finalizados : $finalizadosUsuario; ?></span>
            </div>

          </div>

        </div>
      </section>
      
      <br>
<!-- PAGINADOR SUPERIOR -->
      <div class="d-flex justify-content-center">
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
<!-- PAGINADOR SUPERIOR -->
 
      <?php
      $registros_mostrados = count($this->correo); // lo que estás mostrando en esta página
      $total = $this->total_registros;

      $inicio = ($this->paginaactual - 1) * $this->registros_por_pagina + 1;
      $fin = $inicio + $registros_mostrados - 1;
      ?>
      <div class="text-center text-muted mt-2">
        Mostrando <?php echo $registros_mostrados; ?> registro<?php echo $registros_mostrados == 1 ? '' : 's'; ?> de <?php echo $total; ?>
      </div>

<section class="content">
  <div class="container-fluid">
    <div class="row justify-content-center" id="contenedor_cards">
      <?php foreach ($this->correo as $correo): ?>

        <?php
          // Badge y clase según estado
          switch ($correo->estado) {
            case 1: $badgeClass = 'badge-sin-asignar';  $badgeText = 'Sin asignar';  break;
            case 2: $badgeClass = 'badge-asignado';     $badgeText = 'Asignado';     break;
            case 3: $badgeClass = 'badge-finalizado';   $badgeText = 'Finalizado';   break;
            case 4: $badgeClass = 'badge-en-progreso';  $badgeText = 'En progreso';  break;
            case 5: $badgeClass = 'badge-eliminado';    $badgeText = 'Eliminado';    break;
            case 6: $badgeClass = 'badge-realizado';    $badgeText = 'Realizado';    break;
            default: $badgeClass = 'badge-asignado';   $badgeText = 'Desconocido';
          }
          // Asignado texto
          $asignadoTexto = !empty($correo->asignado) ? $correo->asignado : 'Sin asignar';
          // Correos destino
          $correos_raw    = $correo->correo_destino;
          $correos_array  = explode(',', str_replace(['[', ']', '"'], '', $correos_raw));
          $correos_limpios = implode(', ', array_map('trim', $correos_array));
          $destinatario   = !empty(trim($correos_limpios)) ? $correos_limpios : 'No disponible';
          $cc             = empty($correo->cc) ? 'No disponible' : $correo->cc;
          // Tiempos
          if ($correo->dias_desde_creacion > 0)
            $creacion = 'Hace ' . $correo->dias_desde_creacion . ' ' . ($correo->dias_desde_creacion == 1 ? 'día' : 'días');
          elseif ($correo->horas_desde_creacion > 0)
            $creacion = 'Hace ' . $correo->horas_desde_creacion . ' ' . ($correo->horas_desde_creacion == 1 ? 'hora' : 'horas');
          elseif ($correo->minutos_desde_creacion > 0)
            $creacion = 'Hace ' . $correo->minutos_desde_creacion . ' ' . ($correo->minutos_desde_creacion == 1 ? 'minuto' : 'minutos');
          else $creacion = '--';

          if ($correo->dias_desde_actualizacion > 0)
            $actualizacion = 'Hace ' . $correo->dias_desde_actualizacion . ' ' . ($correo->dias_desde_actualizacion == 1 ? 'día' : 'días');
          elseif ($correo->horas_desde_actualizacion > 0)
            $actualizacion = 'Hace ' . $correo->horas_desde_actualizacion . ' ' . ($correo->horas_desde_actualizacion == 1 ? 'hora' : 'horas');
          elseif ($correo->minutos_desde_actualizacion > 0)
            $actualizacion = 'Hace ' . $correo->minutos_desde_actualizacion . ' ' . ($correo->minutos_desde_actualizacion == 1 ? 'minuto' : 'minutos');
          else $actualizacion = 'Sin actualizaciones';
        ?>

        <div class="col-12 col-lg-10 col-xl-9">
          <div class="ticket-card-modern estado-<?php echo $correo->estado; ?>">

            <!-- BODY -->
            <div class="ticket-card-body">
              <div class="ticket-layout">

                <!-- COLUMNA PRINCIPAL -->
                <div class="ticket-main">

                  <!-- Header: badge + uid -->
                  <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                    <span class="ticket-badge <?php echo $badgeClass; ?>"><?php echo $badgeText; ?></span>
                    <?php if (!empty($correo->asignado)): ?>
                      <span style="font-size:11px; color:#64748b; font-weight:500;">
                        → <strong><?php echo htmlspecialchars($correo->asignado); ?></strong>
                      </span>
                    <?php endif; ?>
                    <span class="ticket-uid">#<?php echo $correo->uid; ?></span>
                  </div>

                  <!-- Asunto -->
                  <p class="ticket-subject"><?php echo htmlspecialchars($correo->asunto); ?></p>

                  <!-- Grid de info -->
                  <div class="ticket-info-grid">
                    <div class="ticket-info-item">
                      <div class="ticket-info-icon"><i class="fas fa-envelope-open-text"></i></div>
                      <div style="min-width:0;">
                        <div class="ticket-info-label">Correo origen</div>
                        <div class="ticket-info-value" title="<?php echo $correo->correo_origen; ?>"><?php echo $correo->correo_origen; ?></div>
                      </div>
                    </div>
                    <div class="ticket-info-item">
                      <div class="ticket-info-icon"><i class="fas fa-inbox"></i></div>
                      <div style="min-width:0;">
                        <div class="ticket-info-label">Destinatario</div>
                        <div class="ticket-info-value" title="<?php echo $destinatario; ?>"><?php echo $destinatario; ?></div>
                      </div>
                    </div>
                    <div class="ticket-info-item">
                      <div class="ticket-info-icon"><i class="fas fa-users"></i></div>
                      <div style="min-width:0;">
                        <div class="ticket-info-label">CC</div>
                        <div class="ticket-info-value" title="<?php echo $cc; ?>"><?php echo $cc; ?></div>
                      </div>
                    </div>
                    <div class="ticket-info-item">
                      <div class="ticket-info-icon"><i class="fas fa-calendar-alt"></i></div>
                      <div style="min-width:0;">
                        <div class="ticket-info-label">Fecha recepción</div>
                        <div class="ticket-info-value"><?php echo $correo->fecha_envio; ?></div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- SIDEBAR DERECHO -->
                <div class="ticket-sidebar">

                  <!-- Tiempos -->
                  <div style="display:flex; gap:1.5rem; flex-wrap:wrap; margin-bottom:1rem;">
                    <div>
                      <div class="ticket-time-label">Creación</div>
                      <div class="ticket-time-value"><?php echo $creacion; ?>
                        <i class="fas fa-info-circle text-success" style="font-size:11px;" data-tooltip="tooltip" title="Tiempo desde la creación en la base de datos."></i>
                      </div>
                    </div>
                    <div>
                      <div class="ticket-time-label">Última actualización</div>
                      <div class="ticket-time-value" style="color:#0059bb;"><?php echo $actualizacion; ?></div>
                    </div>
                  </div>

                  <!-- Botones primarios -->
                  <div style="display:flex; gap:8px; margin-bottom:8px;">
                    <button class="btn-ticket-primary open-contenido-modal"
                      data-id="<?php echo $correo->uid; ?>"
                      data-message-id="<?php echo $correo->message_id; ?>"
                      data-toggle="modal"
                      data-target="#modalContenido"
                      data-tooltip="tooltip"
                      title="Visualización de contenido extraído del correo">
                      Ver contenido
                    </button>
                    <button class="btn-ticket-secondary open-detalle-modal"
                      data-id="<?php echo $correo->uid; ?>"
                      data-toggle="modal"
                      data-target="#modalDetalle"
                      data-tooltip="tooltip"
                      title="Detalles asociados al correo">
                      Ver detalles
                    </button>
                  </div>

                  <!-- Botones secundarios -->
                  <div style="display:flex; gap:6px; flex-wrap:wrap;">

                    <?php if (strtolower($permiso) == 'admin'): ?>
                      <button class="btn-ticket-outline open-asignacion-modal"
                        data-id="<?php echo $correo->uid; ?>"
                        data-fecha="<?php echo $correo->fecha_envio; ?>"
                        data-correo-origen="<?php echo $correo->correo_origen; ?>"
                        data-asunto="<?php echo htmlspecialchars($correo->asunto, ENT_QUOTES); ?>"
                        data-toggle="modal"
                        data-target="#modalAsignacion"
                        data-tooltip="tooltip"
                        title="Asignar este ticket a un usuario">
                        <i class="fas fa-user-plus" style="font-size:11px;"></i> Asignar
                      </button>
                    <?php endif; ?>

                    <?php if (strtolower($permiso) != 'admin'): ?>
                      <!-- <button class="btn-ticket-outline open-cambiar-modal"
                        data-id="<?php echo $correo->uid; ?>"
                        data-toggle="modal"
                        data-target="#modalCambiarEstado"
                        data-tooltip="tooltip"
                        title="Actualizar estado del ticket asignado">
                        <i class="fas fa-sync-alt" style="font-size:11px;"></i> Actualizar
                      </button> -->
                    <?php endif; ?>

                    <?php if (strtolower($permiso) == 'admin'): ?>
                      <button class="btn-ticket-outline open-editar-modal"
                        data-id="<?php echo $correo->uid; ?>"
                        data-fecha="<?php echo $correo->fecha_envio; ?>"
                        data-asunto="<?php echo htmlspecialchars($correo->asunto, ENT_QUOTES); ?>"
                        data-asignado="<?php echo $correo->asignado; ?>"
                        data-estado-actual="<?php echo $correo->estado; ?>"
                        data-toggle="modal"
                        data-target="#modalEditar"
                        data-tooltip="tooltip"
                        title="Actualizar el estado de este ticket">
                        <i class="fas fa-flag" style="font-size:11px;"></i> Estado
                      </button>
                      <button class="btn-ticket-outline danger spam"
                        data-id="<?php echo $correo->uid; ?>"
                        data-correo-origen="<?php echo $correo->correo_origen; ?>"
                        data-tooltip="tooltip"
                        title="Marcar correo como spam"
                        onclick="marcarSpam();">
                        <i class="fas fa-ban" style="font-size:11px;"></i> Spam
                      </button>
                    <?php endif; ?>

                  </div>
                </div>
              </div>
            </div>

            <!-- FOOTER -->
            <div class="ticket-footer">
              <div style="display:flex; gap:16px;">
                <button class="btn-ticket-link open-historial"
                  data-id="<?php echo $correo->uid; ?>"
                  data-toggle="modal"
                  data-target="#modalHistorial"
                  data-tooltip="tooltip"
                  title="Historial de cambios del ticket #<?php echo $correo->uid; ?>">
                  <i class="fas fa-history"></i> Historial
                </button>
                <button class="btn-ticket-link open-hilo"
                  style="display:none;"
                  data-message-id="<?php echo htmlspecialchars($correo->message_id, ENT_QUOTES); ?>"
                  data-uid="<?php echo $correo->uid; ?>"
                  data-toggle="modal"
                  data-target="#modalHilo"
                  data-tooltip="tooltip"
                  title="Ver respuestas relacionadas con este ticket">
                  <i class="fas fa-comments"></i> Ver hilo
                </button>
              </div>
              <span style="font-size:11px; color:#cbd5e1; font-weight:500;">
                #<?php echo $correo->uid; ?>
              </span>
            </div>

          </div>
        </div>

      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- PAGINADOR INFERIOR -->
      <div class="d-flex justify-content-center">
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
<!-- PAGINADOR INFERIOR -->

<!-- FIN DE TODO EL CONTENIDO -->