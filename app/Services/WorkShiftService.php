<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Http\Resources\WorkShiftResource;
use App\Models\User;
use App\Models\WorkShift;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;

class WorkShiftService {
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

    public function create($data) {
        if ($data['start'] === $data['end']) {
            $this->httpError("Dates cannot be the same");
        }

        if ($data['start'] < date('Y-m-d H:i')) {
            $this->httpError("The starting date cannot be less than the current one");
        }

        if($data['end'] < $data['start']) {
            $this->httpError("The end date cannot be greater than the start date");
        }

        $workShift = WorkShift::firstOrCreate($data);

        return response()->json([
            'id' => $workShift->id,
            'start' => $workShift->start,
            'end' => $workShift->end
        ], 201);
    }

    public function open(int $id) {
        $countOfActiveWorkShifts = WorkShift::where('active', true)->get()->count();
        if ($countOfActiveWorkShifts > 0) {
            $this->httpError("Forbidden. There are open shifts!", 403);
        }

        $workShift = WorkShift::find($id);
        $workShift->update([
            'active' => true
        ]);

        return new WorkShiftResource($workShift);
    }

    public function close(int $id) {
        $workShift = WorkShift::find($id);

        if ($workShift->active) {
            $workShift->update([
                'active' => false
            ]);

            return new WorkShiftResource($workShift);
        } else {
            $this->httpError("Forbidden. The shift is already closed!", 403);
        }
    }
}
