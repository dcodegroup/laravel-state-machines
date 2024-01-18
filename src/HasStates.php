<?php

namespace Dcodegroup\StateMachines;

use Dcodegroup\StateMachines\Models\Status;
use Dcodegroup\StateMachines\Models\Statusable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasStates
{
    abstract public function state();

    public function statusables(): MorphMany
    {
        return $this->morphMany(Statusable::class, 'statusable');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }
}
