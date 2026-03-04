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
        <form action="<?php echo constant('URL'); ?>login/verificar" method="POST" class="space-y-5">

          <!-- EMAIL -->
          <div class="space-y-1.5">
            <label class="text-[13px] font-medium text-slate-500 ml-1">Usuario</label>

            <div
              class="relative group input-focus-effect border border-slate-200 rounded-lg transition-all bg-muted-gray">
              <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                <span class="material-symbols-outlined text-[20px]">person</span>
              </div>

              <input type="email" id="username" name="email" required
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

              <input type="password" id="password" name="pass" required
                class="w-full bg-transparent border-none text-slate-800 rounded-lg pl-11 pr-4 py-3 focus:ring-0 transition-all placeholder:text-slate-300 text-sm"
                placeholder="••••••••">
            </div>
          </div>

          <!-- OPTIONS -->
          <div class="flex items-center justify-between px-1">
            <label class="flex items-center space-x-2 cursor-pointer group">
              <input class="w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary/20 bg-white transition-all"
                type="checkbox" />
              <span class="text-[13px] text-slate-400 group-hover:text-slate-600 transition-colors">Recordarme</span>
            </label>

            <a class="text-[13px] font-medium text-primary hover:underline transition-all" href="#">
              ¿Olvidaste la clave?
            </a>
          </div>

          <!-- BUTTON -->
          <button type="submit"
            class="w-full bg-primary hover:bg-blue-600 text-white font-medium py-3.5 rounded-lg shadow-sm transition-all active:scale-[0.99] mt-2">
            Entrar al Sistema
          </button>

        </form>

        <div class="pt-4 text-center border-t border-slate-50">
          <p class="text-sm text-slate-400">
            ¿No tienes acceso? <a class="text-primary font-medium hover:underline" href="#">Solicitar cuenta</a>
          </p>
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

</body>

</html>