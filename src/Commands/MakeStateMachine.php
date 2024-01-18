<?php

namespace Dcodegroup\StateMachines\Commands;

use Dcodegroup\StateMachines\Commands\Concerns\CreatesBaseClass;
use Dcodegroup\StateMachines\Commands\Concerns\CreatesInterface;
use Dcodegroup\StateMachines\Commands\Concerns\CreatesStateClasses;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeStateMachine extends Command
{
    use CreatesBaseClass;
    use CreatesInterface;
    use CreatesStateClasses;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:state-machine 
                            {name : The model name attached to the state machine} 
                            {--states= : Comma-separated list of states}
                            {--transitions= : Comma-separated list of transitions}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate state machine files.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $model = Str::studly($this->argument('name'));
        $states = $this->parseListOption('states');
        $transitions = $this->parseListOption('transitions');

        $this->generateStateMachine($model, $states, $transitions);

        $this->info('State machine generated successfully!');
    }

    protected function parseListOption($optionName)
    {
        $optionValue = $this->option($optionName);

        return $optionValue ? explode(',', $optionValue) : [];
    }

    protected function generateStateMachine($model, $states, $transitions)
    {
        $pluralName = Str::plural($model);
        $name = Str::camel($model);

        // Create the directory for the state machine
        $directory = app_path("StateMachines/{$pluralName}");
        File::makeDirectory($directory, 0755, true, true);

        // Generate the interface file
        $this->createInterface($directory, $model, compact('model', 'transitions', 'pluralName'));

        // Generate the base state file
        $this->createBaseClass($directory, $model, compact('model', 'transitions', 'pluralName', 'name'));

        // Generate a file for each state
        foreach ($states as $state) {
            $stateName = Str::studly($state);
            $this->createStateClass($directory, compact('stateName', 'model', 'pluralName'));
        }
    }
}