<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkShiftRequest;
use App\Services\WorkShiftService;
use Illuminate\Http\Request;

class WorkShiftController extends Controller {
    public function __construct(private readonly WorkShiftService $service) {}

    public function create(WorkShiftRequest $request) {
        return $this->service->create($request->validated());
    }

    public function open(int $id) {
        return $this->service->open($id);
    }

    public function close(int $id) {
        return $this->service->close($id);
    }
}
