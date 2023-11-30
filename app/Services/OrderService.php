<?php

namespace App\Services;

use App\Http\Requests\OrderChangeRequest;
use App\Http\Resources\OrderChangeResource;
use App\Http\Resources\OrderCreateResource;
use App\Http\Resources\OrderGetResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\WorkShiftOrderResource;
use App\Models\Order;
use App\Models\Table;
use App\Models\User;
use App\Models\WorkShift;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderService {
    private function httpError(string $message, int $code = 422) {
        throw new HttpResponseException(
            response()->json([
                "error" => [
                    "code" => $code,
                    "message" => $message,
                ]
            ], $code)
        );
    }

    public function create($request) {
        $data = $request->validated();

        $workShift = WorkShift::find($data['work_shift_id']);

        if(!$workShift->active) {
            $this->httpError("Forbidden. The shift must be active!", 403);
        }

        if(empty(auth()->user()->workShift->id) || auth()->user()->workShift->id !== $workShift->id) {
            $this->httpError("Forbidden. You don't work this shift!", 403);
        }

        $order = Order::create([
            'table_id' => $data['table_id'],
            'shift_workers' => auth()->user()->name,
            'status' => 'Принят',
            'work_shift_id' => $workShift->id,
        ]);

        return new OrderCreateResource($order);
    }

    public function get(int $id) {
        $validator = Validator::make(['id' => $id], ['id' => 'exists:orders']);
        if($validator->fails()) {
            $this->httpError("Forbidden. There is no such order", 403);
        }

        $order = Order::find($id);

        if(auth()->user()->name !== $order->shift_workers) {
            $this->httpError("Forbidden. You did accept this order!", 403);
        }

        return new OrderGetResource($order);
    }

    public function orders(int $id) {
        $validator = Validator::make(['id' => $id], ['id' => 'exists:work_shifts']);
        if($validator->fails()) {
            $this->httpError("Forbidden. There is no such shift", 403);
        }

        $workShift = WorkShift::find($id);

        if ($workShift->id !== auth()->user()->workShift->id) {
            $this->httpError("Forbidden. You did not accept this order!", 403);
        }

        return new WorkShiftOrderResource($workShift);
    }

    public function changeStatus(OrderChangeRequest $request, int $id) {
        $validator = Validator::make(['id' => $id], ['id' => 'exists:orders']);
        if($validator->fails()) {
            $this->httpError("Forbidden. There is no such order", 403);
        }

        $order = Order::find($id);
        $data = $request->validated();

        if(auth()->user()->name !== $order->shift_workers) {
            $this->httpError("Forbidden! You did not accept this order!", 403);
        }

        if(!$order->workShift->active) {
            $this->httpError("You cannot change the order status of a closed shift!", 403);
        }

        switch ($data['status']) {
            case 'cancelled':
                $order->update([
                    'status' => 'Отменен'
                ]);

                return new OrderChangeResource($order);
            case 'paid-up':
                $order->update([
                    'status' => 'Оплачен'
                ]);

                return new OrderChangeResource($order);
            default:
                $this->httpError("Forbidden! Can't change existing order status", 403);
        }
    }

    public function getAllForChef() {
        $workShift = WorkShift::where('active', 'true')->first();

        $orders = $workShift->orders()
            ->where('status', 'Принят')
            ->orWhere('status', 'Готовится')
            ->get();

        return OrderResource::collection($orders);
    }

    public function changeStatusForChef(OrderChangeRequest $request, int $id) {
        $data = $request->validated();

        $order = Order::find($id);

        if(!$order->workShift->active) {
            $this->httpError("You cannot change the order status of a closed shift!", 403);
        }

        switch ($data['status']) {
            case 'preparing':
                $order->update([
                    'status' => 'Готовится'
                ]);

                return new OrderChangeResource($order);
            case 'ready':
                $order->update([
                    'status' => 'Готов'
                ]);

                return new OrderChangeResource($order);
            default:
                $this->httpError("Forbidden! Can't change existing order status", 403);
        }
    }
}
