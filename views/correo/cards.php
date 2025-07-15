<?php
$permiso = $this->permiso;
$asignacion = $this->asignacion;
?>
<!-- CARDS -->
<script>
  var rol = <?php echo json_encode($permiso); ?>;
  var usuarioID = <?php echo json_encode($asignacion); ?>;

  //PARAR MOSTRAR PERMISOS DEL USUARIO
  //console.log("Rol:", rol);
  //console.log("ID usuario:", usuarioID);
</script>


<section class="content">
  <div class="container-fluid">
    <div class="row justify-content-center" id="contenedor_cards">
      <?php
      foreach ($this->correo as $correo) {

        // Determinamos el color de la card según el estado
        $cardColor = '';
        switch ($correo->estado) {
          case 1: // Sin asignar
            $cardColor = 'card-info'; // Azul claro (info)
            break;
          case 2: // Asignado
            $cardColor = 'card-primary'; // Azul (primary)
            break;
          case 3: // Finalizado
            $cardColor = 'card-success'; // Verde (success)
            break;
          case 4: // En progreso
            $cardColor = 'card-warning'; // Amarillo (warning)
            break;
          case 5: // Eliminado
            $cardColor = 'card-danger'; // Rojo (danger)
            break;
          case 6:
            $cardColor = 'card-purple';
            break;
          default:
            $cardColor = 'card-secondary';
            break;
        }
        ?>
        <div class="col-12 col-sm-10 col-md-11 col-lg-7 mb-4">
          <div style="max-width: 800px;" class="card <?php echo $cardColor; ?> card-outline">
            <div class="card-header">
              <h3 class="card-title">Ticket #<?php echo $correo->uid; ?></h3>
              <div class="card-tools">
              </div>
            </div>
            <!-- asd -->

            <div class="card-body p-2">
              <div class="row align-items-start">

                <div class="col-md-8 col-12">
                  <p class="mb-1"><strong>Estado del ticket:</strong>
                    <?php
                    if ($correo->estado == 1) {
                      echo "Sin asignar";
                    } elseif ($correo->estado == 2) {
                      echo "Asignado" . (!empty($correo->asignado) ? " / <strong>Asignado a:</strong> " . $correo->asignado : " / <em>sin usuario asignado</em>");
                    } elseif ($correo->estado == 3) {
                      echo "Finalizado" . (!empty($correo->asignado) ? " / <strong>Asignado a:</strong> " . $correo->asignado : " / <em>sin usuario asignado</em>");
                    } elseif ($correo->estado == 5) {
                      echo "Eliminado" . (!empty($correo->asignado) ? " / <strong>Asignado a:</strong> " . $correo->asignado : " / <em>sin usuario asignado</em>");
                    } elseif ($correo->estado == 6) {
                      echo "Realizado" . (!empty($correo->asignado) ? " / <strong>Asignado a:</strong> " . $correo->asignado : " / <em>sin usuario asignado</em>");
                    } else {
                      echo "En progreso" . (!empty($correo->asignado) ? " / <strong>Asignado a:</strong> " . $correo->asignado : " / <em>sin usuario asignado</em>");
                    }
                    ?>
                  </p>
                  <?php
                  $correos_raw = $correo->correo_destino;
                  $correos_array = explode(',', str_replace(['[', ']', '"'], '', $correos_raw));
                  $correos_limpios = implode(', ', array_map('trim', $correos_array));
                  ?>
                  <p class="mb-1"><strong>Correo origen:</strong> <?php echo $correo->correo_origen ?></p>
                  <p class="mb-1"><strong>Destinatario:</strong>
                    <?= !empty(trim($correos_limpios)) ? $correos_limpios : 'No disponible' ?>
                  </p>
                  <p class="mb-1"><strong>CC:</strong> <?php echo empty($correo->cc) ? 'No disponible' : $correo->cc ?></p>
                  <p class="mb-1"><strong>Asunto:</strong> <?php echo $correo->asunto ?></p>
                  <p class="mb-1">
                    <strong>Fecha de recepción:</strong>
                    <?php echo $correo->fecha_envio ?> <i class="text-success" data-tooltip="tooltip" title="Fecha en que el servidor recibió el correo.">ⓘ</i>
                  </p>
                  <p class="mb-1"><strong>Creación:</strong>
                    <?php
                    if ($correo->dias_desde_creacion > 0) {
                      echo 'Hace ' . $correo->dias_desde_creacion . ' ' . ($correo->dias_desde_creacion == 1 ? 'día' : 'días');
                    } elseif ($correo->horas_desde_creacion > 0) {
                      echo 'Hace ' . $correo->horas_desde_creacion . ' ' . ($correo->horas_desde_creacion == 1 ? 'hora' : 'horas');
                    } elseif ($correo->minutos_desde_creacion > 0) {
                      echo 'Hace ' . $correo->minutos_desde_creacion . ' ' . ($correo->minutos_desde_creacion == 1 ? 'minuto' : 'minutos');
                    } else {
                      echo '--';
                    }
                    ?> <i class="text-success" data-tooltip="tooltip" title="Tiempo transcurrido desde su creación en la base de datos.">ⓘ</i>
                  </p>
                  <p class="mb-1"><strong>Última actualización:</strong>
                    <?php
                    if ($correo->dias_desde_actualizacion > 0) {
                      echo 'Hace ' . $correo->dias_desde_actualizacion . ' ' . ($correo->dias_desde_actualizacion == 1 ? 'día' : 'días');
                    } elseif ($correo->horas_desde_actualizacion > 0) {
                      echo 'Hace ' . $correo->horas_desde_actualizacion . ' ' . ($correo->horas_desde_actualizacion == 1 ? 'hora' : 'horas');
                    } elseif ($correo->minutos_desde_actualizacion > 0) {
                      echo 'Hace ' . $correo->minutos_desde_actualizacion . ' ' . ($correo->minutos_desde_actualizacion == 1 ? 'minuto' : 'minutos');
                    } else {
                      echo 'No hay actualizaciones';
                    }
                    ?>
                  </p>
                  
                </div>

                <div class="col-md-4 col-12 d-flex justify-content-end align-items-start mt-2 mt-md-0">
                  <!-- Sección de acciones -->
                  <div class="actions mt-0 w-100">
                    <div class="d-flex flex-row flex-md-column align-items-end flex-wrap">
                      <button class="btn btn-primary btn-sm open-contenido-modal mb-2 ml-1" style="width: 100px;"
                        data-id="<?php echo $correo->uid; ?>" 
                        data-message-id="<?php echo $correo->message_id; ?>"
                        data-toggle="modal" 
                        data-target="#modalContenido"
                        data-tooltip="tooltip" 
                        title="Visualización de contenido extraído del correo">
                        Ver contenido
                      </button>
                      <button class="btn btn-warning btn-sm open-detalle-modal mb-2 ml-1" style="width: 100px;" 
                        data-id="<?php echo $correo->uid; ?>"
                        data-toggle="modal" 
                        data-target="#modalDetalle" 
                        data-tooltip="tooltip"
                        title="Detalles asociados al correo">
                        Ver detalles
                      </button>

                      <?php if (strtolower($permiso) == 'admin') { ?>
                        <button class="btn btn-success btn-sm open-asignacion-modal mb-2 ml-1" style="width: 100px;"
                          data-id="<?php echo $correo->uid; ?>" 
                          data-fecha="<?php echo $correo->fecha_envio; ?>"
                          data-correo-origen="<?php echo $correo->correo_origen; ?>"
                          data-asunto="<?php echo htmlspecialchars($correo->asunto, ENT_QUOTES); ?>" 
                          data-toggle="modal"
                          data-target="#modalAsignacion" data-tooltip="tooltip" title="Asignar este ticket a un usuario">
                          Asignar
                        </button>
                      <?php } ?>

                      <?php if (strtolower($permiso) != 'admin') { ?>
                        <button class="btn btn-success btn-sm open-cambiar-modal mb-2 ml-1" 
                          data-id="<?php echo $correo->uid; ?>"
                          data-toggle="modal" 
                          data-target="#modalCambiarEstado" 
                          data-tooltip="tooltip"
                          title="Actualizar estado del ticket asignado">
                          Actualizar Estado
                        </button>
                      <?php } ?>

                      <?php if (strtolower($permiso) == 'admin') { ?>
                        <button class="btn btn-info btn-sm open-editar-modal mb-2 ml-1" style="width: 100px;" 
                          data-id="<?php echo $correo->uid; ?>"
                          data-fecha="<?php echo $correo->fecha_envio; ?>"
                          data-asunto="<?php echo htmlspecialchars($correo->asunto, ENT_QUOTES); ?>" 
                          data-toggle="modal"
                          data-asignado="<?php echo $correo->asignado; ?>"
                          data-estado-actual="<?php echo $correo->estado; ?>"
                          data-target="#modalEditar" 
                          data-tooltip="tooltip" 
                          title="Actualizar el estado de este ticket">
                          Estado
                        </button>
                        <button class="btn btn-danger spam btn-sm mb-2 ml-1" style="width: 100px;" 
                          data-id="<?php echo $correo->uid; ?>"
                          data-correo-origen="<?php echo $correo->correo_origen; ?>" 
                          data-tooltip="tooltip"
                          title="Marcar correo como spam" 
                          onclick="marcarSpam();">
                          Spam
                        </button>
                      <?php } ?>
                      <button class="btn btn-light btn-sm open-historial mb-2 ml-1" style="width: 100px;" 
                        data-id="<?php echo $correo->uid; ?>"
                        data-toggle="modal" 
                        data-target="#modalHistorial" 
                        data-tooltip="tooltip"
                        title="Muestra el historial de cambios del ticket #<?php echo $correo->uid; ?>">
                        Historial
                      </button>
                      <button class="btn btn-secondary btn-sm open-hilo mb-2 ml-1" style="width: 100px; display: none;" 
                        data-message-id="<?php echo htmlspecialchars($correo->message_id, ENT_QUOTES); ?>"
                        data-uid="<?php echo $correo->uid; ?>"
                        data-message-id="<?php echo $correo->message_id; ?>"
                        data-toggle="modal"
                        data-target="#modalHilo"
                        data-tooltip="tooltip"
                        title="Ver todas las respuestas relacionadas con este ticket">
                        Ver hilo
                      </button>

                    </div>
                  </div>
                </div>

              </div>
            </div>


            <!-- asd -->
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
</section>

<!-- FIN DE TODO EL CONTENIDO -->