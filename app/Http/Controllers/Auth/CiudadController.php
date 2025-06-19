<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ciudad;

class CiudadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $ciudades = Ciudad::when($search, function ($query, $search) {
                return $query->where('NombreCiudad', 'LIKE', "%{$search}%");
            })
            ->orderBy('idCiudad', 'asc')
            ->paginate(10);

        return view('auth.ciudades', compact('ciudades', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auth.ciudades_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'NombreCiudad' => 'required|string|max:100|unique:ciudades,NombreCiudad',
        ]);

        Ciudad::create($validated);

        return redirect()->route('ciudades.index')
                         ->with('success', 'Ciudad creada exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $ciudad = Ciudad::findOrFail($id);
        return view('auth.ciudades_edit', compact('ciudad'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $ciudad = Ciudad::findOrFail($id);

        $validated = $request->validate([
            'NombreCiudad' => 'required|string|max:100|unique:ciudades,NombreCiudad,' . $ciudad->idCiudad . ',idCiudad',
        ]);

        $ciudad->update($validated);

        return redirect()->route('ciudades.index')
                         ->with('success', 'Ciudad actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ciudad = Ciudad::findOrFail($id);
        $ciudad->delete();

        return redirect()->route('ciudades.index')
                         ->with('success', 'Ciudad eliminada exitosamente.');
    }
}
