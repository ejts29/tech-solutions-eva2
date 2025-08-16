{{-- resources/views/proyectos/editar.blade.php --}}
{{-- Editar proyecto (web) --}}

@extends('auth.layouts.app')

@section('title', 'Editar Proyecto - Tech Solutions')

@section('styles')
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
@endsection

@section('content')
<div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-xl flex-grow">
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-4xl font-extrabold text-gray-800">
      Editar Proyecto: {{ $proyecto->nombre }}
    </h2>
    <div class="flex space-x-4">
      <a href="{{ route('proyectos.lista') }}"
         class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 transform hover:scale-105 shadow-md">
        <i class="fas fa-arrow-left mr-2"></i> Volver al Listado
      </a>
      <a href="{{ route('proyectos.mostrarDetalles', ['proyectos_tech_solution' => $proyecto->id]) }}"
         class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 transform hover:scale-105 shadow-md">
        <i class="fas fa-eye mr-2"></i> Ver Detalles
      </a>
    </div>
  </div>

  {{-- Errores de validación --}}
  @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
      <strong class="font-bold">¡Ups!</strong>
      <span class="block sm:inline">Hubo algunos problemas con tu entrada:</span>
      <ul class="mt-3 list-disc list-inside">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  @if ($proyecto)
    <form action="{{ route('proyectos.actualizar', ['proyectos_tech_solution' => $proyecto->id]) }}"
          method="POST" class="space-y-6" id="formEditar">
      @csrf
      @method('PUT')

      {{-- Nombre --}}
      <div>
        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre del Proyecto</label>
        <input type="text" name="nombre" id="nombre" required
               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
               value="{{ old('nombre', $proyecto->nombre) }}">
        @error('nombre')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      {{-- Fecha de inicio (formato Y-m-d (año-mes-dia) para input date) --}}
      <div>
        <label for="fecha_inicio" class="block text-sm font-medium text-gray-700">Fecha de Inicio</label>
        <input type="date" name="fecha_inicio" id="fecha_inicio" required
               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
               value="{{ old('fecha_inicio', optional($proyecto->fecha_inicio)->format('Y-m-d')) }}">
        @error('fecha_inicio')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      {{-- Estado (values en minúscula para coincidir con ENUM) --}}
      <div>
        <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
        <select name="estado" id="estado" required
                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
          <option value="">Seleccione un estado</option>
          <option value="pendiente"   @selected(old('estado', $proyecto->estado) === 'pendiente')>Pendiente</option>
          <option value="en_progreso" @selected(old('estado', $proyecto->estado) === 'en_progreso')>En Progreso</option>
          <option value="completado"  @selected(old('estado', $proyecto->estado) === 'completado')>Completado</option>
          <option value="pausado"     @selected(old('estado', $proyecto->estado) === 'pausado')>Pausado</option>
          <option value="cancelado"   @selected(old('estado', $proyecto->estado) === 'cancelado')>Cancelado</option>
        </select>
        @error('estado')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      {{-- Responsable --}}
      <div>
        <label for="responsable" class="block text-sm font-medium text-gray-700">Responsable</label>
        <input type="text" name="responsable" id="responsable" required
               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
               value="{{ old('responsable', $proyecto->responsable) }}">
        @error('responsable')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      {{-- Monto --}}
      <div>
        <label for="monto" class="block text-sm font-medium text-gray-700">Monto</label>
        <input type="number" name="monto" id="monto" step="0.01" min="0" required
               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
               value="{{ old('monto', $proyecto->monto) }}">
        @error('monto')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div class="flex justify-end mt-6">
        <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 transform hover:scale-105 shadow-lg">
          <i class="fas fa-sync-alt mr-2"></i> Actualizar Proyecto
        </button>
      </div>
    </form>
  @else
    <div class="text-center py-8 text-gray-500 text-lg">
      <i class="fas fa-exclamation-triangle text-4xl mb-3"></i><br>
      El proyecto solicitado para edición no fue encontrado.
    </div>
  @endif
</div>
@endsection

@section('scripts')
<script>
  // Gate visual: si no hay token válido, manda a login
  (function(){
    const token = localStorage.getItem('token');
    const exp   = Number(localStorage.getItem('token_exp') || 0);
    const isAuth = !!token && Date.now() < exp;
    if (!isAuth) {
      window.location.href = "{{ route('login.view') }}";
    }
  })();
</script>
@endsection
