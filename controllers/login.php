<?php
class Login extends Controller
{
  function __construct()
  {
    //llama al constructor del Controlador Base
    parent::__construct();
  }
  function render()
  {
    $this->view->render('login/index');
  }
  function verificar()
  {
      $email = $_POST['email'];
      $pass = md5($_POST['pass']);  // Ojo, si puedes, usa un método más seguro para manejar contraseñas.
  
      // Comprobar las credenciales del usuario.
      if ($this->model->verificar($email, $pass)) {
          session_start();
          $_SESSION["usuario"] = $_POST["email"];
          
          // Pasar los permisos
          $permisos = $this->model->getmenu($email);
          $this->view->usuariosperfil = $permisos;
  
          // Redirigir al dashboard o página principal después de un login correcto.
          header('Location: ' . constant('URL') . 'correo/verPaginacion/1');
      } else {
          // Si las credenciales son incorrectas, almacenar el error en la sesión.
          session_start();
          $_SESSION['login_error'] = 'Por favor, ingrese su correo y/o contraseña correctamente';  // Mensaje de error
  
          // Redirigir al login nuevamente
          header('Location: ' . constant('URL') . 'login');
      }
  }
  

  function salir()
  {
    $this->view->render('login/index');
  }
}
?>