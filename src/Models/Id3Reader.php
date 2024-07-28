<?php

namespace Kiwilan\Audio\Models;

use getID3;

class Id3Reader
{
    protected array $raw = [];

    protected function __construct(
        protected getID3 $instance,
        protected bool $is_writable = false,
        protected ?string $version = null,
        protected ?int $filesize = null,
        protected ?string $filepath = null,
        protected ?string $filename = null,
        protected ?string $filenamepath = null,
        protected ?int $avdataoffset = null,
        protected ?int $avdataend = null,
        protected ?string $fileformat = null,
        protected ?Id3Audio $audio = null,
        protected ?Id3Video $video = null,
        protected ?Id3AudioTag $tags = null,
        protected ?Id3Comments $comments = null,
        protected ?string $encoding = null,
        protected ?string $mime_type = null,
        protected ?array $mpeg = null,
        protected ?float $playtime_seconds = null,
        protected ?Id3TagsHtml $tags_html = null,
        protected ?float $bitrate = null,
        protected ?string $playtime_string = null,
    ) {}

    public static function make(string $path): self
    {
        $self = new self(new getID3);

        $self->raw = $self->instance->analyze($path);
        $self->is_writable = $self->instance->is_writable($path);
        $metadata = $self->raw;

        $audio = Id3Audio::make($metadata['audio'] ?? null);
        $video = Id3Video::make($metadata['video'] ?? null);
        $tags = Id3AudioTag::make($metadata['tags'] ?? null);
        $comments = Id3Comments::make($metadata['comments'] ?? null);
        $tags_html = Id3TagsHtml::make($metadata['tags_html'] ?? null);
        $bitrate = $metadata['bitrate'] ?? null;
        if ($bitrate) {
            $bitrate = intval($bitrate);
        }

        $self->version = $metadata['GETID3_VERSION'] ?? null;
        $self->filesize = $metadata['filesize'] ?? null;
        $self->filepath = $metadata['filepath'] ?? null;
        $self->filename = $metadata['filename'] ?? null;
        $self->filenamepath = $metadata['filenamepath'] ?? null;
        $self->avdataoffset = $metadata['avdataoffset'] ?? null;
        $self->avdataend = $metadata['avdataend'] ?? null;
        $self->fileformat = $metadata['fileformat'] ?? null;
        $self->audio = $audio;
        $self->video = $video;
        $self->tags = $tags;
        $self->comments = $comments;
        $self->encoding = $metadata['encoding'] ?? null;
        $self->mime_type = $metadata['mime_type'] ?? null;
        $self->mpeg = $metadata['mpeg'] ?? null;
        $self->playtime_seconds = $metadata['playtime_seconds'] ?? null;
        $self->tags_html = $tags_html;
        $self->bitrate = $bitrate;
        $self->playtime_string = $metadata['playtime_string'] ?? null;

        return $self;
    }

    public function getInstance(): getID3
    {
        return $this->instance;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function getFilesize(): ?int
    {
        return $this->filesize;
    }

    public function getFilepath(): ?string
    {
        return $this->filepath;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function getFilenamepath(): ?string
    {
        return $this->filenamepath;
    }

    public function getAvdataoffset(): ?int
    {
        return $this->avdataoffset;
    }

    public function getAvdataend(): ?int
    {
        return $this->avdataend;
    }

    public function getFileformat(): ?string
    {
        return $this->fileformat;
    }

    public function getAudio(): ?Id3Audio
    {
        return $this->audio;
    }

    public function getTags(): ?Id3AudioTag
    {
        return $this->tags;
    }

    public function getComments(): ?Id3Comments
    {
        return $this->comments;
    }

    public function getEncoding(): ?string
    {
        return $this->encoding;
    }

    public function getMimeType(): ?string
    {
        return $this->mime_type;
    }

    public function getMpeg(): mixed
    {
        return $this->mpeg;
    }

    public function getPlaytimeSeconds(): ?float
    {
        return $this->playtime_seconds;
    }

    public function getTagsHtml(): ?Id3TagsHtml
    {
        return $this->tags_html;
    }

    public function getBitrate(): ?float
    {
        return $this->bitrate;
    }

    public function getPlaytimeString(): ?string
    {
        return $this->playtime_string;
    }

    public function isWritable(): bool
    {
        return $this->is_writable;
    }

    public function getRaw(): array
    {
        return $this->raw;
    }

    public function toTags(?string $audioFormat = null): array
    {
        $rawTags = $this->raw['tags_html'] ?? [];

        if (count($rawTags) === 0) {
            return [];
        }

        $tagsItems = [];
        if ($audioFormat) {
            $tagsItems = $rawTags[$audioFormat] ?? [];
        } else {
            if (count($rawTags) > 1) {
                $entries = [];
                foreach ($rawTags as $key => $keyTags) {
                    $entries[$key] = count($keyTags);
                }
                $maxKey = array_search(max($entries), $entries);
                $tagsItems = $rawTags[$maxKey] ?? [];
            } else {
                $tagsItems = reset($rawTags);
            }
        }

        return Id3Reader::cleanTags($tagsItems);
    }

    public static function cleanTags(?array $tagsItems): array
    {
        if (! $tagsItems) {
            return [];
        }

        $temp = [];
        foreach ($tagsItems as $k => $v) {
            $temp[$k] = $v[0] ?? null;
        }

        $items = [];
        foreach ($temp as $k => $v) {
            $k = strtolower($k);
            $k = str_replace(' ', '_', $k);
            $items[$k] = $v;
        }

        return $items;
    }

    public function toAudioFormats(): array
    {
        return $this->raw['tags_html'] ?? [];
    }

    public function toArray(): array
    {
        $raw = $this->raw;
        $raw['id3v2']['APIC'] = null;
        $raw['ape']['items']['cover art (front)'] = null;
        $raw['comments'] = null;

        return $raw;
    }
}

class Id3Audio
{
    /** @var Id3Stream[] */
    protected array $streams = [];

    protected function __construct(
        protected ?string $dataformat = null,
        protected ?int $channels = null,
        protected ?int $sample_rate = null,
        protected ?float $bitrate = null,
        protected ?string $channelmode = null,
        protected ?string $bitrate_mode = null,
        protected ?string $codec = null,
        protected ?string $encoder = null,
        protected bool $lossless = false,
        protected ?string $encoder_options = null,
        protected ?float $compression_ratio = null,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $streams = [];
        if (array_key_exists('streams', $metadata)) {
            foreach ($metadata['streams'] as $stream) {
                $streams[] = Id3Stream::make($stream);
            }
        }

        $self = new self(
            dataformat: $metadata['dataformat'] ?? null,
            channels: $metadata['channels'] ?? null,
            sample_rate: $metadata['sample_rate'] ?? null,
            bitrate: $metadata['bitrate'] ?? null,
            channelmode: $metadata['channelmode'] ?? null,
            bitrate_mode: $metadata['bitrate_mode'] ?? null,
            codec: $metadata['codec'] ?? null,
            encoder: $metadata['encoder'] ?? null,
            lossless: $metadata['lossless'] ?? false,
            encoder_options: $metadata['encoder_options'] ?? null,
            compression_ratio: $metadata['compression_ratio'] ?? null,
        );
        $self->streams = $streams;

        return $self;
    }

    /** @return Id3Stream[] */
    public function streams(): array
    {
        return $this->streams;
    }

    public function dataformat(): ?string
    {
        return $this->dataformat;
    }

    public function channels(): ?int
    {
        return $this->channels;
    }

    public function sample_rate(): ?int
    {
        return $this->sample_rate;
    }

    public function bitrate(): ?float
    {
        return $this->bitrate;
    }

    public function channelmode(): ?string
    {
        return $this->channelmode;
    }

    public function bitrate_mode(): ?string
    {
        return $this->bitrate_mode;
    }

    public function codec(): ?string
    {
        return $this->codec;
    }

    public function encoder(): ?string
    {
        return $this->encoder;
    }

    public function lossless(): bool
    {
        return $this->lossless;
    }

    public function encoder_options(): ?string
    {
        return $this->encoder_options;
    }

    public function compression_ratio(): ?float
    {
        return $this->compression_ratio;
    }

    public function stream(): ?Id3Stream
    {
        return $this->streams[0] ?? null;
    }
}

class Id3Video
{
    protected function __construct(
        protected ?string $dataformat = null,
        protected ?int $rotate = null,
        protected ?float $resolution_x = null,
        protected ?float $resolution_y = null,
        protected ?float $frame_rate = null,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $self = new self(
            dataformat: $metadata['dataformat'] ?? null,
            rotate: $metadata['rotate'] ?? null,
            resolution_x: $metadata['resolution_x'] ?? null,
            resolution_y: $metadata['resolution_y'] ?? null,
            frame_rate: $metadata['frame_rate'] ?? null,
        );

        return $self;
    }

    public function dataformat(): ?string
    {
        return $this->dataformat;
    }

    public function rotate(): ?int
    {
        return $this->rotate;
    }

    public function resolution_x(): ?float
    {
        return $this->resolution_x;
    }

    public function resolution_y(): ?float
    {
        return $this->resolution_y;
    }

    public function frame_rate(): ?float
    {
        return $this->frame_rate;
    }
}

class Id3Stream
{
    protected function __construct(
        protected ?string $dataformat = null,
        protected ?int $channels = null,
        protected ?int $sample_rate = null,
        protected ?float $bitrate = null,
        protected ?string $channelmode = null,
        protected ?string $bitrate_mode = null,
        protected ?string $codec = null,
        protected ?string $encoder = null,
        protected bool $lossless = false,
        protected ?string $encoder_options = null,
        protected ?float $compression_ratio = null,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $self = new self(
            dataformat: $metadata['dataformat'] ?? null,
            channels: $metadata['channels'] ?? null,
            sample_rate: $metadata['sample_rate'] ?? null,
            bitrate: $metadata['bitrate'] ?? null,
            channelmode: $metadata['channelmode'] ?? null,
            bitrate_mode: $metadata['bitrate_mode'] ?? null,
            codec: $metadata['codec'] ?? null,
            encoder: $metadata['encoder'] ?? null,
            lossless: $metadata['lossless'] ?? false,
            encoder_options: $metadata['encoder_options'] ?? null,
            compression_ratio: $metadata['compression_ratio'] ?? null,
        );

        return $self;
    }

    public function dataformat(): ?string
    {
        return $this->dataformat;
    }

    public function channels(): ?int
    {
        return $this->channels;
    }

    public function sample_rate(): ?int
    {
        return $this->sample_rate;
    }

    public function bitrate(): ?float
    {
        return $this->bitrate;
    }

    public function channelmode(): ?string
    {
        return $this->channelmode;
    }

    public function bitrate_mode(): ?string
    {
        return $this->bitrate_mode;
    }

    public function codec(): ?string
    {
        return $this->codec;
    }

    public function encoder(): ?string
    {
        return $this->encoder;
    }

    public function lossless(): bool
    {
        return $this->lossless;
    }

    public function encoder_options(): ?string
    {
        return $this->encoder_options;
    }

    public function compression_ratio(): ?float
    {
        return $this->compression_ratio;
    }
}

class Id3AudioTag
{
    protected function __construct(
        protected ?Id3AudioTagV1 $id3v1 = null,
        protected ?Id3AudioTagV2 $id3v2 = null,
        protected ?Id3TagQuicktime $quicktime = null,
        protected ?Id3TagAsf $asf = null,
        protected ?Id3TagVorbisComment $vorbiscomment = null,
        protected ?Id3TagRiff $riff = null,
        protected ?Id3TagMatroska $matroska = null,
        protected ?Id3TagApe $ape = null,
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
            id3v1: Id3AudioTagV1::make($id3v1),
            id3v2: Id3AudioTagV2::make($id3v2),
            quicktime: Id3TagQuicktime::make($quicktime),
            asf: Id3TagAsf::make($asf),
            vorbiscomment: Id3TagVorbisComment::make($vorbiscomment),
            riff: Id3TagRiff::make($riff),
            matroska: Id3TagMatroska::make($matroska),
            ape: Id3TagApe::make($ape),
        );

        return $self;
    }

    public function id3v1(): ?Id3AudioTagV1
    {
        return $this->id3v1;
    }

    public function id3v2(): ?Id3AudioTagV2
    {
        return $this->id3v2;
    }

    public function quicktime(): ?Id3TagQuicktime
    {
        return $this->quicktime;
    }

    public function asf(): ?Id3TagAsf
    {
        return $this->asf;
    }

    public function vorbiscomment(): ?Id3TagVorbisComment
    {
        return $this->vorbiscomment;
    }

    public function riff(): ?Id3TagRiff
    {
        return $this->riff;
    }

    public function matroska(): ?Id3TagMatroska
    {
        return $this->matroska;
    }

    public function ape(): ?Id3TagApe
    {
        return $this->ape;
    }
}

class Id3AudioTagV1
{
    public function __construct(
        protected ?string $title = null,
        protected ?string $artist = null,
        protected ?string $album = null,
        protected ?string $year = null,
        protected ?string $genre = null,
        protected ?string $comment = null,
        protected ?string $track_number = null,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $self = new self(
            title: $metadata['title'] ?? null,
            artist: $metadata['artist'] ?? null,
            album: $metadata['album'] ?? null,
            year: $metadata['year'] ?? null,
            genre: $metadata['genre'] ?? null,
            comment: $metadata['comment'] ?? null,
            track_number: $metadata['track_number'] ?? null,
        );

        return $self;
    }

    public function title(): ?string
    {
        return $this->title;
    }

    public function artist(): ?string
    {
        return $this->artist;
    }

    public function album(): ?string
    {
        return $this->album;
    }

    public function year(): ?string
    {
        return $this->year;
    }

    public function genre(): ?string
    {
        return $this->genre;
    }

    public function comment(): ?string
    {
        return $this->comment;
    }

    public function track_number(): ?string
    {
        return $this->track_number;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'artist' => $this->artist,
            'album' => $this->album,
            'year' => $this->year,
            'genre' => $this->genre,
            'comment' => $this->comment,
            'track_number' => $this->track_number,
        ];
    }
}

class Id3AudioTagV2
{
    public function __construct(
        protected ?string $album = null,
        protected ?string $artist = null,
        protected ?string $band = null,
        protected ?string $comment = null,
        protected ?string $composer = null,
        protected ?string $part_of_a_set = null,
        protected ?string $genre = null,
        protected ?string $part_of_a_compilation = null,
        protected ?string $title = null,
        protected ?string $track_number = null,
        protected ?string $year = null,
        protected ?string $copyright = null,
        protected ?string $text = null,
        protected ?string $unsynchronised_lyric = null,
        protected ?string $language = null,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $self = new self(
            album: $metadata['album'] ?? null,
            artist: $metadata['artist'] ?? null,
            band: $metadata['band'] ?? null,
            comment: $metadata['comment'] ?? null,
            composer: $metadata['composer'] ?? null,
            part_of_a_set: $metadata['part_of_a_set'] ?? null,
            genre: $metadata['genre'] ?? null,
            part_of_a_compilation: $metadata['part_of_a_compilation'] ?? null,
            title: $metadata['title'] ?? null,
            track_number: $metadata['track_number'] ?? null,
            year: $metadata['year'] ?? null,
            copyright: $metadata['copyright_message'] ?? null,
            text: $metadata['text'] ?? null,
            unsynchronised_lyric: $metadata['unsynchronised_lyric'] ?? null,
            language: $metadata['language'] ?? null,
        );

        return $self;
    }

    public function album(): ?string
    {
        return $this->album;
    }

    public function artist(): ?string
    {
        return $this->artist;
    }

    public function band(): ?string
    {
        return $this->band;
    }

    public function comment(): ?string
    {
        return $this->comment;
    }

    public function composer(): ?string
    {
        return $this->composer;
    }

    public function part_of_a_set(): ?string
    {
        return $this->part_of_a_set;
    }

    public function genre(): ?string
    {
        return $this->genre;
    }

    public function part_of_a_compilation(): ?string
    {
        return $this->part_of_a_compilation;
    }

    public function title(): ?string
    {
        return $this->title;
    }

    public function track_number(): ?string
    {
        return $this->track_number;
    }

    public function year(): ?string
    {
        return $this->year;
    }

    public function copyright(): ?string
    {
        return $this->copyright;
    }

    public function text(): ?string
    {
        return $this->text;
    }

    public function unsynchronised_lyric(): ?string
    {
        return $this->unsynchronised_lyric;
    }

    public function language(): ?string
    {
        return $this->language;
    }

    public function toArray(): array
    {
        return [
            'album' => $this->album,
            'artist' => $this->artist,
            'band' => $this->band,
            'comment' => $this->comment,
            'composer' => $this->composer,
            'part_of_a_set' => $this->part_of_a_set,
            'genre' => $this->genre,
            'part_of_a_compilation' => $this->part_of_a_compilation,
            'title' => $this->title,
            'track_number' => $this->track_number,
            'year' => $this->year,
            'copyright' => $this->copyright,
            'text' => $this->text,
            'unsynchronised_lyric' => $this->unsynchronised_lyric,
            'language' => $this->language,
        ];
    }
}

class Id3Comments
{
    protected function __construct(
        protected ?string $language = null,
        protected ?Id3CommentsPicture $picture = null,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $language = $metadata['language'][0] ?? null;
        $picture = Id3CommentsPicture::make($metadata['picture'][0] ?? null);

        $self = new self(
            language: $language,
            picture: $picture,
        );

        return $self;
    }

    public function picture(): ?Id3CommentsPicture
    {
        return $this->picture;
    }
}

class Id3CommentsPicture
{
    protected function __construct(
        protected ?string $data = null,
        protected ?string $image_mime = null,
        protected ?int $image_width = null,
        protected ?int $image_height = null,
        protected ?string $picturetype = null,
        protected ?string $description = null,
        protected ?int $datalength = null,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $self = new self(
            data: $metadata['data'] ?? null,
            image_mime: $metadata['image_mime'] ?? null,
            image_width: $metadata['image_width'] ?? null,
            image_height: $metadata['image_height'] ?? null,
            picturetype: $metadata['picturetype'] ?? null,
            description: $metadata['description'] ?? null,
            datalength: $metadata['datalength'] ?? null,
        );

        return $self;
    }

    public function data(): ?string
    {
        return $this->data;
    }

    public function image_mime(): ?string
    {
        return $this->image_mime;
    }

    public function image_width(): ?int
    {
        return $this->image_width;
    }

    public function image_height(): ?int
    {
        return $this->image_height;
    }

    public function picturetype(): ?string
    {
        return $this->picturetype;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function datalength(): ?int
    {
        return $this->datalength;
    }
}

class Id3TagQuicktime
{
    public function __construct(
        protected ?string $title = null,
        protected ?string $track_number = null,
        protected ?string $disc_number = null,
        protected ?string $compilation = null,
        protected ?string $album = null,
        protected ?string $genre = null,
        protected ?string $composer = null,
        protected ?string $creation_date = null,
        protected ?string $copyright = null,
        protected ?string $artist = null,
        protected ?string $album_artist = null,
        protected ?string $encoded_by = null,
        protected ?string $encoding_tool = null,
        protected ?string $description = null,
        protected ?string $description_long = null,
        protected ?string $language = null,
        protected ?string $lyrics = null,
        protected ?string $comment = null,
        protected ?string $stik = null,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $self = new self(
            title: $metadata['title'] ?? null,
            track_number: $metadata['track_number'] ?? null,
            disc_number: $metadata['disc_number'] ?? null,
            compilation: $metadata['compilation'] ?? null,
            album: $metadata['album'] ?? null,
            genre: $metadata['genre'] ?? null,
            composer: $metadata['composer'] ?? null,
            creation_date: $metadata['creation_date'] ?? null,
            copyright: $metadata['copyright'] ?? null,
            artist: $metadata['artist'] ?? null,
            album_artist: $metadata['album_artist'] ?? null,
            encoded_by: $metadata['encoded_by'] ?? null,
            encoding_tool: $metadata['encoding_tool'] ?? null,
            description: $metadata['description'] ?? null,
            description_long: $metadata['description_long'] ?? null,
            language: $metadata['language'] ?? null,
            lyrics: $metadata['lyrics'] ?? null,
            comment: $metadata['comment'] ?? null,
            stik: $metadata['stik'] ?? null,
        );

        return $self;
    }

    public function title(): ?string
    {
        return $this->title;
    }

    public function track_number(): ?string
    {
        return $this->track_number;
    }

    public function disc_number(): ?string
    {
        return $this->disc_number;
    }

    public function compilation(): ?string
    {
        return $this->compilation;
    }

    public function album(): ?string
    {
        return $this->album;
    }

    public function genre(): ?string
    {
        return $this->genre;
    }

    public function composer(): ?string
    {
        return $this->composer;
    }

    public function creation_date(): ?string
    {
        return $this->creation_date;
    }

    public function copyright(): ?string
    {
        return $this->copyright;
    }

    public function artist(): ?string
    {
        return $this->artist;
    }

    public function album_artist(): ?string
    {
        return $this->album_artist;
    }

    public function encoded_by(): ?string
    {
        return $this->encoded_by;
    }

    public function encoding_tool(): ?string
    {
        return $this->encoding_tool;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function description_long(): ?string
    {
        return $this->description_long;
    }

    public function language(): ?string
    {
        return $this->language;
    }

    public function lyrics(): ?string
    {
        return $this->lyrics;
    }

    public function comment(): ?string
    {
        return $this->comment;
    }

    public function stik(): ?string
    {
        return $this->stik;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'track_number' => $this->track_number,
            'disc_number' => $this->disc_number,
            'compilation' => $this->compilation,
            'album' => $this->album,
            'genre' => $this->genre,
            'composer' => $this->composer,
            'creation_date' => $this->creation_date,
            'copyright' => $this->copyright,
            'artist' => $this->artist,
            'album_artist' => $this->album_artist,
            'encoded_by' => $this->encoded_by,
            'encoding_tool' => $this->encoding_tool,
            'description' => $this->description,
            'description_long' => $this->description_long,
            'language' => $this->language,
            'lyrics' => $this->lyrics,
            'comment' => $this->comment,
            'stik' => $this->stik,
        ];
    }
}

class Id3TagAsf
{
    public function __construct(
        protected ?string $title = null,
        protected ?string $artist = null,
        protected ?string $album = null,
        protected ?string $albumartist = null,
        protected ?string $composer = null,
        protected ?string $partofset = null,
        protected ?string $genre = null,
        protected ?string $track_number = null,
        protected ?string $year = null,
        protected ?string $encodingsettings = null,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }
        $self = new self(
            title: $metadata['title'] ?? null,
            artist: $metadata['artist'] ?? null,
            album: $metadata['album'] ?? null,
            albumartist: $metadata['albumartist'] ?? null,
            composer: $metadata['composer'] ?? null,
            partofset: $metadata['partofset'] ?? null,
            genre: $metadata['genre'] ?? null,
            track_number: $metadata['track_number'] ?? null,
            year: $metadata['year'] ?? null,
            encodingsettings: $metadata['encodingsettings'] ?? null,
        );

        return $self;
    }

    public function title(): ?string
    {
        return $this->title;
    }

    public function artist(): ?string
    {
        return $this->artist;
    }

    public function album(): ?string
    {
        return $this->album;
    }

    public function albumartist(): ?string
    {
        return $this->albumartist;
    }

    public function composer(): ?string
    {
        return $this->composer;
    }

    public function partofset(): ?string
    {
        return $this->partofset;
    }

    public function genre(): ?string
    {
        return $this->genre;
    }

    public function track_number(): ?string
    {
        return $this->track_number;
    }

    public function year(): ?string
    {
        return $this->year;
    }

    public function encodingsettings(): ?string
    {
        return $this->encodingsettings;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'artist' => $this->artist,
            'album' => $this->album,
            'albumartist' => $this->albumartist,
            'composer' => $this->composer,
            'partofset' => $this->partofset,
            'genre' => $this->genre,
            'track_number' => $this->track_number,
            'year' => $this->year,
            'encodingsettings' => $this->encodingsettings,
        ];
    }
}

class Id3TagVorbisComment
{
    public function __construct(
        protected ?string $description = null,
        protected ?string $encoder = null,
        protected ?string $title = null,
        protected ?string $artist = null,
        protected ?string $album = null,
        protected ?string $genre = null,
        protected ?string $comment = null,
        protected ?string $albumartist = null,
        protected ?string $composer = null,
        protected ?string $discnumber = null,
        protected ?string $compilation = null,
        protected ?string $date = null,
        protected ?string $tracknumber = null,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }
        $self = new self(
            description: $metadata['description'] ?? null,
            encoder: $metadata['encoder'] ?? null,
            title: $metadata['title'] ?? null,
            artist: $metadata['artist'] ?? null,
            album: $metadata['album'] ?? null,
            genre: $metadata['genre'] ?? null,
            comment: $metadata['comment'] ?? null,
            albumartist: $metadata['albumartist'] ?? null,
            composer: $metadata['composer'] ?? null,
            discnumber: $metadata['discnumber'] ?? null,
            compilation: $metadata['compilation'] ?? null,
            date: $metadata['date'] ?? null,
            tracknumber: $metadata['tracknumber'] ?? null,
        );

        return $self;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function title(): ?string
    {
        return $this->title;
    }

    public function artist(): ?string
    {
        return $this->artist;
    }

    public function album(): ?string
    {
        return $this->album;
    }

    public function genre(): ?string
    {
        return $this->genre;
    }

    public function comment(): ?string
    {
        return $this->comment;
    }

    public function albumartist(): ?string
    {
        return $this->albumartist;
    }

    public function composer(): ?string
    {
        return $this->composer;
    }

    public function discnumber(): ?string
    {
        return $this->discnumber;
    }

    public function compilation(): ?string
    {
        return $this->compilation;
    }

    public function date(): ?string
    {
        return $this->date;
    }

    public function tracknumber(): ?string
    {
        return $this->tracknumber;
    }

    public function encoder(): ?string
    {
        return $this->encoder;
    }

    public function toArray(): array
    {
        return [
            'description' => $this->description,
            'encoder' => $this->encoder,
            'title' => $this->title,
            'artist' => $this->artist,
            'album' => $this->album,
            'genre' => $this->genre,
            'comment' => $this->comment,
            'albumartist' => $this->albumartist,
            'composer' => $this->composer,
            'discnumber' => $this->discnumber,
            'compilation' => $this->compilation,
            'date' => $this->date,
            'tracknumber' => $this->tracknumber,
        ];
    }
}

class Id3TagRiff
{
    public function __construct(
        protected ?string $artist = null,
        protected ?string $comment = null,
        protected ?string $creationdate = null,
        protected ?string $genre = null,
        protected ?string $title = null,
        protected ?string $product = null,
        protected ?string $software = null,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }
        $self = new self(
            artist: $metadata['artist'] ?? null,
            comment: $metadata['comment'] ?? null,
            creationdate: $metadata['creationdate'] ?? null,
            genre: $metadata['genre'] ?? null,
            title: $metadata['title'] ?? null,
            product: $metadata['product'] ?? null,
            software: $metadata['software'] ?? null,
        );

        return $self;
    }

    public function artist(): ?string
    {
        return $this->artist;
    }

    public function comment(): ?string
    {
        return $this->comment;
    }

    public function creationdate(): ?string
    {
        return $this->creationdate;
    }

    public function genre(): ?string
    {
        return $this->genre;
    }

    public function title(): ?string
    {
        return $this->title;
    }

    public function product(): ?string
    {
        return $this->product;
    }

    public function software(): ?string
    {
        return $this->software;
    }

    public function toArray(): array
    {
        return [
            'artist' => $this->artist,
            'comment' => $this->comment,
            'creationdate' => $this->creationdate,
            'genre' => $this->genre,
            'title' => $this->title,
            'product' => $this->product,
            'software' => $this->software,
        ];
    }
}

class Id3TagMatroska
{
    public function __construct(
        protected ?string $title = null,
        protected ?string $muxingapp = null,
        protected ?string $writingapp = null,
        protected ?string $album = null,
        protected ?string $artist = null,
        protected ?string $album_artist = null,
        protected ?string $comment = null,
        protected ?string $composer = null,
        protected ?string $disc = null,
        protected ?string $genre = null,
        protected ?string $compilation = null,
        protected ?string $part_number = null,
        protected ?string $date = null,
        protected ?string $encoder = null,
        protected ?string $duration = null,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $self = new self(
            title: $metadata['title'] ?? null,
            muxingapp: $metadata['muxingapp'] ?? null,
            writingapp: $metadata['writingapp'] ?? null,
            album: $metadata['album'] ?? null,
            artist: $metadata['artist'] ?? null,
            album_artist: $metadata['album_artist'] ?? null,
            comment: $metadata['comment'] ?? null,
            composer: $metadata['composer'] ?? null,
            disc: $metadata['disc'] ?? null,
            genre: $metadata['genre'] ?? null,
            compilation: $metadata['compilation'] ?? null,
            part_number: $metadata['part_number'] ?? null,
            date: $metadata['date'] ?? null,
            encoder: $metadata['encoder'] ?? null,
            duration: $metadata['duration'] ?? null,
        );

        return $self;
    }

    public function title(): ?string
    {
        return $this->title;
    }

    public function muxingapp(): ?string
    {
        return $this->muxingapp;
    }

    public function writingapp(): ?string
    {
        return $this->writingapp;
    }

    public function album(): ?string
    {
        return $this->album;
    }

    public function artist(): ?string
    {
        return $this->artist;
    }

    public function album_artist(): ?string
    {
        return $this->album_artist;
    }

    public function comment(): ?string
    {
        return $this->comment;
    }

    public function composer(): ?string
    {
        return $this->composer;
    }

    public function disc(): ?string
    {
        return $this->disc;
    }

    public function genre(): ?string
    {
        return $this->genre;
    }

    public function compilation(): ?string
    {
        return $this->compilation;
    }

    public function part_number(): ?string
    {
        return $this->part_number;
    }

    public function date(): ?string
    {
        return $this->date;
    }

    public function encoder(): ?string
    {
        return $this->encoder;
    }

    public function duration(): ?string
    {
        return $this->duration;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'muxingapp' => $this->muxingapp,
            'writingapp' => $this->writingapp,
            'album' => $this->album,
            'artist' => $this->artist,
            'album_artist' => $this->album_artist,
            'comment' => $this->comment,
            'composer' => $this->composer,
            'disc' => $this->disc,
            'genre' => $this->genre,
            'compilation' => $this->compilation,
            'part_number' => $this->part_number,
            'date' => $this->date,
            'encoder' => $this->encoder,
            'duration' => $this->duration,
        ];
    }
}

class Id3TagApe
{
    public function __construct(
        protected ?string $title = null,
        protected ?string $artist = null,
        protected ?string $album = null,
        protected ?string $album_artist = null,
        protected ?string $composer = null,
        protected ?string $comment = null,
        protected ?string $genre = null,
        protected ?string $disc = null,
        protected ?string $compilation = null,
        protected ?string $track = null,
        protected ?string $date = null,
        protected ?string $encoder = null,
        protected ?string $description = null,
        protected ?string $copyright = null,
        protected ?string $lyrics = null,
        protected ?string $podcastdesc = null,
        protected ?string $language = null,
        protected ?string $year = null,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $self = new self(
            title: $metadata['title'] ?? null,
            artist: $metadata['artist'] ?? null,
            album: $metadata['album'] ?? null,
            album_artist: $metadata['album_artist'] ?? $metadata['albumartist'] ?? null,
            composer: $metadata['composer'] ?? null,
            comment: $metadata['comment'] ?? null,
            genre: $metadata['genre'] ?? null,
            disc: $metadata['disc'] ?? $metadata['discnumber'] ?? null,
            compilation: $metadata['compilation'] ?? null,
            track: $metadata['track'] ?? null,
            date: $metadata['date'] ?? null,
            encoder: $metadata['encoder'] ?? null,
            description: $metadata['description'] ?? null,
            copyright: $metadata['copyright'] ?? null,
            lyrics: $metadata['unsyncedlyrics'] ?? null,
            podcastdesc: $metadata['podcastdesc'] ?? null,
            language: $metadata['language'] ?? null,
            year: $metadata['year'] ?? null,
        );

        return $self;
    }

    public function title(): ?string
    {
        return $this->title;
    }

    public function artist(): ?string
    {
        return $this->artist;
    }

    public function album(): ?string
    {
        return $this->album;
    }

    public function album_artist(): ?string
    {
        return $this->album_artist;
    }

    public function composer(): ?string
    {
        return $this->composer;
    }

    public function comment(): ?string
    {
        return $this->comment;
    }

    public function genre(): ?string
    {
        return $this->genre;
    }

    public function disc(): ?string
    {
        return $this->disc;
    }

    public function compilation(): ?string
    {
        return $this->compilation;
    }

    public function track(): ?string
    {
        return $this->track;
    }

    public function date(): ?string
    {
        return $this->date;
    }

    public function encoder(): ?string
    {
        return $this->encoder;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function copyright(): ?string
    {
        return $this->copyright;
    }

    public function lyrics(): ?string
    {
        return $this->lyrics;
    }

    public function podcastdesc(): ?string
    {
        return $this->podcastdesc;
    }

    public function language(): ?string
    {
        return $this->language;
    }

    public function year(): ?string
    {
        return $this->year;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'artist' => $this->artist,
            'album' => $this->album,
            'album_artist' => $this->album_artist,
            'composer' => $this->composer,
            'comment' => $this->comment,
            'genre' => $this->genre,
            'disc' => $this->disc,
            'compilation' => $this->compilation,
            'track' => $this->track,
            'date' => $this->date,
            'encoder' => $this->encoder,
            'description' => $this->description,
            'copyright' => $this->copyright,
            'lyrics' => $this->lyrics,
            'podcastdesc' => $this->podcastdesc,
            'language' => $this->language,
            'year' => $this->year,
        ];
    }
}

class Id3TagsHtml
{
    protected function __construct(
        protected ?Id3AudioTagV1 $id3v1 = null,
        protected ?Id3AudioTagV2 $id3v2 = null,
        protected ?Id3TagQuicktime $quicktime = null,
        protected ?Id3TagAsf $asf = null,
        protected ?Id3TagVorbisComment $vorbiscomment = null,
        protected ?Id3TagRiff $riff = null,
        protected ?Id3TagMatroska $matroska = null,
        protected ?Id3TagApe $ape = null,
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
            id3v1: Id3AudioTagV1::make($id3v1),
            id3v2: Id3AudioTagV2::make($id3v2),
            quicktime: Id3TagQuicktime::make($quicktime),
            asf: Id3TagAsf::make($asf),
            vorbiscomment: Id3TagVorbisComment::make($vorbiscomment),
            riff: Id3TagRiff::make($riff),
            matroska: Id3TagMatroska::make($matroska),
            ape: Id3TagApe::make($ape),
        );

        return $self;
    }

    public function id3v1(): ?Id3AudioTagV1
    {
        return $this->id3v1;
    }

    public function id3v2(): ?Id3AudioTagV2
    {
        return $this->id3v2;
    }

    public function quicktime(): ?Id3TagQuicktime
    {
        return $this->quicktime;
    }

    public function asf(): ?Id3TagAsf
    {
        return $this->asf;
    }

    public function vorbiscomment(): ?Id3TagVorbisComment
    {
        return $this->vorbiscomment;
    }

    public function riff(): ?Id3TagRiff
    {
        return $this->riff;
    }

    public function matroska(): ?Id3TagMatroska
    {
        return $this->matroska;
    }

    public function ape(): ?Id3TagApe
    {
        return $this->ape;
    }
}
