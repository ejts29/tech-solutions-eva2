<!DOCTYPE html>
{{-- resources/views/auth/layouts/app.blade.php --}}
{{--  Layout principal para las vistas en comun   --}}
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tech Solutions - Gestión de Proyectos')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        
        header {
            position: sticky;
            top: 0;
            z-index: 50;
            background: linear-gradient(90deg, #0f766e 0%, #115e59 100%);
            transition: all 0.3s ease;
        }
        
        header a:hover {
            transform: translateY(-2px);
            transition: transform 0.2s ease;
        }
        
        nav a {
            transition: color 0.3s ease;
        }
        
        nav a:hover {
            color: #f97316;
        }
        
        footer {
            background: linear-gradient(90deg, #1f2937 0%, #111827 100%);
        }
        
        .social-icon {
            transition: transform 0.3s ease, color 0.3s ease;
        }
        
        .social-icon:hover {
            transform: scale(1.2);
            color: #f97316;
        }
    </style>
    
    @yield('styles')

    <script src="{{ asset('js/auth.js') }}"></script>

</head>

<body class="bg-gray-50 min-h-screen flex flex-col">
    {{-- HEADER: Encabezado principal de la aplicación --}}
    <header class="text-white p-4 shadow-lg">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <a href="{{ route('proyectos.lista') }}" class="flex items-center space-x-3">
                <img
                    src="{{ asset('imagenes/Tech-solutions-Logo.png') }}"
                    alt="Logo Tech Solutions"
                    class="h-12 w-auto transition-transform duration-200"
                >
                <span class="text-xl font-semibold hidden md:block">Tech Solutions</span>
            </a>

<nav>
  <ul class="flex items-center space-x-6">
      {{-- Visible SOLO SI hay token --}}
      <li data-auth="required" class="hidden">
          <a href="{{ route('proyectos.lista') }}" class="flex flex-col items-center space-y-1 text-sm font-medium hover:text-orange-400">
              <img src="{{ asset('imagenes/iconoProyectos.png') }}" class="h-6 w-auto" alt="">
              <span>Proyectos</span>
          </a>
      </li>

      {{-- Visible SOLO SI NO hay token --}}
      <li data-auth="guest" class="hidden">
          <a href="{{ route('login.view') }}" class="text-sm font-medium hover:text-orange-400">
              Iniciar sesión
          </a>
      </li>
      <li data-auth="guest" class="hidden">
          <a href="{{ route('register.view') }}" class="text-sm font-medium hover:text-orange-400">
              Crear cuenta
          </a>
      </li>

      {{-- Visible SOLO SI hay token: botón cerrar sesión --}}
      <li data-auth="required" class="hidden">
          <a href="#" data-action="logout" class="text-sm font-medium hover:text-orange-400">
              Cerrar sesión
          </a>
      </li>

      {{-- Tu componente UF --}}
      <li>@include('components.uf')</li>
  </ul>
</nav>
        </div>
    </header>

    {{-- MAIN: Contenido principal de la página --}}
    <main class="flex-grow container mx-auto px-4 py-8">
        @yield('content')
    </main>

    {{-- FOOTER: Pie de página --}}
    <footer class="py-10 bg-gray-900 text-white text-sm">
        <div class="container mx-auto px-6 flex flex-col md:flex-row justify-between items-center">
            <div class="mb-6 md:mb-0 md:w-1/3 text-center md:text-left">
                <a href="{{ route('proyectos.lista') }}">
                    <img
                        src="{{ asset('imagenes/Tech-solutions-Logo.png') }}"
                        alt="Logo Tech Solutions"
                        class="h-10 w-auto mx-auto md:mx-0"
                    >
                </a>
                <p class="mt-2 text-gray-400">Innovación y excelencia en gestión de proyectos</p>
            </div>

            <div class="mb-6 md:mb-0 md:w-1/3 text-center">
                <h3 class="text-lg font-semibold mb-3">Enlaces Rápidos</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('proyectos.lista') }}" class="hover:text-orange-400 transition-colors">Proyectos</a></li>
                    <li><a href="#" class="hover:text-orange-400 transition-colors">Servicios</a></li>
                    <li><a href="#" class="hover:text-orange-400 transition-colors">Contacto</a></li>
                </ul>
            </div>

            <div class="md:w-1/3 text-center md:text-right">
                <p class="mb-2"><i class="fas fa-phone-alt mr-2"></i>(+56) 9 8401 0588</p>
                <p class="mb-2"><i class="fas fa-envelope mr-2"></i>contacto@techsolutiongroups.com</p>
                <p><i class="fas fa-map-marker-alt mr-2"></i>Santiago, Chile</p>
                <div class="flex justify-center md:justify-end space-x-4 mt-4">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>

        <div class="w-full text-center mt-8 pt-6 border-t border-gray-700">
            © {{ date('Y') }} Tech Solutions. Todos los derechos reservados.
        </div>
    </footer>

    @yield('scripts')
</body>
</html>