<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class CarController extends Controller
{

    public function index(Request $request)
    {

        return response()->json([
            'message' => 'Lista de autos (Acceso autorizado)',
            'user' => auth()->user(),
            'data' => Car::all()
        ], 200);

    }

    public function create()
    {
        
    }

    public function show($id)
    {
        try {
            // load eager loading 
            // If it does not exist returns 404
            $car = Car::with('user')
                    ->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Detalles del auto',
                'data' => [
                    'car' => $car,
                    'current_user' => auth()->user()->only('id', 'name', 'email')
                ]
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Auto no encontrado'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);
        try {
            $car = Car::findOrFail($id);

            // Only if the owner is the current user
            if ($car->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para actualizar este auto'
                ], 403);
            }

            $car->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Auto actualizado exitosamente',
                'data' => [
                    'car' => $car->fresh(), // Refresh the model with updated data
                    'owner' => $car->user()->select('id', 'name', 'email')->first()
                ]
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Auto no encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el auto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        // Validations
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        try {
            $car = Car::create([
                'user_id' => auth()->user()->id,
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'price' => $validatedData['price'],
                'status' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Auto creado exitosamente',
                'data' => $car
            ], 201); // Código HTTP 201: Created

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el auto',
                'error' => $e->getMessage()
            ], 500); // Código HTTP 500: Internal Server Error
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $car = Car::findOrFail($id);
            $car->delete();

            return response()->json([
                'success' => true,
                'message' => 'Auto eliminado permanentemente',
                'data' => [
                    'deleted_car_id' => $id,
                    'user' => auth()->user()->only('id', 'name')
                ]
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Auto no encontrado'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error eliminando auto: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno al eliminar el auto',
                'error' => config('app.debug') ? $e->getMessage() : 'Contacta al administrador'
            ], 500);
        }
    }
}
