<?php

namespace App\Http\Controllers\API;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(protected AuthService $service) {}

    public function register(RegisterRequest $request)
    {
        $user = $this->service->register($request);

        return response()->success('User registered successfully', new UserResource($user), 201);
    }

    public function login(LoginRequest $request)
    {
        [$user, $token] = $this->service->login($request);

        return response()->success('Login successful', [
            'token' => $token,
            'user' => new UserResource($user),
        ]);
    }

    public function logout(){

        $this->service->logOut();
        
        return response()->success('Logout Berhasil!');
    }
}
