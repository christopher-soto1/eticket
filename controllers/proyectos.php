<?php
class Proyectos extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function render()
    {
        // Carga la vista views/proyectos/index.php
        $this->view->render('proyectos/index');
    }

    # Obtiene todos los datos de la tabla para mostrarlos en el front
    public function verTabla()
    {
        // FILTROS DEL FRONT
        $id = $_POST['id'] ?? null;
        $nombre_proyecto = $_POST['nombre_proyecto'] ?? null;
        $fecha_creacion = $_POST['fecha_creacion'] ?? null;
        $responsable = $_POST['responsable'] ?? null;
        $estado = $_POST['estado'] ?? null;

        $permisos = $this->model->getmenu($_SESSION['usuario']);
        $this->view->permiso = $permisos;

        // Obtener registros desde el modelo
        $totalRegistros = $this->model->getregistros($id, $nombre_proyecto, $fecha_creacion, $responsable, $estado);

        // Enviar datos a la vista
        $this->view->totalRegistros = $totalRegistros;
        $this->view->render('proyectos/index');
    }

    # Crea proyecto en la base de datos y crea carpeta en el servidor
    public function crearProyecto()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        header('Content-Type: application/json');

        $nombre_proyecto = $_POST['nombre_proyecto'] ?? null;
        $descripcion = $_POST['descripcion'] ?? '';
        $responsable = $_POST['responsable'] ?? '';
        $estado = $_POST['estado'] ?? 3;
        $fecha_creacion = $_POST['fecha_creacion'] ?? date('Y-m-d');

        if (empty($nombre_proyecto) || empty($descripcion) || empty($responsable)) {
            echo json_encode(['status' => 'error', 'message' => 'Faltan campos obligatorios']);
            return;
        }

        try {
            // ✅ Insertar en la base de datos y obtener el ID generado
            $idProyecto = $this->model->insertarProyecto($nombre_proyecto, $descripcion, $responsable, $estado, $fecha_creacion);

            if ($idProyecto) {
                // ✅ Ruta base donde se guardarán las carpetas
                $rutaBase = "C:/wamp/www/eticket/public/proyectos/"; #local
                $rutaProyecto = $rutaBase . $idProyecto;
                $rutaBaseBD = "/eticket/public/proyectos/". $idProyecto;

                // ✅ Crear carpeta si no existe
                if (!file_exists($rutaProyecto)) {
                    mkdir($rutaProyecto, 0777, true);
                    $directorio = $this->model->actualizarRutaProyecto($idProyecto, $rutaBaseBD);

                    if ($directorio == true){
                        echo json_encode(['status' => 'success', 'id' => $idProyecto, 'message' => 'Proyecto y directorio creados en el servidor']);
                    }
                    else{
                        echo json_encode(['status' => 'success', 'id' => $idProyecto, 'message' => 'Proyecto creado sin directorio en el servidor']);
                    }

                }
                else{
                    echo json_encode(['status' => 'success', 'id' => $idProyecto, 'message' => 'Proyecto creado correctamente, directorio ya existente']);
                }

                #echo json_encode(['status' => 'success', 'id' => $idProyecto, 'message' => 'Proyecto creado correctamente']);
            } 
            
            else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo crear el proyecto']);
            }

        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error interno: ' . $e->getMessage()]);
        }
    }


    // Valida que hayan documentos dentro de la carpeta indicada
    public function verificarDocumentos()
    {
        // Ruta base en tu servidor local
        $rutaBase = "C:/wamp/www/eticket/public/proyectos/";

        // Recibe el arreglo desde jQuery
        $proyectos = $_POST['proyectos'] ?? [];

        $resultado = [];

        foreach ($proyectos as $idProyecto) {
            $rutaProyecto = $rutaBase . $idProyecto;
            $tieneArchivos = false;

            if (is_dir($rutaProyecto)) {
                $archivos = scandir($rutaProyecto);
                // Elimina . y ..
                $archivos = array_diff($archivos, ['.', '..']);
                if (count($archivos) > 0) {
                    $tieneArchivos = true;
                }
            }

            $resultado[$idProyecto] = $tieneArchivos;
        }

        header('Content-Type: application/json');
        echo json_encode($resultado);
    }


    // Actualiza datos del proyecto desde el front
    public function actualizarProyecto()
    {
        $id = $_POST['id'];
        $nombre = $_POST['nombre_proyecto'];
        $descripcion = $_POST['descripcion'];
        $responsable = $_POST['responsable'];
        $estado = $_POST['estado'];
        $fecha = $_POST['fecha_creacion'];

        $resultado = $this->model->actualizarProyecto($id, $nombre, $descripcion, $responsable, $estado, $fecha);

        if ($resultado) {
            echo json_encode(['status' => 'success', 'message' => 'Proyecto actualizado correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar el proyecto.']);
        }
    }


    // Agregar archivos relacionados al proyecto
    public function adjuntarArchivos()
    {
        header('Content-Type: application/json');

        $idProyecto = $_POST['id_registro'] ?? null;
        $descripciones = $_POST['descripcion'] ?? [];
        
        if (!$idProyecto || empty($_FILES['archivo']['name'][0])) {
            echo json_encode(['status' => 'error', 'message' => 'No se recibieron archivos o ID de proyecto.']);
            return;
        }

        $rutaBase = "C:/wamp/www/eticket/public/archivos_proyectos/";
        $rutaProyecto = $rutaBase . $idProyecto;

        if (!file_exists($rutaProyecto)) {
            mkdir($rutaProyecto, 0777, true);
        }

        $archivosSubidos = 0;
        $totalArchivos = count($_FILES['archivo']['name']);

        for ($i = 0; $i < $totalArchivos; $i++) {
            $nombreArchivo = $_FILES['archivo']['name'][$i];
            $tmpArchivo = $_FILES['archivo']['tmp_name'][$i];
            $descripcion = trim($descripciones[$i] ?? '');

            if ($descripcion === '') {
                echo json_encode(['status' => 'error', 'message' => "La descripción del archivo {$nombreArchivo} es obligatoria."]);
                return;
            }

            // Validar duplicados
            $rutaDestino = $rutaProyecto . "/" . basename($nombreArchivo);
            $rutaInfo = pathinfo($rutaDestino);
            $contador = 2;

            while (file_exists($rutaDestino)) {
                $nuevoNombre = $rutaInfo['filename'] . " ($contador)." . $rutaInfo['extension'];
                $rutaDestino = $rutaInfo['dirname'] . "/" . $nuevoNombre;
                $contador++;
            }

            $nombreFinal = basename($rutaDestino);
            $rutaEnBD = "/eticket/public/archivos_proyectos/" . $idProyecto . "/" . $nombreFinal;

            if (move_uploaded_file($tmpArchivo, $rutaDestino)) {
                $this->model->insertarRutaArchivoProyecto($idProyecto, $descripcion, $rutaEnBD);
                $archivosSubidos++;
            }
        }


        if ($archivosSubidos > 0) {
            echo json_encode(['status' => 'success', 'message' => "Se subieron $archivosSubidos archivo(s) correctamente."]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudieron subir los archivos.']);
        }
    }

    # COMPLEMENTO PARA VISUALIZACION DE ARCHIVOS EN EL FRONT
    public function listarDocumentos()
    {
        header('Content-Type: application/json');

        $idProyecto = $_POST['id_proyecto'] ?? null;
        if (!$idProyecto) {
            echo json_encode(['archivos' => []]); return;
        }

        $archivos = $this->model->obtenerArchivosPorProyecto($idProyecto);

        // Opcional: transformar rutas para que el frontend tenga el nombre del archivo
        $resultado = [];
        foreach ($archivos as $a) {
            $resultado[] = [
                'id' => $a['id'],
                'descripcion' => $a['descripcion'],
                'ruta_archivo' => $a['ruta_archivo'],
                'fecha_registro' => $a['fecha_registro'],
                'nombre_archivo' => basename($a['ruta_archivo'])
            ];
        }

        echo json_encode(['archivos' => $resultado]);
    }

    # COMPLEMENTO PARA VISUALIZACION DE ARCHIVOS EN EL FRONT
    public function descargarArchivo()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo "Archivo no especificado."; exit;
        }

        $fila = $this->model->obtenerArchivoPorId($id);
        if (!$fila) {
            http_response_code(404);
            echo "Archivo no encontrado."; exit;
        }

        // Convierte ruta BD a ruta física en servidor (ajusta según cómo guardaste rutas)
        $rutaRel = $fila['ruta_archivo']; // ej. "/eticket/public/archivos_proyectos/1/archivo.pdf"
        $rutaFisica = $_SERVER['DOCUMENT_ROOT'] . $rutaRel; // ejemplo: C:/wamp/www/eticket/public/...
        if (!file_exists($rutaFisica)) {
            http_response_code(404);
            echo "Archivo no existe en el servidor.";
            exit;
        }

        // Forzar descarga
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($rutaFisica) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($rutaFisica));
        readfile($rutaFisica);
        exit;
    }

    public function eliminarArchivo()
    {
        header('Content-Type: application/json');

        $idArchivo = $_POST['id_archivo'] ?? null;

        if (!$idArchivo) {
            echo json_encode(['status' => 'error', 'message' => 'ID de archivo no recibido.']);
            return;
        }

        // Buscar el archivo en la base de datos
        $archivo = $this->model->obtenerArchivoPorId($idArchivo);

        if (!$archivo) {
            echo json_encode(['status' => 'error', 'message' => 'Archivo no encontrado en la base de datos.']);
            return;
        }

        // Ruta física en el servidor
        $rutaFisica = "C:/wamp/www" . $archivo['ruta_archivo']; // Asumiendo que la ruta en BD empieza con /eticket/public/...

        // Intentar eliminar el archivo
        if (file_exists($rutaFisica)) {
            unlink($rutaFisica);
        }

        // Eliminar el registro de la base de datos
        $resultado = $this->model->eliminarArchivoPorId($idArchivo);

        if ($resultado) {
            echo json_encode(['status' => 'success', 'message' => 'Archivo eliminado correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar el registro de la base de datos.']);
        }
    }









}
