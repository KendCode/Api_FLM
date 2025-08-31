<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cita;
use Illuminate\Support\Facades\Auth;

class CitaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/citas",
     *     summary="Obtener todas las citas",
     *     tags={"Citas"},
     *     @OA\Response(response=200, description="Listado de citas")
     * )
     */
    public function index()
    {
        return Cita::with('user')->get();
    }

    /**
     * @OA\Post(
     *     path="/api/citas",
     *     summary="Crear una nueva cita",
     *     tags={"Citas"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"paciente","fecha_hora","tipo"},
     *             @OA\Property(property="paciente", type="string", example="Juan Pérez"),
     *             @OA\Property(property="fecha_hora", type="string", format="date-time", example="2025-09-01 10:00:00"),
     *             @OA\Property(property="tipo", type="string", example="psicologica"),
     *             @OA\Property(property="estado", type="string", example="pendiente"),
     *             @OA\Property(property="prioridad", type="string", example="alta"),
     *             @OA\Property(property="confirmada", type="boolean", example=false),
     *             @OA\Property(property="observaciones", type="string", example="Primera consulta")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Cita creada correctamente"),
     *     @OA\Response(response=401, description="Usuario no autenticado"),
     *     @OA\Response(response=422, description="Datos inválidos")
     * )
     */
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

        $validated['user_id'] = Auth::id();
        $cita = Cita::create($validated);
        return response()->json($cita, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/citas/{id}",
     *     summary="Obtener una cita por ID",
     *     tags={"Citas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Cita encontrada"),
     *     @OA\Response(response=404, description="Cita no encontrada")
     * )
     */
    public function show($id)
    {
        return Cita::with('user')->findOrFail($id);
    }

    /**
     * @OA\Put(
     *     path="/api/citas/{id}",
     *     summary="Actualizar una cita",
     *     tags={"Citas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="paciente", type="string"),
     *             @OA\Property(property="fecha_hora", type="string"),
     *             @OA\Property(property="tipo", type="string"),
     *             @OA\Property(property="estado", type="string"),
     *             @OA\Property(property="prioridad", type="string"),
     *             @OA\Property(property="confirmada", type="boolean"),
     *             @OA\Property(property="observaciones", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Cita actualizada"),
     *     @OA\Response(response=404, description="Cita no encontrada")
     * )
     */
    public function update(Request $request, $id)
    {
        $cita = Cita::findOrFail($id);
        $cita->update($request->all());
        return response()->json($cita, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/citas/{id}",
     *     summary="Eliminar una cita",
     *     tags={"Citas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Cita eliminada"),
     *     @OA\Response(response=404, description="Cita no encontrada")
     * )
     */
    public function destroy($id)
    {
        Cita::destroy($id);
        return response()->json(['message' => 'Cita eliminada'], 204);
    }
}
