<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Listar todos los usuarios
    public function index()
    {
        $usuarios = DB::talbe('usuarios')->get();
        return response()->json($usuarios);
    }

    // Listar usuario por ID
    public function show($id)
    {
        $usuario = DB::table('usuarios')->where('id', $id)->first();
        if (!$usuario) {
            return response()->json(['error' => 'usuario no encontrado'], 404);
        }
        return response()->json($usuario);
    }

    //login de usuario
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            return response()->json($user);
        }

        return response()->json(['message' => 'Credenciales incorrectas'], 401);
    }

    // crear un usuario

    public function create(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|unique:usuarios',
            'password' => 'required',
            'email' => 'required|email|unique:usuarios',
            'name' => 'required',
            'surname' => 'required',
            'age' => 'required|integer',
            'isAdmin' => 'boolean',
            'permittedLessions' => 'nullable|json',
            'image' => 'nullable|string',
        ]);

        $data['password'] = Hash::make($data['password']);
        DB::table('usuarios')->insert($data);
        return response()->json(['message' => 'Usuario creado con Exito'], 201);
    }

    //actualizar usuario existente

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'username' =>  'sometimes|unique:usuarios,username,' . $id,
            'password' => 'sometimes',
            'email' => 'sometimes|email|unique:usuarios,email,' . $id,
            'name' => 'sometimes',
            'surname' => 'sometimes',
            'age' => 'sometimes|integer',
            'isAdmin' => 'boolean',
            'permittedLessons' => 'nullable|json',
            'image' => 'nullable|string',
        ]);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        DB::table('usuarios')->where('id', $id)->update($data);
        return response()->json(['message' => 'usuarioactualizado con exito'], 201);
    }

    // eliminar usuario
    public function destroy($id)
    {
        DB::table('usuarios')->where('id', $id)->delete();
        return response()->json(['message' => 'usuario eliminado con exito'], 201);
    }
}
