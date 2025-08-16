{{--  resources\views\proyectos\mostrarDetalles.blade.php   --}}
{{-- Detalle de un proyecto --}}

@extends('auth.layouts.app')

@section('title', 'Detalles del Proyecto - Tech Solutions')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
@endsection

@section('content')
<div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-xl flex-grow">
  @if ($proyecto)
    {{-- Encabezado --}}
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-4xl font-extrabold text-gray-800">{{ $proyecto->nombre }}</h2>

      <div class="flex space-x-4">
        {{-- Volver al listado (nombre correcto de la ruta: proyectos.lista) --}}
        <a href="{{ route('proyectos.lista') }}"
           class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 transform hover:scale-105 shadow-md">
          <i class="fas fa-arrow-left mr-2"></i> Volver al Listado
        </a>

        {{-- Acciones (ocultas si no hay token) --}}
        <a href="{{ route('proyectos.editar', ['proyectos_tech_solution' => $proyecto->id]) }}"
           class="btn-editar bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 transform hover:scale-105 shadow-md">
          <i class="fas fa-edit mr-2"></i> Editar Proyecto
        </a>

        <a href="{{ route('proyectos.confirmarEliminar', ['proyectos_tech_solution' => $proyecto->id]) }}"
           class="btn-eliminar bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 transform hover:scale-105 shadow-md">
          <i class="fas fa-trash-alt mr-2"></i> Eliminar
        </a>
      </div>
    </div>

    {{-- Mensajes de éxito --}}
    @if (session('success'))
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
        <strong class="font-bold">¡Éxito!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
      </div>
    @endif

    @if (session('error'))
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
        <strong class="font-bold">¡Error!</strong>
        <span class="block sm:inline">{{ session('error') }}</span>
      </div>
    @endif

    @php
      // Normaliza estado y arma etiqueta bonita + color
      $estado = $proyecto->estado;
      $estadoLabel = [
        'pendiente'   => 'Pendiente',
        'en_progreso' => 'En Progreso',
        'completado'  => 'Completado',
        'pausado'     => 'Pausado',
        'cancelado'   => 'Cancelado',
      ][$estado] ?? ucwords(str_replace('_',' ', (string)$estado));

      $estadoClase =
        $estado === 'completado'  ? 'bg-green-100 text-green-800'  :
        ($estado === 'en_progreso'? 'bg-yellow-100 text-yellow-800':
        ($estado === 'pausado'    ? 'bg-red-100 text-red-800'     :
        ($estado === 'cancelado'  ? 'bg-gray-300 text-gray-800'   :
                                     'bg-gray-200 text-gray-800')));
    @endphp

    {{-- Detalle --}}
    <div class="bg-white p-8 rounded-lg shadow-md border border-gray-200">
      <div class="flex items-center py-3 border-b border-gray-200">
        <span class="font-bold text-gray-700 w-36 flex-shrink-0">ID:</span>
        <span class="text-gray-800 flex-grow">{{ $proyecto->id }}</span>
      </div>

      <div class="flex items-center py-3 border-b border-gray-200">
        <span class="font-bold text-gray-700 w-36 flex-shrink-0">Nombre:</span>
        <span class="text-gray-800 flex-grow">{{ $proyecto->nombre }}</span>
      </div>

      <div class="flex items-center py-3 border-b border-gray-200">
        <span class="font-bold text-gray-700 w-36 flex-shrink-0">Fecha de Inicio:</span>
        <span class="text-gray-800 flex-grow">
          {{ optional($proyecto->fecha_inicio)->format('Y-m-d') }}
        </span>
      </div>

      <div class="flex items-center py-3 border-b border-gray-200">
        <span class="font-bold text-gray-700 w-36 flex-shrink-0">Estado:</span>
        <span class="text-gray-800 flex-grow">
          <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $estadoClase }}">
            {{ $estadoLabel }}
          </span>
        </span>
      </div>

      <div class="flex items-center py-3 border-b border-gray-200">
        <span class="font-bold text-gray-700 w-36 flex-shrink-0">Responsable:</span>
        <span class="text-gray-800 flex-grow">{{ $proyecto->responsable }}</span>
      </div>

      <div class="flex items-center py-3">
        <span class="font-bold text-gray-700 w-36 flex-shrink-0">Monto:</span>
        <span class="text-gray-800 flex-grow">
          ${{ number_format($proyecto->monto, 2, ',', '.') }}
        </span>
      </div>

      {{-- Acciones inferiores (también protegidas visualmente) --}}
      <div class="flex justify-end mt-8 space-x-4">
        <a href="{{ route('proyectos.editar', ['proyectos_tech_solution' => $proyecto->id]) }}"
           class="btn-editar bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 transform hover:scale-105 shadow-md">
          <i class="fas fa-edit mr-2"></i> Editar
        </a>

        <a href="{{ route('proyectos.confirmarEliminar', ['proyectos_tech_solution' => $proyecto->id]) }}"
           class="btn-eliminar bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 transform hover:scale-105 shadow-md">
          <i class="fas fa-trash-alt mr-2"></i> Eliminar
        </a>
      </div>
    </div>
  @else
    <div class="text-center py-8 text-gray-500 text-lg">
      <i class="fas fa-exclamation-triangle text-4xl mb-3"></i><br>
      El proyecto solicitado no fue encontrado.
    </div>
  @endif
</div>
@endsection

{{-- Gate visual: oculta Editar/Eliminar si no hay token válido --}}
@section('scripts')
<script>
  const token = localStorage.getItem('token');
  const exp = Number(localStorage.getItem('token_exp') || 0);
  const isAuth = !!token && Date.now() < exp;

  if (!isAuth) {
    document.querySelectorAll('.btn-editar, .btn-eliminar').forEach(el => el.classList.add('hidden'));
  }
</script>
@endsection
