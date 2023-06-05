<?php

namespace Kiwilan\Audio;

use Kiwilan\Audio\Models\AudioCore;
use Kiwilan\Audio\Models\Id3AudioTagV1;
use Kiwilan\Audio\Models\Id3AudioTagV2;
use Kiwilan\Audio\Models\Id3TagApe;
use Kiwilan\Audio\Models\Id3TagAsf;
use Kiwilan\Audio\Models\Id3TagMatroska;
use Kiwilan\Audio\Models\Id3TagQuicktime;
use Kiwilan\Audio\Models\Id3TagVorbisComment;

class AudioConverter
{
    protected function __construct(
        protected AudioCore $core,
    ) {
    }

    public static function toId3v2(AudioCore $core): Id3AudioTagV2
    {
        return new Id3AudioTagV2(
            album: $core->album(),
            artist: $core->artist(),
            band: $core->albumArtist(),
            comment: $core->comment(),
            composer: $core->composer(),
            part_of_a_set: $core->discNumber(),
            genre: $core->genre(),
            part_of_a_compilation: $core->isCompilation(),
            title: $core->title(),
            track_number: $core->trackNumber(),
            year: $core->year(),
        );
    }

    public static function toId3v1(AudioCore $core): Id3AudioTagV1
    {
        return new Id3AudioTagV1(
            album: $core->album(),
            artist: $core->artist(),
            comment: $core->comment(),
            genre: $core->genre(),
            title: $core->title(),
            track_number: $core->trackNumber(),
            year: $core->year(),
        );
    }

    public static function toVorbisComment(AudioCore $core): Id3TagVorbisComment
    {
        return new Id3TagVorbisComment(
            album: $core->album(),
            artist: $core->artist(),
            albumartist: $core->albumArtist(),
            comment: $core->comment(),
            composer: $core->composer(),
            compilation: $core->isCompilation(),
            discnumber: $core->discNumber(),
            genre: $core->genre(),
            title: $core->title(),
            tracknumber: $core->trackNumber(),
            date: $core->year(),
            encoder: $core->encoding(),
            description: $core->description(),
        );
    }

    public static function toQuicktime(AudioCore $core): Id3TagQuicktime
    {
        return new Id3TagQuicktime(
            title: $core->title(),
            track_number: $core->trackNumber(),
            disc_number: $core->discNumber(),
            compilation: $core->isCompilation(),
            album: $core->album(),
            genre: $core->genre(),
            composer: $core->composer(),
            creation_date: $core->creationDate(),
            copyright: $core->copyright(),
            artist: $core->artist(),
            album_artist: $core->albumArtist(),
            encoded_by: $core->encoding(),
            encoding_tool: $core->encoding(),
            description: $core->description(),
            description_long: $core->description(),
            lyrics: $core->lyrics(),
            comment: $core->comment(),
            stik: $core->stik(),
        );
    }

    public static function toMatroska(AudioCore $core): Id3TagMatroska
    {
        return new Id3TagMatroska(
            title: $core->title(),
            album: $core->album(),
            artist: $core->artist(),
            album_artist: $core->albumArtist(),
            comment: $core->comment(),
            composer: $core->composer(),
            disc: $core->discNumber(),
            compilation: $core->isCompilation(),
            genre: $core->genre(),
            part_number: $core->trackNumber(),
            date: $core->year(),
            encoder: $core->encoding(),
        );
    }

    public static function toApe(AudioCore $core): Id3TagApe
    {
        return new Id3TagApe(
            album: $core->album(),
            artist: $core->artist(),
            album_artist: $core->albumArtist(),
            comment: $core->comment(),
            composer: $core->composer(),
            disc: $core->discNumber(),
            compilation: $core->isCompilation(),
            genre: $core->genre(),
            title: $core->title(),
            track: $core->trackNumber(),
            date: $core->year(),
            encoder: $core->encoding(),
        );
    }

    public static function toAsf(AudioCore $core): Id3TagAsf
    {
        return new Id3TagAsf(
            album: $core->album(),
            artist: $core->artist(),
            albumartist: $core->albumArtist(),
            composer: $core->composer(),
            partofset: $core->discNumber(),
            genre: $core->genre(),
            track_number: $core->trackNumber(),
            year: $core->year(),
            encodingsettings: $core->encoding(),
        );
    }

    public static function fromId3(?Id3AudioTagV1 $v1, Id3AudioTagV2 $v2): AudioCore
    {
        return new AudioCore(
            album: $v2->album() ?? $v1->album(),
            artist: $v2->artist() ?? $v1->artist(),
            albumArtist: $v2->band() ?? null,
            comment: $v2->comment() ?? $v1->comment(),
            composer: $v2->composer() ?? null,
            discNumber: $v2->part_of_a_set() ?? null,
            genre: $v2->genre() ?? $v1->genre(),
            isCompilation: $v2->part_of_a_compilation() ?? null,
            title: $v2->title() ?? $v1->title(),
            trackNumber: $v2->track_number() ?? $v1->track_number(),
            year: $v2->year() ?? $v1->year(),
        );
    }

    public static function fromId3v2(Id3AudioTagV2 $tag): AudioCore
    {
        return new AudioCore(
            album: $tag->album(),
            artist: $tag->artist(),
            albumArtist: $tag->band(),
            comment: $tag->comment(),
            composer: $tag->composer(),
            discNumber: $tag->part_of_a_set(),
            genre: $tag->genre(),
            isCompilation: $tag->part_of_a_compilation(),
            title: $tag->title(),
            trackNumber: $tag->track_number(),
            year: $tag->year(),
        );
    }

    public static function fromId3v1(Id3AudioTagV1 $tag): AudioCore
    {
        return new AudioCore(
            album: $tag->album(),
            artist: $tag->artist(),
            comment: $tag->comment(),
            genre: $tag->genre(),
            title: $tag->title(),
            trackNumber: $tag->track_number(),
            year: $tag->year(),
        );
    }

    public static function fromQuicktime(Id3TagQuicktime $tag): AudioCore
    {

        $creation_date = $tag->creation_date();
        $description = $tag->description();
        $description_long = $tag->description_long();

        if ($description_long && $description && strlen($description_long) > strlen($description)) {
            $description = $description_long;
        }

        $core = new AudioCore(
            title: $tag->title(),
            artist: $tag->artist(),
            album: $tag->album(),
            genre: $tag->genre(),
            trackNumber: $tag->track_number(),
            discNumber: $tag->disc_number(),
            composer: $tag->composer(),
            isCompilation: $tag->compilation(),
            comment: $tag->comment(),
            albumArtist: $tag->album_artist(),
            encodingBy: $tag->encoded_by(),
            encoding: $tag->encoding_tool(),
        );

        if ($creation_date) {
            if (strlen($creation_date) === 4) {
                $core->setYear((int) $creation_date);
            } else {
                $creation_date = date_create_from_format('Y-m-d\TH:i:s\Z', $creation_date);
                $core->setCreationDate($creation_date?->format('Y-m-d\TH:i:s\Z'));
                $core->setYear((int) $creation_date?->format('Y'));
            }
        }

        $core->setCopyright($tag->copyright());
        $core->setDescription($description);
        $core->setLyrics($tag->lyrics());
        $core->setStik($tag->stik());

        return $core;
    }

    public static function fromVorbisComment(Id3TagVorbisComment $tag): AudioCore
    {
        return new AudioCore(
            title: $tag->title(),
            artist: $tag->artist(),
            album: $tag->album(),
            genre: $tag->genre(),
            trackNumber: $tag->tracknumber(),
            comment: $tag->comment(),
            albumArtist: $tag->albumartist(),
            composer: $tag->composer(),
            discNumber: $tag->discnumber(),
            isCompilation: $tag->compilation(),
            year: (int) $tag->date(),
            encoding: $tag->encoder(),
            description: $tag->description(),
        );
    }

    public static function fromAsf(Id3TagAsf $tag): AudioCore
    {
        return new AudioCore(
            title: $tag->title(),
            artist: $tag->artist(),
            album: $tag->album(),
            albumArtist: $tag->albumartist(),
            composer: $tag->composer(),
            discNumber: $tag->partofset(),
            genre: $tag->genre(),
            trackNumber: $tag->track_number(),
            year: (int) $tag->year(),
            encoding: $tag->encodingsettings(),
        );
    }

    public static function fromMatroska(Id3TagMatroska $tag): AudioCore
    {
        return new AudioCore(
            title: $tag->title(),
            album: $tag->album(),
            artist: $tag->artist(),
            albumArtist: $tag->album_artist(),
            comment: $tag->comment(),
            composer: $tag->composer(),
            discNumber: $tag->disc(),
            genre: $tag->genre(),
            isCompilation: $tag->compilation(),
            trackNumber: $tag->part_number(),
            year: (int) $tag->date(),
            encoding: $tag->encoder(),
        );
    }

    public static function fromApe(Id3TagApe $tag): AudioCore
    {
        return new AudioCore(
            album: $tag->album(),
            artist: $tag->artist(),
            albumArtist: $tag->album_artist(),
            comment: $tag->comment(),
            composer: $tag->composer(),
            discNumber: $tag->disc(),
            genre: $tag->genre(),
            isCompilation: $tag->compilation(),
            title: $tag->title(),
            trackNumber: $tag->track(),
            year: (int) $tag->date(),
            encoding: $tag->encoder(),
        );
    }
}
