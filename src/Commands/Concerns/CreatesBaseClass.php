<?php

namespace Dcodegroup\StateMachines\Commands\Concerns;

use Illuminate\Support\Facades\File;

trait CreatesBaseClass
{
    protected function createBaseClass(string $directory, string $model, array $data)
    {
        $content = File::get(__DIR__."/../../../stubs/base_state.stub");

        foreach ($data as $key => $value) {
            $content = $this->replaceBaseClassPlaceholders($key, $value, $content);
        }

        $path = "{$directory}/Base{$model}State.php";

        File::put($path, $content);

        $this->info("File created: {$path}");
    }

    protected function replaceBaseClassPlaceholders($placeholder, $value, $content)
    {
        if (is_array($value)) {
            $transitions = '';

            foreach ($value as $key => $subValue) {
                $transitions .= "    public function $subValue()\n";
                $transitions .= "    {\n";
                $transitions .= "        throw new CannotTransitionToStateException();\n";
                $transitions .= "    }\n\n";
            }

            $content = str_replace('{{ transition_methods }}', rtrim($transitions), $content);
        } else {
            $content = str_replace("{{ $placeholder }}", $value, $content);
        }

        return $content;
    }
}