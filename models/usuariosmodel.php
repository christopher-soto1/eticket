<?php
include_once 'models/usuarios.php';
class UsuariosModel extends Model
{
	  public function __construct(){
        parent::__construct();
    }
 public function getmenu($idu){
        $items = [];
        include_once 'models/usuariosperfil.php';
        try{
            $query=$this->db->connect()->query("SELECT * FROM usuariosperfil WHERE idusuario='".$idu."' AND  habilitado='S'");
            while($row=$query->fetch()){
                 $item = new Usuariosperfil();
                 $item->id            = $row['id'];
                 $item->idusuario     = $row['idusuario'];
                 $item->menu          = $row['menu'];
                 $item->habilitado    = $row['habilitado'];
                 $item->principal    = $row['principal'];
                 $item->permisos    = $row['permisos'];
                 array_push($items,$item);
                 }
          return $items;
       }catch(PDOException $e){
           return [];
       }
   }
    public function get(){
        $items = [];
        try{
            $query=$this->db->connect()->query("SELECT * FROM usuarios");
            while($row=$query->fetch()){
                 $item = new Usuarios();
                 $item->id     = $row['id'];
                 $item->email     = $row['email'];
                 $item->pass     = $row['pass'];
                 $item->foto     = $row['foto'];
                 array_push($items,$item);
                  }
           return $items;
        }catch(PDOException $e){
            return [];
        }
    }
    public function getregistros($s){
         try{
    if ($s==null){ 
                  $query=$this->db->connect()->query("SELECT count(*) as son FROM usuarios");
              }else{
                  $query=$this->db->connect()->query("SELECT count(*) as son FROM usuarios WHERE id=".$s);                               }
             while($row=$query->fetch()){
                  $cuantos    = $row['son'];
                   }
            return $cuantos;
         }catch(PDOException $e){
             return [];
         }
     }
    public function getpag($iniciar, $autoporpag, $s)
    {
        $items = [];
        try {
            if ($s == null) {
                $query = $this->db->connect()->query("SELECT * FROM usuarios order by id DESC LIMIT $iniciar,$autoporpag");
            } else {
                $query = $this->db->connect()->query("SELECT * FROM usuarios WHERE id=" . $s . " order by id DESC LIMIT $iniciar,$autoporpag");
            }
            while ($row = $query->fetch()) {
                $item = new Usuarios();
                $item->id = $row['id'];
                $item->email = $row['email'];
                $item->pass = $row['pass'];
                $item->foto = $row['foto'];
                array_push($items, $item);
            }
            return $items;
        } catch (PDOException $e) {
            return [];
        }
    }
    public function getById($id){
        $item=new Usuarios();
        $query=$this->db->connect()->prepare("SELECT * FROM usuarios WHERE id=:id");
        try{
           $query->execute(['id'=>$id]);
            while($row=$query->fetch()){
                 $item->id     = $row['id'];
                 $item->email     = $row['email'];
                 $item->pass     = $row['pass'];
                 $item->foto     = $row['foto'];
                  }
           return $item;
        }catch(PDOException $e){
           return null;
        }
    }
    public function update($item){
$query=$this->db->connect()->prepare("UPDATE usuarios SET email=:email,pass=:pass,foto=:foto WHERE id=:id");
    try{
       $query->execute(['id'=>$item['id'],'email'=>$item['email'],'pass'=>$item['pass'],'foto'=>$item['foto']]);
       return true;
    }catch(PDOException $e){
       return false;
    }
    }
public function insert($datos){
       try{
         $query=$this->db->connect()->prepare('INSERT INTO usuarios(id,email,pass,foto) VALUES  (:id,:email,:pass,:foto)');
          $query->execute(['id'=>$datos['id'],'email'=>$datos['email'],'pass'=>md5(trim($datos['pass'])),'foto'=>$datos['foto']]);
  /*llevar datos a perfil*/
         $query2=$this->db->connect()->prepare("SELECT * FROM menu");
          $query2->execute();
           while($row2=$query2->fetch()){
                $id       = $row2['id'];
                $menu     = $row2['menu'];
                $principal    = $row2['principal'];
                $query3=$this->db->connect()->prepare('INSERT INTO usuariosperfil(idusuario,habilitado,menu,principal) VALUES  ("'.$datos['email'].'","S","'.$menu.'","'.$principal.'")');
                $query3->execute();
              }
         /*fin llevar datos a perfil*/
           return true;
       }catch(PDOException $e){
          return false;
      }
    } 
public function insertcsv($datos){
       try{
         $query=$this->db->connect()->prepare('INSERT INTO usuarios(id,email,pass,foto) VALUES  (:id,:email,:pass,:foto)');
          $query->execute(['id'=>$datos['id'],'email'=>$datos['email'],'pass'=>$datos['pass'],'foto'=>$datos['foto']]);
           return true;
       }catch(PDOException $e){
          return false;
      }
    } 
    public function delete($id){
$query=$this->db->connect()->prepare("DELETE FROM usuarios WHERE id=:id");
    try{
       $query->execute([
         'id'=>$id,
       ]);
       return true;
    }catch(PDOException $e){
       return false;
    }
    }
}
?>
