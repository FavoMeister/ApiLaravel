<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            //'password' => ['required', 'confirmed', Password::defaults()],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function registerJson(Request $request)
    {
        $json = $request->input('json', null);
        $params = json_decode($json);

        $email = (!is_null($json) && isset($params->email)) ? $params->email : null;
        $name = (!is_null($json) && isset($params->name)) ? $params->name : null;
        $lastname = (!is_null($json) && isset($params->lastname)) ? $params->lastname : null;
        $role = 'ROLE_USER';
        $password = (!is_null($json) && isset($params->password)) ? $params->password : null;

        if(!is_null($email) && !is_null($password) && !is_null($name)) {
            $isseUser = User::where('email', $email)->first();

            if (count($isseUser) > 0) {
                $data = array(
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Usuario no creado'
                );
            } else {
                $user = User::create([
                    'email' => $email,
                    'name' => $name,
                    'surname' => $lastname,
                    'role' => $role,
                    'password' => Hash::make($password),
                ]);
                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Usuario creado correctamente'
                );
            }
            

            
        } else {
            $data = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'Usuario no creado'
            );
        }

        return response()->json($data, 200);

    }

    public function register(Request $request)
{
    $email = $request->input('email');
    $name = $request->input('name');
    $lastname = $request->input('lastname');
    $role = 'ROLE_USER';
    $password = $request->input('password');

    if(!is_null($email) && !is_null($password) && !is_null($name)) {
        $isseUser = User::where('email', $email)->first();

        if ($isseUser) {
            $data = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'Usuario ya existe'
            );
        } else {
            $user = User::create([
                'email' => $email,
                'name' => $name,
                'surname' => $lastname,
                'role' => $role,
                'password' => Hash::make($password),
            ]);
            $data = array(
                'status' => 'success',
                'code' => 200,
                'message' => 'Usuario creado correctamente'
            );
        }
    } else {
        $data = array(
            'status' => 'error',
            'code' => 400,
            'message' => 'Datos incompletos'
        );
    }

    return response()->json($data, 200);
}

}
