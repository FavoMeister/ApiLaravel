<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
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
}
