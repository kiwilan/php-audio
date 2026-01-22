<?php

namespace Kiwilan\Audio;

use Kiwilan\Audio\Engines\AudioEngine;
use Kiwilan\Audio\Engines\ExiftoolEngine;
use Kiwilan\Audio\Engines\FfmpegEngine;
use Kiwilan\Audio\Engines\Id3Engine;
use Kiwilan\Audio\Enums\AudioEngineEnum;
use Kiwilan\Audio\Exceptions\AudioException;
use Kiwilan\Audio\Models\AudioContainer;
use Kiwilan\Audio\Models\AudioProperties;
use Kiwilan\Audio\Models\AudioTags;

class Audio
{
    protected ?AudioEngine $engine = null;

    protected ?AudioTags $tags = null;

    protected ?AudioProperties $properties = null;

    protected function __construct(
        protected AudioContainer $container,
        protected AudioEngineEnum $engine_type,
    ) {}

    public static function read(string $path, AudioEngineEnum $engine_type = AudioEngineEnum::getid3): self
    {
        $fileExists = file_exists($path);
        if (! $fileExists) {
            throw new AudioException("File not found at {$path}");
        }

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $self = new self(
            container: AudioContainer::handle($path),
            engine_type: $engine_type,
        );

        $self->engine = $self->handleEngine();
        $self->handleTags();
        $self->handleProperties();

        return $self;
    }

    /**
     * Get `AudioContainer` with filesystem informations.
     */
    public function getContainer(): AudioContainer
    {
        return $this->container;
    }

    /**
     * Get audio format if recognized, like `AudioEngineEnum::ffmpeg`.
     */
    public function getEngineType(): AudioEngineEnum
    {
        return $this->engine_type;
    }

    /**
     * Get audio format if recognized, like `FfmpegEngine`.
     */
    public function getEngine(): AudioEngine
    {
        return $this->engine;
    }

    /**
     * Get `AudioTags` with audio tags informations.
     */
    public function getTags(): AudioTags
    {
        return $this->tags;
    }

    /**
     * Get `AudioProperties` with audio properties informations.
     */
    public function getProperties(): AudioProperties
    {
        return $this->properties;
    }

    public function handleEngine(): AudioEngine
    {
        switch ($this->engine_type) {
            case AudioEngineEnum::getid3:
                $engine = Id3Engine::handle($this->container->getPath());
                break;

            case AudioEngineEnum::ffmpeg:
                $engine = FfmpegEngine::handle($this->container->getPath());
                break;

            case AudioEngineEnum::exiftool:
                $engine = ExiftoolEngine::handle($this->container->getPath());
                break;

            default:
                $engine = Id3Engine::handle($this->container->getPath());
                break;
        }

        return $engine;
    }

    private function handleTags()
    {
        $this->tags = new AudioTags;

        $tags = $this->engine->tags();
        $this->tags->__set('raw', $tags);

        /**
         * `core_tag` like `subtitle`
         * `engine_tag` like `TIT3`
         *
         * Can be an array:
         * `core_tag` like `date`
         * `engine_tag` like `['TDRC','TYER','TDAT','date']`
         */
        foreach ($this->engine->mapping() as $core_tag => $engine_tag) {
            if (is_array($engine_tag)) {
                /**
                 * `sub_engine_tag` like `TYER`
                 */
                foreach ($engine_tag as $sub_engine_tag) {
                    $sub_value = $tags[$sub_engine_tag] ?? null;
                    if (! empty($sub_value)) {
                        $this->tags->__set($core_tag, $sub_value);
                    }
                }
            } else {
                $this->tags->__set($core_tag, $tags[$engine_tag] ?? null);
            }
        }
    }

    private function handleProperties()
    {
        if (empty($this->engine->getOutput())) {
            return;
        }

        $this->properties = new AudioProperties;
        $this->properties = $this->properties->handle($this->engine->getOutput());
    }
}
