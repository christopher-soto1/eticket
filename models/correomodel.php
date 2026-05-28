<?php
include_once 'models/correo.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class CorreoModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getmenu($idu)
    {
        $items = [];
        include_once 'models/usuariosperfil.php';
        try {
            $query = $this->db->connect()->query("SELECT * FROM usuariosperfil WHERE idusuario='" . $idu . "' AND habilitado='S'");
            while ($row = $query->fetch()) {
                $item = new Usuariosperfil();
                $item->id = $row['id'];
                $item->idusuario = $row['idusuario'];
                $item->usuario_rebsol = $row['usuario_rebsol'];
                $item->menu = $row['menu'];
                $item->habilitado = $row['habilitado'];
                $item->principal = $row['principal'];
                $item->permiso = $row['permiso'];
                $item->area = $row['area'];
                array_push($items, $item);
            }
            return $items;
        } catch (PDOException $e) {
            return [];
        }
    }

    public function get()
    {
        $items = [];
        try {
            //var_dump("estado de llegada");
            $query = $this->db->connect()->query("SELECT * FROM correo where estado!=0 and deleted_at is null");
            while ($row = $query->fetch()) {
                $item = new CorreoM();
                $item->id = $row['id'];
                $item->id_correo_original = $row['id_correo_original'];
                $item->message_id = $row['message_id'];
                $item->in_reply_to = $row['in_reply_to'];
                $item->uid = $row['uid'];
                $item->multirespuesta = $row['multirespuesta'];
                $item->correo_origen = $row['correo_origen'];
                $item->correo_destino = $row['correo_destino'];
                $item->asunto = $row['asunto'];
                $item->fecha_envio = $row['fecha_envio'];
                $item->cuerpo = $row['cuerpo'];
                $item->asignado = $row['asignado'];
                $item->estado = $row['estado'];
                $item->created_at = $row['created_at'];
                $item->updated_at = $row['updated_at'];
                $item->deleted_at = $row['deleted_at'];

                array_push($items, $item);
            }
            return $items;
        } catch (PDOException $e) {
            return [];
        }
    }

    /* ------ OBTIENE REGISTROS PARA EL PAGINADOR ------ */
    public function getregistros(
        $permiso,
        $idusuario,
        $fecha_inicio = null,
        $fecha_fin = null,
        $usuario_asignado = null,
        $estado = null,
        $correo_origen = null,
        $id_ticket = null,
        $dias_creacion = null,
        $multirespuesta,
        $asunto = null
    ) {
        try {
            $inicio = "$fecha_inicio 00:00:00";
            $fin = "$fecha_fin 23:59:59";

            $debug = " / INICIO - ";


            if ($permiso != 'admin') {
                //  NO ADMIN
                #echo "<pre>";
                #var_dump([
                #    'usuario_asignado' => empty($usuario_asignado),
                #    'estado' => empty($estado),
                #    'fecha_inicio' => empty($fecha_inicio),
                #    'fecha_fin' => empty($fecha_fin),
                #    'correo_origen' => empty($correo_origen),
                #    'dias_creacion' => empty($dias_creacion),
                #    'id_ticket' => empty($id_ticket),
                #    'multirespuesta' => empty($multirespuesta),
                #    'asunto' => empty($asunto)
                #]);
                #echo "</pre>";
                #no modificar la linea de 'SELECT * FROM correo' con salto de linea, se caera la query
                $sql = "SELECT * FROM correo where 1"; 
                if (empty($estado) && empty($fecha_inicio) && empty($fecha_fin) && empty($correo_origen) && empty($dias_creacion) && empty($id_ticket) && empty($multirespuesta) && empty($asunto)) {
                    $sql .= " AND (correo_origen = '$idusuario' OR correo_origen IN (SELECT usuario_asociado
                                                                                    FROM usuariosperfil_asignaciones
                                                                                    WHERE usuario_principal = '$idusuario')) AND estado = 1 and multirespuesta != 1";
                    $debug .= ".1";
                } else {
                    if (!empty($multirespuesta)) {
                        if ($multirespuesta == 2) {
                            $sql .= " AND multirespuesta = 0";
                            $debug .= ".3";
                        } else {
                            $sql .= " AND multirespuesta = $multirespuesta";
                            $debug .= ".4";
                        }
                    }
                    if (!empty($fecha_inicio)) {
                        $sql .= " AND fecha_envio >= '$inicio'";
                        $debug .= ".5";
                    }

                    if (!empty($fecha_fin)) {
                        $sql .= " AND fecha_envio <= '$fin'";
                        $debug .= ".6";
                    }
                    if (!empty($estado)) {
                        if ($estado == 5) {
                            $sql .= " AND estado = $estado AND deleted_at is not null";
                            $debug .= ".7";
                        } else {
                            $sql .= " AND estado = $estado";
                            $debug .= ".8";
                        }
                    }
                    if (!empty($correo_origen)) {
                        $sql .= " AND correo_origen like '%$correo_origen%'";
                        $debug.=".9";
                    }
                    else {
                        $sql .= " AND (correo_origen = '$idusuario' OR correo_origen IN (SELECT usuario_asociado
                                                                                    FROM usuariosperfil_asignaciones
                                                                                    WHERE usuario_principal = '$idusuario'))";
                        $debug.=".99";
                    }
                    if (!empty($asunto)) {
                        $sql .= " AND asunto like '%$asunto%'";
                        $debug .= ".10";
                    }
                    if (!empty($id_ticket)) {
                        $sql .= " AND uid like '%$id_ticket%'";
                        $debug .= ".11";
                    }
                    if (!empty($dias_creacion)) {
                        if ($dias_creacion == "hoy") {
                            $sql .= " AND IFNULL(TIMESTAMPDIFF(DAY, fecha_envio, NOW()), 0) = 0 ";
                            $debug .= ".12";
                        }
                        if ($dias_creacion == "1") {
                            $sql .= " AND IFNULL(TIMESTAMPDIFF(DAY, fecha_envio, NOW()), 0) = 1 ";
                            $debug .= ".13";
                        }
                        if ($dias_creacion == "2") {
                            $sql .= " AND IFNULL(TIMESTAMPDIFF(DAY, fecha_envio, NOW()), 0) = 2 ";
                            $debug .= ".14";
                        }
                        if ($dias_creacion == "3") {
                            $sql .= " AND IFNULL(TIMESTAMPDIFF(DAY, fecha_envio, NOW()), 0) = 3 ";
                            $debug .= ".15";
                        }
                        if ($dias_creacion == "4") {
                            $sql .= " AND IFNULL(TIMESTAMPDIFF(DAY, fecha_envio, NOW()), 0) = 4 ";
                            $debug .= ".16";
                        }
                        if ($dias_creacion == "5") {
                            $sql .= " AND IFNULL(TIMESTAMPDIFF(DAY, fecha_envio, NOW()), 0) = 5 ";
                            $debug .= ".17";
                        }
                        if ($dias_creacion == "mas_de_5") {
                            $sql .= " AND IFNULL(TIMESTAMPDIFF(DAY, fecha_envio, NOW()), 0) > 5";
                            $debug .= ".18";
                        }
                    }
                }

            } else {
                //  ADMIN
                $sql = "SELECT * FROM correo where 1";
                if (empty($usuario_asignado) && empty($estado) && empty($fecha_inicio) && empty($fecha_fin) && empty($correo_origen) && empty($dias_creacion) && empty($id_ticket) && empty($multirespuesta) && empty($asunto)) {
                    $sql .= " AND estado = 1 and multirespuesta != 1";
                } else {
                    if (!empty($usuario_asignado)) {
                        $sql .= " AND asignado = '$usuario_asignado'";
                        $debug .= ".6";
                    }
                    if (!empty($multirespuesta)) {
                        if ($multirespuesta == 2) {
                            $sql .= " AND multirespuesta = 0";
                            $debug .= ".multi0r";
                        } else {
                            $sql .= " AND multirespuesta = $multirespuesta";
                            $debug .= ".multi1r";
                        }
                    }
                    if (!empty($fecha_inicio)) {
                        $sql .= " AND fecha_envio >= '$inicio'";
                        $debug .= ".8";
                    }

                    if (!empty($fecha_fin)) {
                        $sql .= " AND fecha_envio <= '$fin'";
                        $debug .= ".9";
                    }
                    if (!empty($estado)) {
                        $debug .= ".estado";
                        if ($estado == 5) {
                            $sql .= " AND estado = $estado AND deleted_at is not null";
                            $debug .= ".estado5";
                        } else {
                            $sql .= " AND estado = $estado";
                            $debug .= ".estadoNormal";
                        }
                    }
                    if (!empty($correo_origen)) {
                        $sql .= " AND correo_origen like '%$correo_origen%'";
                        $debug .= ".14";
                    }
                    if (!empty($asunto)) {
                        $sql .= " AND asunto like '%$asunto%'";
                        $debug .= ".4asunto99";
                    }
                    if (!empty($id_ticket)) {
                        $sql .= " AND uid like '%$id_ticket%'";
                        $debug .= ".99aadminidticket";
                    }
                    if (!empty($dias_creacion)) {
                        if ($dias_creacion == "hoy") {
                            $sql .= " AND IFNULL(TIMESTAMPDIFF(DAY, fecha_envio, NOW()), 0) = 0 ";
                            $debug .= ".15";
                        }
                        if ($dias_creacion == "1") {
                            $sql .= " AND IFNULL(TIMESTAMPDIFF(DAY, fecha_envio, NOW()), 0) = 1 ";
                            $debug .= ".16";
                        }
                        if ($dias_creacion == "2") {
                            $sql .= " AND IFNULL(TIMESTAMPDIFF(DAY, fecha_envio, NOW()), 0) = 2 ";
                            $debug .= ".17";
                        }
                        if ($dias_creacion == "3") {
                            $sql .= " AND IFNULL(TIMESTAMPDIFF(DAY, fecha_envio, NOW()), 0) = 3 ";
                            $debug .= ".18";
                        }
                        if ($dias_creacion == "4") {
                            $sql .= " AND IFNULL(TIMESTAMPDIFF(DAY, fecha_envio, NOW()), 0) = 4 ";
                            $debug .= ".19";
                        }
                        if ($dias_creacion == "5") {
                            $sql .= " AND IFNULL(TIMESTAMPDIFF(DAY, fecha_envio, NOW()), 0) = 5 ";
                            $debug .= ".20";
                        }
                        if ($dias_creacion == "mas_de_5") {
                            $sql .= " AND IFNULL(TIMESTAMPDIFF(DAY, fecha_envio, NOW()), 0) > 5";
                            $debug .= ".21";
                        }
                    }
                }
            }

            // Paginación y orden
            if ($estado != 5) {
                $sql .= " AND deleted_at is null";
            }
            $sql .= " ORDER BY fecha_envio DESC";

            //Para debuguear
            #echo "<p> getRegistros / PAGINADOR </p>";
            #echo "<pre>";
            #echo $sql; 
            #echo "</pre>";
            #echo "<pre>";
            #echo $debug;
            #echo "</pre>";

            // Primero obtenemos el conteo de registros con los mismos filtros
            $countSql = $sql;  // Guardamos la misma consulta que usamos para obtener los registros
            $countSql = preg_replace('/SELECT \* FROM/', 'SELECT count(*) as son FROM', $countSql);  // Reemplazamos * por count(*) para obtener el conteo
            $queryCount = $this->db->connect()->query($countSql);
            $totalRegistros = $queryCount->fetch(PDO::FETCH_ASSOC)['son'];  // Obtén el total de registros

            // Ahora obtenemos los registros
            $queryData = $this->db->connect()->query($sql);
            $resultados = $queryData->fetchAll(PDO::FETCH_ASSOC); // Obtén los registros de la consulta

            return ['total' => $totalRegistros, 'registros' => $resultados]; // Devuelve tanto el total de registros como los datos

        } catch (PDOException $e) {
            return [];
        }
    }
    /* ------ OBTIENE REGISTROS PARA EL PAGINADOR ------ */


    /* ------ OBTIENE REGISTROS PARA LAS CARDS ------ */
    public function getpag(
        $iniciar,
        $autoporpag,
        $permiso,
        $idusuario,
        $fecha_inicio = null,
        $fecha_fin = null,
        $usuario_asignado = null,
        $estado = null,
        $correo_origen = null,
        $id_ticket = null,
        $dias_creacion = null,
        $multirespuesta,
        $asunto = null
    ) {
        $items = [];
        try {


            $inicio = "$fecha_inicio 00:00:00";
            $fin = "$fecha_fin 23:59:59";
            $debug = " /inicio - ";

            if ($permiso != 'admin') {
                //  NO ADMIN

                #echo "<pre>";
                #var_dump([
                #    'usuario_asignado' => empty($usuario_asignado),
                #    'estado' => empty($estado),
                #    'fecha_inicio' => empty($fecha_inicio),
                #    'fecha_fin' => empty($fecha_fin),
                #    'correo_origen' => empty($correo_origen),
                #    'dias_creacion' => empty($dias_creacion),
                #    'id_ticket' => empty($id_ticket),
                #    'multirespuesta' => empty($multirespuesta),
                #    'asunto' => empty($asunto)
                #]);
                #echo "</pre>";

                $sql = "SELECT *,
                            IFNULL(TIMESTAMPDIFF(DAY, fecha_envio, NOW()), 0) AS dias_desde_creacion,
                            IFNULL(TIMESTAMPDIFF(hour, fecha_envio, NOW()), 0) AS horas_desde_creacion,
                            IFNULL(TIMESTAMPDIFF(minute , fecha_envio, NOW()), 0) AS minutos_desde_creacion,
                            IFNULL(TIMESTAMPDIFF(DAY, updated_at, NOW()), 0) AS dias_desde_actualizacion,
                            IFNULL(TIMESTAMPDIFF(hour , updated_at, NOW()), 0) AS horas_desde_actualizacion,
                            IFNULL(TIMESTAMPDIFF(minute , updated_at, NOW()), 0) AS minutos_desde_actualizacion
                            FROM correo 
                            WHERE 1";
                #                if (empty($usuario_asignado) && empty($estado) && empty($fecha_inicio) && empty($fecha_fin) && empty($correo_origen) && empty($dias_creacion) && empty($id_ticket) && empty($multirespuesta) && empty($asunto)) {

                if (empty($correo_origen) && empty($estado) && empty($fecha_inicio) && empty($fecha_fin) && empty($correo_origen) && empty($dias_creacion) && empty($id_ticket) && empty($multirespuesta) && empty($asunto)) {
                    $sql .= " AND (correo_origen = '$idusuario' OR correo_origen IN (SELECT usuario_asociado
                                                                                    FROM usuariosperfil_asignaciones
                                                                                    WHERE usuario_principal = '$idusuario')) AND estado in (1) and multirespuesta != 1";
                    $debug .= ".1";
                } else {

                    if (!empty($fecha_inicio)) {
                        $sql .= " AND fecha_envio >= '$inicio'";
                        $debug .= ".3";
                    }

                    if (!empty($multirespuesta)) {
                        if ($multirespuesta == 2) {
                            $sql .= " AND multirespuesta = 0";
                            $debug .= ".4";
                        } else {
                            $sql .= " AND multirespuesta = $multirespuesta";
                            $debug .= ".5";
                        }
                    }

                    if (!empty($fecha_fin)) {
                        $sql .= " AND fecha_envio <= '$fin'";
                        $debug .= ".6";
                    }

                    if (!empty($estado)) {
                        if ($estado == 5) {
                            $sql .= " AND estado = $estado AND deleted_at is not null";
                        } else {
                            $sql .= " AND estado = $estado";
                        }

                        $debug .= ".7";
                    }
                    if (!empty($id_ticket)) {
                        $sql .= " AND uid like '%$id_ticket%'";
                        $debug .= ".8";
                    }

                    /* CORREO DE ORIGEN / JEFES DE GRUPO */
                    if (!empty($correo_origen)) {
                        $sql .= " AND correo_origen like '%$correo_origen%'";
                        $debug .= ".9";
                    }
                    else{
                        $sql .= " AND (correo_origen = '$idusuario' OR correo_origen IN (SELECT usuario_asociado
                                                                                    FROM usuariosperfil_asignaciones
                                                                                    WHERE usuario_principal = '$idusuario'))";
                    }
                    /* CORREO DE ORIGEN / JEFES DE GRUPO */

                    if (!empty($asunto)) {
                        $sql .= " AND asunto like '%$asunto%'";
                        $debug .= ".10";
                    }
                    if (!empty($dias_creacion)) {
                        if ($dias_creacion == "hoy") {
                            $sql .= " AND IFNULL(TIMESTAMPDIFF(DAY, fecha_envio, NOW()), 0) = 0 ";
                            $debug .= ".11";
                        }
                        if ($dias_creacion == "1") {
                            $sql .= " AND IFNULL(TIMESTAMPDIFF(DAY, fecha_envio, NOW()), 0) = 1 ";
                            $debug .= ".12";
                        }
                        if ($dias_creacion == "2") {
                            $sql .= " AND IFNULL(TIMESTAMPDIFF(DAY, fecha_envio, NOW()), 0) = 2 ";
                            $debug .= ".13";
                        }
                        if ($dias_creacion == "3") {
                            $sql .= " AND IFNULL(TIMESTAMPDIFF(DAY, fecha_envio, NOW()), 0) = 3 ";
                            $debug .= ".14";
                        }
                        if ($dias_creacion == "4") {
                            $sql .= " AND IFNULL(TIMESTAMPDIFF(DAY, fecha_envio, NOW()), 0) = 4 ";
                            $debug .= ".15";
                        }
                        if ($dias_creacion == "5") {
                            $sql .= " AND IFNULL(TIMESTAMPDIFF(DAY, fecha_envio, NOW()), 0) = 5 ";
                            $debug .= ".16";
                        }
                        if ($dias_creacion == "mas_de_5") {
                            $sql .= " AND IFNULL(TIMESTAMPDIFF(DAY, fecha_envio, NOW()), 0) > 5";
                            $debug .= ".17";
                        }


                    }
                }

            } else {
                //  ADMIN
                $sql = "SELECT *,
                            IFNULL(TIMESTAMPDIFF(DAY, fecha_envio, NOW()), 0) AS dias_desde_creacion,
                            IFNULL(TIMESTAMPDIFF(hour, fecha_envio, NOW()), 0) AS horas_desde_creacion,
                            IFNULL(TIMESTAMPDIFF(minute , fecha_envio, NOW()), 0) AS minutos_desde_creacion,
                            IFNULL(TIMESTAMPDIFF(DAY, updated_at, NOW()), 0) AS dias_desde_actualizacion,
                            IFNULL(TIMESTAMPDIFF(hour , updated_at, NOW()), 0) AS horas_desde_actualizacion,
                            IFNULL(TIMESTAMPDIFF(minute , updated_at, NOW()), 0) AS minutos_desde_actualizacion
                            FROM correo where 1";
                if (empty($usuario_asignado) && empty($estado) && empty($fecha_inicio) && empty($fecha_fin) && empty($correo_origen) && empty($dias_creacion) && empty($id_ticket) && empty($multirespuesta) && empty($asunto)) {
                    $sql .= " AND estado = 1 and multirespuesta != 1";
                } else {
                    if (!empty($multirespuesta)) {
                        if ($multirespuesta == 2) {
                            $sql .= " AND multirespuesta = 0";
                            $debug .= ".1";
                        } else {
                            $sql .= " AND multirespuesta = $multirespuesta";
                            $debug .= ".2";
                        }
                    }
                    if (!empty($usuario_asignado)) {
                        $sql .= " AND asignado = '$usuario_asignado'";
                        $debug .= ".3";
                    }
                    if (!empty($fecha_inicio)) {
                        $sql .= " AND fecha_envio >= '$inicio'";
                        $debug .= ".4";
                    }

                    if (!empty($fecha_fin)) {
                        $sql .= " AND fecha_envio <= '$fin'";
                        $debug .= ".5";
                    }
                    if (!empty($estado)) {
                        if ($estado == 5) {
                            $sql .= " AND estado = $estado AND deleted_at is not null";
                        } else {
                            $sql .= " AND estado = $estado";
                        }
                        $debug .= ".6";
                    }
                    if (!empty($correo_origen)) {
                        $sql .= " AND correo_origen like '%$correo_origen%'";
                        $debug .= ".7";
                    }
                    if (!empty($asunto)) {
                        $sql .= " AND asunto like '%$asunto%'";
                        $debug .= ".8";
                    }
                    if (!empty($id_ticket)) {
                        $sql .= " AND uid like '%$id_ticket%'";
                        $debug .= ".9";
                    }
                    if (!empty($dias_creacion)) {
                        if ($dias_creacion == "hoy") {
                            $sql .= " AND IFNULL(TIMESTAMPDIFF(DAY, fecha_envio, NOW()), 0) = 0 ";
                            $debug .= ".10";
                        }
                        if ($dias_creacion == "1") {
                            $sql .= " AND IFNULL(TIMESTAMPDIFF(DAY, fecha_envio, NOW()), 0) = 1 ";
                            $debug .= ".11";
                        }
                        if ($dias_creacion == "2") {
                            $sql .= " AND IFNULL(TIMESTAMPDIFF(DAY, fecha_envio, NOW()), 0) = 2 ";
                            $debug .= ".12";
                        }
                        if ($dias_creacion == "3") {
                            $sql .= " AND IFNULL(TIMESTAMPDIFF(DAY, fecha_envio, NOW()), 0) = 3 ";
                            $debug .= ".13";
                        }
                        if ($dias_creacion == "4") {
                            $sql .= " AND IFNULL(TIMESTAMPDIFF(DAY, fecha_envio, NOW()), 0) = 4 ";
                            $debug .= ".14";
                        }
                        if ($dias_creacion == "5") {
                            $sql .= " AND IFNULL(TIMESTAMPDIFF(DAY, fecha_envio, NOW()), 0) = 5 ";
                            $debug .= ".15";
                        }
                        if ($dias_creacion == "mas_de_5") {
                            $sql .= " AND IFNULL(TIMESTAMPDIFF(DAY, fecha_envio, NOW()), 0) > 5";
                            $debug .= ".16";
                        }


                    }
                }
            }

            if ($estado != 5) {
                $sql .= " AND deleted_at is null";
            }
            // Paginación y orden
            $sql .= " ORDER BY fecha_envio DESC LIMIT $iniciar, $autoporpag";

            //Para debuguear
            #echo "<p> getpag / CARDS </p>";
            #echo "<pre>";
            #echo $sql; 
            #echo "</pre>";
            #echo "<pre>";
            #echo $debug;
            #echo "</pre>";


            $query = $this->db->connect()->query($sql);




            while ($row = $query->fetch()) {
                $item = new CorreoM();
                $item->id = $row['id'];
                $item->id_correo_original = $row['id_correo_original'];
                $item->message_id = $row['message_id'];
                $item->in_reply_to = $row['in_reply_to'];
                $item->uid = $row['uid'];
                $item->multirespuesta = $row['multirespuesta'];
                $item->correo_origen = $row['correo_origen'];
                $item->correo_destino = $row['correo_destino'];
                $item->asunto = $row['asunto'];
                $item->fecha_envio = $row['fecha_envio'];
                $item->cuerpo = $row['cuerpo'];
                $item->asignado = $row['asignado'];
                $item->imagenes_embebidas = $row['imagenes_embebidas'];
                $item->respuesta_correo = $row['respuesta_correo'];
                $item->comentario_desarrollador = $row['comentario_desarrollador'];
                $item->references = $row['references'];
                $item->cc = $row['cc'];
                $item->carpeta = $row['carpeta'];
                $item->estado = $row['estado'];
                $item->created_at = $row['created_at'];
                $item->updated_at = $row['updated_at'];
                $item->deleted_at = $row['deleted_at'];
                $item->dias_desde_creacion = $row['dias_desde_creacion'];
                $item->horas_desde_creacion = $row['horas_desde_creacion'];
                $item->minutos_desde_creacion = $row['minutos_desde_creacion'];
                $item->dias_desde_actualizacion = $row['dias_desde_actualizacion'];
                $item->horas_desde_actualizacion = $row['horas_desde_actualizacion'];
                $item->minutos_desde_actualizacion = $row['minutos_desde_actualizacion'];
                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            return [];
        }
    }
    /* ------ OBTIENE REGISTROS PARA LAS CARDS ------ */

    /* ------ OBTIENE REGISTROS PARA LAS ESTADISTICAS ------ */
    public function estadisticas()
    {
        try {
            $resultado = [];

            $sql = "SELECT
                    c.asignado AS usuario,
                    COUNT(CASE WHEN c.estado = 2 THEN 1 END) AS asignado,
                    COUNT(CASE WHEN c.estado = 4 THEN 1 END) AS en_progreso,
                    COUNT(CASE WHEN c.estado = 6 THEN 1 END) AS realizados,
                    COUNT(CASE WHEN c.estado = 3 THEN 1 END) AS finalizados
                    FROM correo c
                    JOIN usuariosperfil u ON u.idusuario = c.asignado
                    WHERE c.estado IN (2, 3, 4, 6) and deleted_at is null
                    GROUP BY c.asignado, u.idusuario
                    order by finalizados DESC;";
            //Para debuguear
            //echo "<p> estadisticas() </p>";
            //echo "<pre>";
            //echo $sql; 
            //echo "</pre>";
            //echo "<pre>";
            //echo $debug;
            //echo "</pre>";
            $query = $this->db->connect()->query($sql);

            while ($row = $query->fetch()) {
                $registro = new \stdClass();
                $registro->usuario = $row['usuario'];
                $registro->asignado = $row['asignado'];
                $registro->en_progreso = $row['en_progreso'];
                $registro->realizado = $row['realizados'];
                $registro->finalizado = $row['finalizados'];
                $resultado[] = $registro;
            }

            return $resultado;
        } catch (PDOException $e) {
            return [];
        }
    }

    public function estadisticasEnProgreso()
    {
        try {
            $resultado = [];

            $sql = "SELECT
                    uid,
                    IFNULL(asignado, 'No asignado') AS asignado,
                    asunto,
                    fecha_envio as fecha_recepcion,

                    CASE
                        WHEN TIMESTAMPDIFF(MINUTE, fecha_envio, NOW()) < 60 THEN
                            CONCAT(TIMESTAMPDIFF(MINUTE, fecha_envio, NOW()), ' minuto(s)')

                        WHEN TIMESTAMPDIFF(HOUR, fecha_envio, NOW()) < 24 THEN
                            CONCAT(
                                TIMESTAMPDIFF(HOUR, fecha_envio, NOW()), ' hora(s) ',
                                TIMESTAMPDIFF(MINUTE, fecha_envio, NOW()) % 60, ' minuto(s)'
                            )

                        ELSE
                            CONCAT(
                                TIMESTAMPDIFF(DAY, fecha_envio, NOW()), ' día(s) ',
                                TIMESTAMPDIFF(HOUR, fecha_envio, NOW()) % 24, ' hora(s) ',
                                TIMESTAMPDIFF(MINUTE, fecha_envio, NOW()) % 60, ' minuto(s)'
                            )
                    END AS tiempo_transcurrido

                    FROM correo WHERE estado = 4 and deleted_at is null 
                    ORDER BY asignado, created_at;";
            //Para debuguear
            //echo "<p> estadisticasEnProgreso() </p>";
            //echo "<pre>";
            //echo $sql; 
            //echo "</pre>";
            //echo "<pre>";
            //echo $debug;
            //echo "</pre>";
            $query = $this->db->connect()->query($sql);

            while ($row = $query->fetch()) {
                $registro = new \stdClass();
                $registro->uid = $row['uid'];
                $registro->asignado = $row['asignado'];
                $registro->asunto = $row['asunto'];
                $registro->fecha_recepcion = $row['fecha_recepcion'];
                $registro->tiempo_transcurrido = $row['tiempo_transcurrido'];
                $resultado[] = $registro;
            }

            return $resultado;
        } catch (PDOException $e) {
            return [];
        }
    }
    /* ------ OBTIENE REGISTROS PARA LAS ESTADISTICAS ------ */


    public function getById($id)
    {
        $item = new CorreoM();
        $query = $this->db->connect()->prepare("SELECT * FROM correo WHERE id=:id");
        try {
            $query->execute(['id' => $id]);
            while ($row = $query->fetch()) {
                $item->id = $row['id'];
                $item->id_correo_original = $row['id_correo_original'];
                $item->message_id = $row['message_id'];
                $item->in_reply_to = $row['in_reply_to'];
                $item->uid = $row['uid'];
                $item->multirespuesta = $row['multirespuesta'];
                $item->correo_origen = $row['correo_origen'];
                $item->correo_destino = $row['correo_destino'];
                $item->asunto = $row['asunto'];
                $item->fecha_envio = $row['fecha_envio'];
                $item->cuerpo = $row['cuerpo'];
                $item->asignado = $row['asignado'];
                $item->estado = $row['estado'];
                $item->created_at = $row['created_at'];
                $item->updated_at = $row['updated_at'];
                $item->deleted_at = $row['deleted_at'];
            }
            return $item;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function update($item)
    {
        $query = $this->db->connect()->prepare("UPDATE correo SET asunto=:asunto,estado=:estado WHERE id=:id");
        try {
            $query->execute(['asunto' => $item['asunto'], 'estado' => $item['estado']]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function insert($datos)
    {
        /* Pendiente siguiente iteracion */
        try {
            $query = $this->db->connect()->prepare('INSERT INTO correo(id,medico,foto) VALUES  (:id,:medico,:foto)');
            $query->execute(['id' => $datos['id'], 'medico' => $datos['medico'], 'foto' => $datos['foto']]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete($uid)
    {
        // Ocultar el registro (marcarlo como eliminado)
        $query = $this->db->connect()->prepare('UPDATE correo SET estado=0, deleted_at=NOW() WHERE uid=:uid');

        try {
            $query->execute(['uid' => $uid]);
            return true; // Éxito
        } catch (PDOException $e) {
            return false; // Error
        }
    }

    /* -------------------- IMAP -------------------- */
    public function guardarCorreoComoHTML($uid, $bodyHtml, $imagenesEmbebidasJson)
    {
        // GUARDA CORREOS COMO HTML PARA MOSTRARLOS EN IFRAME
        $imagenesEmbebidas = json_decode($imagenesEmbebidasJson, true); // array PHP

        // Ruta donde se guardará el archivo HTML
        $htmlDir = $_SERVER['DOCUMENT_ROOT'] . '/eticket/public/correos_html/';
        if (!is_dir($htmlDir)) {
            mkdir($htmlDir, 0777, true); // Crear carpeta si no existe
        }

        $htmlPath = $htmlDir . $uid . '.html'; // Nombre del archivo .html con el UID del correo

        // Reemplazar las referencias 'cid:' por la ruta de la imagen en el servidor
        foreach ($imagenesEmbebidas as $imgInfo) {
            if (!isset($imgInfo['cid']) || !isset($imgInfo['name']))
                continue;

            $cid = preg_quote($imgInfo['cid'], '/');
            $replacement = "/eticket/public/imagenes_embebidas/" . $imgInfo['name'];

            // Reemplaza cualquier coincidencia de cid con ese ID
            $bodyHtml = preg_replace('/cid:' . $cid . '/i', $replacement, $bodyHtml);
        }


        //sleep(1);
        // Guardar el contenido HTML con referencias corregidas
        file_put_contents($htmlPath, $bodyHtml);
    }

    /* NUEVA FUNCION IMPLEMENTADA 30/01/2026 */
    public function obtenerYGuardarCorreos($esRespuesta = 0)
    {
        ini_set('max_execution_time', 3000);
        ini_set('memory_limit', '512M');

        $location = constant('URL');
        $pwcorreo = constant('PWCORREO');
        $namecorreo = constant('CORREO');
        date_default_timezone_set('America/Santiago');

        $imap_base = '{mail.iopa.cl:993/imap/ssl}';
        $username = $namecorreo;
        $password = $pwcorreo;

        $totalProcesados = 0;
        $carpetas = ['INBOX', 'INBOX.Sent'];

        $limite = ($esRespuesta == 1) ? 25 : 150;

        foreach ($carpetas as $carpeta) {
            $mbox = imap_open($imap_base . $carpeta, $username, $password) or die("No se pudo conectar a $carpeta: " . imap_last_error());

            // --- NUEVA LÓGICA DE OBTENCIÓN EFICIENTE ---
            $check = imap_check($mbox);
            $total_mensajes = $check->Nmsgs;

            if ($total_mensajes > 0) {
                // Definimos un margen de 500 para seguridad total en producción
                $rango_inicio = max(1, $total_mensajes - $limite);
                // Obtenemos solo el resumen de los últimos 500
                $emails_overview = imap_fetch_overview($mbox, "$rango_inicio:$total_mensajes", 0);
                // Invertimos para procesar el más reciente primero
                $emails_overview = array_reverse($emails_overview);

                foreach ($emails_overview as $message) {
                    $email_num = $message->msgno; // Número de mensaje para funciones imap_...
                    $uid = $message->uid;

                    // Asignar prefijo según la carpeta
                    $prefijo = '';
                    if ($carpeta === 'INBOX') {
                        $prefijo = 'R-';
                    } elseif ($carpeta === 'INBOX.Sent') {
                        $prefijo = 'E-';
                    }
                    $uid_personalizado = $prefijo . $uid;

                    // Verificar si ya existe el correo (Tu lógica original)
                    $query = $this->db->connect()->prepare('SELECT COUNT(*) FROM correo WHERE uid = :uid');
                    $query->execute(['uid' => $uid_personalizado]);
                    $exists = $query->fetchColumn();

                    if ($exists == 0) {
                        // --- TODO LO QUE SIGUE ES TU FUNCIÓN ORIGINAL SIN CAMBIOS ---
                        $nombre_carpeta = 'Desconocida';
                        if ($carpeta === 'INBOX') {
                            $nombre_carpeta = 'Bandeja de entrada';
                        } elseif ($carpeta === 'INBOX.Sent') {
                            $nombre_carpeta = 'Enviado';
                        }

                        $structure = imap_fetchstructure($mbox, $email_num);
                        preg_match('/<(.+)>/', $message->message_id ?? '', $matches);
                        $message_id_simplificado = explode('@', $matches[1] ?? '')[0] ?? null;

                        $resultado = $this->getBodyRecursive($mbox, $email_num, $structure, $message_id_simplificado);
                        $body = is_array($resultado) ? $resultado['body'] : $resultado;
                        $imagenesEmbebidas = is_array($resultado) ? $resultado['imagenes'] : [];

                        // ----------- IN_REPLY_TO -----------
                        preg_match('/<(.+)>/', $message->in_reply_to ?? '', $matches_reply);
                        $in_reply_to_simplificado = explode('@', $matches_reply[1] ?? '')[0] ?? null;

                        // ----------- MULTIRESPUESTA -----------
                        $multirespuesta = !empty($in_reply_to_simplificado) ? 1 : 0;

                        // ----------- REFERENCES -----------
                        $references = isset($message->references) ? trim($message->references) : null;
                        if ($references) {
                            $references = preg_replace_callback('/<([^@>]+)@[^>]+>/', function ($matches) {
                                return "<{$matches[1]}@iopa.cl>";
                            }, $references);
                        }

                        $asunto = isset($message->subject) ? imap_utf8($message->subject) : 'Sin asunto';

                        // Lista de correos spam
                        $querySpam = $this->db->connect()->prepare("SELECT correo_spam FROM correo_spam WHERE estado = 1 AND deleted_at IS NULL");
                        $querySpam->execute();
                        $spamList = $querySpam->fetchAll(PDO::FETCH_COLUMN);

                        $correo_origen = 'Desconocido';
                        if (isset($message->from)) {
                            if (preg_match('/<(.+?)>/', $message->from, $matches_from)) {
                                $correo_origen = $matches_from[1];
                            } elseif (filter_var($message->from, FILTER_VALIDATE_EMAIL)) {
                                $correo_origen = $message->from;
                            }
                        }

                        if ($carpeta == 'INBOX' && in_array($correo_origen, $spamList)) {
                            continue;
                        }

                        if (stripos($asunto, 'de Ticket') !== false) {
                            continue;
                        }

                        $fecha_envio = $message->date ?? null;
                        $timestamp = strtotime($fecha_envio);
                        $fecha_formateada = $timestamp ? date('Y-m-d H:i:s', $timestamp) : null;

                        $header = imap_fetchheader($mbox, $email_num);
                        $header = preg_replace("/\r\n[ \t]+/", " ", $header);

                        // ----------- DESTINATARIOS -----------
                        preg_match('/^To:\s*(.*)$/mi', $header, $matches_to);
                        $destinatarios = 'No disponible';
                        if (!empty($matches_to[1])) {
                            preg_match_all('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $matches_to[1], $emails_to);
                            if (!empty($emails_to[0])) {
                                $destinatarios = implode(',', $emails_to[0]);
                            }
                        }

                        // ----------- CC -----------
                        $cc = '';
                        if (isset($message) && property_exists($message, 'cc') && !empty($message->cc)) {
                            preg_match_all('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $message->cc, $emails_cc);
                            if (!empty($emails_cc[0])) {
                                $cc = implode(',', $emails_cc[0]);
                            }
                        } else {
                            preg_match('/^Cc:\s*(.*)$/mi', $header, $matches_cc);
                            if (!empty($matches_cc[1])) {
                                preg_match_all('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $matches_cc[1], $emails_cc);
                                if (!empty($emails_cc[0])) {
                                    $cc = implode(',', $emails_cc[0]);
                                }
                            }
                        }

                        $this->guardarCorreoComoHTML($uid_personalizado, $body, json_encode($imagenesEmbebidas));

                        $queryINSERT = $this->db->connect()->prepare('
                            INSERT INTO correo (
                                id_correo_original, message_id, in_reply_to, uid, multirespuesta,
                                correo_origen, correo_destino, asunto, fecha_envio, cuerpo,
                                imagenes_embebidas, `references`, cc, carpeta, estado, created_at, updated_at, deleted_at
                            ) VALUES (
                                :id_correo_original, :message_id, :in_reply_to, :uid, :multirespuesta,
                                :correo_origen, :correo_destino, :asunto, :fecha_envio, :cuerpo,
                                :imagenes_embebidas, :references, :cc, :carpeta, :estado, :created_at, :updated_at, :deleted_at
                            )');

                        $queryINSERT->execute([
                            'id_correo_original' => null,
                            'message_id' => $message_id_simplificado,
                            'in_reply_to' => $in_reply_to_simplificado,
                            'uid' => $uid_personalizado,
                            'multirespuesta' => $multirespuesta,
                            'correo_origen' => $correo_origen,
                            'correo_destino' => $destinatarios,
                            'asunto' => $asunto,
                            'fecha_envio' => $fecha_formateada,
                            'cuerpo' => "/eticket/public/correos_html/$uid_personalizado.html",
                            'imagenes_embebidas' => json_encode($imagenesEmbebidas),
                            'references' => $references,
                            'cc' => $cc,
                            'carpeta' => $nombre_carpeta,
                            'estado' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => null,
                            'deleted_at' => null
                        ]);

                        $totalProcesados++;
                    }
                }
            }
            imap_close($mbox);
        }

        //echo "Total de correos procesados: $totalProcesados\n";
        return $totalProcesados;
    }
    /* NUEVA FUNCION IMPLEMENTADA 30/01/2026 */

    /* FUNCION DEPRECADA POR RENDIMIENTO, REVERTIR LA FECHA EN EL NOMBRE DE LA FUNCION A 'public function obtenerYGuardarCorreos' PARA VOLVER A LA FUNCION ORIGINAL */
    public function obtenerYGuardarCorreos2()
    {
        ini_set('max_execution_time', 3000);
        ini_set('memory_limit', '512M');

        $location = constant('URL');
        $pwcorreo = constant('PWCORREO');
        $namecorreo = constant('CORREO');
        date_default_timezone_set('America/Santiago');

        $imap_base = '{mail.iopa.cl:993/imap/ssl}';
        $username = $namecorreo;
        $password = $pwcorreo;

        $totalProcesados = 0;

        // Carpetas que vamos a procesar: INBOX y Sent
        $carpetas = ['INBOX', 'INBOX.Sent']; // Puedes cambiar 'Sent' si tu servidor usa otro nombre

        foreach ($carpetas as $carpeta) {
            $mbox = imap_open($imap_base . $carpeta, $username, $password) or die("No se pudo conectar a $carpeta: " . imap_last_error());
            $emails = imap_search($mbox, 'ALL');

            $carpetas = imap_list($mbox, $imap_base, '*');



            /* if ($carpetas === false) {
                echo "No se pudieron obtener las carpetas: " . imap_last_error();
            } else {
                echo "Carpetas disponibles en el servidor:\n";
                foreach ($carpetas as $carpeta) {
                    echo $carpeta . "\n";
                }
            }
            return; */
            if ($emails) {
                rsort($emails);
                foreach ($emails as $email_num) {
                    $overview = imap_fetch_overview($mbox, $email_num, 0);
                    if (!$overview || !isset($overview[0]))
                        continue;
                    $message = $overview[0];
                    $uid = imap_uid($mbox, $email_num);

                    // Asignar prefijo según la carpeta
                    $prefijo = '';
                    if ($carpeta === 'INBOX') {
                        $prefijo = 'R-';
                    } elseif ($carpeta === 'INBOX.Sent') {
                        $prefijo = 'E-';
                    }
                    $uid_personalizado = $prefijo . $uid;

                    // Verificar si ya existe el correo
                    $query = $this->db->connect()->prepare('SELECT COUNT(*) FROM correo WHERE uid = :uid');
                    $query->execute(['uid' => $uid_personalizado]);
                    $exists = $query->fetchColumn();

                    if ($exists == 0) {

                        $nombre_carpeta = 'Desconocida';
                        if ($carpeta === 'INBOX') {
                            $nombre_carpeta = 'Bandeja de entrada';
                        } elseif ($carpeta === 'INBOX.Sent') {
                            $nombre_carpeta = 'Enviado';
                        }

                        $structure = imap_fetchstructure($mbox, $email_num);
                        preg_match('/<(.+)>/', $message->message_id ?? '', $matches);
                        $message_id_simplificado = explode('@', $matches[1] ?? '')[0] ?? null;

                        $resultado = $this->getBodyRecursive($mbox, $email_num, $structure, $message_id_simplificado);
                        $body = is_array($resultado) ? $resultado['body'] : $resultado;
                        $imagenesEmbebidas = is_array($resultado) ? $resultado['imagenes'] : [];

                        //$references = isset($message->references) ? trim($message->references) : null; //debug
                        //$in_reply_to = isset($message->in_reply_to) ? trim($message->in_reply_to) : null; //debug

                        // ----------- IN_REPLY_TO -----------
                        preg_match('/<(.+)>/', $message->in_reply_to ?? '', $matches);
                        $in_reply_to_simplificado = explode('@', $matches[1] ?? '')[0] ?? null;
                        // ----------- IN_REPLY_TO -----------

                        // ----------- MULTIRESPUESTA -----------
                        $multirespuesta = !empty($in_reply_to_simplificado) ? 1 : 0;
                        // ----------- MULTIRESPUESTA -----------


                        // ----------- REFERENCES -----------
                        $references = isset($message->references) ? trim($message->references) : null;
                        if ($references) {
                            // Reemplaza los dominios de todos los Message-ID con @iopa.cl
                            $references = preg_replace_callback('/<([^@>]+)@[^>]+>/', function ($matches) {
                                return "<{$matches[1]}@iopa.cl>";
                            }, $references);
                        }
                        // ----------- REFERENCES -----------


                        //$multirespuesta = !empty($in_reply_to) ? 1 : 0; //debug

                        $asunto = isset($message->subject) ? imap_utf8($message->subject) : 'Sin asunto';

                        // Lista de correos spam
                        $querySpam = $this->db->connect()->prepare("SELECT correo_spam FROM correo_spam WHERE estado = 1 AND deleted_at IS NULL");
                        $querySpam->execute();
                        $spamList = $querySpam->fetchAll(PDO::FETCH_COLUMN);

                        $correo_origen = 'Desconocido';
                        if (isset($message->from)) {
                            if (preg_match('/<(.+?)>/', $message->from, $matches)) {
                                $correo_origen = $matches[1];
                            } elseif (filter_var($message->from, FILTER_VALIDATE_EMAIL)) {
                                $correo_origen = $message->from;
                            }
                        }

                        // Si es INBOX y está en lista spam, lo saltamos
                        if ($carpeta == 'INBOX' && in_array($correo_origen, $spamList)) {
                            echo "Correo $correo_origen está marcado como spam, se omite.\n";
                            continue;
                        }

                        // Si el asunto contiene "Finalización de Ticket", también lo saltamos
                        //echo $asunto;
                        if (stripos($asunto, 'de Ticket') !== false) {
                            //echo "Correo con uid '$uid_personalizado' corresponde a finalización automática, se omite.\n";
                            //aplica para correos de finalizacion y de realizacion 29/09/2025
                            continue;
                        }

                        $fecha_envio = $message->date ?? null;
                        $timestamp = strtotime($fecha_envio);
                        $fecha_formateada = $timestamp ? date('Y-m-d H:i:s', $timestamp) : null;

                        $header = imap_fetchheader($mbox, $email_num);
                        $header = preg_replace("/\r\n[ \t]+/", " ", $header);

                        // ----------- DESTINATARIOS -----------
                        preg_match('/^To:\s*(.*)$/mi', $header, $matches_to);
                        $destinatarios = 'No disponible';
                        if (!empty($matches_to[1])) {
                            preg_match_all('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $matches_to[1], $emails_to);
                            if (!empty($emails_to[0])) {
                                $destinatarios = implode(',', $emails_to[0]);
                            }
                        }
                        // ----------- FIN DESTINATARIOS -----------

                        // ----------- CC -----------
                        $cc = '';
                        if (isset($message) && property_exists($message, 'cc') && !empty($message->cc)) {
                            preg_match_all('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $message->cc, $emails_cc);
                            if (!empty($emails_cc[0])) {
                                $cc = implode(',', $emails_cc[0]);
                            }
                        } else {
                            preg_match('/^Cc:\s*(.*)$/mi', $header, $matches_cc);
                            if (!empty($matches_cc[1])) {
                                preg_match_all('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $matches_cc[1], $emails_cc);
                                if (!empty($emails_cc[0])) {
                                    $cc = implode(',', $emails_cc[0]);
                                }
                            }
                        }
                        // ----------- FIN CC -----------

                        // Guardar HTML
                        $this->guardarCorreoComoHTML($uid_personalizado, $body, json_encode($imagenesEmbebidas));

                        $queryINSERT = $this->db->connect()->prepare('
                            INSERT INTO correo (
                                id_correo_original, message_id, in_reply_to, uid, multirespuesta,
                                correo_origen, correo_destino, asunto, fecha_envio, cuerpo,
                                imagenes_embebidas, `references`, cc, carpeta, estado, created_at, updated_at, deleted_at
                            ) VALUES (
                                :id_correo_original, :message_id, :in_reply_to, :uid, :multirespuesta,
                                :correo_origen, :correo_destino, :asunto, :fecha_envio, :cuerpo,
                                :imagenes_embebidas, :references, :cc, :carpeta, :estado, :created_at, :updated_at, :deleted_at
                            )');

                        $queryINSERT->execute([
                            'id_correo_original' => null,
                            'message_id' => $message_id_simplificado,
                            'in_reply_to' => $in_reply_to_simplificado,
                            'uid' => $uid_personalizado,
                            'multirespuesta' => $multirespuesta,
                            'correo_origen' => $correo_origen,
                            'correo_destino' => $destinatarios,
                            'asunto' => $asunto,
                            'fecha_envio' => $fecha_formateada,
                            'cuerpo' => "/eticket/public/correos_html/$uid_personalizado.html",
                            'imagenes_embebidas' => json_encode($imagenesEmbebidas),
                            'references' => $references,
                            'cc' => $cc,
                            'carpeta' => $nombre_carpeta,
                            'estado' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => null,
                            'deleted_at' => null
                        ]);

                        $totalProcesados++;
                    }
                }
            }

            imap_close($mbox);
        }

        echo "Total de correos procesados: $totalProcesados\n";
        return $totalProcesados;
    }
    /* FUNCION DEPRECADA POR RENDIMIENTO, REVERTIR LA FECHA EN EL NOMBRE DE LA FUNCION A 'public function obtenerYGuardarCorreos' PARA VOLVER A LA FUNCION ORIGINAL */

    public function getBodyRecursive($inbox, $emailNumber, $structure, $uid, $partNumber = '', &$imageCounter = 1)
    {
        $htmlBody = '';
        $imagePaths = [];
        $foundHtml = false;
        $foundPlain = false;
        $plainBody = '';

        // Si tiene partes, recorremos recursivamente
        if (isset($structure->parts)) {
            foreach ($structure->parts as $index => $subPart) {
                $subPartNumber = $partNumber ? $partNumber . '.' . ($index + 1) : (string) ($index + 1);

                // Imagen embebida (inline)
                if ($subPart->type == 5 || $subPart->type == 6) {
                    $imageInfo = $this->saveEmbeddedImage($inbox, $emailNumber, $subPart, $subPartNumber, $uid, $imageCounter++);
                    if ($imageInfo && isset($imageInfo['name'])) {
                        $imagePaths[] = [
                            'cid' => $imageInfo['cid'],
                            'name' => $imageInfo['name']
                        ];
                    }
                }

                // Parte HTML
                if (strtolower($subPart->subtype ?? '') === 'html') {
                    $data = imap_fetchbody($inbox, $emailNumber, $subPartNumber);
                    switch ($subPart->encoding) {
                        case 3:
                            $data = base64_decode($data);
                            break;
                        case 4:
                            $data = quoted_printable_decode($data);
                            break;
                    }
                    $htmlBody .= $data;
                    $foundHtml = true;
                }

                // Parte texto plano (solo si no se encontró HTML aún)
                elseif (strtolower($subPart->subtype ?? '') === 'plain' && !$foundHtml) {
                    $data = imap_fetchbody($inbox, $emailNumber, $subPartNumber);
                    switch ($subPart->encoding) {
                        case 3:
                            $data = base64_decode($data);
                            break;
                        case 4:
                            $data = quoted_printable_decode($data);
                            break;
                    }
                    $plainBody .= $data;
                    $foundPlain = true;
                }

                // Recursividad si tiene más partes
                if (isset($subPart->parts)) {
                    $result = $this->getBodyRecursive($inbox, $emailNumber, $subPart, $uid, $subPartNumber, $imageCounter);
                    if (!empty($result['body'])) {
                        $htmlBody .= $result['body'];
                    }
                    if (!empty($result['imagenes'])) {
                        $imagePaths = array_merge($imagePaths, $result['imagenes']);
                    }
                }
            }
        } else {
            // Si no hay partes, puede ser un mensaje simple (sin multipart)
            if (strtolower($structure->subtype ?? '') === 'html') {
                $data = imap_fetchbody($inbox, $emailNumber, $partNumber ?: '1');
                switch ($structure->encoding) {
                    case 3:
                        $data = base64_decode($data);
                        break;
                    case 4:
                        $data = quoted_printable_decode($data);
                        break;
                }
                $htmlBody .= $data;
                $foundHtml = true;
            } elseif (strtolower($structure->subtype ?? '') === 'plain') {
                $data = imap_fetchbody($inbox, $emailNumber, $partNumber ?: '1');
                switch ($structure->encoding) {
                    case 3:
                        $data = base64_decode($data);
                        break;
                    case 4:
                        $data = quoted_printable_decode($data);
                        break;
                }
                $plainBody .= $data;
                $foundPlain = true;
            }
        }

        // Si no se encontró HTML pero sí texto plano, lo convertimos a HTML completo
        if (!$foundHtml && $foundPlain) {
            $htmlBody = $this->plainTextToHtml($plainBody);
        }

        return [
            'body' => $htmlBody,
            'imagenes' => $imagePaths
        ];
    }



    public function saveEmbeddedImage($inbox, $emailNumber, $part, $partNumber, $uid, $imageCounter)
    {
        $imageData = imap_fetchbody($inbox, $emailNumber, $partNumber);

        if ($part->encoding == 3) {
            $imageData = base64_decode($imageData);
        } elseif ($part->encoding == 4) {
            $imageData = quoted_printable_decode($imageData);
        }

        $rawName = 'imag-e-' . $uid . '-' . md5(uniqid((string) $imageCounter, true));
        $cleanName = preg_replace('/[^a-zA-Z0-9\-]/', '-', $rawName);
        $extension = strtolower($part->subtype);
        $imageName = $cleanName . '.' . $extension;

        $imageDir = $_SERVER['DOCUMENT_ROOT'] . '/eticket/public/imagenes_embebidas/';
        if (!is_dir($imageDir)) {
            mkdir($imageDir, 0777, true);
        }

        $imagePath = $imageDir . $imageName;
        $saveResult = file_put_contents($imagePath, $imageData);

        if ($saveResult === false) {
            echo "Error al guardar la imagen: $imagePath\n";
            return null;
        }

        // Obtener CID desde Content-ID
        $cid = null;
        if (!empty($part->id)) {
            $cid = trim($part->id, "<>");
        } elseif (!empty($part->parameters)) {
            foreach ($part->parameters as $param) {
                if (strtolower($param->attribute) === 'name') {
                    $cid = pathinfo($param->value, PATHINFO_FILENAME);
                }
            }
        }

        return ['cid' => $cid, 'name' => $imageName];
    }

    private function plainTextToHtml($text)
    {
        // Escapar caracteres especiales de HTML
        $escaped = htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        // Reemplazar saltos de línea por <br>
        $formattedText = nl2br($escaped);

        // Envolver en un HTML completo con estilos básicos
        return '<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Correo</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    font-size: 14px;
                    line-height: 1.5;
                    margin: 20px;
                    padding: 10px;
                    background-color: #f9f9f9;
                    color: #333;
                }
                .email-container {
                    background: #fff;
                    padding: 15px;
                    border-radius: 5px;
                    box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
                }
                pre {
                    white-space: pre-wrap;
                    word-wrap: break-word;
                }
            </style>
        </head>
        <body>
            <div class="email-container">
                <pre>' . $formattedText . '</pre>
            </div>
        </body>
        </html>';
    }



    /* -------------------- IMAP -------------------- */


    /* -------------------- CONTANDORES ADMIN -------------------- */
    public function getTicketsNoAsignados()
    {
        try {

            $query = $this->db->connect()->query("SELECT count(uid) as uid FROM correo where estado = 1 and multirespuesta!=1 and deleted_at is null");

            while ($row = $query->fetch()) {
                $sinAsignar = $row['uid'];
            }
            return $sinAsignar;
        } catch (PDOException $e) {
            return 0;
        }
    }
    public function getTicketsAsignados()
    {
        try {

            $query = $this->db->connect()->query("SELECT count(uid) as uid FROM correo where estado = 2 and deleted_at is null");

            while ($row = $query->fetch()) {
                $asignados = $row['uid'];
            }
            return $asignados;
        } catch (PDOException $e) {
            return 0;
        }
    }
    public function getTicketsEnProgreso()
    {
        try {

            $query = $this->db->connect()->query("SELECT count(uid) as uid FROM correo where estado = 4 and deleted_at is null");

            while ($row = $query->fetch()) {
                $finalizado = $row['uid'];
            }
            return $finalizado;
        } catch (PDOException $e) {
            return 0;
        }
    }
    public function getTicketsFinalizados()
    {
        try {

            $query = $this->db->connect()->query("SELECT count(uid) as uid FROM correo where estado = 3 and deleted_at is null");

            while ($row = $query->fetch()) {
                $finalizado = $row['uid'];
            }
            return $finalizado;
        } catch (PDOException $e) {
            return 0;
        }
    }
    public function getTicketsRealizados()
    {
        try {

            $query = $this->db->connect()->query("SELECT count(uid) as uid FROM correo where estado = 6 and deleted_at is null");

            while ($row = $query->fetch()) {
                $realizados = $row['uid'];
            }
            return $realizados;
        } catch (PDOException $e) {
            return 0;
        }
    }
    /* -------------------- COTANDORES ADMIN -------------------- */


    /* -------------------- COTANDORES USUARIOS -------------------- */
    public function getTicketsNoAsignadosUsuario($idusuario)
    {
        try {
            //$sql = "SELECT COUNT(uid) FROM correo WHERE estado = 2 AND asignado = $idusuario";
            #echo $sql;
            $query = $this->db->connect()->query("SELECT count(uid) as total 
                                                    FROM correo 
                                                    WHERE estado = 1 AND (correo_origen = '$idusuario'
                                                                            OR correo_origen IN (
                                                                                SELECT usuario_asociado
                                                                                FROM usuariosperfil_asignaciones
                                                                                WHERE usuario_principal = '$idusuario')) 
                                                            AND multirespuesta != 1 and deleted_at is null");
            $row = $query->fetch();
            return $row['total']; // 
        } catch (PDOException $e) {
            return 0;
        }
    }
    public function getTicketsAsignadosUsuario($idusuario)
    {
        try {
            //$sql = "SELECT COUNT(uid) FROM correo WHERE estado = 2 AND asignado = $idusuario";
            #echo $sql;
            $query = $this->db->connect()->query("SELECT count(uid) as total 
                                                    FROM correo 
                                                    WHERE estado = 2 
                                                    AND (correo_origen = '$idusuario'
                                                        OR correo_origen IN (
                                                            SELECT usuario_asociado
                                                            FROM usuariosperfil_asignaciones
                                                            WHERE usuario_principal = '$idusuario'))
                                                    and deleted_at is null");
            $row = $query->fetch();
            return $row['total'];
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function getTicketsEnProgresoUsuario($idusuario)
    {
        try {
            //$sql = "SELECT COUNT(uid) FROM correo WHERE estado = 4 AND asignado = $idusuario";
            #echo $sql;
            $query = $this->db->connect()->query("SELECT count(uid) as total 
                                                    FROM correo 
                                                    WHERE estado = 4 
                                                    AND (correo_origen = '$idusuario'
                                                        OR correo_origen IN (
                                                            SELECT usuario_asociado
                                                            FROM usuariosperfil_asignaciones
                                                            WHERE usuario_principal = '$idusuario'))
                                                    and deleted_at is null");
            $row = $query->fetch();

            return $row['total'];
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function getTicketsRealizadoUsuario($idusuario)
    {
        try {
            //$sql = "SELECT COUNT(uid) FROM correo WHERE estado = 4 AND asignado = $idusuario";
            #echo $sql;
            $query = $this->db->connect()->query("SELECT count(uid) as total 
                                                    FROM correo 
                                                    WHERE estado = 6 
                                                    AND (correo_origen = '$idusuario'
                                                        OR correo_origen IN (
                                                            SELECT usuario_asociado
                                                            FROM usuariosperfil_asignaciones
                                                            WHERE usuario_principal = '$idusuario')) 
                                                    and deleted_at is null");
            $row = $query->fetch();

            return $row['total'];
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function getTicketsFinalizadosUsuario($idusuario)
    {
        try {
            //$sql = "SELECT COUNT(uid) FROM correo WHERE estado = 3 AND asignado = $idusuario";
            #echo $sql;
            $query = $this->db->connect()->query("SELECT count(uid) as total 
                                                    FROM correo 
                                                    WHERE estado = 3 
                                                    AND (correo_origen = '$idusuario'
                                                        OR correo_origen IN (
                                                            SELECT usuario_asociado
                                                            FROM usuariosperfil_asignaciones
                                                            WHERE usuario_principal = '$idusuario'))
                                                    and deleted_at is null");
            #echo $query;
            $row = $query->fetch();

            return $row['total'];
        } catch (PDOException $e) {
            return 0;
        }
    }
    /* -------------------- COTANDORES USUARIOS -------------------- */

    /* -------------------- ASIGNACIONES -------------------- */
    public function obtenerAsignaciones($usuario, $permiso)
    {
        try {

            if ($permiso == 'admin') {

                $sql = "SELECT *
                        FROM usuariosperfil_asignaciones
                        ORDER BY usuario_principal ASC
                    ";

                $stmt = $this->db->connect()->prepare($sql);

                $stmt->execute();

            } else {

                $sql = "SELECT *
                            FROM usuariosperfil_asignaciones
                            WHERE usuario_principal = :usuario
                            ORDER BY usuario_asociado ASC
                        ";

                $stmt = $this->db->connect()->prepare($sql);

                $stmt->execute([
                    ':usuario' => $usuario
                ]);
            }

            return $stmt->fetchAll(PDO::FETCH_OBJ);

        } catch (PDOException $e) {

            throw new Exception($e->getMessage());
        }
    }

    public function eliminarAsignacion($principal, $asociado)
    {
        try {

            $sql = "DELETE FROM usuariosperfil_asignaciones
                    WHERE usuario_principal = :principal
                    AND usuario_asociado = :asociado";

            $stmt = $this->db->connect()->prepare($sql);

            return $stmt->execute([
                ':principal' => $principal,
                ':asociado' => $asociado
            ]);

        } catch (PDOException $e) {

            throw new Exception($e->getMessage());
        }
    }

    public function crearAsignaciones($principal, $asociados)
    {
        try {

            $conexion = $this->db->connect();

            $sql = "
                INSERT IGNORE INTO usuariosperfil_asignaciones
                (
                    usuario_principal,
                    usuario_asociado
                )
                VALUES
                (
                    :principal,
                    :asociado
                )
            ";

            $stmt = $conexion->prepare($sql);

            foreach ($asociados as $asociado) {

                $asociado = trim($asociado);

                if (empty($asociado)) {
                    continue;
                }

                $stmt->execute([
                    ':principal' => $principal,
                    ':asociado' => $asociado
                ]);
            }

            return true;

        } catch (PDOException $e) {

            throw new Exception($e->getMessage());
        }
    }
    /* -------------------- ASIGNACIONES -------------------- */


    



    /* -------------------- OBTENER LISTADO DE USUARIOS PERMITIDOS -------------------- */
    public function getAsignacion()
    {
        $query = $this->db->connect()->prepare("SELECT idusuario FROM usuariosperfil u WHERE habilitado = 'S' AND menu = 'Correo' AND permiso = 'admin' GROUP BY u.idusuario order by area;");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    /* -------------------- OBTENER LISTADO DE USUARIOS PERMITIDOS -------------------- */
    public function obtenerAsignacionPorGrupo()
    {
        $query = $this->db->connect()->prepare("SELECT idusuario FROM usuariosperfil u WHERE habilitado = 'S' AND menu = 'Correo' GROUP BY u.idusuario order by area;");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
    /* -------------------- OBTENER LISTADO DE USUARIOS PERMITIDOS -------------------- */
    public function obtenerAsignacionPorGrupoParaUsuario($usuarioLogueado)
    {
        $query = $this->db->connect()->prepare("SELECT usuario_asociado FROM usuariosperfil_asignaciones ua WHERE usuario_principal = '$usuarioLogueado';");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    /* -------------------- ASIGNAR UN E-TICKET A UN USUARIO -------------------- */
    public function asignarUsuario($uid, $idusuario, $usuario)
    {
        try {
            $db = $this->db->connect();

            $query = $this->db->connect()->prepare("UPDATE correo SET asignado = :idusuario, estado = 2, updated_at = NOW(), deleted_at = NULL WHERE uid = :uid");
            $query->bindParam(':idusuario', $idusuario);
            $query->bindParam(':uid', $uid);

            //LOGS
            $usuario = trim($usuario);
            $uid = trim($uid);
            $sqllog = "INSERT INTO logs (uid, usuario, accion, detalle, metodo, modulo, fecha) VALUES ('$uid','$usuario','Asignación','Realizó una asignación en el ticket #$uid: asignado a $idusuario','Botón Asignado: Front','Correo',NOW())";
            $querylog = $db->prepare($sqllog);
            $querylog->execute();

            return $query->execute();
        } catch (PDOException $e) {
            error_log("Error en asignarUsuario: " . $e->getMessage());
            return false;
        }
    }

    /* -------------------- ACTUALIZAR UN ESTADO DE UN E-TICKET -------------------- */
    public function actualizarEstado($uid, $estado, $comentario, $comentarioDesarrollador, $idusuario, $estado_actual, $estado_actualPalabra, $nuevoEstado, $nuevoEstadoPalabra)
    {
        try {
            $db = $this->db->connect();

            $idusuario = trim($idusuario);
            $uid = trim($uid);


            /* ASIGNADO */
            if ($estado == 2) {
                $sql = "UPDATE correo SET estado = $estado, updated_at = NOW(), deleted_at = NULL WHERE uid = '$uid'";
                $sqllog = "INSERT INTO logs (uid, usuario, accion, detalle, metodo, modulo, fecha) VALUES ('$uid','$idusuario','Estado','Realizó un cambio de estado en el ticket #$uid de $estado_actualPalabra a $nuevoEstadoPalabra','Botón Estado: Front','Correo',NOW())";
            }

            /* SIN ASIGNAR */ elseif ($estado == 1) {
                $sql = "UPDATE correo SET asignado = NULL, estado = $estado, respuesta_correo = NULL, comentario_desarrollador = NULL, updated_at = NOW(), deleted_at = NULL WHERE uid = '$uid'";
                $sqllog = "INSERT INTO logs (uid, usuario, accion, detalle, metodo, modulo, fecha) VALUES ('$uid','$idusuario','Estado','Realizó un cambio de estado en el ticket #$uid de $estado_actualPalabra a $nuevoEstadoPalabra','Botón Estado: Front','Correo',NOW())";
            }

            /* FINALIZADO */ elseif ($estado == 3) {
                if (empty($comentario)) {
                    throw new Exception("Comentario requerido para finalizar el ticket.");
                }
                $sql = "UPDATE correo SET estado = $estado, respuesta_correo = '$comentario', updated_at = NOW() WHERE uid = '$uid'";
                $sqllog = "INSERT INTO logs (uid, usuario, accion, detalle, metodo, modulo, fecha) VALUES ('$uid','$idusuario','Estado','Realizó un cambio de estado en el ticket #$uid de $estado_actualPalabra a $nuevoEstadoPalabra','Botón Estado: Front','Correo',NOW())";
            }

            /* EN PROGRESO */ /* NO-ADMIN */ elseif ($estado == 4) {
                $sql = "UPDATE correo SET estado = $estado, updated_at = NOW(), deleted_at = NULL WHERE uid = '$uid'";
                $sqllog = "INSERT INTO logs (uid, usuario, accion, detalle, metodo, modulo, fecha) VALUES ('$uid','$idusuario','Estado', 'Realizó un cambio de estado en el ticket #$uid de $estado_actualPalabra a $nuevoEstadoPalabra','Botón Estado: Front','Correo',NOW())";
            }

            /* ELIMINADO */ elseif ($estado == 5) {
                $sql = "UPDATE correo SET estado = $estado, deleted_at = NOW() WHERE uid = '$uid'";
                $sqllog = "INSERT INTO logs (uid, usuario, accion, detalle, metodo, modulo, fecha) VALUES ('$uid','$idusuario','Estado','Realizó un cambio de estado en el ticket #$uid de $estado_actualPalabra a $nuevoEstadoPalabra','Botón Estado: Front','Correo',NOW())";
            }

            /* REALIZADO */ /* NO-ADMIN */ elseif ($estado == 6) {
                if (empty($comentarioDesarrollador)) {
                    throw new Exception("Comentario de desarrollador requerido para realizar el ticket.");
                }
                $sql = "UPDATE correo SET estado = $estado, comentario_desarrollador = '$comentarioDesarrollador', updated_at = NOW(), deleted_at = NULL WHERE uid = '$uid'";
                $sqllog = "INSERT INTO logs (uid, usuario, accion, detalle, metodo, modulo, fecha) VALUES ('$uid','$idusuario','Estado','Realizó un cambio de estado en el ticket #$uid de $estado_actualPalabra a $nuevoEstadoPalabra','Botón Estado: Front','Correo',NOW())";
            } else {
                throw new Exception("Estado no válido.");
            }

            //Para debuguear
            //echo "<p> actualizarEstado() </p>";
            //
            //echo "<pre>";
            //echo $sql; 
            //echo "</pre>";
            //
            //echo "<pre>";
            //echo $sqllog; 
            //echo "</pre>";

            //echo "<pre>";
            //echo $debug;
            //echo "</pre>";
            $query = $db->prepare($sql);

            $querylog = $db->prepare($sqllog);
            $querylog->execute();

            //$this->enviarRespuestaEstatica();

            return $query->execute();
        } catch (PDOException $e) {
            error_log("Error actualizarEstado (PDO): " . $e->getMessage());
            return false;
        }
    }

    public function enviarCorreoAsignacion($uid, $idusuario, $asunto, $fecha_envio, $notificar)
    {
        $mail = new PHPMailer(true);
        $pwcorreo = constant('PWCORREO');
        $namecorreo = constant('CORREO');

        $nombreDestinatario = $this->obtenerNombreCompletoDesdeCorreo($idusuario);


        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'mail.iopa.cl';
            $mail->SMTPAuth = true;
            $mail->Username = $namecorreo;
            $mail->Password = $pwcorreo;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            // Codificacion de caracteres
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            //debbug
            $mail->SMTPDebug = 2; // o 3 para más detalle
            $mail->Debugoutput = function ($str, $level) {
                error_log("SMTP DEBUG: $str");
            };


            $mensajeHTML = "
                <body style='background-color: #f4f4f4; padding: 20px; font-family: Arial, sans-serif;'>
                    <div style='max-width: 600px; margin: auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);'>
                        <h2 style='color: #2c3e50; text-align: center;'>📬 Asignación de Ticket</h2>
                        <p>Estimado(a) <strong>$nombreDestinatario</strong>,</p>
                        <p>Se le informa que se ha <strong>asignado un nuevo ticket</strong> a su bandeja de entrada. A continuación, se detallan los datos del requerimiento:</p>
                        
                        <div style='background-color: #f9f9f9; border-left: 4px solid #2980b9; padding: 15px; margin: 20px 0;'>
                            <p style='margin: 5px 0;'><strong>Ticket ID:</strong> #$uid</p>
                            <p style='margin: 5px 0;'><strong>Asunto:</strong> $asunto</p>
                            <!-- <p style='margin: 5px 0;'><strong>Prioridad:</strong> <span style='color: #e74c3c; font-weight: bold;'>Alta</span></p> -->
                            <p style='margin: 5px 0;'><strong>Fecha:</strong> $fecha_envio</p>
                        </div>

                        <p style='color: #555; font-size: 14px; line-height: 1.5;'>
                            Por favor, ingrese al sistema para revisar los detalles técnicos y dar inicio a la gestión del requerimiento dentro de los plazos establecidos.
                        </p>

                        <p style='text-align: center; margin-top: 30px;'>
                            <a href='" . constant('URL') . "' style='background-color: #2c3e50; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;'>Acceder al Sistema de Tickets</a>
                        </p>

                        <hr style='margin: 30px 0; border: 0; border-top: 1px solid #eee;'>
                        
                        <p style='font-size: 12px; color: #000000; text-align: center;'>
                            <strong>❗ Este mensaje fue enviado automáticamente por el sistema de tickets de IOPA.</strong><br>
                            Por favor, no responda a este correo ya que no está monitoreado.
                        </p>
                        <p style='font-size: 12px; color: #999999; text-align: center; margin-top: 8px;'>
                            Iopa System: E-Tickets | Todos los derechos reservados &copy; " . date('Y') . "
                        </p>
                    </div>
                </body>";


            // Remitente y destinatario
            $mail->setFrom('soporte@iopa.cl', 'Soporte IOPA');
            $mail->addAddress($idusuario); //correo para notificar al usuario iopa de que se la asigno un ticket

            // Contenido
            $mail->isHTML(true);
            $mail->Subject = '#' . trim($uid) . ' - Asignación de Ticket';
            $mail->Body = $mensajeHTML;

            $mail->send();

            // --- LLAMADA A LA SEGUNDA FUNCIÓN ANTES DEL RETURN ---
            if ($notificar == 1 && $idusuario !== 'soporte@iopa.cl') {
                $this->enviarCorreoAsignacionUsuarioSolicitante($uid, $asunto);
            }

            return true;

        } catch (Exception $e) {
            error_log("Error al enviar correo: {$mail->ErrorInfo}");
            return false;
        }
    }

    public function enviarCorreoAsignacionUsuarioSolicitante($uid, $asunto)
    {
        try {
            // 1. Obtener datos mediante la query
            $db = $this->db->connect();
            $query = $db->prepare("SELECT
                                        correo_origen as solicitante,
                                        uid as uid,
                                        CASE 
                                            WHEN asignado IS NULL OR asignado = '' THEN 'Proceso de asignación'
                                            ELSE asignado 
                                        END AS asignado,
                                        asunto as asunto
                                    FROM correo 
                                    WHERE uid = :uid");
            $query->execute(['uid' => $uid]);
            $datos = $query->fetch(PDO::FETCH_OBJ);

            if (!$datos)
                return false;

            // 2. Procesar nombres (formato nombre.apellido@iopa.cl)
            $formatearNombre = function ($correo) {
                $nombre_parte = explode('@', $correo)[0];
                $partes = explode('.', $nombre_parte);
                return ucwords(implode(' ', $partes));
            };

            $nombreSolicitante = $formatearNombre($datos->solicitante);
            #$nombreDesarrollador = $formatearNombre($correo_desarrollador);

            // 3. Configurar PHPMailer para el segundo envío
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'mail.iopa.cl';
            $mail->SMTPAuth = true;
            $mail->Username = constant('CORREO');
            $mail->Password = constant('PWCORREO');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;
            $mail->CharSet = 'UTF-8';

            /* <p style='margin: 5px 0;'><strong>Desarrollador Asignado:</strong> $nombreDesarrollador ($correo_desarrollador)</p> */

            $mensajeHTML = "
            <body style='background-color: #f4f4f4; padding: 20px; font-family: Arial, sans-serif;'>
                <div style='max-width: 600px; margin: auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);'>
                    <h2 style='color: #2c3e50; text-align: center;'>📩 Recepción de Ticket</h2>
                    <p>Estimado(a) <strong>$nombreSolicitante</strong>,</p>
                    <p>Le informamos que hemos recibido su solicitud y se ha generado un ticket de seguimiento en nuestra plataforma.</p>
                    
                    <div style='background-color: #f9f9f9; border-left: 4px solid #2980b9; padding: 15px; margin: 20px 0;'>
                        <p style='margin: 5px 0;'><strong>Ticket ID:</strong> #$uid</p>
                        <p style='margin: 5px 0;'><strong>Asunto:</strong> $asunto</p>
                    </div>

                    <p style='color: #555; font-style: italic; font-size: 13px; line-height: 1.5;'>
                        <strong>Nota:</strong> Su solicitud ha sido asignada formalmente a un desarrollador y ya se encuentra en la bandeja del especialista. 
                        El inicio del desarrollo técnico queda sujeto a la cola de trabajo y carga actual del asignado; mientras el proceso continúe, 
                        se le notificará oportunamente si surge alguna duda o una vez que el ticket sea finalizado.
                    </p>

                    <p>Agradecemos su paciencia.</p>

                    <hr style='margin: 30px 0; border: 0; border-top: 1px solid #eee;'>
                    <p style='font-size: 12px; color: #000000; text-align: center;'>
                        <strong>❗ Este es un mensaje automático. Por favor, no responda a este correo.</strong><br>
                        Iopa System: E-Tickets | Todos los derechos reservados &copy; " . date('Y') . "
                    </p>
                </div>
            </body>";

            $mail->setFrom('soporte@iopa.cl', 'Soporte IOPA');
            $mail->addAddress($datos->solicitante);
            $mail->isHTML(true);
            $mail->Subject = '#' . trim($uid) . ' - Recepción de Ticket';
            $mail->Body = $mensajeHTML;

            $mail->send();
            return true;

        } catch (Exception $e) {
            error_log("Error en envío a solicitante: " . $e->getMessage());
            return false;
        }
    }

    public function enviarCorreoFinalizado($uid, $idusuario, $asunto, $fecha_envio, $correo_origen, $comentario)
    {
        $mail = new PHPMailer(true);
        $pwcorreo = constant('PWCORREO');
        $namecorreo = constant('CORREO');
        $uid_trim = trim($uid);
        $nombreDestinatario = $this->obtenerNombreCompletoDesdeCorreo($correo_origen);

        // Recuperar comentario desarrollador desde la BD directamente
        $query = $this->db->connect()->prepare("SELECT comentario_desarrollador FROM correo WHERE uid = '$uid_trim';");
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        $comentarioDesarrollador = isset($result->comentario_desarrollador) && trim($result->comentario_desarrollador) !== ''
            ? trim($result->comentario_desarrollador)
            : 'Sin comentario del responsable.';

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'mail.iopa.cl'; // Cambia esto si usás otro
            $mail->SMTPAuth = true;
            $mail->Username = $namecorreo;
            $mail->Password = $pwcorreo;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // o STARTTLS si tu servidor lo requiere
            $mail->Port = 465;

            // Codificacion de caracteres
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            //debbug
            $mail->SMTPDebug = 2; // o 3 para más detalle
            $mail->Debugoutput = function ($str, $level) {
                error_log("SMTP DEBUG: $str");
            };

            /* Se quita respuesta del desarrollador para el usuario final - Christopher 12/01/2026*/
            /* <tr>
                <td style='padding: 5px; background-color: #ecf0f1; width: 35%;'><strong>Respuesta del responsable:</strong></td>
                <td style='padding: 5px;'>$comentarioDesarrollador</td>
            </tr> */

            $mensajeHTML = "
            <body style='background-color: #f4f4f4; padding: 20px; font-family: Arial, sans-serif;'>
                <div style='max-width: 600px; margin: auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);'>
                    <h2 style='color: #2c3e50; text-align: center;'> Ticket Finalizado</h2>
                    <p>Estimado(a) <strong>$nombreDestinatario</strong>,</p>
                    <p>Le informamos que su solicitud ha sido <strong>resuelta</strong> y el ticket ha sido marcado como <strong>finalizado</strong> exitosamente en nuestro sistema.</p>
                    
                    <div style='background-color: #f9f9f9; border-left: 4px solid #27ae60; padding: 15px; margin: 20px 0;'>
                        <p style='margin: 5px 0;'><strong>ID Ticket:</strong> #$uid</p>
                        <p style='margin: 5px 0;'><strong>Asunto:</strong> $asunto</p>
                        <p style='margin: 5px 0;'><strong>Fecha de creación:</strong> $fecha_envio</p>
                        <p style='margin: 5px 0;'><strong>Respuesta de cierre:</strong> $comentario</p>
                        <p style='margin: 5px 0;'><strong>Cerrado por:</strong> $idusuario</p>
                    </div>

                    <p style='color: #555; font-size: 14px; line-height: 1.5;'>
                        Agradecemos su paciencia. Si requiere asistencia adicional sobre este u otro tema, no dude en generar un nuevo requerimiento.
                    </p>

                    <hr style='margin: 30px 0; border: 0; border-top: 1px solid #eee;'>
                    
                    <p style='font-size: 12px; color: #000000; text-align: center;'>
                        <strong>❗ Este mensaje fue generado automáticamente por el sistema de E-Tickets de IOPA.</strong><br>
                        Por favor, no responda a este correo ya que no es monitoreado.
                    </p>
                    <p style='font-size: 12px; color: #999999; text-align: center; margin-top: 8px;'>
                        IOPA System - E-Tickets &copy; " . date('Y') . "
                    </p>
                </div>
            </body>";
            //

            // Remitente y destinatario
            $mail->setFrom('soporte@iopa.cl', 'Soporte IOPA');
            $mail->addAddress($correo_origen); //correo para enviar la respuesta a la persona involucrada

            // Contenido
            $mail->isHTML(true);
            $mail->Subject = '#' . $uid_trim . ' Finalización de Ticket';
            $mail->Body = $mensajeHTML;

            $mail->send();
            return true;

        } catch (Exception $e) {
            error_log("Error al enviar correo: {$mail->ErrorInfo}");
            return false;
        }
    }

    public function enviarCorreoRealizado($uid, $idusuario, $asunto, $fecha_envio, $correo_origen, $comentario)
    {
        $mail = new PHPMailer(true);

        try {
            // --- SMTP ---
            $pwcorreo = constant('PWCORREO');
            $namecorreo = constant('CORREO');

            $mail->isSMTP();
            $mail->Host = 'mail.iopa.cl';
            $mail->SMTPAuth = true;
            $mail->Username = $namecorreo;
            $mail->Password = $pwcorreo;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->SMTPDebug = 0;

            // --- Buscar ticket en BD ---
            $uid_trim = trim($uid);
            $query = $this->db->connect()->query("SELECT * FROM correo WHERE uid = '$uid_trim' LIMIT 1");
            $result = $query->fetch(PDO::FETCH_ASSOC);


            $comentarioDesarrollador = !empty($result['comentario_desarrollador']) ? trim($result['comentario_desarrollador']) : 'Sin comentario del responsable.';
            $desarrolladorAsignado = !empty($result['asignado']) ? trim($result['asignado']) : 'Sin desarrollador asignado.';

            // --- Determinar destinatario ---
            $programacion = [
                'n2@n2.cl',
                'nstuardo@gmail.com',
                'daniel.navarrete@iopa.cl',
                'christopher.soto@iopa.cl',
                'luis.farias@iopa.cl',
                'dimas.delmoral@iopa.cl',
                'marcos.huenchunir@iopa.cl'
            ];

            $soporteTI = [
                'boris.sanchez@iopa.cl',
                'luis.plaza@iopa.cl',
                'nelson.leiva@iopa.cl'
            ];

            $mail->setFrom('soporte@iopa.cl', 'Soporte IOPA');

            if (in_array($desarrolladorAsignado, $programacion)) {
                # ---------- COMENTADO MIENTRAS CATA ESTA DE VACACIONES ----------
                $mail->addAddress('catalina.henriquez@iopa.cl', 'Catalina Henriquez');
                $nombreDestinatario = 'Catalina Henriquez';
                # ---------- COMENTADO MIENTRAS CATA ESTA DE VACACIONES ----------
                #$mail->addAddress('christopher.soto@iopa.cl', 'Christopher Soto');
                #$nombreDestinatario = 'Christopher Soto';
            } elseif (in_array($desarrolladorAsignado, $soporteTI)) {
                $mail->addAddress('luis.plaza@iopa.cl', 'Luis Plaza');
                $nombreDestinatario = 'Luis Plaza';
            } else {
                $mail->addAddress('christopher.soto@iopa.cl', 'Christopher Soto');
                $nombreDestinatario = 'Christopher Soto';
            }


            // --- Ruta del HTML guardada en BD ---
            $rutaHtml = $result && !empty($result['cuerpo']) ? $result['cuerpo'] : null;
            $innerHtml = "<p style='color:#e74c3c;'>No se encontró contenido para el ticket ($uid_trim).</p>";

            if ($rutaHtml) {
                $fullPath = $_SERVER['DOCUMENT_ROOT'] . $rutaHtml;
                if (file_exists($fullPath)) {
                    $htmlContent = file_get_contents($fullPath);
                    $htmlContent = mb_convert_encoding($htmlContent, 'UTF-8', 'auto');
                    if (preg_match('@<body[^>]*>(.*?)</body>@is', $htmlContent, $m)) {
                        $innerHtml = $m[1];
                    } else {
                        $innerHtml = $htmlContent;
                    }

                    // --- Procesar imágenes dentro del HTML ---
                    $doc = new DOMDocument();
                    libxml_use_internal_errors(true);
                    $doc->loadHTML($htmlContent);
                    libxml_clear_errors();

                    $tags = $doc->getElementsByTagName('img');
                    foreach ($tags as $tag) {
                        $src = $tag->getAttribute('src');
                        if ($src && strpos($src, 'imagenes_embebidas/') !== false) {
                            $basename = basename($src);
                            $cid = md5($basename);

                            // reemplazo en el HTML
                            $innerHtml = str_replace($src, "cid:$cid", $innerHtml);

                            // path físico
                            $imgPath = $_SERVER['DOCUMENT_ROOT'] . '/eticket/public/imagenes_embebidas/' . $basename;
                            if (file_exists($imgPath)) {
                                $mail->addEmbeddedImage($imgPath, $cid, $basename);
                            }
                        }
                    }
                }
            }

            // --- Plantilla final del correo ---
            $mensajeHTML = "
            <html>
            <head>
            <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
            <meta charset='UTF-8'>
            </head>
            <body style='background-color: #f4f4f4; padding: 20px; font-family: Arial, sans-serif;'>
                <div style='max-width: 800px; margin: auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);'>
                    <h2 style='color: #2c3e50; text-align: center;'>Ticket Realizado ☑️</h2>
                    <p>Estimado(a) <strong>$nombreDestinatario</strong>,</p>
                    <p>Se informa que el ticket ha sido marcado como <strong>realizado</strong>. A continuación, se detallan los comentarios y la información del requerimiento:</p>
                    
                    <div style='background-color: #f9f9f9; border-left: 4px solid #6f42c1; padding: 15px; margin: 20px 0;'>
                        <p style='margin: 5px 0;'><strong>ID Ticket:</strong> #" . htmlspecialchars($uid_trim) . "</p>
                        <p style='margin: 5px 0;'><strong>Asunto:</strong> " . htmlspecialchars($asunto) . "</p>
                        <p style='margin: 5px 0;'><strong>Fecha de creación:</strong> " . htmlspecialchars($fecha_envio) . "</p>
                        <p style='margin: 5px 0;'><strong>Comentario del desarrollador:</strong><br><span style='color: #555;'>" . nl2br(htmlspecialchars($comentarioDesarrollador)) . "</span></p>
                        <p style='margin: 5px 0;'><strong>Desarrollador:</strong> " . htmlspecialchars($desarrolladorAsignado) . "</p>
                    </div>

                    <h3 style='color: #2c3e50; border-bottom: 1px solid #eee; padding-bottom: 10px;'>Contenido del Ticket</h3>
                    <div style='margin-top:10px; padding:15px; border:1px solid #e9e9e9; border-radius:6px; background:#fafafa; overflow-x: auto;'>
                        " . $innerHtml . "
                    </div>

                    <hr style='margin: 30px 0; border: 0; border-top: 1px solid #eee;'>
                    
                    <div style='padding:15px; background:#f4f6f7; text-align:center; border-radius:8px;'>
                        <p style='font-size:13px; color:#2c3e50; margin:0;'>
                            <strong>❗ Este mensaje se generó automáticamente.</strong><br>
                            Por favor, <strong style='color:#e74c3c;'>no responder a este correo</strong>.
                        </p>
                        <p style='font-size:12px; color:#95a5a6; margin-top:8px;'>
                            IOPA System - E-Tickets &copy; " . date('Y') . "
                        </p>
                    </div>
                </div>
            </body>
            </html>";

            $mail->isHTML(true);
            $mail->Subject = '#' . $uid_trim . ' Realización de Ticket';
            $mail->Body = $mensajeHTML;
            $mail->AltBody = strip_tags("Ticket #$uid_trim - $asunto\n\n" . $comentarioDesarrollador);

            $mail->send();
            return true;
        } catch (\Exception $e) {
            error_log("Error al enviar correo (enviarCorreoRealizado): " . $e->getMessage());
            return false;
        }
    }

    public function enviarCorreoEnProgresoUsuario($uid, $idusuario, $asunto, $fecha_envio, $correo_origen, $comentario)
    {
        $mail = new PHPMailer(true);

        try {
            // 1. Obtener Área de Soporte dinámicamente
            $uid_trim = trim($uid);
            $areaSoporte = 'Informática'; // Valor por defecto

            $queryArea = $this->db->connect()->query("
                SELECT area 
                FROM usuariosperfil 
                WHERE idusuario = (SELECT asignado FROM correo WHERE uid = '$uid_trim') 
                LIMIT 1
            ");
            $resArea = $queryArea->fetch(PDO::FETCH_ASSOC);

            if ($resArea && !empty($resArea['area'])) {
                $areaSoporte = $resArea['area'];
            }

            // 2. Procesar nombre del solicitante (formato nombre.apellido@iopa.cl)
            $formatearNombre = function ($correo) {
                $nombre_parte = explode('@', $correo)[0];
                $partes = explode('.', $nombre_parte);
                return ucwords(implode(' ', $partes));
            };
            $nombreSolicitante = $formatearNombre($correo_origen);

            // 3. Configuración SMTP
            $mail->isSMTP();
            $mail->Host = 'mail.iopa.cl';
            $mail->SMTPAuth = true;
            $mail->Username = constant('CORREO');
            $mail->Password = constant('PWCORREO');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            // 4. Plantilla HTML con formato "Asignación Usuario Solicitante"
            $mensajeHTML = "
            <body style='background-color: #f4f4f4; padding: 20px; font-family: Arial, sans-serif;'>
                <div style='max-width: 600px; margin: auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);'>
                    <h2 style='color: #2c3e50; text-align: center;'>⚙️ Ticket en Progreso</h2>
                    <p>Estimado(a) <strong>$nombreSolicitante</strong>,</p>
                    <p>Le informamos que su solicitud ha cambiado de estado y actualmente se encuentra <strong>en proceso de atención técnica</strong>.</p>
                    
                    <div style='background-color: #f9f9f9; border-left: 4px solid #f39c12; padding: 15px; margin: 20px 0;'>
                        <p style='margin: 5px 0;'><strong>Ticket ID:</strong> #$uid_trim</p>
                        <p style='margin: 5px 0;'><strong>Asunto:</strong> $asunto</p>
                        <p style='margin: 5px 0;'><strong>Área Responsable:</strong> $areaSoporte</p>
                    </div>

                    <p style='color: #555; font-style: italic; font-size: 13px; line-height: 1.5;'>
                        <strong>Nota:</strong> El personal del área de <strong>$areaSoporte</strong> ya está trabajando en su requerimiento. 
                        Mientras el proceso continúe, el sistema le notificará automáticamente cualquier cambio significativo o 
                        una vez que el ticket haya sido finalizado exitosamente.
                    </p>

                    <p>Agradecemos su paciencia mientras resolvemos su solicitud.</p>

                    <hr style='margin: 30px 0; border: 0; border-top: 1px solid #eee;'>
                    <p style='font-size: 12px; color: #000000; text-align: center;'>
                        <strong>❗ Este es un mensaje automático. Por favor, no responda a este correo.</strong><br>
                        Iopa System: E-Tickets | Todos los derechos reservados &copy; " . date('Y') . "
                    </p>
                </div>
            </body>";

            // 5. Envío
            $mail->setFrom(constant('CORREO'), 'Soporte IOPA');
            $mail->addAddress($correo_origen);
            $mail->isHTML(true);

            // Asunto solicitado terminando en "de ticket"
            $mail->Subject = '#' . $uid_trim . ' - Actualización de Ticket ';

            $mail->Body = $mensajeHTML;
            $mail->AltBody = "Su ticket #$uid_trim ($asunto) está en progreso por el área de $areaSoporte.";

            $mail->send();
            return true;

        } catch (\Exception $e) {
            error_log("Error en enviarCorreoEnProgresoUsuario: " . $e->getMessage());
            return false;
        }
    }

    public function enviarRespuestaEstatica()
    {
        try {
            $mail = new PHPMailer(true);
            $pwcorreo = constant('PWCORREO');
            $namecorreo = constant('CORREO');

            $mail->isSMTP();
            $mail->Host = 'mail.iopa.cl';
            $mail->SMTPAuth = true;
            $mail->Username = $namecorreo;
            $mail->Password = $pwcorreo;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            // Codificación
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            // Remitente
            $mail->setFrom('soporte@iopa.cl', 'soporte');

            // Destinatario principal
            $mail->addAddress('christopher.soto@iopa.cl');

            // CC
            $cc_string = 'luis.farias@iopa.cl';
            $cc_array = explode(',', $cc_string);
            foreach ($cc_array as $cc) {
                $cc = trim($cc);
                if (filter_var($cc, FILTER_VALIDATE_EMAIL)) {
                    $mail->addCC($cc);
                }
            }

            // Encabezados para responder al hilo
            $asunto_original = 'Test de Respuesta en Hilo';
            $in_reply_to = '01a301dbcb58$181bfe90$4853fbb0$';
            $references = '<01a301dbcb58$181bfe90$4853fbb0$@iopa.cl>';

            $mail->addCustomHeader('In-Reply-To', "<$in_reply_to@iopa.cl>");
            $mail->addCustomHeader('References', $references);

            // Asunto de respuesta
            $mail->Subject = 'Re: ' . $asunto_original;

            // Cuerpo del mensaje
            $mail->isHTML(true);
            $mail->Body = '
                <p>Estimado/a,</p>
                <p>RECIBIDO</p>
                <p>Saludos cordiales,<br>Equipo de soporte</p>
            ';

            // Enviar
            if ($mail->send()) {
                //  Guardar en carpeta Sent vía IMAP
                $imapPath = '{mail.iopa.cl:993/imap/ssl}INBOX.Sent';
                $imapStream = imap_open($imapPath, $namecorreo, $pwcorreo);

                if ($imapStream) {
                    // Requiere PHPMailer >= 6.1 para acceder a este método protegido
                    $mime = $mail->getSentMIMEMessage();
                    imap_append($imapStream, $imapPath, $mime);
                    imap_close($imapStream);
                } else {
                    return 'Correo enviado, pero no se pudo guardar en INBOX.Sent: ' . imap_last_error();
                }

                return "Correo enviado correctamente y guardado en carpeta Enviados";
            } else {
                return "Error al enviar: " . $mail->ErrorInfo;
            }

        } catch (Exception $e) {
            return 'Excepción al enviar correo: ' . $e->getMessage();
        }
    }



    public function marcarSpam($uid, $idusuario, $correo_origen)
    {
        try {
            $motivo = "Desactivacion desde el front. UID: #$uid";
            $queryInsert = $this->db->connect()->prepare("INSERT INTO correo_spam (correo_spam, motivo, marcado_por, estado, created_at, updated_at, deleted_at) 
            VALUES (:correo_spam, :motivo, :idusuario, 1, now(), null, null)");
            $queryInsert->bindParam(':correo_spam', $correo_origen);
            $queryInsert->bindParam(':idusuario', $idusuario);
            $queryInsert->bindParam(':motivo', $motivo);


            $insertResult = $queryInsert->execute();

            // Actualizar correos existentes del mismo origen
            $queryUpdate = $this->db->connect()->prepare("
                UPDATE correo 
                SET estado = 0, updated_at = NOW(), deleted_at = NOW()
                WHERE correo_origen = :correo_origen
            ");
            $queryUpdate->bindParam(':correo_origen', $correo_origen);
            $updateResult = $queryUpdate->execute();

            //LOGS
            $db = $this->db->connect();
            $idusuario = trim($idusuario);
            $uid = trim($uid);
            $sqllog = "INSERT INTO logs (uid, usuario, accion, detalle, metodo, modulo, fecha) VALUES ('$uid','$idusuario','Spam','Marcó como spam el ticket #$uid','Botón Spam: Front','Correo',NOW())";
            $querylog = $db->prepare($sqllog);
            $querylog->execute();


            // Verificar que ambas operaciones fueron exitosas
            if ($insertResult && $updateResult) {
                return true;
            } else {
                return false;
            }

        } catch (PDOException $e) {
            error_log('Error al marcar como spam: ' . $e->getMessage());
            return false;
        }
    }

    /* -------------------- OBTENER LISTADO DE USUARIOS PERMITIDOS -------------------- */
    /* public function historial() {
        $query = $this->db->connect()->prepare("SELECT * FROM logs ORDER BY fecha DESC;");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    } */

    /* public function obtenerHistorialPorUid($uid)
    {
        $query = $this->db->connect()->prepare("SELECT * FROM logs WHERE uid = :uid ORDER BY fecha DESC");
        $query->execute(['uid' => $uid]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    } */

    public function historialPorUid($uid)
    {
        $query = $this->db->connect()->prepare("SELECT * FROM logs WHERE uid = :uid ORDER BY fecha DESC");
        $query->execute(['uid' => $uid]);

        return $query->fetchAll(PDO::FETCH_OBJ);
    }


    public function obtenerCorreosRespuestas()
    {
        $query = $this->db->connect()->prepare("SELECT * FROM correo where (multirespuesta = 1 or in_reply_to!='')  and (estado!=0 or deleted_at is null) ORDER BY fecha_envio DESC;");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function enviarRespuestaUsuario($uid, $correo_origen, $correo_destino, $cc, $asunto, $fecha_envio, $references, $texto_respuesta, $message_id, $in_reply_to, $idusuario, $fusion)
    {
        try {
            $mail = new PHPMailer(true);
            $pwcorreo = constant('PWCORREO');
            $namecorreo = constant('CORREO');

            $mail->isSMTP();
            $mail->Host = 'mail.iopa.cl';
            $mail->SMTPAuth = true;
            $mail->Username = $namecorreo;
            $mail->Password = $pwcorreo;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->setFrom('soporte@iopa.cl', 'soporte');

            // ----------- DESTINATARIOS DESDE FUSION -----------
            $fusion_string = trim($fusion);
            $soporte_correo = 'soporte@iopa.cl';

            if (stripos($fusion_string, 'fusión:') === 0) {
                $fusion_string = trim(substr($fusion_string, strlen('fusión:')));
            }

            if (!empty($fusion_string)) {
                $fusion_array = array_map('trim', explode(',', $fusion_string));
                foreach ($fusion_array as $correo) {
                    if (
                        filter_var($correo, FILTER_VALIDATE_EMAIL) &&
                        strtolower($correo) !== strtolower($soporte_correo)
                    ) {
                        $mail->addAddress($correo);
                    }
                }
            }
            // ----------- FIN DESTINATARIOS DESDE FUSION -----------



            // ----------- CC -----------
            $cc_string = $cc ?? '';
            $cc_array = [];

            if (!empty(trim($cc_string))) {
                $cc_array = explode(',', $cc_string);
                foreach ($cc_array as $ccItem) {
                    $ccItem = trim($ccItem);
                    if (
                        filter_var($ccItem, FILTER_VALIDATE_EMAIL) &&
                        strtolower($ccItem) !== strtolower($soporte_correo) // NO agregar soporte
                    ) {
                        $mail->addCC($ccItem);
                    }
                }
            }
            // ----------- FIN CC -----------


            // ----------- REFERENCES -----------
            if (!empty($references) && $references !== 'N/A') {
                $mail->addCustomHeader('References', $references);
            }
            // ----------- FIN REFERENCES -----------

            // ----------- IN_REPLY_TO -----------
            if (!empty($message_id) && $message_id !== 'N/A' && $message_id !== 'No disponible') {
                $mail->addCustomHeader('In-Reply-To', "<$message_id@iopa.cl>");
            }
            // ----------- FIN IN_REPLY_TO -----------

            $mail->Subject = $asunto;
            if ($correo_origen == 'soporte@iopa.cl') {
                $nombre_completo = 'Usuario'; #fix para evitar que cuando el sistema se auto responde a si mismo, este escriba estimado soporte
            } else {
                $nombre_completo = $this->obtenerNombreCompletoDesdeCorreo($correo_origen);
            }
            $mail->isHTML(true);
            $mail->Body = "
            <div style='font-family: Arial, sans-serif; font-size: 14px; color: #333; line-height: 1.6;'>

                <p><strong>Estimado/a " . htmlspecialchars($nombre_completo) . ",</strong></p>

                <p>Gracias por su mensaje. A continuación, le entregamos la respuesta de nuestro equipo:</p>

                <div style='background-color: #e6f3ff; padding: 15px; border-left: 4px solid #007BFF; border-radius: 5px; margin-bottom: 20px; color: #2c3e50;'>
                    " . $texto_respuesta . "
                </div>

                <p>Si desea complementar esta solicitud, puede responder a este mismo correo.</p>

                <hr style='border: none; border-top: 1px solid #ccc; margin: 30px 0;'>

                <p style='font-size: 13px; color: #666;'><strong>Referencia del ticket:</strong></p>
                <ul style='font-size: 13px; color: #666; padding-left: 20px;'>
                    <li><strong>Asunto:</strong> " . htmlspecialchars($asunto) . "</li>
                    <li><strong>Ticket N.º:</strong> $uid</li>
                    <li><strong>Fecha:</strong> $fecha_envio</li>
                </ul>

                <p style='font-size: 13px; color: #999;'>Atentamente,<br>
                <strong>$idusuario</strong><br>
                Equipo de Soporte IOPA</p>
            </div>
        ";


            if ($mail->send()) {
                //  Guardar en carpeta Sent vía IMAP
                // Si el único destinatario es soporte, NO guardar en Sent
                $destinatarios = [$correo_origen];
                $cc_limpios = $cc_array;

                $todos_destinatarios = array_merge($destinatarios, $cc_limpios);
                $todos_destinatarios_limpios = array_filter(array_map('trim', $todos_destinatarios));

                $soporte_correo = 'soporte@iopa.cl';

                if (count($todos_destinatarios_limpios) === 1 && strtolower($todos_destinatarios_limpios[0]) === strtolower($soporte_correo)) {
                    // Es autorespuesta, guardar en Sent igualmente para que se sincronice
                    $imapPath = '{mail.iopa.cl:993/imap/ssl}INBOX.Sent';
                    $imapStream = imap_open($imapPath, $namecorreo, $pwcorreo);
                    if ($imapStream) {
                        $mime = $mail->getSentMIMEMessage();
                        imap_append($imapStream, $imapPath, $mime);
                        imap_close($imapStream);
                    }
                    return true;
                }

                // Si hay uno o más destinatarios y uno de ellos es soporte, quitarlo antes de guardar en Sent
                $destinatarios_finales = array_filter($todos_destinatarios_limpios, function ($email) use ($soporte_correo) {
                    return strtolower($email) !== strtolower($soporte_correo);
                });

                // Crear una copia del mensaje con los destinatarios sin soporte para guardarla
                $mailParaGuardar = clone $mail;

                // Limpiar destinatarios originales
                $mailParaGuardar->clearAddresses();
                $mailParaGuardar->clearCCs();

                // Volver a agregar destinatarios sin soporte
                foreach ($destinatarios_finales as $email) {
                    $mailParaGuardar->addAddress($email);
                }

                // Guardar mensaje en Sent
                $imapPath = '{mail.iopa.cl:993/imap/ssl}INBOX.Sent';
                $imapStream = imap_open($imapPath, $namecorreo, $pwcorreo);

                if ($imapStream) {
                    $mime = $mailParaGuardar->getSentMIMEMessage();
                    imap_append($imapStream, $imapPath, $mime);
                    imap_close($imapStream);
                } else {
                    return 'Correo enviado, pero no se pudo guardar en INBOX.Sent: ' . imap_last_error();
                }

                return true;
            } else {
                return "Error al enviar: " . $mail->ErrorInfo;
            }

        } catch (Exception $e) {
            return 'Excepción al enviar correo: ' . $e->getMessage();
        }
    }


    public function obtenerNombreCompletoDesdeCorreo($correo)
    {
        $parte_usuario = explode('@', $correo)[0]; // Ej: christopher.soto
        $fragmentos = preg_split('/[\.\-_]/', $parte_usuario); // ['christopher', 'soto']

        $nombre = isset($fragmentos[0]) ? ucfirst(strtolower($fragmentos[0])) : '';
        $apellido = isset($fragmentos[1]) ? ucfirst(strtolower($fragmentos[1])) : '';

        return trim("$nombre $apellido");
    }

    public function correoExiste($correo)
    {
        // Buscar en tabla usuarios
        $query = $this->db->connect()->prepare("SELECT * FROM usuarios WHERE email = :correo LIMIT 1");
        $query->execute(['correo' => $correo]);
        $resultadoUsuarios = $query->fetch(PDO::FETCH_OBJ);

        // Si existe en usuarios, retornar true
        if ($resultadoUsuarios) {
            return true;
        }

        // Buscar en tabla usuariosperfil
        $query = $this->db->connect()->prepare("SELECT * FROM usuariosperfil WHERE idusuario = :correo LIMIT 1");
        $query->execute(['correo' => $correo]);
        $resultadoPerfil = $query->fetch(PDO::FETCH_OBJ);

        // Si existe en usuariosperfil, retornar true
        if ($resultadoPerfil) {
            return true;
        }

        // No existe en ninguna tabla
        return false;
    }

    // Agregar nuevo usuario
    public function agregarUsuario($datos)
    {
        try {
            $pdo = $this->db->connect();

            // Iniciar transacción
            $pdo->beginTransaction();

            // 1. INSERTAR EN TABLA USUARIOS
            $query = $pdo->prepare("INSERT INTO usuarios (email, pass, foto) VALUES (:email, :pass, NULL)");
            $query->execute([
                'email' => $datos['correo'],
                'pass' => $datos['contrasena']
            ]);

            // 2. INSERTAR EN TABLA USUARIOSPERFIL (4 registros)
            $menus = [
                ['menu' => 'tablas', 'principal' => ''],
                ['menu' => 'usuarios', 'principal' => 'Tablas'],
                ['menu' => 'medicos', 'principal' => 'Tablas'],
                ['menu' => 'correo', 'principal' => 'Tablas']
            ];

            $query = $pdo->prepare("INSERT INTO usuariosperfil (idusuario, menu, habilitado, principal, permiso, area) 
                                   VALUES (:idusuario, :menu, 'S', :principal, 'admin', :area)");

            foreach ($menus as $item) {
                $query->execute([
                    'idusuario' => $datos['correo'],
                    'menu' => $item['menu'],
                    'principal' => $item['principal'],
                    'area' => $datos['area']
                ]);
            }

            // Confirmar transacción
            $pdo->commit();
            return true;

        } catch (Exception $e) {
            // Revertir cambios si hay error
            $pdo->rollBack();
            return false;
        }
    }

    public function eliminarUsuario($id_usuario)
    {
        try {
            $pdo = $this->db->connect();

            // Iniciar transacción
            $pdo->beginTransaction();

            // 1. Eliminar de usuariosperfil
            $query = $pdo->prepare("DELETE FROM usuariosperfil WHERE idusuario = :idusuario");
            $query->execute([
                'idusuario' => $id_usuario
            ]);

            // 2. Eliminar de usuarios
            $query = $pdo->prepare("DELETE FROM usuarios WHERE email = :email");
            $query->execute([
                'email' => $id_usuario
            ]);

            // Confirmar
            $pdo->commit();
            return true;

        } catch (Exception $e) {
            $pdo->rollBack();
            return false;
        }
    }
    
    public function getTicketsFiltrados($estadoID, $permiso, $usuario)
    {
        try {
            $usuariotrim = trim($usuario);
            $permisotrim = trim($permiso);

            // BASE de la query
            $query = "SELECT 
                    uid as uid, 
                    correo_origen as usuario_solicitante,
                    asunto as asunto,
                    fecha_envio as fecha_envio,
                    asignado as asignado,
                    respuesta_correo as comentario_finalizacion,
                    comentario_desarrollador as comentario_desarrollador,
                    estado as estado
                FROM correo
                WHERE estado = :estadoID
                AND deleted_at IS NULL";

            // Parámetros
            $params = [':estadoID' => $estadoID];

            // Si es admin, mostrar multirespuesta = 0 solo si estado es 1
            if ($permisotrim === 'admin') {
                if ($estadoID == 1) {
                    $query .= " AND multirespuesta = 0";
                }
            } else {
                // Si NO es admin, filtrar por usuario Y multirespuesta = 0 si estado es 1
                $query .= " AND (
                    correo_origen = :usuario1
                    OR correo_origen IN (
                        SELECT usuario_asociado
                        FROM usuariosperfil_asignaciones
                        WHERE usuario_principal = :usuario2
                    )
                )";

                $params[':usuario1'] = $usuariotrim;
                $params[':usuario2'] = $usuariotrim;

                if ($estadoID == 1) {
                    $query .= " AND multirespuesta = 0";
                }
            }

            $query .= " ORDER BY fecha_envio DESC";

            $stmt = $this->db->connect()->prepare($query);
            $result = $stmt->execute($params);

            if (!$result) {
                throw new Exception("Error ejecutando query: " . json_encode($stmt->errorInfo()));
            }

            $data = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $data;

        } catch (PDOException $e) {
            error_log("PDOException en getTicketsFiltrados: " . $e->getMessage());
            throw new Exception("PDOException: " . $e->getMessage());
        } catch (Exception $e) {
            error_log("Exception en getTicketsFiltrados: " . $e->getMessage());
            throw new Exception("Exception: " . $e->getMessage());
        }
    }

}
?>