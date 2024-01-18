<?php

namespace Dcodegroup\StateMachines\Commands\Concerns;

use Illuminate\Support\Facades\File;

trait CreatesInterface
{
    protected function createInterface(string $directory, string $model, array $data)
    {
        $content = File::get(resource_path(__DIR__."/../../stubs/state_machine_interface.stub"));

        foreach ($data as $key => $value) {
            $content = $this->replaceInterfacePlaceholders($key, $value, $content);
        }

        $path = "{$directory}/{$model}StateContract.php";

        File::put($path, $content);

        $this->info("File created: {$path}");
    }

    protected function replaceInterfacePlaceholders($placeholder, $value, $content)
    {
        if (is_array($value)) {
            $transitions = '';

            foreach ($value as $key => $subValue) {
                $transitions .= "    public function $subValue();\n\n";
            }

            $content = str_replace('{{ transition_methods }}', rtrim($transitions), $content);
        } else {
            $content = str_replace("{{ $placeholder }}", $value, $content);
        }

        return $content;
    }
}