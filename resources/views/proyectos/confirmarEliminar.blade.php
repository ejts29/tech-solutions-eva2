{{-- Ruta: resources/views/proyectos/confirmarEliminar.blade.php --}}

{{-- Esta vista se utiliza para confirmar la eliminación de un proyecto específico.
     - Muestra los detalles del proyecto a eliminar y solicita confirmación.
     - Proporciona opciones para confirmar la eliminación o cancelar y volver a los detalles. --}}

@extends('auth.layouts.app')

@section('title', 'Confirmar Eliminación de Proyecto - Tech Solutions') {{-- Define el título de la página --}}

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
@endsection

@section('content') {{-- Sección de contenido principal --}}
    <div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-xl flex-grow">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Confirmar Eliminación de Proyecto</h1>
        <p class="text-lg text-gray-700 mb-8">¿Estás seguro de que quieres eliminar el proyecto "<strong>{{ $proyecto->nombre }}</strong>"? Esta acción es irreversible.</p>

        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 shadow-sm mb-8"> {{-- Contenedor para los detalles del proyecto --}}
            <div class="border-b border-gray-200 pb-4 mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Detalles del Proyecto</h3>
            </div>
            <div class="space-y-3">
                <p class="text-gray-700"><strong class="text-gray-900">ID:</strong> {{ $proyecto->id }}</p>
                <p class="text-gray-700"><strong class="text-gray-900">Nombre:</strong> {{ $proyecto->nombre }}</p>
                <p class="text-gray-700"><strong class="text-gray-900">Responsable:</strong> {{ $proyecto->responsable }}</p>
                <p class="text-gray-700"><strong class="text-gray-900">Monto:</strong> ${{ number_format($proyecto->monto, 2, ',', '.') }}</p>
            </div>
        </div>

        {{-- Formulario para confirmar la eliminación --}}
        <form action="{{ route('proyectos.eliminar', ['proyectos_tech_solution' => $proyecto->id]) }}" method="POST" class="flex space-x-4 justify-end">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 transform hover:scale-105 shadow-md">
                <i class="fas fa-trash-alt mr-2"></i> Sí, eliminar proyecto
            </button>
            {{-- El botón "Cancelar" no es un submit, es un enlace que regresa a los detalles. --}}
            <a href="{{ route('proyectos.mostrarDetalles', ['proyectos_tech_solution' => $proyecto->id]) }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 transform hover:scale-105 shadow-md">
                <i class="fas fa-times-circle mr-2"></i> Cancelar
            </a>
        </form>
    </div>
@endsection
