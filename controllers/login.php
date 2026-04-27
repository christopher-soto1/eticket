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
  function verificar1()
  {
      $email = $_POST['email'];
      $pass = md5($_POST['pass']);  // Ojo, si puedes, usa un método más seguro para manejar contraseñas.
  
      // Comprobar las credenciales del usuario.
      if ($this->model->verificar($email, $pass)) {
          session_start();
          $_SESSION["usuario"] = $_POST["email"];
          
          // Pasar los permisos
          /* $permisos = $this->model->getmenu($email);
          $this->view->usuariosperfil = $permisos; */
  
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


/*   function salir()
  {
    $this->view->render('login/index');
  } */

  function loginAjax()
  {
      session_start();
      header("Content-Type: application/json");
      require_once __DIR__ . '/../libs/database.php';
      
      $datos = json_decode(file_get_contents("php://input"), true);
      $usuario = trim($datos['usuario'] ?? '');
      $pass = trim($datos['pass'] ?? '');
      
      if (empty($usuario) || empty($pass)) {
          echo json_encode(["success" => false, "message" => "Usuario y contraseña requeridos"]);
          exit;
      }
      
      try {
          # -------------------------- Validacion de Rebsol --------------------------
          $dsn = "mysql:host=" . IPHOST_REBSOL . ";dbname=" . DBNAME_REBSOL . ";charset=" . CHARSET_REBSOL;

          $pdo = new PDO($dsn, USER_REBSOL, PW_REBSOL, [
              PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
              PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
              PDO::ATTR_EMULATE_PREPARES => false,
          ]);
  
          $stmt = $pdo->prepare("SELECT nombre_usuario 
                                FROM USUARIO_REBSOL 
                                WHERE nombre_usuario = :usuario 
                                AND contrasena_md5 = :clave");
          
          $stmt->bindParam(':usuario', $usuario);
          $stmt->bindValue(':clave', md5($pass));
          $stmt->execute();
          
          $usuarioEncontradoRebsol = $stmt->fetch(PDO::FETCH_ASSOC);
          
          
          if ($usuarioEncontradoRebsol) {
          # -------------------------- Fin Validacion de Rebsol --------------------------

              # -------------------------- Validacion de proyecto --------------------------
              $db = new Database();
              $pdoLocal = $db->connect();
              $stmtValidacion = $pdoLocal->prepare("SELECT * 
                                                    FROM usuariosperfil 
                                                    WHERE usuario_rebsol = :usuario 
                                                    AND habilitado = 'S'
                                                    LIMIT 1");
              $stmtValidacion->bindParam(':usuario', $usuario);
              $stmtValidacion->execute();
              $usuarioPerfil = $stmtValidacion->fetch(PDO::FETCH_ASSOC);
              if ($usuarioPerfil) {
                    $idusuario = $usuarioPerfil['idusuario'];
                    $_SESSION['usuario'] = $idusuario;
                    echo json_encode([
                      "success" => true,
                      "usuario" => $idusuario
                  ]);
              }
              else{
                  echo json_encode([
                      "success" => false,
                      "requiere_registro" => true,
                      "usuario" => $usuario,
                      "message" => "Posee usuario Rebsol pero no esta habilitado en el sistema E-Tickets"
                  ]);
                  exit;
              }
              
              # -------------------------- Fin Validacion de proyecto --------------------------
          } 
          else {
              echo json_encode([
                  "success" => false,
                  "message" => "Usuario no encontrado en Rebsol"
              ]);
          }
          
      } catch (PDOException $e) {
          echo json_encode([
              "success" => false,
              "message" => "Error al conectar: " . $e->getMessage()
          ]);
      }
  }

  function registrarUsuario()
  {
      session_start();
      header("Content-Type: application/json");
      require_once __DIR__ . '/../libs/database.php';

      $datos = json_decode(file_get_contents("php://input"), true);
      $usuario = $datos['usuario'] ?? '';
      $correo = $datos['correo'] ?? '';

      if (!$usuario || !$correo) {
          echo json_encode(["success" => false, "message" => "Datos incompletos"]);
          exit;
      }

      try {
        $db = new Database();
        $pdo = $db->connect();
        $pdo->beginTransaction();
    
        // 1. borrar usuariosperfil 
        $stmt1 = $pdo->prepare("DELETE FROM usuariosperfil WHERE idusuario = :correo");
        $stmt1->bindParam(':correo', $correo);
        $stmt1->execute();

        // 2. borrar usuario 
        $stmt2 = $pdo->prepare("DELETE FROM usuarios WHERE email = :correo");
        $stmt2->bindParam(':correo', $correo);
        $stmt2->execute();
    
        // 3. INSERTAR NUEVO REGISTRO
        $stmtInsert = $pdo->prepare("INSERT INTO usuariosperfil (idusuario, usuario_rebsol, menu, habilitado, principal, permiso, area) 
                                      VALUES (:correo, :usuario, 'correo', 'S', 'Tablas', 'usuario', 'Externo')");
    
        $stmtInsert->bindParam(':usuario', $usuario);
        $stmtInsert->bindParam(':correo', $correo);
        $stmtInsert->execute();
    
        // CONFIRMAR
        $pdo->commit();
    
        echo json_encode(["success" => true]);
    
    } catch (PDOException $e) {
    
        // SI ALGO FALLA → DESHACE TODO
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
    
        echo json_encode([
            "success" => false,
            "message" => $e->getMessage()
        ]);
    }
  }

  function validarCorreo()
    {
        session_start();
        header("Content-Type: application/json");
        require_once __DIR__ . '/../libs/database.php';

        $datos = json_decode(file_get_contents("php://input"), true);
        $usuario = $datos['usuario'] ?? '';
        $correo = $datos['correo'] ?? '';

        if (!$usuario || !$correo) {
            echo json_encode([
                "success" => false,
                "message" => "Datos incompletos"
            ]);
            exit;
        }

        try {
            $db = new Database();
            $pdo = $db->connect();

            // VALIDACIÓN usuario vs correo
            $usuario = strtolower(trim($usuario));
            $correo = strtolower(trim($correo));

            $inicial = substr($usuario, 0, 1);
            $apellido = substr($usuario, 1);

            $parteCorreo = explode('@', $correo)[0];
            $partes = explode('.', $parteCorreo);

            $nombre = $partes[0] ?? '';
            $apellidoCorreo = $partes[1] ?? '';

            if (substr($nombre, 0, 1) !== $inicial || $apellidoCorreo !== $apellido) {
                echo json_encode([
                    "success" => false,
                    "message" => "El correo no coincide con el usuario Rebsol"
                ]);
                exit;
            }

            if (count($partes) < 2) {
                echo json_encode([
                    "success" => false,
                    "message" => "Formato de correo inválido"
                ]);
                exit;
            }

            $stmt = $pdo->prepare("SELECT idusuario 
                                    FROM usuariosperfil 
                                    WHERE idusuario = :correo
                                    LIMIT 1");

            $stmt->bindParam(':correo', $correo);
            $stmt->execute();

            $existe = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existe) {
                // YA EXISTE
                echo json_encode([
                    "success" => false,
                    "message" => "El correo ya está registrado, intenta con otro"
                ]);
            } else {
                // NO EXISTE
                echo json_encode([
                    "success" => true
                ]);
            }

        } catch (PDOException $e) {
            echo json_encode([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

  function salir()
  {
      session_start();
      session_unset();  // Elimina todas las variables de sesión
      session_destroy(); // Destruye la sesión por completo

      // Redirige al login
      header('Location: ' . constant('URL') . 'login');
      exit();
  }
}
?>