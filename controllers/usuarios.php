<?php
session_start();
class Usuarios extends Controller{

function __construct()
	{
		parent::__construct();
			$this->view->usuarios=[];
                if (!isset($_SESSION['usuario'])){
                echo "Acceso Negado";
                  session_destroy();    
                $location=constant('URL');
                header("Location: " . $location);
                exit;
                }else{
            }
	}
function render(){
   $usuarios=$this->model->get();
   $this->view->usuarios=$usuarios;
   $this->view->render('usuarios/index');
   }
   function verPaginacion($param = null)
   {
      $id = $param[0];
      $autorizacionporpagina = 5;
      $totalautorizaciones = $this->model->getregistros(null);
      $paginas = $totalautorizaciones / $autorizacionporpagina;
      $iniciar = ($id - 1) * $autorizacionporpagina;
      $usuarios = $this->model->getpag($iniciar, $autorizacionporpagina, null);
      $this->view->usuarios = $usuarios;
      $this->view->mensaje = 'son' . $totalautorizaciones;
      $this->view->paginas = $paginas;
      $this->view->paginaactual = $id;
      $this->view->usuario = $_SESSION['usuario'];
      /*Pasar sus Permisos*/
      $permisos = $this->model->getmenu($_SESSION['usuario']);
      $this->view->usuariosperfil = $permisos;
      /*fin*/

      $this->view->render('usuarios/index');
   }
   function verPaginacionsearch($param = null)
   {
      $id = $param[0];
      $txtbuscar = $_POST['txtbuscar'];
      $autorizacionporpagina = 5;
      $totalautorizaciones = $this->model->getregistros($txtbuscar);
      $paginas = $totalautorizaciones / $autorizacionporpagina;
      $iniciar = ($id - 1) * $autorizacionporpagina;
      $usuarios = $this->model->getpag($iniciar, $autorizacionporpagina, $txtbuscar);
      $this->view->usuarios = $usuarios;
      $this->view->mensaje = 'son' . $totalautorizaciones;
      $this->view->paginas = $paginas;
      $this->view->paginaactual = $id;
      /*Pasar sus Permisos*/
      $permisos = $this->model->getmenu($_SESSION['usuario']);
      $this->view->usuariosperfil = $permisos;
      /*fin*/
      $this->view->render('usuarios/index');
   }
function verUsuarios($param=null){
       $id = $param[0];
       $usuarios = $this->model->getById($id);
       $this->view->usuarios=$usuarios;
       $this->view->mensaje='';
       	/*Pasar sus Permisos*/
          	$permisos=$this->model->getmenu($_SESSION['usuario']); 	
         $this->view->usuariosperfil=$permisos;
          /*fin*/
       $this->view->render('usuarios/detalle');
    }
function nuevoUsuarios($param=null){
       	/*Pasar sus Permisos*/
          	$permisos=$this->model->getmenu($_SESSION['usuario']); 	
         $this->view->usuariosperfil=$permisos;
          /*fin*/
       $this->view->render('usuarios/nuevo');
    }
function importarUsuarios($param=null){
       $this->view->render('usuarios/importar');
    }
function imguploadUsuarios(){
         $target_dir = $_SERVER['DOCUMENT_ROOT'] ."/irebsol/public/uploads/";
         $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
         $uploadOk = 1;
         $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
         // Check if image file is a actual image or fake image
         if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
               echo "File is an image - " . $check["mime"] . ".";
               $uploadOk = 1;
               } else {
               echo "File is not an image.";
               $uploadOk = 0;
               }
            }
            // Check if file already exists
            if (file_exists($target_file)) {
               echo "Sorry, file already exists.";
              $uploadOk = 0;
               }
            // Check file size
            if ($_FILES["fileToUpload"]["size"] > 500000) {
               echo "Sorry, your file is too large.";
               $uploadOk = 0;
               }
              // Allow certain file formats
              if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
              && $imageFileType != "gif" ) {
              echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
              $uploadOk = 0;
              }
            if ($uploadOk == 0) {
               echo "Sorry, your file was not uploaded.";
               // if everything is ok, try to upload file
               } else {
                 if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                     echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
                     } else {
                     echo "Sorry, there was an error uploading your file.";
                }
                }
       }
function xlsuploadUsuarios(){
   $target_dir = $_SERVER['DOCUMENT_ROOT'] ."/irebsol/public/uploads/";
         $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
         $uploadOk = 1;
         $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
         // Check if image file is a actual image or fake image
            // Check if file already exists
            if (file_exists($target_file)) {
               echo "Sorry, file already exists.";
              $uploadOk = 0;
               }
            // Check file size
            if ($_FILES["fileToUpload"]["size"] > 5000000) {
               echo "Sorry, your file is too large.";
               $uploadOk = 0;
               }
              // Allow certain file formats
              if($imageFileType != "xls" && $imageFileType != "xlsx" ) {
              echo "Sorry, only xls & xlsx files are allowed.";
              $uploadOk = 0;
              }
             // Check if  is set to 0 by an error
            if ($uploadOk == 0) {
               echo "Sorry, your file was not uploaded.";
               // if everything is ok, try to upload file
               } else {
                 if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                     echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
                     } else {
                     echo "Sorry, there was an error uploading your file.";
                }
                }
       }
function csvuploadUsuarios(){
   $target_dir = $_SERVER['DOCUMENT_ROOT'] ."/irebsol/public/uploads/";
         $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
         $uploadOk = 1;
         $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
         // Check if image file is a actual image or fake image
            // Check if file already exists
            if (file_exists($target_file)) {
               echo "Sorry, file already exists.";
              $uploadOk = 0;
               }
            // Check file size
            if ($_FILES["fileToUpload"]["size"] > 5000000) {
               echo "Sorry, your file is too large.";
               $uploadOk = 0;
               }
              // Allow certain file formats
              if($imageFileType != "csv" ) {
              echo "Sorry, only csv files are allowed.";
              $uploadOk = 0;
              }
             // Check if  is set to 0 by an error
            if ($uploadOk == 0) {
               echo "Sorry, your file was not uploaded.";
               // if everything is ok, try to upload file
               } else {
                 if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                   //  echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
                       //leer datos
                       $linea = 0;
                       $linea2 =0 ;
                       $archivo = $target_dir .basename( $_FILES["fileToUpload"]["name"]);
                       $archivo = fopen($archivo, "r");
                       while (($datos = fgetcsv($archivo, ",")) == true) 
                             {
                             $num = count($datos);
                             $linea++;
                             $pos=0;
                             for ($columna = 0; $columna < $num; $columna++) 
                                 {
                                 if ($pos==0){ 
                                     $c1=$datos[$columna] ;
                                    }
                                if ($pos==1){ 
                                     $c2=$datos[$columna] ;
                                     $pos=0;
                                     //insertar datos
                                     if ($this->model->insertcsv([ 'id'=>$c1, 'nacionalidad'=>$c2])){
                                        $mensaje="Nuevo Nacionalidades Creado";
 		                               }
                                     //fin insertar datos
                               }else{
                                $pos++;    
                                 }
                               }
                            }
                            //Cerramos el archivo
                            fclose($archivo);
                       	$nacionalidades=$this->model->get(); 	
                        $this->view->nacionalidades=$nacionalidades;
     	                $this->view->render('nacionalidades/index');
                     } else {
                     echo "Sorry, there was an error uploading your file.";
                }
                }
       }
function actualizarUsuarios(){
   $id=$_POST['id'];
   $email=$_POST['email'];
   $pass=$_POST['pass'];
   $foto=basename($_FILES["foto"]["name"]);
   $target_dir = $_SERVER['DOCUMENT_ROOT'] ."/afp/public/uploads/";
   $target_file = $target_dir . basename($_FILES["foto"]["name"]);
   move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);
   if ($this->model->update(['id'=>$id,'email'=>$email,'pass'=>$pass,'foto'=>$foto])) {
      $usuarios=new Usuarios();
      $usuarios->id=$id;
      $usuarios->email=$email;
      $usuarios->pass=$pass;
      $usuarios->foto=$foto;
      $this->view->usuarios=$usuarios;
      $this->view->mensaje='usuarios Actualizado Correctamente';
   }else{
      $this->view->mensaje='No se puedo Actualizar';
   }
   	$usuarios=$this->model->get(); 	
       $this->view->usuarios=$usuarios;
       	/*Pasar sus Permisos*/
          	$permisos=$this->model->getmenu($_SESSION['usuario']); 	
         $this->view->usuariosperfil=$permisos;
          /*fin*/
       $this->view->render('usuarios/index');
   }
function crearUsuarios(){
   $id=$_POST['id'];
   $email=$_POST['email'];
   $pass=$_POST['pass'];
   $foto=basename($_FILES["foto"]["name"]);
   $target_dir = $_SERVER['DOCUMENT_ROOT'] ."/afp/public/uploads/";
   $target_file = $target_dir . basename($_FILES["foto"]["name"]);
   move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);
 	$mensaje="";
 		if ($this->model->insert([ 'id'=>$id, 'email'=>$email, 'pass'=>$pass, 'foto'=>$foto])){
             $mensaje="Nuevo Usuarios Creado";
 		}else{
             $mensaje="usuarios ya Existe";
 		}
       $this->view->mensaje = $mensaje;	
       	/*Pasar sus Permisos*/
          	$permisos=$this->model->getmenu($_SESSION['usuario']); 	
         $this->view->usuariosperfil=$permisos;
          /*fin*/
       $this->render('usuarios/index');	
   }
function eliminarUsuarios($param = null){
   $id=$param[0];
     if ($this->model->delete($id)){
      $this->view->mensaje='usuarios Eliminado Correctamente';
   }else{
      $this->view->mensaje='No se puedo Eliminado';
   }
   $usuarios=$this->model->get();   
    $this->view->usuarios=$usuarios;
       	/*Pasar sus Permisos*/
          	$permisos=$this->model->getmenu($_SESSION['usuario']); 	
         $this->view->usuariosperfil=$permisos;
          /*fin*/
   $this->view->render('usuarios/index');
 }
}
?>
