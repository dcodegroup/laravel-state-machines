<?php

namespace Dcodegroup\StateMachines;

use Dcodegroup\StateMachines\Exceptions\StatusNotFoundException;
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
            self::setDefaultState($model);
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

    /**
     * @param  string  $machineName
     * @return void
     * @throws StatusNotFoundException
     */
    public function setStatus(string $machineName)
    {
        $status = Status::findByMachineName($machineName);

        if (! $status) {
            throw new StatusNotFoundException("The status $machineName could not be found.");
        }

        Statusable::create([
            'status_id' => $status->id,
            'statusable_id' => $this->id,
            'statusable_type' => self::class,
        ]);

        $this->update(['status_id', $status->id]);
    }

    protected static function setDefaultState($model)
    {
        if (! self::$defaultState) {
            return;
        }

        $model->setStatus(self::$defaultState);
    }
}
