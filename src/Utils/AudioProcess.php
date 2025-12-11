<?php

namespace Kiwilan\Audio\Utils;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class AudioProcess
{
    /**
     * Execute command.
     *
     * @param  string[]  $command
     */
    public static function execute(string|array $command)
    {
        if (is_string($command)) {
            $command = explode(' ', $command);
        }

        $process = new Process($command);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process->getOutput();
    }

    /**
     * Check if binary is available.
     */
    public static function isCommandAvailable(string|array $command): bool
    {
        if (is_string($command)) {
            $command = explode(' ', $command);
        }

        $process = new Process($command);
        $process->run();

        return $process->isSuccessful();
    }
}
