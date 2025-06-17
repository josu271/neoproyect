<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empleado;

class EmpleadoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $empleados = Empleado::query()
            ->when($search, fn($query, $search) =>
                $query->where('NombreEmpleado', 'like', "%{$search}%")
                      ->orWhere('ApellidopEmpleado', 'like', "%{$search}%")
                      ->orWhere('DNI', 'like', "%{$search}%")
            )
            ->orderBy('NombreEmpleado')
            ->get();

        return view('auth.empleado', compact('empleados', 'search'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'DNI' => 'required|unique:empleado,DNI|integer',
            'NombreEmpleado' => 'required|string|max:45',
            'ApellidopEmpleado' => 'required|string|max:45',
            'ApellidomEmpleado' => 'nullable|string|max:45',
            'TelefonoEmpleado' => 'nullable|string|max:45',
            'RolEmpleado' => 'nullable|string|max:45',
            'ActivoEmpleado' => 'required|string|in:SI,NO',
            'ContrasenaEmpleado' => 'required|string',
        ]);
        $data['ContrasenaEmpleado'] = bcrypt($data['ContrasenaEmpleado']);
        Empleado::create($data);

        return redirect()->route('empleados.index')->with('success', 'Empleado creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);
        $data = $request->validate([
            'NombreEmpleado' => 'required|string|max:45',
            'ApellidopEmpleado' => 'required|string|max:45',
            'ApellidomEmpleado' => 'nullable|string|max:45',
            'TelefonoEmpleado' => 'nullable|string|max:45',
            'RolEmpleado' => 'nullable|string|max:45',
            'ActivoEmpleado' => 'required|string|in:SI,NO',
            'ContrasenaEmpleado' => 'nullable|string',
        ]);
        if (!empty($data['ContrasenaEmpleado'])) {
            $data['ContrasenaEmpleado'] = bcrypt($data['ContrasenaEmpleado']);
        } else {
            unset($data['ContrasenaEmpleado']);
        }
        $empleado->update($data);

        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado correctamente.');
    }
}
