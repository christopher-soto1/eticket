<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    session_unset();
    session_destroy();
    header("Location: " . constant('URL') . "login");
    exit();
}


require 'views/header_proyectos.php';
$permiso = $this->permiso;

?>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

  <!-- Moment.js (requerido por daterangepicker) -->
  <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

  <!-- Daterangepicker -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
  <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<style>
    .powerbi-container {
        position: relative;
        width: 100%;
        height: auto; /* ✅ deja que crezca según el contenido */
        overflow: visible; /* ✅ muestra todo el contenido */
        padding-bottom: 180px; /* espacio para el footer fijo */
    }

    /* .powerbi-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
    } */
    h1 {
        font-family: Arial, sans-serif;
        font-size: 2em;
        color: #555;
        text-transform: uppercase; /* Convierte el texto a mayúsculas */
        letter-spacing: 2px; /* Aumenta el espacio entre las letras */
        margin-bottom: 15px;
        }
    #tablaProyectos {
        margin-bottom: 160px;
    }

    footer {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: #f8f9fa;
        padding: 15px 0;
        text-align: center;
        border-top: 1px solid #dee2e6;
        color: #6c757d;
        font-size: 14px;
        z-index: 1000;
    }
    .btnEstilos {
        width: 120px;         /* 🔧 ajusta según tu gusto */
        text-align: center;   /* centra el texto */
        margin: 2px;
    }

</style>

<div class="powerbi-container">
    <div class="container mt-4">
            <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#modalAgregarProyecto">
            + Agregar proyecto
            </button>
            <!-- <a class="btn btn-success mb-3 btn-descargar-plantilla" onclick="descargarPlantilla()">
                <i class="fas fa-download"></i> 
                Plantilla de Proyectos
            </a> -->
            <a href="#" id="btnDescargarPlantilla" class="descargar-plantilla-funcional">
                <i class="fas fa-download"></i>
                Plantilla de Proyectos
            </a>


        <?php if (!empty($this->totalRegistros)): ?>
        <table id="tablaProyectos" class="table table-striped table-bordered mt-3">
            <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Nombre del Proyecto</th>
                <th>Descripción</th>
                <th>Fecha de Creación</th>
                <!-- <th>Ruta</th> -->
                <th>Responsable</th>
                <th>Estado</th>
                <!-- <th>Adjuntar Documentos</th> -->
                <!-- <th>Adjuntar Credenciales</th> -->
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($this->totalRegistros as $registro): ?>
                <tr>
                    <!-- ID -->
                    <td><?= htmlspecialchars($registro->id) ?></td>

                    <!-- Nombre proyecto -->
                    <td><?= htmlspecialchars($registro->nombre_proyecto) ?></td>

                    <!-- Descripcion -->
                    <td><?= htmlspecialchars($registro->descripcion) ?></td>

                    <!-- Fecha creacion -->
                    <td><?= date('Y-m-d', strtotime($registro->fecha_creacion)) ?></td>

                    <!-- Directorio -->
                    <!-- <td><?= htmlspecialchars($registro->ruta_directorio_global) ?></td> -->

                    <!-- Responsable -->
                    <td><?= htmlspecialchars($registro->responsable) ?></td>

                    <!-- Estado -->
                    <td>
                        <?php
                        switch ($registro->estado) {
                            case 1: echo 'Producción'; break;
                            case 2: echo 'Desarrollo'; break;
                            case 3: echo 'No asignado'; break;
                            default: echo 'Desconocido'; break;
                        }
                        ?>
                    </td>

                    <!-- Adjuntar Archivos -->
                    <!-- <td id="boton-<?= $registro->id ?>" 
                        class="celda-boton"
                        data-id="<?= $registro->id ?>"
                        data-nombre="<?= htmlspecialchars($registro->nombre_proyecto) ?>"
                        data-directorio="<?= htmlspecialchars($registro->ruta_directorio_global ?? '') ?>">
                        
                        <span class="btn-verificar" data-id="<?= $registro->id ?>"></span>
                    </td> -->



                    <!-- Adjuntar Credenciales -->
                    <!-- <td>

                    </td> -->

                    <!-- Editar -->
                    <td>
                        <button class="btn btn-primary btn-sm btnEditarProyecto btnEstilos"
                                style="margin: 1px;"
                                data-toggle="modal"
                                data-target="#modalEditarProyecto"
                                data-id="<?= htmlspecialchars($registro->id) ?>"
                                data-nombre="<?= htmlspecialchars($registro->nombre_proyecto) ?>"
                                data-descripcion="<?= htmlspecialchars($registro->descripcion) ?>"
                                data-responsable="<?= htmlspecialchars($registro->responsable) ?>"
                                data-fecha="<?= htmlspecialchars($registro->fecha_creacion) ?>"
                                data-estado="<?= htmlspecialchars($registro->estado) ?>">
                        <i class="fa fa-edit"></i> Editar
                        </button>

                        <?php
                        if ($registro->ruta_directorio_global) { ?>
                            <button class="btn btn-info btn-sm btnEstilos"
                                    style="margin: 1px;"
                                    data-toggle="modal"
                                    data-target="#modalVerDocumentos"
                                    data-nombre="<?= htmlspecialchars($registro->nombre_proyecto) ?>"
                                    data-id="<?= htmlspecialchars($registro->id) ?>">
                                    Ver documentos
                            </button>
                            <button class="btn btn-success btn-sm btnEstilos"
                                    style="margin: 1px;"
                                    data-toggle="modal"
                                    data-target="#modalAdjuntar"
                                    data-id="<?= htmlspecialchars($registro->id) ?>"
                                    data-nombre="<?= htmlspecialchars($registro->nombre_proyecto) ?>"
                                    data-tipo="documento">
                                    <i class="fa fa-upload"></i> 
                                    Adjuntar
                            </button>
                        <?php } else {?>
                            <i class="fa fa-times text-danger"></i> Sin directorio
                        <?php } ?>
                    </td>

                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
            <?php else: ?>
            <div class="alert alert-warning mt-3">
                No hay proyectos registrados en la base de datos.
            </div>
            <?php endif; ?>

    </div>


</div>

<!-- MODAL ADJUNTO-->
<div class="modal fade" id="modalAdjuntar" tabindex="-1" role="dialog" aria-labelledby="modalAdjuntarLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalAdjuntarLabel">Adjuntar archivos</h5>
      </div>

      <div class="modal-body">
        <form id="formAdjuntar" enctype="multipart/form-data">
          <input type="hidden" name="id_registro" id="id_registro">

          <div id="contenedor-archivos">
            <div class="form-group archivo-item mb-3 border rounded p-2">
              <label>Seleccionar archivo:</label>
              <input type="file" name="archivo[]" class="form-control" required>

              <label class="mt-2">Descripción:</label>
              <textarea name="descripcion[]" class="form-control" rows="2" placeholder="Ingrese una descripción..."></textarea>
            </div>
          </div>

          <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="btnAgregarArchivo">
            <i class="fa fa-plus"></i> Agregar otro archivo
          </button>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="submit" form="formAdjuntar" class="btn btn-success">Subir</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal AGREGAR Proyecto -->
<div class="modal fade" id="modalAgregarProyecto" tabindex="-1" aria-labelledby="modalAgregarProyectoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalAgregarProyectoLabel">Agregar nuevo proyecto</h5>
      </div>

      <form id="formNuevoProyecto">
        <div class="modal-body">
          
          <div class="mb-3">
            <label class="form-label">Nombre del Proyecto</label>
            <input type="text" name="nombre_proyecto" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="3" required></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Fecha de Creación</label>
            <input type="date" name="fecha_creacion" class="form-control" value="<?= date('Y-m-d') ?>">
          </div>

          <div class="mb-3">
            <label class="form-label">Creador(es) del proyecto</label>
            <input type="text" name="responsable" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Estado</label>
            <select name="estado" class="form-select" required>
              <option value="">Seleccione...</option>
              <option value="1">Producción</option>
              <option value="2">Desarrollo</option>
              <option value="3">No asignado</option>
            </select>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">Guardar proyecto</button>
        </div>
      </form>

    </div>
  </div>
</div>

<!-- Modal EDITAR Proyecto -->
<div class="modal fade" id="modalEditarProyecto" tabindex="-1" aria-labelledby="modalEditarProyectoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalEditarProyectoLabel">Editar proyecto</h5>
      </div>

      <form id="formEditarProyecto">
        <div class="modal-body">

          <!-- ID oculto -->
          <input type="hidden" name="id" id="editar_id">

          <div class="mb-3">
            <label class="form-label">Nombre del Proyecto</label>
            <input type="text" name="nombre_proyecto" id="editar_nombre" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" id="editar_descripcion" class="form-control" rows="3" required></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Fecha de Creación</label>
            <input type="date" name="fecha_creacion" id="editar_fecha" class="form-control" value="<?= date('Y-m-d') ?>">
          </div>

          <div class="mb-3">
            <label class="form-label">Creador(es) del proyecto</label>
            <input type="text" name="responsable" id="editar_responsable" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Estado</label>
            <select name="estado" id="editar_estado" class="form-select" required>
              <option value="">Seleccione...</option>
              <option value="1">Producción</option>
              <option value="2">Desarrollo</option>
              <option value="3">No asignado</option>
            </select>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">Actualizar</button>
        </div>
      </form>

    </div>
  </div>
</div>

<!-- MODAL VER DOCUMENTOS -->
<div class="modal fade" id="modalVerDocumentos" tabindex="-1" role="dialog" aria-labelledby="modalVerDocumentosLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="modalVerDocumentosLabel">Documentos del proyecto</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <!-- Lugar para mostrar un spinner mientras carga -->
        <div id="doc-loading" class="text-center my-3" style="display: none;">
          <div class="spinner-border" role="status"><span class="sr-only">Cargando...</span></div>
          <div>Cargando documentos...</div>
        </div>

        <!-- Contenedor donde se inyectará la tabla/lista -->
        <div id="doc-contenido">
          <!-- Aquí se inyectará una tabla dinámica -->
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>






<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>

<script>
    // FUNCION COMPLEMENTARIA PARA VISUALIZACION DE BOTONES DE ACCIONES EN EL FRONT
    function verificarBotones() {
        let proyectos = [];

        // Recorre cada celda de botón
        $('.btn-verificar').each(function() {
            const id = $(this).data('id');
            const celda = $('#boton-' + id);
            const directorio = celda.data('directorio');

            if (!directorio || directorio.trim() === '') {
                // No tiene ruta -> mensaje en lugar de botón
                celda.html('<i class="fa fa-times text-danger"></i> Sin directorio');
            } else {
                // Tiene ruta, se incluye para verificar archivos
                celda.html('<span class="text-muted">Verificando...</span>');
                proyectos.push(id);
            }
        });

        // Visualizacion temporal para usuarios
        /* const celda = $('#boton-' + id);
        celda.html('<span class="text-muted">Verificando...</span>'); */
        // Llamada al backend si hay proyectos con directorio
        if (proyectos.length > 0) {
            $.ajax({
                url: "<?= constant('URL'); ?>proyectos/verificarDocumentos",
                method: "POST",
                data: { proyectos: proyectos },
                dataType: "json",
                success: function(respuesta) {
                    // Recorremos la respuesta (clave = ID, valor = true/false)
                    $.each(respuesta, function(id, tieneArchivos) {
                        const celda = $('#boton-' + id);
                        const nombre = celda.data('nombre');

                        if (tieneArchivos) {
                            // ✅ Ver documentos
                            celda.html(`
                                <button class="btn btn-info btn-sm"
                                        data-toggle="modal"
                                        data-target="#modalVerDocumentos"
                                        data-id="${id}">
                                    Ver documentos
                                </button>
                            `);
                        } else {
                            // 📎 Adjuntar documentos
                            celda.html(`
                                <button class="btn btn-success btn-sm"
                                        data-toggle="modal"
                                        data-target="#modalAdjuntar"
                                        data-id="${id}"
                                        data-nombre="${nombre}"
                                        data-tipo="documento">
                                    <i class="fa fa-upload"></i> Adjuntar
                                </button>
                            `);
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error AJAX:", error);
                }
            });
        }
    }

    // FUNCION COMPLEMENTARIA PARA VISUALIZACION DE ARCHIVOS EN EL FRONT
    function escapeHtml(text) {
        if (!text && text !== 0) return '';
        return String(text)
        .replace(/&/g, '&amp;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
    }

    $(document).ready(function() {
        console.log("INICIO");


        console.log("verificarBotones");
        verificarBotones();


        /* ADJUNTAR ARCHIVO DEL PROYECTO */
        // Cuando se abre el modal, limpiar los campos
        $('#modalAdjuntar').on('show.bs.modal', function (event) {
            console.log("MODAL ADJUNTAR");
            var button = $(event.relatedTarget);
            var idProyecto = button.data('id');
            var nombreProyecto = button.data('nombre');
            var modal = $(this);

            modal.find('#id_registro').val(idProyecto);
            modal.find('.modal-title').text('Adjuntar archivos – Proyecto: ' + nombreProyecto);

            // Reiniciar contenido
            $('#contenedor-archivos').html(`
                <div class="form-group archivo-item mb-3 border rounded p-2">
                    <label>Seleccionar archivo:</label>
                    <input type="file" name="archivo[]" class="form-control" required>

                    <label class="mt-2">Descripción:</label>
                    <textarea name="descripcion[]" class="form-control" rows="2" placeholder="Ingrese una descripción..."></textarea>
                </div>
            `);
        });

        /* ADJUNTAR ARCHIVO DEL PROYECTO PARA EL FORM HACIA EL BACK */
        $('#formAdjuntar').on('submit', function (e) {
            e.preventDefault();

            $('#modalAdjuntar .btn-success').prop('disabled', true).text('Subiendo...');
            let formData = new FormData(this);

            $.ajax({
                url: '<?= constant('URL'); ?>/proyectos/adjuntarArchivos',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#modalAdjuntar .btn-success').prop('disabled', false).text('Subir');

                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: 'Archivos subidos correctamente.',
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            $('#modalAdjuntar').modal('hide');
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'No se pudo subir el archivo.',
                            confirmButtonText: 'Entendido'
                        });
                    }
                },
                error: function () {
                    $('#modalAdjuntar .btn-success').prop('disabled', false).text('Subir');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'Ocurrió un problema al subir los archivos.',
                        confirmButtonText: 'Reintentar'
                    });
                }
            });
        });

        /* EDITAR PROYECTO CLICK*/
        $('#modalEditarProyecto').on('show.bs.modal', function (event) {
            console.log("🟢 Abriendo modal de edición");

            var button = $(event.relatedTarget); // Botón que abrió el modal
            var idProyecto = button.data('id');
            var nombreProyecto = button.data('nombre');
            var descripcion = button.data('descripcion');
            var responsable = button.data('responsable');
            var estado = button.data('estado');
            var fecha = button.data('fecha'); // ✅ Capturamos la fecha del botón

            // ✅ Si la fecha viene con hora, cortamos solo la parte AAAA-MM-DD
            if (fecha && fecha.includes(' ')) {
                fecha = fecha.split(' ')[0];
            }

            var modal = $(this);

            // Cargar datos en los campos
            modal.find('#editar_id').val(idProyecto);
            modal.find('#editar_nombre').val(nombreProyecto);
            modal.find('#editar_descripcion').val(descripcion);
            modal.find('#editar_responsable').val(responsable);
            modal.find('#editar_estado').val(estado);
            modal.find('#editar_fecha').val(fecha); // ✅ Cargamos fecha real

            // Título dinámico
            modal.find('.modal-title').text('Editar – ' + nombreProyecto);
        });

        /* EDITAR PROYECTO FORM AL BACK */
        $('#formEditarProyecto').on('submit', function (e) {
            e.preventDefault();

            const datos = {
                id: $('#editar_id').val(),
                nombre_proyecto: $('#editar_nombre').val(),
                descripcion: $('#editar_descripcion').val(),
                responsable: $('#editar_responsable').val(),
                estado: $('#editar_estado').val(),
                fecha_creacion: $('#editar_fecha').val()
            };

            console.log("🟡 Enviando actualización:", datos);

            $.ajax({
                url: "<?= constant('URL'); ?>proyectos/actualizarProyecto",
                type: "POST",
                data: datos,
                dataType: "json",
                success: function(response) {
                    console.log("Respuesta:", response);

                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Proyecto actualizado',
                            text: response.message
                        }).then(() => {
                            $('#modalEditarProyecto').modal('hide');
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'No se pudo actualizar el proyecto.'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error AJAX:", error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error de conexión con el servidor.'
                    });
                }
            });
        });

        /* CREAR PROYECTO */
        $('#formNuevoProyecto').on('submit', function(e) {
            console.log("CREAR PROYECTO");
            e.preventDefault();

            // Muestra en consola lo que se enviará
            const datos = {
                nombre_proyecto: $('input[name="nombre_proyecto"]').val(),
                descripcion: $('textarea[name="descripcion"]').val(),
                responsable: $('input[name="responsable"]').val(),
                estado: $('select[name="estado"]').val(),
                fecha_creacion: $('input[name="fecha_creacion"]').val()
            };

            console.log('Datos enviados:', datos);

            $.ajax({
                url: "<?= constant('URL'); ?>proyectos/crearProyecto",
                type: "POST",
                data: datos,
                success: function(response) {
                    console.log("Respuesta:", response);

                    if (response.status === 'success') {
                        console.log("Carpeta creada para el proyecto ID:", response.id);
                        Swal.fire({
                            icon: 'success',
                            title: 'Proyecto creado',
                            text: response.message
                        }).then(() => {
                            $('#modalAgregarProyecto').modal('hide');
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'No se pudo agregar el proyecto.'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error AJAX:", error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error de conexión con el servidor.'
                    });
                }
            });
        });

        // Cuando se abra el modal, solicitar archivos del proyecto
        $('#modalVerDocumentos').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget); // botón que disparó
            const idProyecto = button.data('id');
            const nombre = button.data('nombre');

            const modal = $(this);
            modal.find('#modalVerDocumentosLabel').text('Documentos del proyecto: ' + nombre);

            // Mostrar spinner y limpiar contenido
            $('#doc-contenido').html('');
            $('#doc-loading').show();

            // Petición AJAX al backend
            $.ajax({
                url: '<?= constant("URL"); ?>proyectos/listarDocumentos', // controladora a crear
                method: 'POST',
                dataType: 'json',
                data: { id_proyecto: idProyecto },
                success: function(response) {
                    $('#doc-loading').hide();

                    if (!response || !Array.isArray(response.archivos)) {
                        $('#doc-contenido').html('<div class="alert alert-warning">No se recibieron documentos.</div>');
                        return;
                    }

                    const archivos = response.archivos;

                    if (archivos.length === 0) {
                        $('#doc-contenido').html('<div class="alert alert-info">No hay archivos cargados para este proyecto.</div>');
                        return;
                    }

                    // Construir tabla HTML
                    let html = `
                    <div class="table-responsive">
                        <table id="tablaDocumentosProyecto" class="table table-sm table-striped table-bordered">
                        <thead>
                            <tr>
                            <th>Archivo</th>
                            <th>Descripción</th>
                            <th>Fecha subida</th>
                            <th style="width:140px">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                    `;

                    archivos.forEach(function(a) {
                        // a debe traer: id, nombre_archivo (o ruta), descripcion, fecha_registro, ruta_archivo
                        const nombre = a.nombre_archivo || a.ruta_archivo.split('/').pop();
                        const descripcion = a.descripcion || '';
                        const fecha = a.fecha_registro || '';
                        // Links: descargar y eliminar (eliminar sólo si quieres)
                        const descargarUrl = '<?= constant("URL"); ?>proyectos/descargarArchivo?id=' + encodeURIComponent(a.id);
                        const eliminarBtn = `<button class="btn btn-danger btn-sm btn-eliminar-archivo" style="margin: 1px;" data-id="${a.id}">Eliminar</button>`;

                        html += `
                        <tr>
                            <td>${escapeHtml(nombre)}</td>
                            <td>${escapeHtml(descripcion)}</td>
                            <td>${escapeHtml(fecha)}</td>
                            <td>
                            <a href="${descargarUrl}" class="btn btn-outline-primary btn-sm" style="margin: 1px;" target="_blank">Descargar</a>
                            ${eliminarBtn}
                            </td>
                        </tr>
                        `;
                    });

                    html += `</tbody></table></div>`;

                    $('#doc-contenido').html(html);

                    // Iniciar DataTable para la tabla de documentos (opcional)
                    if ($.fn.DataTable) {
                        $('#tablaDocumentosProyecto').DataTable({
                            pageLength: 5,
                            lengthMenu: [5,10,20],
                            language: { url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' },
                            ordering: true,
                            searching: true
                        });
                    }
                },
                error: function(xhr, status, err) {
                    $('#doc-loading').hide();
                    $('#doc-contenido').html('<div class="alert alert-danger">Error al cargar documentos.</div>');
                    console.error('Error listarDocumentos:', err, xhr.responseText);
                }
            });
        });

        // Manejo de eliminar archivo (delegado porque botones se generan dinámicamente)
        $(document).on('click', '.btn-eliminar-archivo', function () {
            const idArchivo = $(this).data('id');

            Swal.fire({
                title: '¿Eliminar archivo?',
                text: "Esta acción es irreversible.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= constant("URL"); ?>proyectos/eliminarArchivo',
                        type: 'POST',
                        dataType: 'json',
                        data: { id_archivo: idArchivo },
                        success: function(resp) {
                            if (resp.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Eliminado',
                                    text: resp.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });

                                // Cerrar el modal y limpiar contenido
                                $('#modalVerDocumentos').modal('hide');
                                $('#doc-contenido').empty();

                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: resp.message || 'No se pudo eliminar.'
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error de conexión.'
                            });
                        }
                    });
                }


            });
        });

        $('[data-target="#modalAgregarProyecto"]').on('click', function() {
            console.log("Limpiando filtros de modal crear proyecto");
            // Limpia todos los campos del formulario
            $('#formNuevoProyecto')[0].reset();

            // También puedes reiniciar selects manualmente si quieres volver a la opción vacía
            $('#formNuevoProyecto select').val('');

            // Opcional: establecer nuevamente la fecha actual
            const hoy = new Date().toISOString().split('T')[0];
            $('#formNuevoProyecto input[name="fecha_creacion"]').val(hoy);
        });


    });

    // Botón para agregar más archivos - MANTENER AFUERA DE READY
    $('#btnAgregarArchivo').on('click', function () {
        const nuevoCampo = `
            <div class="form-group archivo-item mb-3 border rounded p-2">
                <label>Seleccionar archivo:</label>
                <input type="file" name="archivo[]" class="form-control" required>

                <label class="mt-2">Descripción:</label>
                <textarea name="descripcion[]" class="form-control" rows="2" placeholder="Ingrese una descripción..." required></textarea>
            </div>
        `;
        $('#contenedor-archivos').append(nuevoCampo);
    });

    // Eliminar campo de archivo individual
    $(document).on('click', '.btnEliminarArchivo', function () {
        $(this).closest('.archivo-item').remove();
    });

    // Activar DataTables al cargar
    const tabla = $('#tablaProyectos').DataTable({
        pageLength: 5,
        lengthMenu: [5, 10, 20, 50],
        language: { url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json" },
        scrollCollapse: true,
        paging: true,
        searching: true,
        ordering: true,
        info: true
    });

    // Ejecutar verificarBotones() cada vez que cambie de página, se filtre o se reordene
    tabla.on('draw.dt', function () {
        console.log("🔁 DataTable redibujado, verificando botones...");
        verificarBotones();
    });

    document.addEventListener("DOMContentLoaded", function () {
        const btn = document.getElementById("btnDescargarPlantilla");

        if (btn) {
            btn.addEventListener("click", function (e) {
                e.preventDefault();
                window.location.href = "/eticket/views/proyectos/descargar_plantilla.php";
            });
        }
    });


</script>



<?php require 'views/footer_proyectos.php'; ?>
