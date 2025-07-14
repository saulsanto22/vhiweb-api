<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $service
    ) {}

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

    public function logout()
    {
        $this->service->logOut();

        return response()->success('Logout Berhasil!');
    }
}
