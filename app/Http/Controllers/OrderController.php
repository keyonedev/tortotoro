<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderChangeRequest;
use App\Http\Requests\OrderCreateRequest;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private readonly OrderService $service) {}

    public function create(OrderCreateRequest $request) {
        return $this->service->create($request);
    }

    public function get(int $id) {
        return $this->service->get($id);
    }

    public function orders(int $id) {
        return $this->service->orders($id);
    }

    public function getAllForChef() {
        return $this->service->getAllForChef();
    }

    public function changeStatus(OrderChangeRequest $request, int $id) {
        return $this->service->changeStatus($request, $id);
    }

    public function changeStatusForChef(OrderChangeRequest $request, int $id) {
        return $this->service->changeStatusForChef($request, $id);
    }
}
