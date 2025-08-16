<?php

// app\Http\Controllers\RutaTechSolutionsController.php
namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;

class RutaTechSolutionsController extends Controller
{
    // GET /proyectos-tech-solutions  -> name: proyectos.lista
    public function index()
    {
        // Tarjetas del dashboard (usando valores canon del modelo)
        $totalProyectos = Proyecto::count();
        $completados    = Proyecto::where('estado', Proyecto::ESTADO_CANON['Completado'])->count();
        $enProgreso     = Proyecto::where('estado', Proyecto::ESTADO_CANON['En Progreso'])->count();
        $pausados       = Proyecto::where('estado', Proyecto::ESTADO_CANON['Pausado'])->count();
        $pendientes     = Proyecto::where('estado', Proyecto::ESTADO_CANON['Pendiente'])->count();

        $proyectos = Proyecto::latest()->get();

        return view('proyectos.listar', compact(
            'proyectos','totalProyectos','completados','enProgreso','pausados','pendientes'
        ));
    }

    // GET /proyectos-tech-solutions/create  -> name: proyectos.crear
    public function create()
    {
        return view('proyectos.crear');
    }

    // POST /proyectos-tech-solutions  -> name: proyectos.guardar
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'       => ['required','string','max:150'],
            'fecha_inicio' => ['required','date'],
            'estado'       => ['required','string','max:50'],
            'responsable'  => ['required','string','max:100'],
            'monto'        => ['required','numeric','min:0'],
        ]);

        // Si la columna 'created_by' es NOT NULL, asigna un usuario (1 = usuario u otro).
        $data['created_by'] = auth()->id() ?? 1;

        $proyecto = Proyecto::create($data);

        return redirect()
            ->route('proyectos.mostrarDetalles', ['proyectos_tech_solution' => $proyecto->id])
            ->with('success', 'Proyecto creado correctamente.');
    }

    // GET /proyectos-tech-solutions/{proyectos_tech_solution}  -> name: proyectos.mostrarDetalles
    public function show(Proyecto $proyectos_tech_solution)
    {
        $proyecto = $proyectos_tech_solution;
        return view('proyectos.mostrarDetalles', compact('proyecto'));
    }

    // GET /proyectos-tech-solutions/{proyectos_tech_solution}/edit -> name: proyectos.editar
    public function edit(Proyecto $proyectos_tech_solution)
    {
        $proyecto = $proyectos_tech_solution;
        return view('proyectos.editar', compact('proyecto'));
    }

    // PUT /proyectos-tech-solutions/{proyectos_tech_solution} -> name: proyectos.actualizar
    public function update(Request $request, Proyecto $proyectos_tech_solution)
    {
        $data = $request->validate([
            'nombre'       => ['required','string','max:150'],
            'fecha_inicio' => ['required','date'],
            'estado'       => ['required','string','max:50'],
            'responsable'  => ['required','string','max:100'],
            'monto'        => ['required','numeric','min:0'],
        ]);

        $proyectos_tech_solution->update($data);

        return redirect()
            ->route('proyectos.mostrarDetalles', ['proyectos_tech_solution' => $proyectos_tech_solution->id])
            ->with('success', 'Proyecto actualizado correctamente.');
    }

    // DELETE /proyectos-tech-solutions/{proyectos_tech_solution} -> name: proyectos.eliminar
    public function destroy(Proyecto $proyectos_tech_solution)
    {
        $proyectos_tech_solution->delete();

        return redirect()
            ->route('proyectos.lista')
            ->with('success', 'Proyecto eliminado.');
    }

    // GET /proyectos-tech-solutions/{proyectos_tech_solution}/confirmar-eliminar -> name: proyectos.confirmarEliminar
    public function confirmarEliminar(Proyecto $proyectos_tech_solution)
    {
        $proyecto = $proyectos_tech_solution;
        return view('proyectos.confirmarEliminar', compact('proyecto'));
    }
}
