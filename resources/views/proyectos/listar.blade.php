{{--  resources/views/proyectos/listar.blade.php  --}}
{{-- Esta vista muestra el dashboard y la tabla de proyectos. --}}

@extends('auth.layouts.app') {{-- Usa tu layout actual --}}
@section('title', 'Gestión de Proyectos - Tech Solutions')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
@endsection

@section('content')
<div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-xl flex-grow">

  {{-- Header + botón NUEVO (se ocultará si no hay token) --}}
  <div class="flex items-center justify-between mb-8">
    <div>
      <h1 class="text-3xl font-bold text-gray-900">Gestión de Proyectos</h1>
      <p class="text-gray-600 mt-1">Administra y supervisa todos tus proyectos</p>
    </div>

    <a href="{{ route('proyectos.crear') }}"
       class="btn-crear bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors whitespace-nowrap shadow-md">
      <div class="flex items-center gap-2">
        <span class="w-5 h-5 flex items-center justify-center">
          <i class="fas fa-plus"></i>
        </span>
        Nuevo Proyecto
      </div>
    </a>
  </div>

  {{-- Mensajes de exito --}}
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

  {{-- Tarjetas resumen --}}
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
          <i class="fas fa-folder text-blue-600 text-xl"></i>
        </div>
        <div>
          <p class="text-gray-600 text-sm">Total Proyectos</p>
          <p class="text-2xl font-bold text-gray-900">{{ $totalProyectos }}</p>
        </div>
      </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
          <i class="fas fa-check-circle text-green-600 text-xl"></i>
        </div>
        <div>
          <p class="text-gray-600 text-sm">Completados</p>
          <p class="text-2xl font-bold text-gray-900">{{ $completados }}</p>
        </div>
      </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
          <i class="fas fa-hourglass-half text-yellow-600 text-xl"></i>
        </div>
        <div>
          <p class="text-gray-600 text-sm">En Progreso</p>
          <p class="text-2xl font-bold text-gray-900">{{ $enProgreso }}</p>
        </div>
      </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
          <i class="fas fa-pause-circle text-red-600 text-xl"></i>
        </div>
        <div>
          <p class="text-gray-600 text-sm">Pausados</p>
          <p class="text-2xl font-bold text-gray-900">{{ $pausados }}</p>
        </div>
      </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
          <i class="fas fa-clock text-gray-700 text-xl"></i>
        </div>
        <div>
          <p class="text-gray-600 text-sm">Pendientes</p>
          <p class="text-2xl font-bold text-gray-900">{{ $pendientes }}</p>
        </div>
      </div>
    </div>
  </div>

  {{-- Tabla --}}
  <div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-6 border-b border-gray-200">
      <h2 class="text-xl font-semibold text-gray-900">Lista de Proyectos</h2>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-4 text-left text-sm font-medium text-gray-500">ID</th>
            <th class="px-6 py-4 text-left text-sm font-medium text-gray-500">Nombre del Proyecto</th>
            <th class="px-6 py-4 text-left text-sm font-medium text-gray-500">Fecha de Inicio</th>
            <th class="px-6 py-4 text-left text-sm font-medium text-gray-500">Estado</th>
            <th class="px-6 py-4 text-left text-sm font-medium text-gray-500">Responsable</th>
            <th class="px-6 py-4 text-left text-sm font-medium text-gray-500">Monto</th>
            <th class="px-6 py-4 text-left text-sm font-medium text-gray-500">Acciones</th>
          </tr>
        </thead>

        <tbody id="projectsTableBody" class="divide-y divide-gray-200">
          @forelse ($proyectos as $proyecto)
            @php
              // estados vienen normalizados en minúsculas con guión_bajo.
              $estado = $proyecto->estado;
              $estadoLabel = [
                'pendiente'   => 'Pendiente',
                'en_progreso' => 'En Progreso',
                'completado'  => 'Completado',
                'pausado'     => 'Pausado',
                'cancelado'   => 'Cancelado',
              ][$estado] ?? ucwords(str_replace('_',' ',$estado));

              $estadoClase =
                $estado === 'completado'  ? 'bg-green-100 text-green-800'  :
                ($estado === 'en_progreso'? 'bg-yellow-100 text-yellow-800':
                ($estado === 'pausado'    ? 'bg-red-100 text-red-800'     :
                ($estado === 'cancelado'  ? 'bg-gray-300 text-gray-800'   :
                                             'bg-gray-200 text-gray-800')));
            @endphp
            <tr class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $proyecto->id }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $proyecto->nombre }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ optional($proyecto->fecha_inicio)->format('Y-m-d') }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $estadoClase }}">
                  {{ $estadoLabel }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $proyecto->responsable }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${{ number_format($proyecto->monto, 2, ',', '.') }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                <div class="accionesCrud flex items-center space-x-3 justify-center">
                  <a href="{{ route('proyectos.mostrarDetalles', ['proyectos_tech_solution' => $proyecto->id]) }}"
                     class="text-blue-600 hover:text-blue-900 transition duration-200" title="Ver Detalles">
                    <i class="fas fa-eye"></i>
                  </a>
                  <a href="{{ route('proyectos.editar', ['proyectos_tech_solution' => $proyecto->id]) }}"
                     class="btn-editar text-indigo-600 hover:text-indigo-900 transition duration-200" title="Editar Proyecto">
                    <i class="fas fa-edit"></i>
                  </a>
                  <a href="{{ route('proyectos.confirmarEliminar', ['proyectos_tech_solution' => $proyecto->id]) }}"
                     class="btn-eliminar text-red-500 hover:text-red-700 transition duration-200" title="Eliminar Proyecto">
                    <i class="fas fa-trash-alt"></i>
                  </a>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center py-8 text-gray-500 text-lg">
                <i class="fas fa-box-open text-4xl mb-3"></i><br>
                No hay proyectos registrados aún. ¡Crea el primero!
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

{{-- Gate visual: oculta crear/editar/eliminar si no hay token válido --}}
@section('scripts')
<script>
  const token = localStorage.getItem('token');
  const exp = Number(localStorage.getItem('token_exp') || 0);
  const isAuth = !!token && Date.now() < exp;

  if (!isAuth) {
    document.querySelectorAll('.btn-crear, .btn-editar, .btn-eliminar, .accionesCrud')
      .forEach(el => el.classList.add('hidden'));
  }
</script>
@endsection
