<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;

class AuthController extends Controller
{
    //En el controlador creamos funciones en las que utilicemos las de los services
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        try {
            $result = $this->authService->register($request->validated());

            return response()->json(
                $result,
                201
            );
        } catch (\Exception $e) {

            //Manejo de errores
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);

        }
    }

    public function login(LoginRequest $request)
    {
        try {
            
            $result = $this->authService->login($request->validated());

            return response()->json(
                $result,
                200
            );

        } catch (\Exception $e) { 

            //Manejo de errores
            return response()->json([
                'message' => $e->getMessage(),
            ], 401);

        }
    }

    public function logout(Request $request)
    {
        try {

            $result = $this->authService->logout($request->user());

            return response()->json(
                $result,
                200
            );

        } catch (\Exception $e) {

            //Manejo de errores
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);

        }
    }
}
