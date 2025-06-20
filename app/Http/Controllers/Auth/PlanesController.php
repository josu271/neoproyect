<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;

class PlanesController extends Controller
{
    /**
     * Muestra la lista de planes con búsqueda.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $planes = Plan::when($search, function ($query, $search) {
                return $query->where('nombrePlan', 'like', "%{$search}%");
            })
            ->orderBy('idPlan', 'asc')
            ->paginate(10);

        return view('auth.planes', compact('planes'));
    }

    /**
     * Almacena un nuevo plan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombrePlan' => 'required|string|max:100',
            'precio'     => 'required|numeric|min:0',
        ]);

        Plan::create([
            'nombrePlan' => $validated['nombrePlan'],
            'precio'     => $validated['precio'],
        ]);

        return redirect()->route('planes.index')
                         ->with('success', 'Plan creado correctamente.');
    }

    /**
     * Actualiza un plan existente.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombrePlan' => 'required|string|max:100',
            'precio'     => 'required|numeric|min:0',
        ]);

        $plan = Plan::findOrFail($id);
        $plan->update([
            'nombrePlan' => $validated['nombrePlan'],
            'precio'     => $validated['precio'],
        ]);

        return redirect()->route('planes.index')
                         ->with('success', 'Plan actualizado correctamente.');
    }

    /**
     * Elimina un plan (opcional, si se desea habilitar).
     */
    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->delete();

        return redirect()->route('planes.index')
                         ->with('success', 'Plan eliminado correctamente.');
    }
}
