<?php
session_start();
class Medicos extends Controller
{
   function __construct()
   {
      parent::__construct();
      $this->view->medicos = [];
      if (!isset($_SESSION['usuario'])) {
         echo "Acceso Negado";
         session_destroy();
         $location = constant('URL');
         header("Location: " . $location);
         exit;
      } else {
      }
   }
   function render()
   {
      $medicos = $this->model->get();
      $this->view->medicos = $medicos;
      $this->view->render('medicos/index');
   }
   function verPaginacion($param = null)
   {
      $id = $param[0];
      $autorizacionporpagina = 5;
      $totalautorizaciones = $this->model->getregistros(null);
      $paginas = $totalautorizaciones / $autorizacionporpagina;
      $iniciar = ($id - 1) * $autorizacionporpagina;
      $medicos = $this->model->getpag($iniciar, $autorizacionporpagina, null);
      $this->view->medicos = $medicos;
      $this->view->mensaje = 'son' . $totalautorizaciones;
      $this->view->paginas = $paginas;
      $this->view->paginaactual = $id;
      $this->view->usuario = $_SESSION['usuario'];
      /*Pasar sus Permisos*/
      $permisos = $this->model->getmenu($_SESSION['usuario']);
      $this->view->usuariosperfil = $permisos;
      /*fin*/
      $this->view->render('medicos/index');
   }
   function verPaginacionsearch($param = null)
   {
      $id = $param[0];
      $txtbuscar = $_POST['txtbuscar'];
      $autorizacionporpagina = 5;
      $totalautorizaciones = $this->model->getregistros($txtbuscar);
      $paginas = $totalautorizaciones / $autorizacionporpagina;
      $iniciar = ($id - 1) * $autorizacionporpagina;
      $medicos = $this->model->getpag($iniciar, $autorizacionporpagina, $txtbuscar);
      $this->view->medicos = $medicos;
      $this->view->mensaje = 'son' . $totalautorizaciones;
      $this->view->paginas = $paginas;
      $this->view->paginaactual = $id;
      /*Pasar sus Permisos*/
      $permisos = $this->model->getmenu($_SESSION['usuario']);
      $this->view->usuariosperfil = $permisos;
      /*fin*/
      $this->view->render('medicos/index');
   }
   function verMedicos($param = null)
   {
      $id = $param[0];
      $medicos = $this->model->getById($id);
      $this->view->medicos = $medicos;
      $this->view->mensaje = '';
      /*Pasar sus Permisos*/
      $permisos = $this->model->getmenu($_SESSION['usuario']);
      $this->view->usuariosperfil = $permisos;
      /*fin*/
      $this->view->render('medicos/detalle');
   }
   function nuevoMedicos($param = null)
   {
      /*Pasar sus Permisos*/
      $permisos = $this->model->getmenu($_SESSION['usuario']);
      $this->view->usuariosperfil = $permisos;
      /*fin*/
      $this->view->render('medicos/nuevo');
   }
   function importarMedicos($param = null)
   {
      $this->view->render('medicos/importar');
   }
   function imguploadMedicos()
   {
      $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/irebsol/public/uploads/";
      $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
      $uploadOk = 1;
      $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
      // Check if image file is a actual image or fake image
      if (isset($_POST["submit"])) {
         $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
         if ($check !== false) {
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
      if (
         $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
         && $imageFileType != "gif"
      ) {
         echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
         $uploadOk = 0;
      }
      if ($uploadOk == 0) {
         echo "Sorry, your file was not uploaded.";
         // if everything is ok, try to upload file
      } else {
         if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
         } else {
            echo "Sorry, there was an error uploading your file.";
         }
      }
   }
   function xlsuploadMedicos()
   {
      $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/irebsol/public/uploads/";
      $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
      $uploadOk = 1;
      $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
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
      if ($imageFileType != "xls" && $imageFileType != "xlsx") {
         echo "Sorry, only xls & xlsx files are allowed.";
         $uploadOk = 0;
      }
      // Check if  is set to 0 by an error
      if ($uploadOk == 0) {
         echo "Sorry, your file was not uploaded.";
         // if everything is ok, try to upload file
      } else {
         if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
         } else {
            echo "Sorry, there was an error uploading your file.";
         }
      }
   }
   function csvuploadMedicos()
   {
      $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/irebsol/public/uploads/";
      $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
      $uploadOk = 1;
      $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
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
      if ($imageFileType != "csv") {
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
            $linea2 = 0;
            $archivo = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $archivo = fopen($archivo, "r");
            while (($datos = fgetcsv($archivo, ",")) == true) {
               $num = count($datos);
               $linea++;
               $pos = 0;
               for ($columna = 0; $columna < $num; $columna++) {
                  if ($pos == 0) {
                     $c1 = $datos[$columna];
                  }
                  if ($pos == 1) {
                     $c2 = $datos[$columna];
                     $pos = 0;
                     //insertar datos
                     if ($this->model->insertcsv(['id' => $c1, 'nacionalidad' => $c2])) {
                        $mensaje = "Nuevo Nacionalidades Creado";
                     }
                     //fin insertar datos
                  } else {
                     $pos++;
                  }
               }
            }
            //Cerramos el archivo
            fclose($archivo);
            $nacionalidades = $this->model->get();
            $this->view->nacionalidades = $nacionalidades;
            $this->view->render('nacionalidades/index');
         } else {
            echo "Sorry, there was an error uploading your file.";
         }
      }
   }
   function actualizarMedicos()
   {
      $id = $_POST['id'];
      $medico = $_POST['medico'];
      $foto = basename($_FILES["foto"]["name"]);
      $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/afp/public/uploads/";
      $target_file = $target_dir . basename($_FILES["foto"]["name"]);
      move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);
      if ($this->model->update(['id' => $id, 'medico' => $medico, 'foto' => $foto])) {
         $medicos = new Medicos();
         $medicos->id = $id;
         $medicos->medico = $medico;
         $medicos->foto = $foto;
         $this->view->medicos = $medicos;
         $this->view->mensaje = 'medicos Actualizado Correctamente';
      } else {
         $this->view->mensaje = 'No se puedo Actualizar';
      }
      $medicos = $this->model->get();
      $this->view->medicos = $medicos;
      /*Pasar sus Permisos*/
      $permisos = $this->model->getmenu($_SESSION['usuario']);
      $this->view->usuariosperfil = $permisos;
      /*fin*/
      $this->view->render('medicos/index');
   }
   function crearMedicos()
   {
      $id = $_POST['id'];
      $medico = $_POST['medico'];
      $foto = basename($_FILES["foto"]["name"]);
      $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/afp/public/uploads/";
      $target_file = $target_dir . basename($_FILES["foto"]["name"]);
      move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);
      $mensaje = "";
      if ($this->model->insert(['id' => $id, 'medico' => $medico, 'foto' => $foto])) {
         $mensaje = "Nuevo Medicos Creado";
      } else {
         $mensaje = "medicos ya Existe";
      }
      $this->view->mensaje = $mensaje;
      /*Pasar sus Permisos*/
      $permisos = $this->model->getmenu($_SESSION['usuario']);
      $this->view->usuariosperfil = $permisos;
      /*fin*/
      $this->render('medicos/index');
   }
   function eliminarMedicos($param = null)
   {
      $id = $param[0];
      if ($this->model->delete($id)) {
         $this->view->mensaje = 'medicos Eliminado Correctamente';
      } else {
         $this->view->mensaje = 'No se pudo eliminar';
      }
      $medicos = $this->model->get();
      $this->view->medicos = $medicos;
      /*Pasar sus Permisos*/
      $permisos = $this->model->getmenu($_SESSION['usuario']);
      $this->view->usuariosperfil = $permisos;
      /*fin*/
      $this->view->render('medicos/index');
   }
}
?>