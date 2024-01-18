<?php

namespace Dcodegroup\StateMachines;

use Dcodegroup\StateMachines\Models\Status;
use Dcodegroup\StateMachines\Models\Statusable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasStates
{
    protected static string $defaultState = '';

    public static function bootHasStates()
    {
        static::created(function ($model) {
            self::createDefaultState($model);
        });
    }

    abstract public function state();

    public function statusables(): MorphMany
    {
        return $this->morphMany(Statusable::class, 'statusable');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    protected static function createDefaultState($model)
    {
        if (! self::$defaultState) {
            return;
        }

        $status = Status::where('machine_name', self::$defaultState)->first();

        Statusable::create([
            'status_id' => $status->id,
            'statusable_id' => $model->id,
            'statusable_type' => self::class,
        ]);

        $model->update(['status_id', $status->id]);
    }
}
