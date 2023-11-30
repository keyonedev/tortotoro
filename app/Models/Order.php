<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @mixin Builder
 */
class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function positions() {
        return $this->belongsToMany(Position::class);
    }

    public function table() {
        return $this->belongsTo(Table::class);
    }

    public function workShift() {
        return $this->belongsTo(WorkShift::class);
    }
}
