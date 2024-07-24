<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\User\AuthUserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * @var UserService
     */
    protected UserService $userService;

    /**
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        return $this->jsonResponse([
            'user' => AuthUserResource::make($this->userService->register($credentials))
        ], 'Success',
            201
        );
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $token = $this->userService->login($credentials);

        if (!$token) {
            return $this->jsonResponse(null, 'Unauthorized', 403);
        }

        return $this->jsonResponse(['token' => $token, 'expires_in' => 60 * 24], 'Success');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request) : JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->jsonResponse(null, 'Successfully logged out');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        return $this->jsonResponse(['user' => AuthUserResource::make($request->user())], 'Success');
    }
}
