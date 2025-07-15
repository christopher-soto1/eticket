<?php
include_once 'models/medicos.php';
class MedicosModel extends Model
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
            $query = $this->db->connect()->query("SELECT * FROM usuariosperfil WHERE idusuario='" . $idu . "' AND  habilitado='S'");
            while ($row = $query->fetch()) {
                $item = new Usuariosperfil();
                $item->id = $row['id'];
                $item->idusuario = $row['idusuario'];
                $item->menu = $row['menu'];
                $item->habilitado = $row['habilitado'];
                $item->principal = $row['principal'];
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
            $query = $this->db->connect()->query("SELECT * FROM medicos");
            while ($row = $query->fetch()) {
                $item = new Medicos();
                $item->id = $row['id'];
                $item->medico = $row['medico'];
                $item->foto = $row['foto'];
                array_push($items, $item);
            }
            return $items;
        } catch (PDOException $e) {
            return [];
        }
    }
    public function getregistros($s)
    {
        try {
            if ($s == null) {
                $query = $this->db->connect()->query("SELECT count(*) as son FROM medicos");
            } else {
                $query = $this->db->connect()->query("SELECT count(*) as son FROM medicos WHERE id=" . $s);
            }
            while ($row = $query->fetch()) {
                $cuantos = $row['son'];
            }
            return $cuantos;
        } catch (PDOException $e) {
            return [];
        }
    }
    public function getpag($iniciar, $autoporpag, $s)
    {
        $items = [];
        try {
            if ($s == null) {
                $query = $this->db->connect()->query("SELECT * FROM medicos order by id DESC LIMIT $iniciar,$autoporpag");
            } else {
                $query = $this->db->connect()->query("SELECT * FROM medicos WHERE id=" . $s . " order by id DESC LIMIT $iniciar,$autoporpag");
            }
            while ($row = $query->fetch()) {
                $item = new Medicos();
                $item->id = $row['id'];
                $item->medico = $row['medico'];
                $item->foto = $row['foto'];
                array_push($items, $item);
            }
            return $items;
        } catch (PDOException $e) {
            return [];
        }
    }
    public function getById($id)
    {
        $item = new Medicos();
        $query = $this->db->connect()->prepare("SELECT * FROM medicos WHERE id=:id");
        try {
            $query->execute(['id' => $id]);
            while ($row = $query->fetch()) {
                $item->id = $row['id'];
                $item->medico = $row['medico'];
                $item->foto = $row['foto'];
            }
            return $item;
        } catch (PDOException $e) {
            return null;
        }
    }
    public function update($item)
    {
        $query = $this->db->connect()->prepare("UPDATE medicos SET medico=:medico,foto=:foto WHERE id=:id");
        try {
            $query->execute(['id' => $item['id'], 'medico' => $item['medico'], 'foto' => $item['foto']]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    public function insert($datos)
    {
        try {
            $query = $this->db->connect()->prepare('INSERT INTO medicos(id,medico,foto) VALUES  (:id,:medico,:foto)');
            $query->execute(['id' => $datos['id'], 'medico' => $datos['medico'], 'foto' => $datos['foto']]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    public function insertcsv($datos)
    {
        try {
            $query = $this->db->connect()->prepare('INSERT INTO medicos(id,medico,foto) VALUES  (:id,:medico,:foto)');
            $query->execute(['id' => $datos['id'], 'medico' => $datos['medico'], 'foto' => $datos['foto']]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    public function delete($id)
    {
        $query = $this->db->connect()->prepare("DELETE FROM medicos WHERE id=:id");
        try {
            $query->execute([
                'id' => $id,
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>