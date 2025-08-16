@extends('auth.layouts.app')
@section('title','Iniciar sesión')

{{-- resources\views\auth\login.blade.php  --}}

{{-- Contenido de la página de inicio de sesión --}}
@section('content')
<div class="container mx-auto max-w-md mt-10 p-6 bg-white rounded-xl shadow border border-gray-200">
  <h1 class="text-2xl font-bold text-gray-900 mb-2">Inicia sesión</h1>
  <p class="text-gray-600 mb-6">Usa tu correo y contraseña</p>

  <div id="alert" class="hidden mb-4 rounded-md border border-red-300 bg-red-50 p-3 text-sm text-red-700"></div>

  <form id="formLogin" class="space-y-4">
    @csrf
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
      <input type="email" name="email" class="w-full rounded-md border-gray-300 p-3 focus:ring-teal-600 focus:border-teal-600" placeholder="usuario@example.com" required>
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
      <input type="password" name="password" class="w-full rounded-md border-gray-300 p-3 focus:ring-teal-600 focus:border-teal-600" placeholder="••••••••" required minlength="8">
    </div>
    <button id="btnLogin" class="w-full bg-teal-700 hover:bg-teal-800 text-white font-semibold py-3 rounded-lg shadow">
      Entrar
    </button>
  </form>
 
  <p class="text-sm text-gray-600 mt-6 text-center">
    ¿No tienes cuenta?
    <a class="text-teal-700 font-semibold hover:underline" href="{{ route('register.view') }}">Regístrate</a>
  </p>

  <pre id="out" class="mt-6 text-xs bg-gray-50 border border-gray-200 rounded p-3 overflow-auto hidden"></pre>
</div>

<script>
const out = document.getElementById('out');
const alertBox = document.getElementById('alert');
const btn = document.getElementById('btnLogin');

function show(obj){
  out.classList.remove('hidden');
  out.textContent = JSON.stringify(obj, null, 2);
}
function error(msg){
  alertBox.textContent = msg;
  alertBox.classList.remove('hidden');
}
function saveToken(token, ttlSeconds){
  // guarda token y fecha de expiración (con colchón de 5s)
  const exp = Date.now() + ((ttlSeconds || 3600) * 1000) - 5000;
  localStorage.setItem('token', token);
  localStorage.setItem('token_exp', String(exp));
}

document.getElementById('formLogin').addEventListener('submit', async (e)=>{
  e.preventDefault();
  alertBox.classList.add('hidden'); out.classList.add('hidden');

  const f = e.target;
  btn.disabled = true; btn.classList.add('opacity-60','cursor-not-allowed');

  try {
    const res = await fetch('/api/login', {
      method:'POST',
      headers:{ 'Accept':'application/json','Content-Type':'application/json' },
      body: JSON.stringify({
        email: f.email.value.trim(),
        password: f.password.value
      })
    });

    const data = await res.json();
    show(data);

    if (res.ok && data.token) {
      saveToken(data.token, data.expires_in);
      // redirige al listado
      window.location.href = "{{ route('proyectos.lista') }}";
    } else {
      // errores de validación o credenciales
      if (data.errors) {
        const list = Object.values(data.errors).flat().join(' ');
        error(list);
      } else {
        error(data.message || 'Credenciales inválidas.');
      }
    }
  } catch(ex) {
    error('Error de red. Intenta nuevamente.');
  } finally {
    btn.disabled = false; btn.classList.remove('opacity-60','cursor-not-allowed');
  }
});
</script>
@endsection
