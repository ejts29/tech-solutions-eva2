@extends('auth.layouts.app')
@section('title','Crear cuenta')

{{-- resources\views\auth\register.blade.php --}}

{{--  Contenido de la página de registro  --}}


@section('content')
<div class="container mx-auto max-w-md mt-10 p-6 bg-white rounded-xl shadow border border-gray-200">
  <h1 class="text-2xl font-bold text-gray-900 mb-2">Crear cuenta</h1>
  <p class="text-gray-600 mb-6">la contraseña se guarda cifrada. y luego inicia sesión para continuar.</p>

  <div id="alert" class="hidden mb-4 rounded-md border border-red-300 bg-red-50 p-3 text-sm text-red-700"></div>
  <div id="ok" class="hidden mb-4 rounded-md border border-green-300 bg-green-50 p-3 text-sm text-green-700"></div>

  <form id="formRegister" class="space-y-4">
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
      <input type="text" name="name" class="w-full rounded-md border-gray-300 p-3 focus:ring-teal-600 focus:border-teal-600" placeholder="Usuario" required>
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
      <input type="email" name="email" class="w-full rounded-md border-gray-300 p-3 focus:ring-teal-600 focus:border-teal-600" placeholder="usuario@example.com" required>
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
      <input type="password" name="password" class="w-full rounded-md border-gray-300 p-3 focus:ring-teal-600 focus:border-teal-600" placeholder="Clave secreta minimo 8 digitos" required minlength="8">
    </div>
    <button id="btnSubmit" class="w-full bg-teal-700 hover:bg-teal-800 text-white font-semibold py-3 rounded-lg shadow">Crear cuenta</button>
  </form>

  <p class="text-sm text-gray-600 mt-6 text-center">
    ¿Ya tienes cuenta?
    <a class="text-teal-700 font-semibold hover:underline" href="{{ route('login.view') }}">Inicia sesión</a>
  </p>

  <pre id="out" class="mt-6 text-xs bg-gray-50 border border-gray-200 rounded p-3 overflow-auto hidden"></pre>
</div>

{{-- Scripts específicos de la página --}}
<script>
const out = document.getElementById('out');
const alertBox = document.getElementById('alert');
const okBox = document.getElementById('ok');
const btn = document.getElementById('btnSubmit');

function show(obj){
  out.classList.remove('hidden');
  out.textContent = JSON.stringify(obj, null, 2);
}
function error(msg){
  alertBox.textContent = msg;
  alertBox.classList.remove('hidden');
}
function success(msg){
  okBox.textContent = msg;
  okBox.classList.remove('hidden');
}

// Manejo del registro de usuarios 
document.getElementById('formRegister').addEventListener('submit', async (e)=>{
  e.preventDefault();
  alertBox.classList.add('hidden'); okBox.classList.add('hidden'); out.classList.add('hidden');

  const f = e.target;
  btn.disabled = true; btn.classList.add('opacity-60','cursor-not-allowed');

  try {
    const res = await fetch('/api/register', {
      method:'POST',
      headers:{ 'Accept':'application/json','Content-Type':'application/json' },
      body: JSON.stringify({ name: f.name.value.trim(), email: f.email.value.trim(), password: f.password.value })
    });

    const data = await res.json();
    show(data);

    if (res.ok) {
      // NO guardamos token aquí. Forzamos a iniciar sesión.
      success('Cuenta creada correctamente. Ahora inicia sesión.');
      setTimeout(()=>{ window.location.href = "{{ route('login.view') }}"; }, 900);
    } else {
      // errores de validación u otros
      if (data.errors) {
        const list = Object.values(data.errors).flat().join(' ');
        error(list);
      } else {
        error(data.message || 'No se pudo crear la cuenta.');
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
