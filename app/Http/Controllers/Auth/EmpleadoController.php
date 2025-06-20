<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empleado;
use Illuminate\Support\Facades\Hash;

class EmpleadoController extends Controller
{
    /**
     * Muestra la lista de empleados.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $empleados = Empleado::when($search, function($q, $search) {
                return $q->where('NombreEmpleado', 'like', "%{$search}%")
                         ->orWhere('ApellidopEmpleado', 'like', "%{$search}%")
                         ->orWhere('ApellidomEmpleado', 'like', "%{$search}%")
                         ->orWhere('DNI', $search);
            })
            ->orderBy('idEmpleado', 'desc')
            ->get();

        return view('auth.empleado', compact('empleados'));
    }

    /**
     * Almacena un nuevo empleado.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'DNI'               => 'required|integer|unique:empleado,DNI',
            'NombreEmpleado'    => 'required|string|max:45',
            'ApellidopEmpleado' => 'required|string|max:45',
            'ApellidomEmpleado' => 'nullable|string|max:45',
            'TelefonoEmpleado'  => 'nullable|string|max:45',
            'RolEmpleado'       => 'nullable|string|max:45',
            'ActivoEmpleado'    => 'required|in:SI,NO',
            'ContrasenaEmpleado'=> 'required|string|min:6',
        ]);

        // Hashear contraseña
        $data['ContrasenaEmpleado'] = Hash::make($data['ContrasenaEmpleado']);

        Empleado::create($data);

        return redirect()
            ->route('empleados.index')
            ->with('success', 'Empleado creado correctamente.');
    }

    /**
     * Actualiza un empleado existente.
     */
    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);

        $data = $request->validate([
            'DNI'               => "required|integer|unique:empleado,DNI,{$id},idEmpleado",
            'NombreEmpleado'    => 'required|string|max:45',
            'ApellidopEmpleado' => 'required|string|max:45',
            'ApellidomEmpleado' => 'nullable|string|max:45',
            'TelefonoEmpleado'  => 'nullable|string|max:45',
            'RolEmpleado'       => 'nullable|string|max:45',
            'ActivoEmpleado'    => 'required|in:SI,NO',
            'ContrasenaEmpleado'=> 'nullable|string|min:6',
        ]);

        // Si viene contraseña, la hasheamos
        if (!empty($data['ContrasenaEmpleado'])) {
            $data['ContrasenaEmpleado'] = Hash::make($data['ContrasenaEmpleado']);
        } else {
            // Si no, no la tocamos
            unset($data['ContrasenaEmpleado']);
        }

        $empleado->update($data);

        return redirect()
            ->route('empleados.index')
            ->with('success', 'Empleado actualizado correctamente.');
    }
}
