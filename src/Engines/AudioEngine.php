<?php

namespace Kiwilan\Audio\Engines;

use Kiwilan\Audio\Utils\AudioProcess;

abstract class AudioEngine
{
    protected ?AudioProcess $process = null;

    protected ?array $output = null;

    abstract public static function handle(string $path);

    /**
     * Raw output of engine for tags.
     */
    abstract public function tags(): ?array;

    /**
     * How to use output of engine with mapping.
     *
     * @return array<string, mixed>
     */
    abstract public function mapping(): array;

    public function getProcess(): ?AudioProcess
    {
        return $this->process;
    }

    public function getOutput(): ?array
    {
        return $this->output;
    }

    protected function toAssociativeArray(mixed $contents): array
    {
        return json_decode($contents, true);
    }

    protected function toPrettyJson(mixed $json): string
    {
        $data = json_decode($json, true);
        $flatJson = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
