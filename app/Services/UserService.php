<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserService {
    public function getAll() {
        return UserResource::collection(User::all());
    }

    public function create($data) {
        $user = User::create($data);

        return response()->json([
            "data" => [
                "id" => $user->id,
                "status" => "created"
            ]
        ], 201);
    }
}
