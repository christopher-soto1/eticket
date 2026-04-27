<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
    rel="stylesheet" />

  <!-- SweetAlert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script id="tailwind-config">
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            "primary": "#137fec",
            "background-light": "#ffffff",
            "muted-gray": "#f8fafc",
          },
          fontFamily: {
            "display": ["Inter", "sans-serif"]
          },
          borderRadius: {
            "DEFAULT": "0.375rem",
            "lg": "0.5rem",
            "xl": "1rem",
            "full": "9999px"
          },
        },
      },
    }
  </script>

  <title>IOPA SYSTEM: E-Tickets Login</title>

  <style type="text/tailwindcss">
    body {
    background-color: #ffffff;
}
.input-focus-effect:focus-within {
    border-color: #137fec;
    box-shadow: 0 0 0 4px rgba(19, 127, 236, 0.05);
}
</style>
</head>

<body class="bg-background-light font-display antialiased text-slate-600">

  <div class="relative min-h-screen w-full flex flex-col items-center justify-center p-6">

    <div class="w-full max-w-[400px]">

      <div class="mb-12 text-center">
        <div class="flex justify-center mb-6">
          <div class="text-primary">
            <svg class="w-12 h-12" fill="none" viewBox="0 0 48 48">
              <path d="M24 4H6V17.3333V30.6667H24V44H42V30.6667V17.3333H24V4Z" stroke="currentColor" stroke-width="1.5">
              </path>
              <path d="M24 17V31" opacity="0.4" stroke="currentColor" stroke-width="1.5"></path>
            </svg>
          </div>
        </div>

        <h1 class="text-2xl font-semibold tracking-tight text-slate-900">
          IOPA SYSTEM <span class="font-light text-slate-400 mx-1">|</span> <span class="text-primary">E-Tickets</span>
        </h1>

        <p class="mt-2 text-sm text-slate-400 font-normal">
          Accede a tu panel de gestión
        </p>
      </div>

      <div class="space-y-8">

        <!-- FORM FUNCIONAL -->
        <form id="formLogin" class="space-y-5">

          <!-- EMAIL -->
          <div class="space-y-1.5">
            <label class="text-[13px] font-medium text-slate-500 ml-1">Usuario <small>(Rebsol Los Leones)</small></label>

            <div
              class="relative group input-focus-effect border border-slate-200 rounded-lg transition-all bg-muted-gray">
              <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                <span class="material-symbols-outlined text-[20px]">person</span>
              </div>

              <input type="text" id="username" name="user" 
                class="w-full bg-transparent border-none text-slate-800 rounded-lg pl-11 pr-4 py-3 focus:ring-0 transition-all placeholder:text-slate-300 text-sm"
                placeholder="Tu nombre de usuario">
            </div>
          </div>

          <!-- PASSWORD -->
          <div class="space-y-1.5">
            <div class="flex items-center justify-between ml-1">
              <label class="text-[13px] font-medium text-slate-500">Contraseña</label>
            </div>

            <div
              class="relative group input-focus-effect border border-slate-200 rounded-lg transition-all bg-muted-gray">
              <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                <span class="material-symbols-outlined text-[20px]">lock</span>
              </div>

              <input type="password" id="password" name="pass" 
                class="w-full bg-transparent border-none text-slate-800 rounded-lg pl-11 pr-4 py-3 focus:ring-0 transition-all placeholder:text-slate-300 text-sm"
                placeholder="••••••••">
            </div>
          </div>

          <!-- OPTIONS -->
          <div class="flex items-center justify-between px-1">
            <!-- <label class="flex items-center space-x-2 cursor-pointer group">
              <input class="w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary/20 bg-white transition-all"
                type="checkbox" />
              <span class="text-[13px] text-slate-400 group-hover:text-slate-600 transition-colors">Recordarme</span>
            </label>

            <a class="text-[13px] font-medium text-primary hover:underline transition-all" href="#">
              ¿Olvidaste la clave?
            </a> -->
          </div>

          <!-- BUTTON -->
          <button type="submit"
            class="w-full bg-primary hover:bg-blue-600 text-white font-medium py-3.5 rounded-lg shadow-sm transition-all active:scale-[0.99] mt-2">
            Entrar al Sistema
          </button>

        </form>

        <div class="pt-4 text-center border-t border-slate-50">
          <!-- <p class="text-sm text-slate-400">
            ¿No tienes acceso? <a class="text-primary font-medium hover:underline" href="#">Solicitar cuenta</a>
          </p> -->
        </div>

      </div>

      <footer class="mt-20 text-center">
        <div class="space-y-6">
          <p class="text-[10px] tracking-[0.2em] uppercase text-slate-300 font-bold">
            Powered by Iopa System
          </p>

          <div class="flex items-center justify-center gap-5">
            <a class="text-[11px] text-slate-400 hover:text-primary transition-colors" href="#">Términos</a>
            <span class="w-1 h-1 bg-slate-200 rounded-full"></span>
            <a class="text-[11px] text-slate-400 hover:text-primary transition-colors" href="#">Privacidad</a>
            <span class="w-1 h-1 bg-slate-200 rounded-full"></span>
            <a class="text-[11px] text-slate-400 hover:text-primary transition-colors" href="#">Soporte</a>
          </div>
        </div>
      </footer>

    </div>

    <div class="fixed top-0 right-0 p-12 opacity-20 pointer-events-none">
      <div class="w-64 h-64 bg-primary/10 rounded-full blur-[100px]"></div>
    </div>

  </div>

  <!-- ERROR LOGIN -->
  <?php if (isset($_SESSION['login_error'])): ?>
    <script>
      window.onload = function () {
        Swal.fire({
          icon: 'error',
          title: '¡Error!',
          text: '<?php echo $_SESSION['login_error']; ?>',
          confirmButtonColor: '#137fec'
        });
      };
    </script>
  <?php unset($_SESSION['login_error']); endif; ?>


  <script>
    document.getElementById('formLogin').addEventListener('submit', function (e) {
      e.preventDefault();

      const email = document.querySelector('input[name="user"]').value;
      const pass = document.querySelector('input[name="pass"]').value;
      //console.log('Enviando:', { usuario: email, pass: pass });

      fetch('<?php echo constant("URL"); ?>login/loginAjax', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          usuario: email,
          pass: pass
        })
      })
      .then(res => {
        console.log('Status:', res.status); // 👈 Y AQUÍ
        return res.json();
      })
        .then(data => {
          console.log('Response:', data);
          if (data.success) {
            Swal.fire({
              icon: 'success',
              title: '¡Bienvenido!',
              text: 'Iniciando sesión...',
              confirmButtonColor: '#137fec',
              timer: 1500,
              showConfirmButton: false
            }).then(() => {
              window.location.href = '<?php echo constant("URL"); ?>correo/verPaginacion/1';
            });
          } else if (data.requiere_registro) {
            // 🔥 POPUP 1: preguntar si quiere registrarse
            Swal.fire({
              icon: 'info',
              title: 'Usuario no registrado',
              text: 'Tienes acceso a Rebsol pero no a E-Tickets. ¿Deseas registrarte?',
              showCancelButton: true,
              confirmButtonText: 'Registrarse',
              cancelButtonText: 'Cancelar'
            }).then((result) => {
              if (result.isConfirmed) {
                mostrarFormularioRegistro(data.usuario);
              }
            });

          } else {
            Swal.fire({
              icon: 'error',
              title: '¡Error!',
              text: data.message,
              confirmButtonColor: '#137fec'
            });
          }
        })
        .catch(() => {
          console.error('Error:', error);
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error de conexión',
            confirmButtonColor: '#137fec'
          });
        });
    });

    function mostrarFormularioRegistro(usuario) {
      Swal.fire({
        title: 'Registro',
        html: `
          <input type="email" id="correo1" class="swal2-input" placeholder="nombre.apellido@iopa.cl">
          <input type="email" id="correo2" class="swal2-input" placeholder="Confirmar correo">
        `,
        confirmButtonText: 'Continuar',
        focusConfirm: false,
        preConfirm: () => {
          const c1 = document.getElementById('correo1').value;
          const c2 = document.getElementById('correo2').value;

          // Vacios
          if (!c1 || !c2) {
            Swal.showValidationMessage('Debes completar ambos campos');
            return false;
          }

          // Coincidencia
          if (c1 !== c2) {
            Swal.showValidationMessage('Los correos no coinciden');
            return false;
          }

          // Validacion de dominio
          if (!c1.endsWith('@iopa.cl') || !c2.endsWith('@iopa.cl')) {
            /* Swal.fire({
              icon: 'error',
              title: 'Correo inválido',
              text: 'Debe ser un correo corporativo (@iopa.cl)'
            }); */
            Swal.showValidationMessage('Debe ser un correo corporativo (@iopa.cl)');
            return false;
          }
          
          // Validacion para que el correo incluya punto
          const parte1 = c1.split('@')[0];
          const parte2 = c2.split('@')[0];

          if (!parte1.includes('.') || !parte2.includes('.')) {
            Swal.showValidationMessage('El correo debe tener formato nombre.apellido');
            return false;
          }

          return { correo: c1 };
        }
      }).then((result) => {
        if (result.isConfirmed) {
          confirmarRegistro(usuario, result.value.correo);
        }
      });
    }
    function confirmarRegistro(usuario, correo) {
      Swal.fire({
        icon: 'warning',
        title: 'Confirmación',
        text: `¿Estás seguro que el correo ${correo} es correcto?`,
        showCancelButton: true,
        confirmButtonText: 'Sí, registrarse',
        cancelButtonText: 'Volver'
      }).then((result) => {
        if (result.isConfirmed) {
          validarCorreo(usuario, correo);
        }
      });
    }
    function validarCorreo(usuario, correo) {
      fetch('<?php echo constant("URL"); ?>login/validarCorreo', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          usuario: usuario,
          correo: correo
        })
      })
      .then(res => res.json())
      .then(data => {

        if (!data.success) {
          // 🔴 YA EXISTE → bloquear
          Swal.fire({
            icon: 'error',
            title: 'Correo ya registrado',
            text: data.message
          });
          return;
        }

        // ✅ SI NO EXISTE → recién preguntar confirmación
        Swal.fire({
          icon: 'warning',
          title: 'Confirmación',
          text: `¿Estás seguro que el correo ${correo} es correcto?`,
          showCancelButton: true,
          confirmButtonText: 'Sí, registrarse',
          cancelButtonText: 'Volver'
        }).then((result) => {
          if (result.isConfirmed) {
            registrarUsuario(usuario, correo);
          }
        });

      })
      .catch(() => {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Error al validar correo'
        });
      });
    }
    function registrarUsuario(usuario, correo) {
      fetch('<?php echo constant("URL"); ?>login/registrarUsuario', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          usuario: usuario,
          correo: correo
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          Swal.fire({
            icon: 'success',
            title: 'Registrado',
            text: 'Tu cuenta fue creada correctamente'
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: data.message
          });
        }
      });
    }
  </script>
</body>

</html>