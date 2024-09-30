<?php

namespace Kiwilan\Audio\Id3\Reader;

use Kiwilan\Audio\Id3\Id3Reader;
use Kiwilan\Audio\Id3\Tag;

class Id3AudioTag
{
    protected function __construct(
        readonly public ?Tag\Id3TagAudioV1 $id3v1 = null,
        readonly public ?Tag\Id3TagAudioV2 $id3v2 = null,
        readonly public ?Tag\Id3TagQuicktime $quicktime = null,
        readonly public ?Tag\Id3TagAsf $asf = null,
        readonly public ?Tag\Id3TagVorbisComment $vorbiscomment = null,
        readonly public ?Tag\Id3TagRiff $riff = null,
        readonly public ?Tag\Id3TagMatroska $matroska = null,
        readonly public ?Tag\Id3TagApe $ape = null,
        readonly public bool $is_empty = false,
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

        $is_empty = false;
        if (! $id3v1 && ! $id3v2 && ! $quicktime && ! $asf && ! $vorbiscomment && ! $riff && ! $matroska && ! $ape) {
            $is_empty = true;
        }

        $self = new self(
            id3v1: Tag\Id3TagAudioV1::make($id3v1),
            id3v2: Tag\Id3TagAudioV2::make($id3v2),
            quicktime: Tag\Id3TagQuicktime::make($quicktime),
            asf: Tag\Id3TagAsf::make($asf),
            vorbiscomment: Tag\Id3TagVorbisComment::make($vorbiscomment),
            riff: Tag\Id3TagRiff::make($riff),
            matroska: Tag\Id3TagMatroska::make($matroska),
            ape: Tag\Id3TagApe::make($ape),
            is_empty: $is_empty,
        );

        return $self;
    }
}
