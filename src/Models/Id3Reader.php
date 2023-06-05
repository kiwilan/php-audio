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
    ) {
    }

    public static function make(string $path): self
    {
        $self = new self(new getID3());

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

    public function instance(): getID3
    {
        return $this->instance;
    }

    public function version(): ?string
    {
        return $this->version;
    }

    public function filesize(): ?int
    {
        return $this->filesize;
    }

    public function filepath(): ?string
    {
        return $this->filepath;
    }

    public function filename(): ?string
    {
        return $this->filename;
    }

    public function filenamepath(): ?string
    {
        return $this->filenamepath;
    }

    public function avdataoffset(): ?int
    {
        return $this->avdataoffset;
    }

    public function avdataend(): ?int
    {
        return $this->avdataend;
    }

    public function fileformat(): ?string
    {
        return $this->fileformat;
    }

    public function audio(): ?Id3Audio
    {
        return $this->audio;
    }

    public function tags(): ?Id3AudioTag
    {
        return $this->tags;
    }

    public function comments(): ?Id3Comments
    {
        return $this->comments;
    }

    public function encoding(): ?string
    {
        return $this->encoding;
    }

    public function mime_type(): ?string
    {
        return $this->mime_type;
    }

    public function mpeg(): mixed
    {
        return $this->mpeg;
    }

    public function playtime_seconds(): ?float
    {
        return $this->playtime_seconds;
    }

    public function tags_html(): ?Id3TagsHtml
    {
        return $this->tags_html;
    }

    public function bitrate(): ?float
    {
        return $this->bitrate;
    }

    public function playtime_string(): ?string
    {
        return $this->playtime_string;
    }

    public function is_writable(): bool
    {
        return $this->is_writable;
    }

    public function raw(): array
    {
        return $this->raw;
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
    ) {
    }

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
    ) {
    }

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
    ) {
    }

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
    ) {
    }

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $self = new self(
            id3v1: Id3AudioTagV1::make($metadata['id3v1'] ?? null),
            id3v2: Id3AudioTagV2::make($metadata['id3v2'] ?? null),
            quicktime: Id3TagQuicktime::make($metadata['quicktime'] ?? null),
            asf: Id3TagAsf::make($metadata['asf'] ?? null),
            vorbiscomment: Id3TagVorbisComment::make($metadata['vorbiscomment'] ?? null),
            riff: Id3TagRiff::make($metadata['riff'] ?? null),
            matroska: Id3TagMatroska::make($metadata['matroska'] ?? null),
            ape: Id3TagApe::make($metadata['ape'] ?? null),
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
    ) {
    }

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $self = new self(
            title: $metadata['title'][0] ?? null,
            artist: $metadata['artist'][0] ?? null,
            album: $metadata['album'][0] ?? null,
            year: $metadata['year'][0] ?? null,
            genre: $metadata['genre'][0] ?? null,
            comment: $metadata['comment'][0] ?? null,
            track_number: $metadata['track_number'][0] ?? null,
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
        protected bool $part_of_a_compilation = false,
        protected ?string $title = null,
        protected ?string $track_number = null,
        protected ?string $year = null,
    ) {
    }

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $compilation = $metadata['part_of_a_compilation'][0] ?? null;

        $self = new self(
            album: $metadata['album'][0] ?? null,
            artist: $metadata['artist'][0] ?? null,
            band: $metadata['band'][0] ?? null,
            comment: $metadata['comment'][0] ?? null,
            composer: $metadata['composer'][0] ?? null,
            part_of_a_set: $metadata['part_of_a_set'][0] ?? null,
            genre: $metadata['genre'][0] ?? null,
            part_of_a_compilation: $compilation === '1' ? true : false,
            title: $metadata['title'][0] ?? null,
            track_number: $metadata['track_number'][0] ?? null,
            year: $metadata['year'][0] ?? null,
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

    public function part_of_a_compilation(): bool
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
}

class Id3Comments
{
    protected function __construct(
        protected ?string $language = null,
        protected ?Id3CommentsPicture $picture = null,
    ) {
    }

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
    ) {
    }

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
        protected bool $compilation = false,
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
        protected ?string $lyrics = null,
        protected ?string $comment = null,
        protected ?string $stik = null,
    ) {
    }

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $compilation = $metadata['compilation'][0] ?? false;
        if ($compilation === 1) {
            $compilation = true;
        }

        $self = new self(
            title: $metadata['title'][0] ?? null,
            track_number: $metadata['track_number'][0] ?? null,
            disc_number: $metadata['disc_number'][0] ?? null,
            compilation: $compilation,
            album: $metadata['album'][0] ?? null,
            genre: $metadata['genre'][0] ?? null,
            composer: $metadata['composer'][0] ?? null,
            creation_date: $metadata['creation_date'][0] ?? null,
            copyright: $metadata['copyright'][0] ?? null,
            artist: $metadata['artist'][0] ?? null,
            album_artist: $metadata['album_artist'][0] ?? null,
            encoded_by: $metadata['encoded_by'][0] ?? null,
            encoding_tool: $metadata['encoding_tool'][0] ?? null,
            description: $metadata['description'][0] ?? null,
            description_long: $metadata['description_long'][0] ?? null,
            lyrics: $metadata['lyrics'][0] ?? null,
            comment: $metadata['comment'][0] ?? null,
            stik: $metadata['stik'][0] ?? null,
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

    public function compilation(): bool
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
    ) {
    }

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }
        $self = new self(
            title: $metadata['title'][0] ?? null,
            artist: $metadata['artist'][0] ?? null,
            album: $metadata['album'][0] ?? null,
            albumartist: $metadata['albumartist'][0] ?? null,
            composer: $metadata['composer'][0] ?? null,
            partofset: $metadata['partofset'][0] ?? null,
            genre: $metadata['genre'][0] ?? null,
            track_number: $metadata['track_number'][0] ?? null,
            year: $metadata['year'][0] ?? null,
            encodingsettings: $metadata['encodingsettings'][0] ?? null,
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
    ) {
    }

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }
        $self = new self(
            description: $metadata['description'][0] ?? null,
            encoder: $metadata['encoder'][0] ?? null,
            title: $metadata['title'][0] ?? null,
            artist: $metadata['artist'][0] ?? null,
            album: $metadata['album'][0] ?? null,
            genre: $metadata['genre'][0] ?? null,
            comment: $metadata['comment'][0] ?? null,
            albumartist: $metadata['albumartist'][0] ?? null,
            composer: $metadata['composer'][0] ?? null,
            discnumber: $metadata['discnumber'][0] ?? null,
            compilation: $metadata['compilation'][0] ?? null,
            date: $metadata['date'][0] ?? null,
            tracknumber: $metadata['tracknumber'][0] ?? null,
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
    ) {
    }

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }
        $self = new self(
            artist: $metadata['artist'][0] ?? null,
            comment: $metadata['comment'][0] ?? null,
            creationdate: $metadata['creationdate'][0] ?? null,
            genre: $metadata['genre'][0] ?? null,
            title: $metadata['title'][0] ?? null,
            product: $metadata['product'][0] ?? null,
            software: $metadata['software'][0] ?? null,
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
        protected bool $compilation = false,
        protected ?string $part_number = null,
        protected ?string $date = null,
        protected ?string $encoder = null,
        protected ?string $duration = null,
    ) {
    }

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $compilation = $metadata['compilation'][0] ?? false;
        if ($compilation === 1) {
            $compilation = true;
        }

        $self = new self(
            title: $metadata['title'][0] ?? null,
            muxingapp: $metadata['muxingapp'][0] ?? null,
            writingapp: $metadata['writingapp'][0] ?? null,
            album: $metadata['album'][0] ?? null,
            artist: $metadata['artist'][0] ?? null,
            album_artist: $metadata['album_artist'][0] ?? null,
            comment: $metadata['comment'][0] ?? null,
            composer: $metadata['composer'][0] ?? null,
            disc: $metadata['disc'][0] ?? null,
            genre: $metadata['genre'][0] ?? null,
            compilation: $compilation,
            part_number: $metadata['part_number'][0] ?? null,
            date: $metadata['date'][0] ?? null,
            encoder: $metadata['encoder'][0] ?? null,
            duration: $metadata['duration'][0] ?? null,
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

    public function compilation(): bool
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
        protected bool $compilation = false,
        protected ?string $track = null,
        protected ?string $date = null,
        protected ?string $encoder = null,
    ) {
    }

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $compilation = $metadata['compilation'][0] ?? false;
        if ($compilation === 1) {
            $compilation = true;
        }

        $self = new self(
            title: $metadata['title'][0] ?? null,
            artist: $metadata['artist'][0] ?? null,
            album: $metadata['album'][0] ?? null,
            album_artist: $metadata['album_artist'][0] ?? null,
            composer: $metadata['composer'][0] ?? null,
            comment: $metadata['comment'][0] ?? null,
            genre: $metadata['genre'][0] ?? null,
            disc: $metadata['disc'][0] ?? null,
            compilation: $compilation,
            track: $metadata['track'][0] ?? null,
            date: $metadata['date'][0] ?? null,
            encoder: $metadata['encoder'][0] ?? null,
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

    public function compilation(): bool
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
    ) {
    }

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $self = new self(
            id3v1: Id3AudioTagV1::make($metadata['id3v1'] ?? null),
            id3v2: Id3AudioTagV2::make($metadata['id3v2'] ?? null),
            quicktime: Id3TagQuicktime::make($metadata['quicktime'] ?? null),
            asf: Id3TagAsf::make($metadata['asf'] ?? null),
            vorbiscomment: Id3TagVorbisComment::make($metadata['vorbiscomment'] ?? null),
            riff: Id3TagRiff::make($metadata['riff'] ?? null),
            matroska: Id3TagMatroska::make($metadata['matroska'] ?? null),
            ape: Id3TagApe::make($metadata['ape'] ?? null),
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
