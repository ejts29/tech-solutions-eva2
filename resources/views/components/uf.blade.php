{{--
|--------------------------------------------------------------------------
| Componente para mostrar el valor de la UF del día.
|--------------------------------------------------------------------------
|
| - Se conecta a la API de mindicador.cl para obtener el valor actual de la UF.
| - El valor se cachea por 23 horas para evitar llamadas excesivas a la API.
| - Incluye manejo de errores para fallos en la conexión o en la respuesta.
|
--}}

{{-- Componente para mostrar el valor de la UF del día --}}

{{-- resources\views\components\uf.blade.php --}}

@php
    $ufValue = null;
    $cacheKey = 'uf_value_today';
    $cacheDuration = now()->addHours(23);

    try {
        // Intenta obtener el valor de la caché. Si no existe, lo busca en la API.
        $ufValue = \Illuminate\Support\Facades\Cache::remember($cacheKey, $cacheDuration, function () {
            $response = \Illuminate\Support\Facades\Http::timeout(5)->get('https://mindicador.cl/api/uf');

            // Verifica si la respuesta de la API es exitosa
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['serie'][0]['valor'])) {
                    return (float) $data['serie'][0]['valor'];
                }
            }
            
            // Si la API falla o el formato es incorrecto, registra el error y no cachea nada.
            \Illuminate\Support\Facades\Log::warning('Falló la obtención de la UF desde mindicador.cl.', [
                'response_body' => $response->body() ?? 'No se recibió respuesta.'
            ]);
            
            return null; // Retorna null para indicar un fallo
        });

    } catch (\Illuminate\Http\Client\RequestException $e) {
        // Manejo de errores específicos de HTTP (e.g., 404, 500)
        \Illuminate\Support\Facades\Log::error('Error en la petición a la API de Mindicador: ' . $e->getMessage());
        $ufValue = 'Error en la petición a la API de UF';
    } catch (\Throwable $e) {
        // Manejo de cualquier otro tipo de error (conexión, etc.)
        \Illuminate\Support\Facades\Log::error('Error general al obtener UF: ' . $e->getMessage());
        $ufValue = 'Error de conexión con la API de UF';
    }
@endphp

<div class="uf-del-dia text-sm font-semibold flex items-center justify-center text-gray-700 bg-gray-100 px-3 py-1 rounded-full shadow-sm border border-gray-200">
    @if(is_numeric($ufValue))
        {{-- Muestra el valor de la UF si es un número válido --}}
        <i class="fas fa-money-bill-wave mr-1 text-green-600"></i>
        <span>UF del Día: **CLP {{ number_format($ufValue, 2, ',', '.') }}**</span>
    @else
        {{-- Muestra el mensaje de error si no se pudo obtener la UF --}}
        <i class="fas fa-exclamation-triangle mr-1 text-yellow-500"></i>
        <span class="text-xs">{{ $ufValue }}</span>
    @endif
</div>