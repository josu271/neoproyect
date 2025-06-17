<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cobertura;
use Illuminate\Http\Request;

class CoberturaController extends Controller
{
    public function index()
    {
        $coberturas = Cobertura::all();
        return view('auth.cobertura', compact('coberturas'));
    }

    public function create()
    {
        return redirect()->route('dashboard');
    }

    public function store(Request $request)
    {
        // Validar los datos recibidos
        $data = $request->validate([
            'ubicacionnap' => 'required|regex:/^-?\d+(\.\d+)?,-?\d+(\.\d+)?$/',
            'maxCli'       => 'required|integer|min:0',
            'ActiCliente'  => 'required|integer|min:0|lte:maxCli',
            'Estado'       => 'required|in:Activo,Inactivo',
        ]);

        // Crear y guardar
        Cobertura::create([
            'ubicacionnap' => $data['ubicacionnap'],
            'maxCli'       => $data['maxCli'],
            'ActiCliente'  => $data['ActiCliente'],
            'Estado'       => $data['Estado'],
        ]);

        return redirect()->route('cobertura.index')
                         ->with('success', 'Cobertura agregada correctamente.');
    }
    public function update(Request $request, $id)
{
    $data = $request->validate([
        'ubicacionnap' => 'required|regex:/^-?\d+(\.\d+)?,-?\d+(\.\d+)?$/',
        'maxCli'       => 'required|integer|min:0',
        'ActiCliente'  => 'required|integer|min:0|lte:maxCli',
        'Estado'       => 'required|in:Activo,Inactivo',
    ]);

    $cob = Cobertura::findOrFail($id);
    $cob->update($data);

    return redirect()->route('cobertura.index')
                     ->with('success', 'Cobertura actualizada correctamente.');
}

}
