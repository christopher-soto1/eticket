<?php 
session_start();
//error_reporting(E_ALL); 
//ini_set('ignore_repeated_errors', TRUE);
//ini_set('display_errors', TRUE);
class Correo extends Controller{
    function __construct(){
        parent::__construct();
        /* Validacion de login */
        $this->view->correo=[];
        if (!isset($_SESSION['usuario'])){
        echo "Acceso Negado";
        session_destroy();
        $location=constant('URL');
        header("Location: " . $location);
        exit;
        }
        else{
        }
    }
    function render(){
        $correo=$this->model->get(); 	
        $this->view->correo=$correo;
        $this->view->render('eticket/index');
    }

    function verPaginacion($param = null)
    {
        //FILTROS DEL FRONT
        $fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null;
        $fecha_fin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null;
        $usuario_asignado = isset($_POST['usuario_asignado']) ? $_POST['usuario_asignado'] : null;
        $estado = isset($_POST['estado']) ? $_POST['estado'] : null;
        $correo_origen = isset($_POST['correo_origen']) ? $_POST['correo_origen'] : null;
        $asunto = isset($_POST['asunto']) ? $_POST['asunto'] : null;
        $id_ticket = isset($_POST['id_ticket']) ? $_POST['id_ticket'] : null;
        $multirespuesta = isset($_POST['multirespuesta']) ? $_POST['multirespuesta'] : null;
        $dias_creacion = isset($_POST['dias_creacion']) ? $_POST['dias_creacion'] : null;


        $permiso = isset($_POST['permiso']) ? $_POST['permiso'] : null;
        $asignacion = isset($_POST['asignacion']) ? $_POST['asignacion'] : null;
    
        $permisos = $this->model->getmenu($_SESSION['usuario']);
        $this->view->usuariosperfil = $permisos;
    
        if (!empty($permisos)) {
            $_SESSION['permiso'] = $permisos[0]->permiso;
            $_SESSION['asignado'] = $permisos[0]->idusuario;
        }
        // Obtener usuarios habilitados
        require_once 'models/correo.php';
        $correoModel = new CorreoModel();
        $usuariosAsignables = $correoModel->getAsignacion();
        $this->view->usuariosAsignables = $usuariosAsignables;
    
        //PAGINADOR
        $id = $param[0];
        $autorizacionporpagina = 10;
    
        $totalRegistros = $this->model->getregistros(
            null,
            $_SESSION['permiso'],
            $_SESSION['asignado'],
            $fecha_inicio,
            $fecha_fin,
            $usuario_asignado,
            $estado,
            $correo_origen,
            $id_ticket,
            $dias_creacion,
            $multirespuesta,
            $asunto
        );
    
        $paginas = ceil($totalRegistros['total'] / $autorizacionporpagina); // Usamos 'total' del resultado
    
        $iniciar = ($id - 1) * $autorizacionporpagina;
        $correo = $this->model->getpag(
            $iniciar,
            $autorizacionporpagina,
            null,
            $_SESSION['permiso'],
            $_SESSION['asignado'],
            $fecha_inicio,
            $fecha_fin,
            $usuario_asignado,
            $estado,
            $correo_origen,
            $id_ticket,
            $dias_creacion,
            $multirespuesta,
            $asunto
        );
    
        $asignaciones = $this->model->getAsignacion();
        $this->view->asignaciones = $asignaciones;
    
        //MODAL ESTADISTICAS
        $estadisticas = $this->model->estadisticas();
        $this->view->estadisticas = $estadisticas;
        $estadisticasEnProgreso = $this->model->estadisticasEnProgreso();
        $this->view->estadisticasEnProgreso = $estadisticasEnProgreso;
        //MODAL ESTADISTICAS

        //MODAL HISTORIAL
        $historial = $this->model->historial();
        $this->view->historial = $historial;
        //MODAL HISTORIAL

        //MODAL CORREOS RESPUESTA
        $correoRespuesta = $this->model->obtenerCorreosRespuestas();
        $this->view->correoRespuesta = $correoRespuesta;
        //MODAL CORREOS RESPUESTA
        
        //echo ('Renderizando vista');
        if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            $this->view->permiso = $permiso; //le mandamos los permisos al renderizar la pagina con filtros
            $this->view->asignacion = $asignacion;
            $this->view->fecha_inicio = $fecha_inicio;
            $this->view->fecha_fin = $fecha_fin;
            $this->view->usuario_asignado = $usuario_asignado;
            $this->view->correo_origen = $correo_origen;
            $this->view->estado = $estado;
            $this->view->correo = $correo;
            $this->view->estadisticas = $estadisticas;
            $this->view->estadisticasEnProgreso = $estadisticasEnProgreso;
            $this->view->historial = $historial;
            $this->view->correoRespuesta = $correoRespuesta;
            $this->view->paginas = $paginas;
            $this->view->paginaactual = $id;
            $this->view->registros_por_pagina = $autorizacionporpagina;
            $this->view->total_registros = $totalRegistros['total'];
            $this->view->render('correo/index');

        } else {
            $this->view->permiso = $_SESSION['permiso']; //le mandamos los permisos al renderizar la pagina por defecto
            $this->view->asignacion = $_SESSION['asignado'];
            $this->view->fecha_inicio = $fecha_inicio;
            $this->view->fecha_fin = $fecha_fin;
            $this->view->usuario_asignado = $usuario_asignado;
            $this->view->correo_origen = $correo_origen;
            $this->view->estado = $estado;
            $this->view->correo = $correo;
            $this->view->estadisticas = $estadisticas;
            $this->view->estadisticasEnProgreso = $estadisticasEnProgreso;
            $this->view->historial = $historial;
            $this->view->correoRespuesta = $correoRespuesta;
            $this->view->paginas = $paginas;
            $this->view->paginaactual = $id;
            $this->view->registros_por_pagina = $autorizacionporpagina;
            $this->view->total_registros = $totalRegistros['total'];
            $this->view->render('correo/index');
        }
    }

    public function obtenerHistorial()
{
    if (isset($_POST['uid'])) {
        require_once 'models/correo.php';
        $correoModel = new CorreoModel();

        $uid = $_POST['uid'];
        $historial = $correoModel->obtenerHistorialPorUid($uid);

        // Devolver como JSON
        header('Content-Type: application/json');
        echo json_encode($historial);
    } else {
        echo json_encode([]);
    }
}
    public function filtrar()
    {
        // Recibir los datos JSON de la solicitud
        $data = json_decode(file_get_contents("php://input"), true);
    
        // Extraer los valores del JSON
        $fecha_inicio = $data['fecha_inicio'] ?? null;
        $fecha_fin = $data['fecha_fin'] ?? null;
        $usuario_asignado = $data['usuario_asignado'] ?? null;
        $estado = $data['estado'] ?? null;
    
        // Obtener los filtros de la sesión
        $permiso = $_SESSION['permiso'] ?? '';
        $idusuario = $_SESSION['asignado'] ?? '';
    
        // Llamar al modelo para obtener los correos filtrados
        $correoFiltrado = $this->model->getCorreosFiltrados($fecha_inicio, $fecha_fin, $usuario_asignado, $estado, $permiso, $idusuario);
    
        // Cargar solo las cards filtradas
        require 'views/correo/cards.php';
        require 'public/js/cards.php';
    }

    function verPaginacionsearch($param = null)
    {
        $id = $param[0];
        $txtbuscar = $_POST['txtbuscar'];
        $autorizacionporpagina = 6;
        $totalautorizaciones = $this->model->getregistros($txtbuscar);
        $paginas = $totalautorizaciones / $autorizacionporpagina;
        $iniciar = ($id - 1) * $autorizacionporpagina;
        $correo = $this->model->getpag($iniciar, $autorizacionporpagina, $txtbuscar);
        $this->view->correo = $correo;
        $this->view->mensaje = 'son' . $totalautorizaciones;
        $this->view->paginas = $paginas;
        $this->view->paginaactual = $id;
        /*Pasar sus Permisos*/
        $permisos = $this->model->getmenu($_SESSION['usuario']);
        $this->view->usuariosperfil = $permisos;
        /*fin*/
        $this->view->render('correo/index');
    }

    function verCorreo($param = null)
    {
        $id = $param[0];
        $correo = $this->model->getById($id);
        $this->view->correo = $correo;
        $this->view->mensaje = '';
        /*Pasar sus Permisos*/
        $permisos = $this->model->getmenu($_SESSION['usuario']);
        $this->view->usuariosperfil = $permisos;
        /*fin*/
        $this->view->render('correo/detalle');
    }
    
    function nuevoCorreo($param = null)
    {
        /*Pasar sus Permisos*/
        $permisos = $this->model->getmenu($_SESSION['usuario']);
        $this->view->usuariosperfil = $permisos;
        /*fin*/
        //$this->view->render('correo/nuevo');
    }
    function importarCorreo($param = null)
    {
        $this->view->render('correo/importar');
    }

    public function eliminar() {
        // Verificar si se envió el UID
        if (isset($_POST['uid'])) {
            $uid = $_POST['uid'];
            
            // Llamar al modelo para eliminar el ticket
            $correoModel = new CorreoModel();
            if ($correoModel->delete($uid)) {
                echo 'success'; // Retornar éxito
            } else {
                echo 'error'; // Retornar error
            }
        } else {
            echo 'error'; // Si no se pasó UID
        }
    }
     

    /* IMAP */
    public function obtenerCorreos() {
        $correoModel = new CorreoModel();
        $correosProcesados = $correoModel->obtenerYGuardarCorreos();
    
        // Devolver una respuesta JSON
        echo json_encode([
            'success' => true,
            'procesados' => $correosProcesados
        ]);
    }
    
    public function enviarCorreoPrueba() {
        $correo = $_POST['correo'] ?? '';
        $asunto = $_POST['asunto'] ?? 'Asunto por defecto';

        if ($this->model->enviarCorreoAsignacion($correo, $asunto)) {
            echo 'Correo enviado correctamente.';
        } else {
            echo 'Error al enviar el correo.';
        }
    }

    //CONTADOR DEL INDEX
    public function Asignacion() {
        $asignacionesTotales = $this->model->getAsignacion();
    
        // Pasar el array a la vista
        $this->view->asignaciones = $asignacionesTotales;
        $this->view->mensaje = "Usuarios asignados: " . count($asignacionesTotales);
        //$this->view->render('correo/index');
    }
    
    //ASIGNACION DE TICKET
    public function asignar()
    {
        //ini_set('display_errors', 1);
        //ini_set('display_startup_errors', 1);
        //error_reporting(E_ALL);
        
        $data = json_decode(file_get_contents("php://input"), true);
        $response = ['success' => false, 'message' => 'Error desconocido'];
    
        if (!isset($data['fecha_envio']) || !isset($data['asunto'])) {
            $response['message'] = 'Datos incompletos: fecha_envio y/o asunto';
            echo json_encode($response);
            return;
        }
    
        if (!isset($data['uid']) || !isset($data['idusuario'])) {
            $response['message'] = 'Datos incompletos: uid y/o idusuario';
            echo json_encode($response);
            return;
        }
    
        $uid = $data['uid'];
        $idusuario = $data['idusuario'];
        $fecha_envio = $data['fecha_envio'];
        $asunto = $data['asunto'];
        $usuario = $data['usuario'];
    
        if ($this->model->asignarUsuario($uid, $idusuario, $usuario)) {
            $correo_enviado = $this->model->enviarCorreoAsignacion($uid, $idusuario, $asunto, $fecha_envio);
            $response = [
                'success' => true,
                'message' => 'Usuario asignado correctamente',
                'correo_enviado' => $correo_enviado
            ];
        } else {
            $response['message'] = 'Error al asignar usuario';
        }
    
        echo json_encode($response);
    }
    
    
    //ESTADO (MENSAJE PARA CAMBIOS DE ESTADO DEL TICKET - ADMIN)
    public function cambiarEstado() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $uid = $_POST['uid'] ?? null;
            $estado = $_POST['estado'] ?? null;
            $comentario = isset($_POST['comentario']) ? trim($_POST['comentario']) : null;
            $comentarioDesarrollador = isset($_POST['comentarioDesarrollador']) ? trim($_POST['comentarioDesarrollador']) : null;
            $idusuario = $_POST['idusuario'] ?? null;
            $asunto = $_POST['asunto'] ?? null;
            $fecha_envio = $_POST['fecha_envio'] ?? null;
            $correo_origen = $_POST['correo_origen'] ?? null;

            $estado_actual = $_POST['estado_actual'] ?? null;
            #$estado_actualPalabra = $_POST['estado_actualPalabra'] ?? null;
            $estado_actualPalabra = isset($_POST['estado_actualPalabra']) ? trim($_POST['estado_actualPalabra']) : null;
            $nuevoEstado = $_POST['nuevoEstado'] ?? null;
            #$nuevoEstadoPalabra = $_POST['nuevoEstadoPalabra'] ?? null;
            $nuevoEstadoPalabra = isset($_POST['nuevoEstadoPalabra']) ? trim($_POST['nuevoEstadoPalabra']) : null;
            
    
            if ($uid && $estado) {
                $actualizado = $this->model->actualizarEstado($uid, $estado, $comentario, $comentarioDesarrollador, $idusuario, $estado_actual, $estado_actualPalabra, $nuevoEstado, $nuevoEstadoPalabra);
                
                if ($actualizado && $estado == 3) {
                    $correoEnviado = $this->model->enviarCorreoFinalizado($uid, $idusuario, $asunto, $fecha_envio, $correo_origen, $comentario, $comentarioDesarrollador);
    
                    if ($correoEnviado) { echo "Estado actualizado a 'Finalizado' y correo enviado correctamente.";} 
                    else { echo "Estado actualizado a 'Finalizado', pero error al enviar el correo.";}
                }
                elseif ($actualizado && $estado == 1) { echo "Estado actualizado a 'No asignado'.";}
                //else{ echo "Error : no se pudo actualizar a 'Sin asignar' actualizado: ".$actualizado ."/ estado: ".$estado;}

                elseif ($actualizado && $estado == 2) { echo "Estado actualizado a 'Asignado'.";} 
                //else{ echo "Error : no se pudo actualizar a 'Asignado' actualizado: ".$actualizado ."/ estado: ".$estado;}

                elseif ($actualizado && $estado == 4) { echo "Estado actualizado a 'En progreso'.";} 
                //else{ echo "Error : no se pudo actualizar a 'En progreso' actualizado: ".$actualizado ."/ estado: ".$estado;}

                elseif ($actualizado && $estado == 5) { echo "Estado actualizado a 'Eliminado'.";} 
                //else{ echo "Error : no se pudo actualizar a 'Finalizado': ".$actualizado ."/ estado: ".$estado;}

                elseif ($actualizado && $estado == 6) { echo "Estado actualizado a 'Realizado'.";} 
                //else{ echo "Error : no se pudo actualizar a 'Realizado': ".$actualizado ."/ estado: ".$estado;}

            } 
            else {
                echo "Faltan parámetros.";
            }
        }
    }
    
    public function marcarSpam() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $uid = $_POST['uid'] ?? null;
            $idusuario = $_POST['idusuario'] ?? null;
            $correo_origen = $_POST['correo_origen'] ?? null;
            
            if ($correo_origen && $idusuario && $uid) {
                $spam = $this->model->marcarSpam($uid, $idusuario, $correo_origen);
                if ($spam) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Se ha marcado como spam este correo, bandeja actualizada.'
                    ]);
                    return;
                } 
                else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'No se pudo marcar como spam en la base de datos.'
                    ]);
                    return;
                }
            } 
            else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Faltan parámetros para marcar como spam.'
                ]);
                return;
            }

        }
    }

    public function historial() {
        $historial = $this->model->historial();
        $this->view->historial = $historial;
    }
    
    public function envioAutomatico()
    {
        if (isset($_POST['ejecutar_envio_estatico'])) {
            try {
                $resultado = $this->model->enviarRespuestaEstatica();

                // Si todo fue bien, enviar mensaje textual
                if ($resultado === true || $resultado === 'OK') {
                    echo 'Correo enviado correctamente';
                } else {
                    // Si el modelo devuelve texto con algún error, lo devolvemos tal cual
                    echo $resultado;
                }
            } catch (Exception $e) {
                echo 'Error inesperado: ' . $e->getMessage();
            }
            return;
        }

        echo 'Solicitud no válida.';
    }

    public function enviarRespuestaUsuario() {
        // Asegúrate de usar filter_input o sanitizar si lo deseas
        header('Content-Type: application/json');

        $uid = $_POST['uid'] ?? null;
        $correo_origen = $_POST['correo_origen'] ?? '';
        $correo_destino = $_POST['correo_destino'] ?? '';
        $cc = $_POST['cc'] ?? '';
        $asunto = $_POST['asunto'] ?? '';
        $fecha_envio = $_POST['fecha_envio'] ?? '';
        $references = $_POST['references'] ?? '';
        $texto_respuesta = $_POST['texto_respuesta'] ?? '';
        $message_id = $_POST['message_id'] ?? '';
        $in_reply_to = $_POST['in_reply_to'] ?? '';
        $idusuario = $_POST['id_usuario'] ?? '';
        $fusion = $_POST['fusion'] ?? '';

        $resultado = $this->model->enviarRespuestaUsuario($uid, $correo_origen, $correo_destino, $cc, $asunto, $fecha_envio, $references, $texto_respuesta, $message_id, $in_reply_to, $idusuario, $fusion);
        if($resultado){
            echo true;
        }
        else{
            echo false;
        }
        //$exito = (is_string($resultado) && str_contains($resultado, 'Correo enviado correctamente'));

        
        //echo json_encode($exito);
    }



}
