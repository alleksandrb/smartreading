<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\MessageResource;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {}

    public function login(LoginRequest $request): MessageResource
    {
        $token = $this->authService->login(
            email: $request->input('email'),
            password: $request->input('password'),
        );
        
        return new MessageResource([
            'message' => 'Successfully logged in',
            'token' => $token,
        ]);
    }

    public function logout(Request $request): MessageResource
    {
        $this->authService->logout($request->user());
        
        return new MessageResource([
            'message' => 'Successfully logged out',
        ]);
    }
} 