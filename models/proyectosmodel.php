<?php
include_once 'models/proyectos.php';
require 'vendor/autoload.php';
 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class ProyectosModel extends Model{

    public function __construct(){
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
                $item->menu = $row['menu'];
                $item->habilitado = $row['habilitado'];
                $item->principal = $row['principal'];
                $item->permiso = $row['permiso'];
                array_push($items, $item);
            }
            return $items;
        } 
        catch (PDOException $e) {
            return [];
        }
    }

    #OBTIENE TODOS LOS REGISTROS DE PROYECTOS
    public function getregistros($id, $nombre_proyecto, $fecha_creacion, $responsable, $estado)
    {
        $items = [];
        try {
            // Construye un query base
            $sql = "SELECT id, nombre_proyecto, descripcion, fecha_creacion, ruta_directorio_global, responsable, estado 
                    FROM proyecto WHERE 1=1";

            // Aplica filtros si están definidos
            if ($id) $sql .= " AND id = '$id'";
            if ($nombre_proyecto) $sql .= " AND nombre_proyecto LIKE '%$nombre_proyecto%'";
            if ($fecha_creacion) $sql .= " AND fecha_creacion = '$fecha_creacion'";
            if ($responsable) $sql .= " AND responsable LIKE '%$responsable%'";
            if ($estado) $sql .= " AND estado = '$estado'";

            $query = $this->db->connect()->query($sql);

            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $item = new Proyectos();
                $item->id = $row['id'];
                $item->nombre_proyecto = $row['nombre_proyecto'];
                $item->descripcion = $row['descripcion'];
                $item->fecha_creacion = $row['fecha_creacion'];
                $item->ruta_directorio_global = $row['ruta_directorio_global'];
                $item->responsable = $row['responsable'];
                $item->estado = $row['estado'];
                $items[] = $item;
            }
            return $items;
        } catch (PDOException $e) {
            error_log('Error en getregistros: ' . $e->getMessage());
            return [];
        }
    }

    #VALIDA SI HAY DOCUMENTOS DENTRO DE LAS CARPETAS DE LOS PROYECTOS
    public function verificarArchivosProyecto($idProyecto)
    {
        $rutaBase = "C:/wamp/www/eticket/public/proyectos/";
        $rutaProyecto = $rutaBase . $idProyecto;

        if (file_exists($rutaProyecto) && is_dir($rutaProyecto)) {
            $archivos = array_diff(scandir($rutaProyecto), ['.', '..']);
            return !empty($archivos); // true si tiene archivos
        }

        return false; // no existe o está vacío
    }



    #INSERTA UN PROYECTO NUEVO
    public function insertarProyecto($nombre, $descripcion, $responsable, $estado, $fecha)
    {
        try {
            $pdo = $this->db->connect();

            $query = $pdo->prepare("
                INSERT INTO proyecto (nombre_proyecto, descripcion, responsable, estado, fecha_creacion)
                VALUES (:nombre, :descripcion, :responsable, :estado, :fecha)
            ");

            $query->execute([
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'responsable' => $responsable,
                'estado' => $estado,
                'fecha' => $fecha
            ]);

            $id = $pdo->lastInsertId();
            error_log("🟢 ID insertado: " . $id);
            return $id;

        } catch (PDOException $e) {
            error_log("❌ Error insertarProyecto: " . $e->getMessage());
            return false;
        }
    }


    #INSERTA RUTA DEL PROYECTO RECIEN CREADO EN LA TABLA PROYECTO
    public function actualizarRutaProyecto($idProyecto, $ruta)
    {
        try{
            $pdo = $this->db->connect();
            $query = $pdo->prepare("UPDATE proyecto SET ruta_directorio_global = :ruta WHERE id = :id");
            $query->execute([
                        'ruta' => $ruta,
                        'id' => $idProyecto
                    ]);

        return true;
        }
        catch (PDOException $e) {
            error_log("❌ Error al actualizar ruta del proyecto: " . $e->getMessage());
            return false;
        }
        
    }


    #ACTUALIZA EL PROYECTO DESDE EL FRONT
    public function actualizarProyecto($id, $nombre, $descripcion, $responsable, $estado, $fecha)
    {
        try {
            $query = $this->db->connect()->prepare("
                UPDATE proyecto 
                SET 
                    nombre_proyecto = :nombre_proyecto,
                    descripcion = :descripcion,
                    fecha_creacion = :fecha_creacion,
                    responsable = :responsable,
                    estado = :estado
                WHERE id = :id
            ");

            $query->execute([
                'id' => $id,
                'nombre_proyecto' => $nombre,
                'descripcion' => $descripcion,
                'fecha_creacion' => $fecha,
                'responsable' => $responsable,
                'estado' => $estado
            ]);

            return true;

        } catch (PDOException $e) {
            error_log('❌ Error al actualizar proyecto: ' . $e->getMessage());
            return false;
        }
    }

    #INSERTAR ARCHIVO EN LA TABLA 
    public function insertarRutaArchivoProyecto($idProyecto, $descripcion, $rutaArchivo)
    {
        try {
            $query = $this->db->connect()->prepare("
                INSERT INTO ruta_archivo_proyecto (id_proyecto, descripcion, ruta_archivo)
                VALUES (:id_proyecto, :descripcion, :ruta_archivo)
            ");

            $query->execute([
                'id_proyecto' => $idProyecto,
                'descripcion' => $descripcion,
                'ruta_archivo' => $rutaArchivo
            ]);

            return true;
        } catch (PDOException $e) {
            error_log('❌ Error al insertar ruta de archivo: ' . $e->getMessage());
            return false;
        }
    }

    #VISUALIZAR ARCHIVO EN EL FRONT
    public function obtenerArchivosPorProyecto($idProyecto)
    {
        try {
            $stmt = $this->db->connect()->prepare("
                SELECT id, id_proyecto, descripcion, ruta_archivo, fecha_registro
                FROM ruta_archivo_proyecto
                WHERE id_proyecto = :id_proyecto
                ORDER BY fecha_registro DESC
            ");
            $stmt->execute(['id_proyecto' => $idProyecto]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerArchivosPorProyecto: " . $e->getMessage());
            return [];
        }
    }

    #VISUALIZAR ARCHIVO EN EL FRONT
    public function obtenerArchivoPorId($id)
    {
        try {
            $stmt = $this->db->connect()->prepare("
                SELECT * FROM ruta_archivo_proyecto WHERE id = :id
            ");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerArchivoPorId: " . $e->getMessage());
            return false;
        }
    }

    #VISUALIZAR ARCHIVO EN EL FRONT
    public function eliminarArchivoPorId($idArchivo)
    {
        try {
            $query = $this->db->connect()->prepare("DELETE FROM ruta_archivo_proyecto WHERE id = :id");
            return $query->execute(['id' => $idArchivo]);
        } catch (PDOException $e) {
            error_log('❌ Error al eliminar archivo: ' . $e->getMessage());
            return false;
        }
    }








    
}
?>