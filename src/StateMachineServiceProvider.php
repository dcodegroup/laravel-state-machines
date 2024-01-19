<?php

namespace Dcodegroup\LaravelStateMachines;

use Dcodegroup\LaravelStateMachines\Commands\MakeStateMachine;
use Illuminate\Support\ServiceProvider;
class StateMachineServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../database/migrations/create_statuses_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_statuses_table.php'),
            __DIR__ . '/../database/migrations/create_statusables_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time() + 1) . '_create_statusables_table.php'),
        ], 'statuses-migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeStateMachine::class,
            ]);
        }
    }
}
