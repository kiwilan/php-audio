<?php

namespace Kiwilan\Audio\Utils;

use Kiwilan\Audio\Exceptions\AudioException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Execute process to handle audio file.
 */
class AudioProcess
{
    protected ?string $command = null;

    protected ?string $binary = null;

    protected bool $binary_available = false;

    protected mixed $output = null;

    protected bool $successful = false;

    /**
     * Execute command.
     *
     * @param  string[]  $command
     */
    public static function execute(string|array $command): self
    {
        $self = new self;

        if (is_string($command)) {
            $command = explode(' ', $command);
        }

        $binary = array_reverse($command);
        $self->binary = array_pop($binary);
        $self->binary_available = $self->testBinary();

        $process = new Process($command);
        $process->run();
        $self->successful = $process->isSuccessful();

        if (! $self->successful) {
            throw new ProcessFailedException($process);
        }

        $self->output = $process->getOutput();
        $self->command = implode(' ', $command);

        return $self;
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function getBinary(): ?string
    {
        return $this->binary;
    }

    public function binaryIsAvailable(): bool
    {
        return $this->binary_available;
    }

    public function getOutput(): mixed
    {
        return $this->output;
    }

    public function isSuccessful(): bool
    {
        return $this->successful;
    }

    /**
     * Check if binary is available.
     */
    private function testBinary(): bool
    {
        $process = new Process($this->convertBinary());
        $process->run();

        return $process->isSuccessful();
    }

    /**
     * @return string[]
     */
    private function convertBinary(): array
    {
        return match ($this->binary) {
            'ffmpeg' => ['ffmpeg', '-version'],
            'ffprobe' => ['ffprobe', '-version'],
            'exiftool' => ['exiftool', '-ver'],
            default => throw new AudioException("{$this->binary} not found!"),
        };
    }
}
