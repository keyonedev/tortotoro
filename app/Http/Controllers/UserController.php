<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct(private readonly UserService $service) {}

    public function getAll() {
        return $this->service->getAll();
    }

    public function create(UserRequest $request) {
        return $this->service->create($request->validated());
    }
}
