<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class AuthService
{
    public function register(array $data) : array
    {
        /*Creamos una variable que almacene un arreglo con los datos del usuario,
        los cuales obtenemos mediante $data*/
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']), 
        ]);

        //Creamos un LOG que registre la creación de un usuario, brindando su información
        Log::info('User registered successfully', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);

        return [
            'message' => 'User registered successfully',
            'user' => $user
        ];
    }

    public function login(array $data)
    {
        //Encontramos al usuario según su correo electrónico
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            //Estas excepciones las capturaremos en el try/catch del controller
            throw new \Exception(
                'User not found'
            );
        }
        
        //Validaciones por si el usuario existe, o si su contraseña no coincide con su hash
        if (!Hash::check($data['password'], $user->password)) {
            throw new \Exception(
                'Invalid credentials'
            );
        }
        
        //Creamos un token para el usuario logeado
        $token = $user->createToken(name: 'auth_token')->plainTextToken;

        //LOG que registre su inicio de sesión
        Log::info('User logged in successfully', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);
        
        //Lo que devuelve el servicio
        return [
            'message' => 'User logged in successfully',
            'user' => $user,
            'token' => $token
        ];

    }

    public function logout(User $user)
    {
        /*Obtenemos el token y lo eliminamos. Este token siempre se guarda en 
        "create_personal_tokens" creado automáticamente cuando instalamos 'api'*/
        $user->tokens()->delete(); //podemos utilizar "currentAccessToken" para eliminar solo el token actual, pero en este caso eliminaremos todos los tokens del usuario

        //LOG que registre su cierre de sesión
        Log::info('User logged out successfully', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        return [
            'message' => 'User logged out successfully',
        ];
    }
}