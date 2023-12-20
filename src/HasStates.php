<?php

namespace Dcodegroup\StateMachines;

use Dcodegroup\StateMachines\Models\Statusable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasStates
{
    public function statusables(): MorphMany
    {
        return $this->morphMany(Statusable::class, 'statusable');
    }


    /**
     * Dynamic relationship from the scopeWithLastStatus().
     * This won't work without the scope as we need to
     * virtually create a `last_statusable_id`.
     *
     * @return BelongsTo
     */
    public function lastStatusable(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Statusable::class);
    }

    /**
     * Use this scope if you want to use the lastStatusable. You will be able to use
     * eloquent features but won't be able to search or filter a model by a state.
     * This scope will eager load the lastStatusable model for you.
     *
     * @param  Builder  $query
     * @return void
     */
    public function scopeWithLastStatus(Builder $query): void
    {
        $query->addSelect(['last_statusable_id' => Statusable::select('id')
            ->whereColumn('statusable_id', 'users.id')
            ->where('statusable_type', self::class)
            ->take(1)
            ->latest(),
        ])->with(['lastStatusable']);
    }
}