<?php

namespace Kiwilan\Audio;

use Kiwilan\Audio\Enums\AudioEngineEnum;
use Kiwilan\Audio\Enums\AudioFormatEnum;

class Audio
{
    protected function __construct(
        protected string $path,
        protected string $extension,
        protected int $size,
        protected AudioFormatEnum $format,
        protected AudioEngineEnum $engine,
    ) {}

    public static function read(string $path, AudioEngineEnum $engine = AudioEngineEnum::getid3): self
    {
        $fileExists = file_exists($path);
        if (! $fileExists) {
            throw new \Exception("File not found: {$path}");
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $extension = strtolower($extension);
        $format = AudioFormatEnum::tryFrom($extension);

        $self = new self(
            path: $path,
            extension: $extension,
            size: filesize($path),
            format: $format ? $format : AudioFormatEnum::unknown,
            engine: $engine,
        );
        $self->handleEngine();

        return $self;
    }

    /**
     * Get audio file path, like `/path/to/audio.mp3`.
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Get audio file extension, like `mp3`.
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * Get audio file size, like `3482910`.
     */
    public function getSize(bool $human = true, int $decimals = 2): int
    {
        return $this->size;
    }

    /**
     * Get audio file size human-readable, like `3.32 MB`.
     */
    public function getSizeHuman(int $decimals = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $factor = floor((strlen((string) $this->size) - 1) / 3);

        return sprintf("%.{$decimals}f %s", $this->size / (1024 ** $factor), $units[$factor]);
    }

    /**
     * Get audio format if recognized, like `AudioFormatEnum::mp3`.
     */
    public function getFormat(): AudioFormatEnum
    {
        return $this->format;
    }

    private function handleEngine()
    {
        switch ($this->engine) {
            case AudioEngineEnum::getid3:
                //
                break;

            case AudioEngineEnum::ffmpeg:
                //
                break;

            case AudioEngineEnum::exiftool:
                //
                break;

            default:
                //
                break;
        }
    }
}
