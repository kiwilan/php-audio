<?php

namespace Kiwilan\Audio\Engines;

use getID3;

class Id3Engine extends AudioEngine
{
    public static function handle(string $path)
    {
        $self = new self;

        $instance = new getID3;
        $metadata = $instance->analyze($path);
        $self->output = $self->cleanData($metadata);

        // $metadata['id3v2']['APIC'] = null;
        // $metadata['ape']['items']['cover art (front)'] = null;
        // $metadata['comments'] = null;

        return $self;
    }

    private function cleanData(array $array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = $this->cleanData($value);
            } else {
                if (is_numeric($value)) {
                    continue;
                }

                // The binary is detected only in strings
                if (is_string($value)) {
                    // If the string contains a null character or too many non-printable characters
                    if (strpos($value, "\0") !== false || (! ctype_print($value) && strlen($value) > 100)) {
                        $array[$key] = '[FILTERED BINARY DATA]';
                    }
                }
            }
        }

        return $array;
    }

    public function tags(): ?array
    {
        $tags_container = $this->output['tags'] ?? null;
        if (! $tags_container) {
            return null;
        }

        $best_codec_tags = $this->getBestCodec($tags_container);
        $tags = $this->handleTags($best_codec_tags);
        ray($tags);

        return $tags;
    }

    public function mapping(): array
    {
        return [];
    }

    private function handleTags(array $items): array
    {
        $tags = [];
        foreach ($items as $tag => $value) {
            if (count($value) === 1) {
                $tags[$tag] = reset($value);

            } else {
                $tags[$tag] = $value;
            }
        }

        return $tags;
    }

    private function getBestCodec(array $tags_container): array
    {
        $codecs = array_map('count', $tags_container);
        arsort($codecs);
        $best_codec = array_key_first($codecs);

        return $tags_container[$best_codec];
    }
}
