

<?php require 'views/header.php' ?>


  <div class="wrapper">

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
          background: linear-gradient(180deg, #f4f6f9 0%, #f4f6f9 100%);
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
          color: #000000;
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
          color: #000000;
        }

        /* Íconos */
        .filtro-icono {
          /* color: #cce5ff; */
          color: #000000;
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
          color: #000000;
        }

        /* Transiciones suaves */
        .filtro-input input,
        .filtro-input select,
        .filtro-boton .btn,
        #btnSincronizar {
          transition: all 0.2s ease-in-out;
        }

        .content-wrapper {
          margin-top: 0px !important;
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
        /* Efecto de elevación al pasar el mouse */
        .card {
            /* Mantenemos la transición en 0.4s con una curva profesional */
            /* El cubic-bezier es lo que hace que parezca "orgánico" */
            transition: transform 0.4s cubic-bezier(0.25, 1, 0.5, 1), 
                        box-shadow 0.4s cubic-bezier(0.25, 1, 0.5, 1) !important;
            
            /* Esto ayuda a que el navegador use la tarjeta de video para la animación */
            will-change: transform;
        }
        .card:hover {
            /* Bajamos a -8px para que no sea tan agresivo el salto */
            transform: translateY(-4px) !important;
            
            /* Sombra más elegante: más difusa (30px) y menos oscura (0.12) */
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12) !important;
        }
      </style>

      <br>

      

      <!-- Main Sidebar Container -->
      <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #ffffff; color: white;">
        <!-- Sidebar -->
        <div class="sidebar px-2">
          <br class="filtro-icono">

          <!-- 
          filtro-icono = se visualiza con el side bard cerrado, se oculta con el side bard abierto
          filtro-input = se visualiza con el side bard abierto, se oculta con el side bard cerrado 
          filtro-boton = se visualiza con el side bard abierto, se oculta con el side bard cerrado 
          -->


          <div class="mt-2">
            <!-- FECHA DE INICIO -->
              <i class="fas fa-calendar-alt me-2 filtro-icono"
                style="display: none; margin-left: 20px;margin-bottom: 20px;"></i>
            <div class="form-group filtro-item">
                <label class="filtro-label filtro-input" for="fecha_inicio">Fecha de Inicio</label>
                <div class="input-group">
                    <input type="date" 
                          class="form-control form-control-sm mb-2 filtro-input" 
                          id="fecha_inicio" 
                          name="fecha_inicio">
                </div>
            </div>

            <!-- Fecha de Fin -->
             <i class="fas fa-calendar-alt filtro-icono" title="Fecha de Fin"
                style="display: none;margin-left: 20px;margin-bottom: 20px;"></i>
            <div class="form-group filtro-item">
                <label class="filtro-label filtro-input" for="fecha_fin">Fecha de Fin</label>
                <input type="date" 
                      class="form-control form-control-sm mb-2 filtro-input" 
                      id="fecha_fin" 
                      name="fecha_fin">
            </div>

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
              <input type="text" placeholder="gonzalez o gonzalez@iopa.cl"
                class="form-control form-control-sm mb-2 filtro-input" id="correo_origen" name="correo_origen">
            </div>

            <!-- Buscar por ID -->
            <div class="form-group filtro-id-ticket">
              <label class="filtro-input" for="id_ticket">ID Ticket</label>
              <i class="fas fa-hashtag filtro-icono" title="ID del ticket"
                style="display: none; margin-left: 20px; margin-bottom: 20px;"></i>
              <input type="text" placeholder="R-123 o r-123 o 123"
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
            <div class="d-flex align-items-center filtro-boton">
              <button class="btn btn-primary btn-sm flex-grow-1 shadow-sm" onclick="filtrarCards();">
                <i class="fas fa-filter mr-1"></i> Filtrar
              </button>

              <button class="btn btn-secondary btn-sm ml-2" id="limpiar_filtros">
                <i class="fas fa-eraser"></i>
              </button>

              <button class="btn btn-sm ml-2" id="reload"
                      style="background-color: #007bff; border-color: #007bff; color: white;">
                  <i class="fas fa-home"></i>
              </button>
            </div>
            <!-- <hr style="border: none; border-top: 1px solid white;"> -->

            <div class="form-group d-flex align-items-center filtro-item">
              <i class="fas fa-sync-alt me-2 filtro-icono" style="display: none; margin-left: 20px;"></i>
            </div>

            <br>
            <hr style="border: none; border-top: 1px solid black;" class="filtro-boton">

            <div class="d-flex justify-content-between mb-2 filtro-boton">
              <button id="btnSincronizar" class="btn btn-warning btn-sm w-100 shadow-sm">
                <i class="fas fa-sync-alt mr-2"></i> Sincronizar E-Tickets
              </button>
            </div>

            <!-- <hr style="border: none; border-top: 1px solid white;"> -->

            <div class="form-group d-flex align-items-center filtro-item">
              <i class="fas fa-chart-line me-2 filtro-icono" style="display: none; margin-left: 20px;"></i>
            </div>

            <div class="d-flex justify-content-between mb-2 filtro-boton">
              <button id="btnEstadisticas" 
                      class="btn btn-info btn-sm w-100 shadow-sm open-estadisticas" 
                      data-toggle="modal" 
                      data-target="#modalEstadisticas"
                <i class="fas fa-chart-pie mr-2"></i> Ver Estadísticas
              </button>
            </div>

            <?php
              
           if (isset($_SESSION['usuario']) &&  in_array($_SESSION['usuario'], ['christopher.soto@iopa.cl','catalina.henriquez@iopa.cl'])) {?>
            <!-- <hr style="border: none; border-top: 1px solid white;"> -->

            <div class="d-flex justify-content-between mb-2 filtro-boton">
              <button type="button" 
                      class="btn btn-success btn-sm w-100 shadow-sm" 
                      data-toggle="modal" 
                      data-target="#modalAgregarUsuario"
                      style="color: white;">
                <i class="fas fa-user-plus mr-2"></i> Agregar Usuarios
              </button>
            </div>
            <?php }?>
            <hr style="border: none; border-top: 1px solid black;" class="filtro-boton">

          </div>

          <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
      </aside>

      <!-- Rescata permisos para la consulta -->
      <meta id="permiso" data-permiso="<?php echo $permiso; ?>">
      <meta id="asignacion" data-asignacion="<?php echo $idusuario0; ?>">

      <script>
        var permiso = document.getElementById("permiso").getAttribute("data-permiso");
        var asignacion = document.getElementById("asignacion").getAttribute("data-asignacion");
      </script>


      <br>
      


      <!-- CARDS -->
      <div id="container-full">
        <?php
        //var_dump($correos);


        include 'views/correo/cards.php';
        ?>
      </div>

      <br>
      

      

      <!-- MODAL DETALLE -->
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

      <!-- MODAL CONTENIDO -->
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

      <!-- MODAL DESCONEXION -->
      <div class="modal fade" id="modalSesion" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content shadow-lg border-0 rounded-lg">

            <!-- Header -->
            <div class="modal-header bg-danger text-white">
              <h5 class="modal-title">
                <i class="fas fa-exclamation-triangle mr-2"></i> Sesión expirada
              </h5>
            </div>

            <!-- Body -->
            <div class="modal-body text-center py-4">
              <p class="mb-3" style="font-size: 16px;">
                Tu sesión ha expirado por seguridad.
              </p>

              <p class="text-muted mb-4">
                Has superado el tiempo máximo de <strong>1 hora de sesión</strong>.
              </p>

              <i class="fas fa-clock fa-3x text-danger mb-3"></i>
            </div>

            <!-- Footer -->
            <div class="modal-footer justify-content-center border-0 pb-4">
              <a href="<?= constant('URL'); ?>login" class="btn btn-danger px-4">
                <i class="fas fa-sign-in-alt mr-1"></i> Volver a iniciar sesión
              </a>
            </div>

          </div>
        </div>
      </div>

      <!-- MODAL AGREGAR USUARIO -->
      <div class="modal fade" id="modalAgregarUsuario" tabindex="-1" role="dialog" aria-labelledby="modalAgregarUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            
            <!-- Header del Modal -->
            <div class="modal-header bg-success text-white">
              <h5 class="modal-title" id="modalAgregarUsuarioLabel">
                <i class="fas fa-user-plus mr-2"></i> Agregar Nuevo Usuario
              </h5>
              <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
              </button>
            </div>
            
            <!-- Body del Modal -->
            <div class="modal-body">
              <form id="formAgregarUsuario" method="POST" action="<?php echo constant('URL'); ?>usuarios/agregarUsuario">
                
                <!-- Correo -->
                <div class="form-group">
                  <label for="correo">
                    <i class="fas fa-envelope mr-1"></i> Correo Electrónico
                  </label>
                  <input type="email" 
                        class="form-control" 
                        id="correo" 
                        name="correo" 
                        placeholder="ejemplo@correo.com" 
                        required>
                </div>
                
                <!-- Contraseña -->
                <div class="form-group">
                  <label for="contrasena">
                    <i class="fas fa-lock mr-1"></i> Contraseña
                  </label>
                  <input type="password" 
                        class="form-control" 
                        id="contrasena" 
                        name="contrasena" 
                        placeholder="Mínimo 6 caracteres" 
                        minlength="6"
                        required>
                </div>
                
                <!-- Área -->
                <div class="form-group">
                  <label for="area">
                    <i class="fas fa-briefcase mr-1"></i> Área
                  </label>
                  <select class="form-control" id="area" name="area" required>
                    <option value="">Seleccione un área</option>
                    <option value="Soporte TI">Soporte TI</option>
                    <option value="Programación">Programación</option>
                  </select>
                </div>
                
              </form>
            </div>
            
            <!-- Footer del Modal -->
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">
                <i class="fas fa-times mr-1"></i> Cancelar
              </button>
              <button type="submit" form="formAgregarUsuario" class="btn btn-success">
                <i class="fas fa-save mr-1"></i> Guardar Usuario
              </button>
            </div>
            
          </div>
        </div>
      </div>

      <div class="modal fade" id="modalGeneral" tabindex="-1" role="dialog" aria-labelledby="modalGeneralLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header text-white" id="modalHeaderColor">
                    <h5 class="modal-title" id="modalGeneralLabel">Cargando...</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modalGeneralBody">
                    <div class="text-center p-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Cargando...</span>
                        </div>
                    </div>
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
          var usuarios = <?php echo json_encode($this->asignaciones); ?>;
          var estadisticas = <?php echo json_encode($this->estadisticas); ?>;
          var estadisticasEnProgreso = <?php echo json_encode($this->estadisticasEnProgreso); ?>;
          //console.log(usuarios);

          // ----------------- TIEMPO DE PARA DESCONEXION DE LA SESION ACTUAL -----------------
          let tiempoTotal = <?= 8 * 60 * 60 * 1000 ?>; // 4 horas
          let tiempoPopup = tiempoTotal - (120 * 1000); // 20 segundos antes
          //let tiempoTotal = 30 * 1000; // 30 segundos //debugg
          //let tiempoPopup = 25 * 1000; // popup antes //debugg
          //console.log("La sesión se cerrará en:", (tiempoTotal / (1000 * 60 * 60)).toFixed(2), "horas");
          // ----------------- TIEMPO DE PARA DESCONEXION DE LA SESION ACTUAL -----------------

          $(document).off('click', '.btn-detalle').on('click', '.btn-detalle', function() {
              // 1. Obtener datos de la card clickeada
              var tipo = $(this).data('tipo');
              var titulo = $(this).data('titulo');
              var colorClase = $(this).data('color'); // Ejemplo: bg-primary, bg-success
              var estado;

              console.log("Tipo: ",tipo);
              console.log("titulo: ",titulo);
              console.log("colorClase: ",colorClase);

              const mapaEstados = {
                  'sin_asignar': 1,
                  'asignados':   2,
                  'progreso':    4,
                  'realizados':  6,
                  'finalizados': 3
              };

              var estadoId = mapaEstados[tipo] || 0;
              console.log("Enviando estado ID:", estadoId);

              // 2. Resetear y aplicar color al header
              // Limpiamos clases de fondo previas para que no se mezclen
              $('#modalHeaderColor').removeClass('bg-primary bg-warning bg-success bg-info bg-purple');
              
              // Si es el color morado y no tienes la clase en CSS, la aplicamos manual
              if(colorClase === 'bg-purple') {
                  $('#modalHeaderColor').css('background-color', '#6f42c1');
              } else {
                  $('#modalHeaderColor').css('background-color', ''); // Limpia el estilo manual
                  $('#modalHeaderColor').addClass(colorClase);
              }

              // 3. Cambiar el título
              $('#modalGeneralLabel').text(titulo);

              // 4. Limpiar el cuerpo del modal y poner el spinner
              $('#modalGeneralBody').html('<div class="text-center p-4"><div class="spinner-border text-secondary" role="status"></div></div>');

              // 5. Llamada AJAX
              $.ajax({
                  url: '<?= constant('URL'); ?>correo/obtenerTicketsPorEstado', // Cambia esto por tu archivo real
                  type: 'POST',
                  data: { estadoId: estadoId },
                  success: function(response) {
                      if (response.success) {
                          var tickets = response.data;
                          var est = estadoId; // El ID del estado actual
                          var html = '';

                          if (tickets.length === 0) {
                              html = '<div class="alert alert-info">No hay registros para este estado.</div>';
                          } else {
                              html = '<div class="table-responsive">' +
                                    '<table class="table table-hover table-sm">' +
                                    '<thead class="thead-light"><tr>' +
                                    '<th style="width: 50px;">#</th>' +
                                    '<th>UID</th>' +
                                    '<th>Solicitante</th>' +
                                    '<th>Asunto</th>';

                              // --- Lógica de Encabezados Dinámicos ---
                              if (est != 1) html += '<th>Asignado</th>';
                              /* if (est == 6 || est == 3) html += '<th>Coment. Desarrollador</th>';
                              if (est == 3) html += '<th>Respuesta Final</th>'; */
                              
                              html += '<th>Fecha</th>';

                              html += '<th>Acciones</th></tr></thead><tbody>';

                              // --- Lógica de Filas ---
                              tickets.forEach(function(t, i) {
                                  var estiloColumna = 'style="word-break: break-all; min-width: 150px; max-width: 250px; font-size: 0.9rem;"';
                                  var numeroRegistro = i + 1;
                                  html += '<tr>' +
                                          '<td class="font-weight-bold text-muted">' + numeroRegistro + '</td>' +
                                          '<td>' + t.uid + '</td>' +
                                          // Columna Solicitante con break
                                          '<td ' + estiloColumna + '>' + 
                                              (t.usuario_solicitante ? t.usuario_solicitante.trim() : '-') + 
                                          '</td>' +
                                          // Columna Asunto con break (le damos un poco más de ancho máximo)
                                          '<td style="word-break: break-word; min-width: 200px; max-width: 300px; font-size: 0.9rem;">' + 
                                              (t.asunto ? t.asunto.trim() : '-') + 
                                          '</td>';

                                  if (est != 1) {
                                      html += '<td>' + (t.asignado ? t.asignado : '<span class="badge badge-secondary">Sin asignar</span>') + '</td>';
                                  }
                                  
                                  /* if (est == 6 || est == 3) {
                                      html += '<td style="word-break: break-word;">' + (t.comentario_desarrollador ? t.comentario_desarrollador : '-') + '</td>';
                                  }

                                  if (est == 3) {
                                      html += '<td style="word-break: break-word;">' + (t.comentario_finalizacion ? t.comentario_finalizacion : '-') + '</td>';
                                  } */

                                  html += '<td>' + t.fecha_envio + '</td>';

                                  // --- Columna de Acciones ---

                                  html += '<td>' +
                                      '<button class="btn btn-primary btn-sm btn-ir-al-ticket shadow-sm" ' +
                                      'data-uid="' + t.uid + '">' +
                                          '<i class="fas fa-search mr-1"></i> Ir' +
                                      '</button>' +
                                  '</td></tr>';
                              });

                              html += '</tbody></table></div>';
                          }
                          $('#modalGeneralBody').html(html);
                      }
                  },
                  error: function() {
                      $('#modalGeneralBody').html('<div class="alert alert-danger">Error al cargar los datos.</div>');
                  }
              });
          });

          $(document).on('click', '.btn-ir-al-ticket', function() {
              var uid = $(this).data('uid');

              // 1. Cerramos el modal
              $('#modalGeneral').modal('hide');

              // 2. Limpiamos los otros inputs de filtro para que no interfieran (Opcional pero recomendado)
              $('#fecha_inicio, #fecha_fin, #correo_origen, #asunto').val('');
              $('#estado, #usuario_asignado, #dias_creacion').val('0');

              // 3. Inyectamos el UID en el input de id_ticket
              // Nota: Asegúrate de que el id sea "id_ticket" como en tu función
              $('#id_ticket').val(uid);

              // 4. Ejecutamos tu función de filtrado existente
              filtrarCards(1); 
              
              // 5. Scroll suave hacia arriba para ver el resultado
              $('html, body').animate({ scrollTop: 0 }, 'slow');
          });

          


          // mostrar popup
          setTimeout(() => {
              //console.log("⚠ Mostrando popup");
              $('#modalSesion').modal('show');
          }, tiempoPopup);

          // cerrar sesión
          setTimeout(() => {
              //console.log("🚪 Cerrando sesión");
              window.location.href = '<?= constant('URL'); ?>correo/salir';
          }, tiempoTotal);

          // seguir (solo cierra popup, NO reinicia nada)
          $('#stayLogged').on('click', function () {
              $('#modalSesion').modal('hide');
          });

          // salir
          $('#logoutNow').on('click', function () {
              window.location.href = '<?= constant('URL'); ?>correo/salir';
          });


          // CONTENIDO
          $(document).off('click', '.open-contenido-modal').on('click', '.open-contenido-modal', function () {
            const uid = $(this).data('id');
            const correo = correos.find(c => c.uid == uid);

            // HILO EN CADENA
            const message_id = $(this).data('message-id'); // message_id del correo principal

            // ------------ DEBUG ------------
            //console.log("UID clickeado:", uid);
            //console.log("Message ID clickeado:", message_id);
            const payload = {
              UID_clickeado: uid,
              Message_ID_clickeado: message_id
            };
            //console.log("CONTENIDO - HILO: ",payload);
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
                    <div id="textareaResponderConHilo" style="display: none;">
                                          <div class="form-group">
                      <label class="form-label" style="color: black;">Respuesta:</label>
                      <textarea id="editorRespuestaUsuario" class="form-control mb-2" rows="4" placeholder="Escribe una respuesta para el usuario..."></textarea>
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

                // ---------------  TEXT AREA RESPUESTA AL USUARIO ---------------

                $('#btnResponderUsuarioFinal').on('click', function (e) {
                    e.preventDefault(); // Evita cualquier comportamiento extraño del botón
                    
                    // Tu lógica de hilos existente
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
          $(document).off('click', '.open-detalle-modal').on('click', '.open-detalle-modal', function () {
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
          $(document).off('click', '.open-asignacion-modal').on('click', '.open-asignacion-modal', function () {
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
                  <div class="form-check mt-3">
                      <input class="form-check-input" type="checkbox" id="checkNotificar" checked>
                      <label class="form-check-label" for="checkNotificar">
                          ¿Enviar notificación de recepción y asignación de ticket al usuario solicitante? (${correo.correo_origen}).
                      </label>
                  </div>
                  <br>
                  <em>Se enviará una notificación al <strong>desarrollador</strong> que se seleccione y al <strong>usuario</strong> que solicitó el requerimiento, indicando la asignación de este ticket.</em>
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
          $(document).off('click', '.open-editar-modal').on('click', '.open-editar-modal', function () {
            const uid = $(this).data('id');
            const correo = correos.find(c => c.uid == uid);
            const asignado = $(this).data('asignado');
            const estado_actual = $(this).data('estado-actual');
            //console.log("estado_actual:", estado_actual);
            const esFinalizado = (estado_actual == 3) ? 'readonly style="background-color: #e9ecef;"' : '';

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
              <label for="comentarioEstado">Comentario de cierre:</label>
              <textarea id="comentarioEstado" class="form-control" rows="3" ${esFinalizado} placeholder="Escribe un comentario para el usuario final...">${correo.respuesta_correo == null ? '' : correo.respuesta_correo}</textarea>
              <em>El comentario será enviado en forma de respuesta automática al correo de origen: <strong>${correo.correo_origen}</strong></em>
            </div>
            <div id="textareaContainerDesarrollador" class="mt-3" style="display: none;">
              <label for="comentarioEstadoDesarrollador">Comentario del responsable:</label>
              <textarea id="comentarioEstadoDesarrollador" class="form-control" rows="3" placeholder="Escribe un comentario respecto a la realización del ticket...">${correo.comentario_desarrollador == null ? '' : correo.comentario_desarrollador}</textarea>
              <em>El comentario será registrado como información para el(los) usuario(s) final(es) asociado(s) a: <strong>${correo.correo_origen}</strong></em>
            </div>

            <div id="checkEnvioCorreoFinalizado" class="form-check mt-3" style="display: none;">
                <input class="form-check-input" type="checkbox" id="checkNotificarCorreoFinalizado" checked>
                <label class="form-check-label" for="checkNotificarCorreoFinalizado">
                    ¿Enviar notificación de finalización de ticket al usuario solicitante? <em><strong>${correo.correo_origen}</strong></em>.
                </label>
            </div>
            <br>

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
                  $('#checkEnvioCorreoFinalizado').show();
                } else {
                  $('#textareaContainer').hide();
                }
              });

              // Si ya está seleccionado "Finalizado" al abrir el modal, mostramos el textarea VALIDAR
              if (correo.estado == 3) {
                $('#textareaContainer').show();
                $('#checkEnvioCorreoFinalizado').show();
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
          $(document).off('click', '.open-cambiar-modal').on('click', '.open-cambiar-modal', function () {
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
          $(document).off('click', '.open-estadisticas').on('click', '.open-estadisticas', function () {
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

          //HISTORIAL NEW 19-02-2026
          $(document).off('click', '.open-historial').on('click', '.open-historial', function () {
              const uid = $(this).data('id');

              $('#modalHistorialLabel').text('Historial de Ticket #' + uid);
              $('#modalHistorialBody').html('<p>Cargando...</p>');

              $.ajax({
                  url: '<?php echo constant('URL'); ?>correo/obtenerHistorialPorUid', // ajusta según tu MVC
                  method: 'POST',
                  data: { uid: uid },
                  success: function (response) {

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

                      if (response.length > 0) {
                          response.forEach((registro, index) => {
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
                      } else {
                          contenido += `<p>No se encontraron registros en el historial.</p>`;
                      }

                      contenido += `</tbody></table></div>`;

                      $('#modalHistorialBody').html(contenido);
                  },
                  error: function () {
                      $('#modalHistorialBody').html('<p>Error al cargar historial</p>');
                  }
              });
          });

          // HILO
          $(document).off('click', '.open-hilo').on('click', '.open-hilo', function () {
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
                  <br>
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

          //console.log("RESPONDER AL USUARIO:", payload);

          $.ajax({
          url: "<?php echo constant('URL'); ?>correo/enviarRespuestaUsuario",
          method: 'POST',
          data: payload,
          success: function (respuesta) {
            if (respuesta === true || respuesta === 'true' || respuesta === '1' || respuesta === 1) {
              Swal.fire({
                icon: 'success',
                title: 'Respuesta enviada',
                  text: 'La respuesta fue enviada correctamente, a continuación se sincronizarán los E-Tickets para que puedas ver tu respuesta en el hilo.',
                confirmButtonText: 'OK'
              }).then((result) => {
                    $('#textareaResponderSinHilo textarea, #textareaResponderConHilo textarea').val('');
                    $('#textareaResponderSinHilo, #textareaResponderConHilo').hide();
                    //console.log("ejecutarSincronizacion");
                    ejecutarSincronizacion();
                    //console.log("filtrarCards(pagina)");
                    //filtrarCards(pagina); // Aquí ejecutas tu función
                    
                });
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
            const notificar = document.getElementById('checkNotificar').checked ? 1 : 0;
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
              usuario: usuario,
              notificar: notificar
            };

            //console.log("GUARDAR ASIGNACION (no modificar listener): ", payload);

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
                usuario,
                notificar
              })
            })
              .then(response => response.json())
              .then(data => {
                //console.log("Respuesta del backend:", data);
                if (data.success) {
                  Swal.fire({
                    title: '¡Éxito!',
                    html: data.message + (parseInt(data.notificar) === 1 
                                          ? '<br><strong>Se notificó</strong> al usuario solicitante.' 
                                          : '<br><strong>No se notificó</strong> al usuario solicitante.'),
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
            var notificar = document.getElementById('checkNotificarCorreoFinalizado').checked ? 1 : 0;

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
              comentarioDesarrollador: comentarioDesarrollador,
              notificar: notificar

            };
            //console.log("ESTADO (ADMIN): ", payload);

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
                      &estado_actualPalabra=${encodeURIComponent(estado_actualPalabra)}
                      &notificar=${encodeURIComponent(notificar)}`
            })
              .then(response => response.text())
              .then(data => {
                //console.log("Respuesta:", data);

                if (data.includes("Estado actualizado")) {
                  let mensaje;

                  if (parseInt(notificar) === 0) {
                    mensaje = `Estado actualizado a <strong>'Finalizado'</strong> y <strong>sin notificación de finalización a usuario</strong>.`;
                  } else {
                    mensaje = data; // mensaje que viene del servidor
                  }
                  
                  $('#modalEditar').modal('hide');
                  /* $('body').removeClass('modal-open'); */
                  $('.modal-backdrop').remove();
                  Swal.fire({
                    title: '¡Éxito!',
                    html: mensaje, // Muestra el mensaje completo que venga del servidor
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

          //console.log("FUNCION DE FILTRADO:", payload);

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
              //document.open();
              //document.write(html); //rompe el flujo de eventos, manejar
              //document.close();
              $("#container-full").html(html);
              $('[data-tooltip="tooltip"]').tooltip({ trigger: 'hover' });
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
            },
            error: function (xhr, status, error) {
              console.error("Error al filtrar:", error);
            }
          });

        }
        // ----- FUNCION DE FILTRADO -----

        // ----- FUNCION DE LIMPIADO CONTROLADO DE UID (solo R, E, guión y números) -----
        $(document).on('input', '#id_ticket', function () {
          //console.log("LIMPIADO");
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
          $('#correo_origen').val('');
          $('#id_ticket').val('');
          $('#asunto').val('');
          $('#multirespuesta').val('0');
          $('#dias_creacion').val('0'); 
          //location.reload();
        });
        // ----- BORRAR FILTROS -----

        // ----- ACTUALIZAR PAGINA -----
        $(document).on('click', '#reload', function () {
          location.reload();
        });
        // ----- ACTUALIZAR PAGINA -----


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
              //console.log("El usuario confirmó");
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
                dataType: 'json', // 👈 IMPORTANTE
                success: function (response) {
                  console.log("RESPONSE: ", response);
                  Swal.fire({
                    icon: 'success',
                    title: '¡Sincronización completa!',
                    html: `Se sincronizaron <strong>${response.procesados} correo(s)</strong> correctamente.`,
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

          //console.log("SPAM:", payload);

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
                  //console.log("Respuesta del servidor:", response);
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

        // RESPUESTA PARA HILO
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
                  //console.log(response);

                  if (response.toLowerCase().includes('correo enviado correctamente')) {
                    Swal.fire({
                      icon: 'success',
                      title: '¡Respuesta enviada!',
                      text: response
                    }).then(() => {
                      //console.log("REALIZADO");
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

        function ejecutarSincronizacion() {
            var pagina = <?php echo $pagina_actual; ?>;
            var correosRespuesta = 1;

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
                data: { 
                    esRespuesta: correosRespuesta // Variable para sincronizar menos correos de la carga habitual
                },
                dataType: 'json',
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Sincronización completa!',
                        html: `Se sincronizaron <strong>${response.procesados} correo(s)</strong> correctamente.`,
                    }).then(() => {
                        //location.reload();
                        filtrarCards(pagina);
                    });
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al sincronizar',
                        text: 'Hubo un problema durante la sincronización.',
                    });
                }
            });
        }

        // GUARDAR NUEVO USUARIO
        $(document).on('submit', '#formAgregarUsuario', function(e) {
          e.preventDefault(); // Prevenir envío tradicional del formulario
          
          const correo = $('#correo').val().trim();
          const contrasena = $('#contrasena').val().trim();
          const area = $('#area').val();

          // Validaciones
          if (!correo || !contrasena || !area) {
            Swal.fire({
              icon: 'warning',
              title: 'Campos incompletos',
              text: 'Todos los campos son obligatorios',
              confirmButtonColor: '#3085d6'
            });
            return;
          }

          // Validar email
          const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          if (!emailRegex.test(correo)) {
            Swal.fire({
              icon: 'error',
              title: 'Email inválido',
              text: 'Por favor ingresa un correo electrónico válido',
              confirmButtonColor: '#3085d6'
            });
            return;
          }

          // Validar contraseña
          if (contrasena.length < 6) {
            Swal.fire({
              icon: 'error',
              title: 'Contraseña corta',
              text: 'La contraseña debe tener al menos 6 caracteres',
              confirmButtonColor: '#3085d6'
            });
            return;
          }

          // Enviar datos al controlador
          $.ajax({
            url: '<?php echo constant('URL'); ?>correo/agregarUsuario',
            type: 'POST',
            data: {
              correo: correo,
              contrasena: contrasena,
              area: area
            },
            dataType: 'json',
            beforeSend: function() {
              // Opcional: Mostrar loading
              Swal.fire({
                title: 'Guardando...',
                text: 'Por favor espera',
                allowOutsideClick: false,
                didOpen: () => {
                  Swal.showLoading();
                }
              });
            },
            success: function(response) {
              if (response.success) {
                Swal.fire({
                  icon: 'success',
                  title: '¡Éxito!',
                  text: 'Usuario creado correctamente',
                  confirmButtonColor: '#28a745'
                }).then(() => {
                  $('#modalAgregarUsuario').modal('hide');
                  location.reload(); // Recargar para ver el nuevo usuario
                });
              } else {
                Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: response.mensaje || 'No se pudo crear el usuario',
                  confirmButtonColor: '#dc3545'
                });
              }
            },
            error: function(xhr, status, error) {
              Swal.fire({
                icon: 'error',
                title: 'Error del servidor',
                text: 'Ocurrió un error al procesar la solicitud',
                confirmButtonColor: '#dc3545'
              });
              console.error('Error:', error);
              console.error('Respuesta:', xhr.responseText);
            }
          });
        });
      </script>

    </div> <!-- content-wrapper -->

  </div> <!-- wrapper -->
<?php require 'views/footer.php'; ?>