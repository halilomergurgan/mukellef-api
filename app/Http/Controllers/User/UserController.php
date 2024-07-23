<?php

namespace App\Http\Controllers\User;

use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    use ApiResponse;

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        $user->load(['subscriptions', 'transactions']);

        return $this->jsonResponse(new UserResource($user), 'Success');
    }
}
