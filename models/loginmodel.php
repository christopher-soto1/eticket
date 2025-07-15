<?php
class LoginModel extends Model
{
	  public function __construct(){
        parent::__construct();
    }
    public function verificar($us,$pa){
        $query=$this->db->connect()->prepare('SELECT * FROM usuarios WHERE email=:us and pass=:pa');
        try{
          $query->execute(['us'=>$us,'pa'=>$pa]);
           while($row=$query->fetch()){
           return true;
                 }
           return false;
        }catch(PDOException $e){
          return false;
       }
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
                 array_push($items,$item);
                 }
          return $items;
       }catch(PDOException $e){
           return [];
       }
   } 
}
?>
