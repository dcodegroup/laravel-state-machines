<?php

namespace Dcodegroup\StateMachines\Commands\Concerns;

use Illuminate\Support\Facades\File;

trait CreatesStateClasses
{
    protected function createStateClass(string $directory, array $data)
    {
        $content = File::get(resource_path(__DIR__."/../../stubs/state.stub"));

        foreach ($data as $key => $value) {
            $content = $this->replaceClassPlaceholders($key, $value, $content);
        }

        $path = "{$directory}/{$data['stateName']}State.php";

        File::put($path, $content);

        $this->info("File created: {$path}");
    }

    protected function replaceClassPlaceholders($placeholder, $value, $content)
    {
        return str_replace("{{ $placeholder }}", $value, $content);
    }
}