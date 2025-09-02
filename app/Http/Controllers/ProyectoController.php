<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class ProyectoController extends Controller
{
    /** Estados permitidos (admite variantes con mayúsculas/espacios) */
    private array $estadosPermitidos = [
        'pendiente','en_progreso','finalizado','completado','pausado','cancelado',
        'Pendiente','En Progreso','Finalizado','Completado','Pausado','Cancelado',
        'en progreso','En Progreso'
    ];

    /** Normaliza el estado a snake_case en minúsculas */
    private function normalizarEstado(string $estado): string
    {
        $estado = trim($estado);
        $estado = str_replace(' ', '_', $estado);
        return mb_strtolower($estado);
    }

    /** Mensajes y alias de campos para validación */
    private function mensajes(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'string'   => 'El campo :attribute debe ser texto.',
            'max'      => 'El campo :attribute no debe superar :max caracteres.',
            'date'     => 'El campo :attribute debe tener un formato de fecha válido (YYYY-MM-DD).',
            'in'       => 'El campo :attribute no tiene un valor permitido.',
            'numeric'  => 'El campo :attribute debe ser numérico.',
            'min'      => 'El campo :attribute debe ser mayor o igual a :min.'
        ];
    }
    private function atributos(): array
    {
        return [
            'nombre'       => 'nombre',
            'fecha_inicio' => 'fecha de inicio',
            'estado'       => 'estado',
            'responsable'  => 'responsable',
            'monto'        => 'monto'
        ];
    }

    // GET /api/proyectos   200 (si no hay datos)
    public function index(): JsonResponse
    {
        $uid = auth('api')->id();

        $proyectos = Proyecto::with('creador:id,name,email')
            ->where('created_by', $uid)
            ->orderByDesc('id')
            ->get();

        return response()->json($proyectos, 200);
    }

    // POST /api/proyectos  201 (con mensajes de validación 422 si falla)
    public function store(Request $request): JsonResponse
    {
        $validador = Validator::make(
            $request->all(),
            [
                'nombre'        => ['required','string','max:150'],
                'fecha_inicio'  => ['required','date'],
                'estado'        => ['required','in:'.implode(',', $this->estadosPermitidos)],
                'responsable'   => ['required','string','max:150'],
                'monto'         => ['required','numeric','min:0'],
            ],
            $this->mensajes(),
            $this->atributos()
        );

        if ($validador->fails()) {
            return response()->json([
                'message' => 'Validación fallida',
                'errors'  => $validador->errors(), // { campo: [mensajes...] }
            ], 422);
        }

        $data = $validador->validated();
        $data['estado']     = $this->normalizarEstado($data['estado']); // "En Progreso"  "en_progreso"
        $data['created_by'] = auth('api')->id();

        $proyecto = Proyecto::create($data);

        return response()->json([
            'message'  => 'Proyecto creado',
            'proyecto' => $proyecto,
        ], 201);
    }

    // GET /api/proyectos/{id}  200 / 404
    public function show($id): JsonResponse
    {
        try {
            $uid = auth('api')->id();
            $proyecto = Proyecto::with('creador:id,name,email')
                ->where('created_by', $uid)
                ->findOrFail($id);

            return response()->json($proyecto, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Proyecto no encontrado'], 404);
        }
    }

    // PUT/PATCH /api/proyectos/{id}  200 / 404 (con validación 422)
    public function update(Request $request, $id): JsonResponse
    {
        $validador = Validator::make(
            $request->all(),
            [
                'nombre'        => ['required','string','max:150'],
                'fecha_inicio'  => ['required','date'],
                'estado'        => ['required','in:'.implode(',', $this->estadosPermitidos)],
                'responsable'   => ['required','string','max:150'],
                'monto'         => ['required','numeric','min:0'],
            ],
            $this->mensajes(),
            $this->atributos()
        );

        if ($validador->fails()) {
            return response()->json([
                'message' => 'Validación fallida',
                'errors'  => $validador->errors(),
            ], 422);
        }

        try {
            $uid = auth('api')->id();
            $proyecto = Proyecto::where('created_by', $uid)->findOrFail($id);

            $data = $validador->validated();
            $data['estado'] = $this->normalizarEstado($data['estado']);

            $proyecto->update($data);

            return response()->json([
                'message'  => 'Proyecto actualizado',
                'proyecto' => $proyecto->fresh('creador:id,name,email'),
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Proyecto no encontrado'], 404);
        }
    }

    // DELETE /api/proyectos/{id}  204 / 404
    public function destroy($id)
    {
        try {
            $uid = auth('api')->id();
            $proyecto = Proyecto::where('created_by', $uid)->findOrFail($id);
            $proyecto->delete();

            return response()->noContent(); // 204, cuerpo vacío
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Proyecto no encontrado'], 404);
        }
    }
}
