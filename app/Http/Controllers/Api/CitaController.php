<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cita;
use Illuminate\Support\Facades\Auth;

class CitaController extends Controller
{
    public function index()
    {
        return Cita::with('user')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'paciente' => 'required|string|max:255',
            'fecha_hora' => 'required|date',
            'tipo' => 'required|in:psicologica,legal,social',
            'estado' => 'sometimes|in:pendiente,atendida,cancelada',
            'prioridad' => 'sometimes|in:alta,normal,baja',
            'confirmada' => 'sometimes|boolean',
            'observaciones' => 'nullable|string'
        ], [
            'paciente.required' => 'El nombre del paciente es obligatorio.',
            'fecha_hora.required' => 'La fecha y hora son obligatorias.',
            'tipo.in' => 'El tipo debe ser psicologica, legal o social.',
            'estado.in' => 'El estado debe ser pendiente, atendida o cancelada.',
            'prioridad.in' => 'La prioridad debe ser alta, normal o baja.',
            'confirmada.boolean' => 'Confirmada debe ser verdadero o falso.'
        ]);
        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        // Asignar automáticamente el usuario autenticado
        $validated['user_id'] = Auth::id();


        $cita = Cita::create($validated);
        return response()->json($cita, 201);
    }

    public function show($id)
    {
        return Cita::with('user')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $cita = Cita::findOrFail($id);
        $cita->update($request->all());
        return response()->json($cita, 200);
    }

    public function destroy($id)
    {
        Cita::destroy($id);
        return response()->json(['message' => 'Cita eliminada'], 204);
    }
}
