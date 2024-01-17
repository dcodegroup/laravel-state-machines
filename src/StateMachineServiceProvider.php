<?php

namespace Dcodegroup\StateMachines;

use Dcodegroup\StateMachines\Commands\MakeStateMachine;
use Illuminate\Support\ServiceProvider;
class StateMachineServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../database/migrations/create_statusables_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_statusables_table.php'),
                __DIR__ . '/../database/migrations/create_statuses_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_statuses_table.php'),
            ]);

            $this->commands([
                MakeStateMachine::class,
            ]);
        }
    }
}
