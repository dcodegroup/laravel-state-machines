<?php

namespace Dcodegroup\LaravelStateMachines\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Statusable extends Model
{
    protected $with = [
        'status'
    ];

    protected $fillable = [
        'status_id',
        'statusable_id',
        'statusable_type',
    ];

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $this->status->name,
        );
    }
}
