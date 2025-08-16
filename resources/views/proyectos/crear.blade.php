{{--  resources/views/proyectos/crear.blade.php  --}}

{{-- Crear nuevo proyecto (web) --}}

@extends('auth.layouts.app')

@section('title', 'Crear Nuevo Proyecto - Tech Solutions')

@section('styles')
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
@endsection

@section('content')
<div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-xl flex-grow">
  <div class="flex items-center justify-between mb-8">
    <div>
      <h1 class="text-3xl font-bold text-gray-900">Crear Nuevo Proyecto</h1>
      <p class="text-gray-600 mt-1">Ingresa los detalles para un nuevo proyecto</p>
    </div>

    {{-- Volver al listado --}}
    <a href="{{ route('proyectos.lista') }}"
       class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-medium hover:bg-gray-300 transition-colors whitespace-nowrap shadow-md">
      <div class="flex items-center gap-2">
        <span class="w-5 h-5 flex items-center justify-center">
          <i class="fas fa-arrow-left"></i>
        </span>
        Volver al Listado
      </div>
    </a>
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

  {{-- Formulario --}}
  <form action="{{ route('proyectos.guardar') }}" method="POST" class="space-y-6" id="formCrear">
    @csrf

    {{-- Nombre --}}
    <div>
      <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Proyecto</label>
      <input type="text" name="nombre" id="nombre"
             class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
             value="{{ old('nombre') }}"
             placeholder="Ej: Desarrollo App Móvil X" required>
      @error('nombre')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
      @enderror
    </div>

    {{-- Fecha de inicio --}}
    <div>
      <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Inicio</label>
      <input type="date" name="fecha_inicio" id="fecha_inicio"
             class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
             value="{{ old('fecha_inicio') }}" required>
      @error('fecha_inicio')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
      @enderror
    </div>

    {{-- Estado (valores en minúscula para coincidir con ENUM de BD) --}}
    <div>
      <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
      <select name="estado" id="estado"
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
              required>
        <option value="">Seleccione un estado</option>
        <option value="pendiente"   @selected(old('estado') === 'pendiente')>Pendiente</option>
        <option value="en_progreso" @selected(old('estado') === 'en_progreso')>En Progreso</option>
        <option value="completado"  @selected(old('estado') === 'completado')>Completado</option>
        <option value="pausado"     @selected(old('estado') === 'pausado')>Pausado</option>
        <option value="cancelado"   @selected(old('estado') === 'cancelado')>Cancelado</option>
      </select>
      @error('estado')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
      @enderror
    </div>

    {{-- Responsable --}}
    <div>
      <label for="responsable" class="block text-sm font-medium text-gray-700 mb-1">Responsable</label>
      <input type="text" name="responsable" id="responsable"
             class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
             value="{{ old('responsable') }}"
             placeholder="Ej: Juan Pérez" required>
      @error('responsable')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
      @enderror
    </div>

    {{-- Monto --}}
    <div>
      <label for="monto" class="block text-sm font-medium text-gray-700 mb-1">Monto</label>
      <input type="number" name="monto" id="monto" step="0.01" min="0"
             class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
             value="{{ old('monto') }}" placeholder="Ej: 50000.00" required>
      @error('monto')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
      @enderror
    </div>

    {{-- Botón --}}
    <div class="flex justify-end">
      <button type="submit"
              class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors shadow-md btn-crear">
        <span class="flex items-center gap-2">
          <span class="w-5 h-5 flex items-center justify-center">
            <i class="fas fa-plus"></i>
          </span>
          Crear Proyecto
        </span>
      </button>
    </div>
  </form>
</div>
@endsection

@section('scripts')
<script>
  // Gate visual: si no hay token (o expiró), redirige a login
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
