<?php
// app\Http\Controllers\ProyectoController.php
namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProyectoController extends Controller
{
    // GET /api/proyectos  (lista los proyectos del usuario autenticado)
    public function index(): JsonResponse
    {
        $uid = auth('api')->id();

        $proyectos = Proyecto::with('creador:id,name,email')
            ->where('created_by', $uid)
            ->orderByDesc('id')
            ->get();

        return response()->json($proyectos);
    }

    // POST /api/proyectos (crea un proyecto para el usuario autenticado)
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nombre'        => ['required','string','max:150'],
            'fecha_inicio'  => ['required','date'],
            'estado'        => ['required','in:pendiente,en_progreso,finalizado'],
            'responsable'   => ['required','string','max:150'],
            'monto'         => ['required','numeric','min:0'],
        ]);

        $data['created_by'] = auth('api')->id();

        $proyecto = Proyecto::create($data);

        return response()->json([
            'message'  => 'Proyecto creado',
            'proyecto' => $proyecto,
        ], 201);
    }
}
