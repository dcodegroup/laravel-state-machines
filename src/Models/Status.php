<?php

namespace Dcodegroup\LaravelStateMachines\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * @param  Builder  $query
     * @param  string  $machineName
     * @return Status|null
     */
    public function scopeFindByMachineName(Builder $query, string $machineName): ?Status
    {
        return $query->where('machine_name', $machineName)->first();
    }
}
