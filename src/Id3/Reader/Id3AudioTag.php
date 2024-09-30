<?php

namespace Kiwilan\Audio\Id3\Reader;

use Kiwilan\Audio\Id3\Id3Reader;
use Kiwilan\Audio\Id3\Tag;

class Id3AudioTag
{
    protected function __construct(
        protected ?Tag\Id3TagAudioV1 $id3v1 = null,
        protected ?Tag\Id3TagAudioV2 $id3v2 = null,
        protected ?Tag\Id3TagQuicktime $quicktime = null,
        protected ?Tag\Id3TagAsf $asf = null,
        protected ?Tag\Id3TagVorbisComment $vorbiscomment = null,
        protected ?Tag\Id3TagRiff $riff = null,
        protected ?Tag\Id3TagMatroska $matroska = null,
        protected ?Tag\Id3TagApe $ape = null,
        protected bool $is_empty = false,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $id3v1 = Id3Reader::cleanTags($metadata['id3v1'] ?? null);
        $id3v2 = Id3Reader::cleanTags($metadata['id3v2'] ?? null);
        $quicktime = Id3Reader::cleanTags($metadata['quicktime'] ?? null);
        $asf = Id3Reader::cleanTags($metadata['asf'] ?? null);
        $vorbiscomment = Id3Reader::cleanTags($metadata['vorbiscomment'] ?? null);
        $riff = Id3Reader::cleanTags($metadata['riff'] ?? null);
        $matroska = Id3Reader::cleanTags($metadata['matroska'] ?? null);
        $ape = Id3Reader::cleanTags($metadata['ape'] ?? null);

        $self = new self(
            id3v1: Tag\Id3TagAudioV1::make($id3v1),
            id3v2: Tag\Id3TagAudioV2::make($id3v2),
            quicktime: Tag\Id3TagQuicktime::make($quicktime),
            asf: Tag\Id3TagAsf::make($asf),
            vorbiscomment: Tag\Id3TagVorbisComment::make($vorbiscomment),
            riff: Tag\Id3TagRiff::make($riff),
            matroska: Tag\Id3TagMatroska::make($matroska),
            ape: Tag\Id3TagApe::make($ape),
        );

        if (! $self->id3v1 && ! $self->id3v2 && ! $self->quicktime && ! $self->asf && ! $self->vorbiscomment && ! $self->riff && ! $self->matroska && ! $self->ape) {
            $self->is_empty = true;
        }

        return $self;
    }

    public function id3v1(): ?Tag\Id3TagAudioV1
    {
        return $this->id3v1;
    }

    public function id3v2(): ?Tag\Id3TagAudioV2
    {
        return $this->id3v2;
    }

    public function quicktime(): ?Tag\Id3TagQuicktime
    {
        return $this->quicktime;
    }

    public function asf(): ?Tag\Id3TagAsf
    {
        return $this->asf;
    }

    public function vorbiscomment(): ?Tag\Id3TagVorbisComment
    {
        return $this->vorbiscomment;
    }

    public function riff(): ?Tag\Id3TagRiff
    {
        return $this->riff;
    }

    public function matroska(): ?Tag\Id3TagMatroska
    {
        return $this->matroska;
    }

    public function ape(): ?Tag\Id3TagApe
    {
        return $this->ape;
    }

    public function isEmpty(): bool
    {
        return $this->is_empty;
    }
}
