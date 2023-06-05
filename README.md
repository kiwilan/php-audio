# PHP Audio

[![php][php-version-src]][php-version-href]
[![version][version-src]][version-href]
[![downloads][downloads-src]][downloads-href]
[![license][license-src]][license-href]

[![tests][tests-src]][tests-href]
[![codecov][codecov-src]][codecov-href]

PHP package to parse and update audio files metadata, with [`JamesHeinrich/getID3`](https://github.com/JamesHeinrich/getID3).

## Supported formats

### Readable formats

-   `id3v2` will be selected before `id3v1` or `riff` if both are available.

| Format | Supported |                About                 |    ID3 type     |         Notes         |
| :----: | :-------: | :----------------------------------: | :-------------: | :-------------------: |
|  AAC   |    ❌     |        Advanced Audio Coding         |                 |                       |
|  ALAC  |    ✅     |      Apple Lossless Audio Codec      |   `quicktime`   |                       |
|  AIF   |    ✅     | Audio Interchange File Format (aif)  | `id3v2`,`riff`  |                       |
|  AIFC  |    ✅     | Audio Interchange File Format (aifc) | `id3v2`,`riff`  |                       |
|  AIFF  |    ✅     | Audio Interchange File Format (aiff) | `id3v2`,`riff`  |                       |
|  DSF   |    ❌     |     Direct Stream Digital Audio      |                 |                       |
|  FLAC  |    ✅     |      Free Lossless Audio Codec       | `vorbiscomment` |                       |
|  MKA   |    ✅     |               Matroska               |   `matroska`    | _Cover not supported_ |
|  MKV   |    ✅     |               Matroska               |   `matroska`    | _Cover not supported_ |
|  APE   |    ❌     |            Monkey's Audio            |                 |                       |
|  MP3   |    ✅     |          MPEG audio layer 3          | `id3v2`,`id3v1` |                       |
|  MP4   |    ✅     | Digital multimedia container format  |   `quicktime`   |                       |
|  M4A   |    ✅     |             mpeg-4 audio             |   `quicktime`   |                       |
|  M4B   |    ✅     |              Audiobook               |   `quicktime`   |                       |
|  M4V   |    ✅     |             mpeg-4 video             |   `quicktime`   |                       |
|  MPC   |    ❌     |               Musepack               |                 |                       |
|  OGG   |    ✅     |        Open container format         | `vorbiscomment` |                       |
|  OPUS  |    ✅     |           IETF Opus audio            | `vorbiscomment` |                       |
|  OFR   |    ❌     |              OptimFROG               |                 |                       |
|  OFS   |    ❌     |              OptimFROG               |                 |                       |
|  SPX   |    ✅     |                Speex                 | `vorbiscomment` | _Cover not supported_ |
|  TAK   |    ❌     |        Tom's Audio Kompressor        |                 |                       |
|  TTA   |    ✅     |              True Audio              |      `ape`      | _Cover not supported_ |
|  WMA   |    ✅     |         Windows Media Audio          |      `asf`      | _Cover not supported_ |
|   WV   |    ✅     |               WavPack                |      `ape`      |                       |
|  WAV   |    ✅     |            Waveform Audio            | `id3v2`,`riff`  |                       |
|  WEBM  |    ✅     |                 WebM                 |   `matroska`    | _Cover not supported_ |

You want to add a format? [See FAQ](#faq)

### Updatable formats

`JamesHeinrich/getID3` can update some formats, but not all.

> -   ID3v1 (v1 & v1.1)
> -   ID3v2 (v2.3, v2.4)
> -   APE (v2)
> -   Ogg Vorbis comments (need `vorbis-tools`)
> -   FLAC comments

| Format |         Notes         |                    Requires                    |
| :----: | :-------------------: | :--------------------------------------------: |
|  FLAC  | _Cover not supported_ |     `flac` (with `brew`, `apt` or `scoop`)     |
|  MP3   |                       |                                                |
|  OGG   | _Cover not supported_ | `vorbis-tools` (with `brew`, `apt` or `scoop`) |

### Convert properties

`Audio::class` convert some properties to be more readable.

| ID3 type |    Original    | New property  |
| :------: | :------------: | :-----------: |
| `id3v2`  |     `band`     | `albumArtist` |
| `id3v2`  | `track_number` | `trackNumber` |

## About

Audio files can use different formats, this package aims to provide a simple way to read them with [`JamesHeinrich/getID3`](https://github.com/JamesHeinrich/getID3). The `JamesHeinrich/getID3` package is excellent to read metadata from audio files, but output is just an array, current package aims to provide a simple way to read audio files with a beautiful API.

## Requirements

-   PHP >= 8.1

## Installation

You can install the package via composer:

```bash
composer require kiwilan/php-audio
```

## Usage

Core metadata:

```php
$audio = Audio::get('path/to/audio.mp3');

$audio->title(); // `?string` to get title
$audio->artist(); // `?string` to get artist
$audio->album(); // `?string` to get album
$audio->genre(); // `?string` to get genre
$audio->year(); // `?int` to get year
$audio->trackNumber(); // `?string` to get track number
$audio->comment(); // `?string` to get comment
$audio->albumArtist(); // `?string` to get album artist
$audio->composer(); // `?string` to get composer
$audio->discNumber(); // `?string` to get disc number
$audio->isCompilation(); // `bool` to know if is compilation
$audio->creationDate(); // `?string` to get creation date (audiobook)
$audio->copyright(); // `?string` to get copyright (audiobook)
$audio->encoding(); // `?string` to get encoding
$audio->description(); // `?string` to get description (audiobook)
$audio->lyrics(); // `?string` (audiobook)
$audio->stik(); // `?string` (audiobook)
$audio->duration(); // `?float` to get duration in seconds
```

Additional metadata:

```php
$audio = Audio::get('path/to/audio.mp3');

$audio->path(); // `string` to get path
$audio->hasCover(); // `bool` to know if has cover
$audio->isValid(); // `bool` to know if file is valid audio file
$audio->format(); // `AudioFormatEnum` to get format (mp3, m4a, ...)
$audio->type(); // `?AudioTypeEnum` ID3 type (id3, riff, asf, quicktime, matroska, ape, vorbiscomment)

$audio->extras(); // `array` with raw metadata (could contains some metadata not parsed)
$audio->reader(); // `?Id3Reader` reader based on `getID3`
$audio->writer(); // `?Id3Writer` writer based on `getid3_writetags`
$audio->stat(); // `FileStat` (from `stat` function)
$audio->audio(); // `?AudioMetadata` with audio metadata
$audio->cover(); // `?AudioCover` with cover metadata
```

### Update

You can update audio files metadata with `Audio::class`, but not all formats are supported. [See supported formats](#updatable-formats)

```php
$audio = Audio::get('path/to/audio.mp3');

// you can use file content
$cover = file_get_contents('path/to/cover.jpg');
// or file path
$cover = 'path/to/cover.jpg';

$tag = $audio->update()
  ->setTitle('New Title')
  ->setArtist('New Artist')
  ->setAlbum('New Album')
  ->setGenre('New Genre')
  ->setYear('2022')
  ->setTrackNumber('2/10')
  ->setAlbumArtist('New Album Artist')
  ->setComment('New Comment')
  ->setComposer('New Composer')
  ->setCreationDate('2021-01-01')
  ->setDescription('New Description')
  ->setDiscNumber('2/2')
  ->setEncodingBy('New Encoding By')
  ->setEncoding('New Encoding')
  ->setIsCompilation(false)
  ->setLyrics('New Lyrics')
  ->setStik('New Stik')
  ->setCover($cover)
  ->save();
```

Some properties are not supported by all formats, for example `MP3` can't handle `lyrics` or `stik` properties, if you try to update these properties, they will be ignored.

### Extras

Audio files format metadata with different methods, `JamesHeinrich/getID3` offer to check these metadatas by different methods. In `extras` property of `Audio::class`, you will find raw metadata from `JamesHeinrich/getID3` package, like `id3v2`, `id3v1`, `riff`, `asf`, `quicktime`, `matroska`, `ape`, `vorbiscomment`...

If you want to extract specific field which can be skipped by `Audio::class`, you can use `extras` property.

```php
$audio = Audio::get('path/to/audio.mp3');
$extras = $audio->extras();

$id3v2 = $extras['id3v2'] ?? [];
```

### AudioMetadata

```php
$audio = Audio::get('path/to/audio.mp3');

$audio->audio()->filesize(); // `?int` in bytes
$audio->audio()->extension(); // `?string` (mp3, m4a, ...)
$audio->audio()->encoding(); // `?string` (UTF-8...)
$audio->audio()->mimeType(); // `?string` (audio/mpeg, audio/mp4, ...)
$audio->audio()->durationSeconds(); // `?float` in seconds
$audio->audio()->durationReadable(); // `?string` (00:00:00)
$audio->audio()->bitrate(); // `?int` in kbps
$audio->audio()->bitrateMode(); // `?string` (cbr, vbr, ...)
$audio->audio()->sampleRate(); // `?int` in Hz
$audio->audio()->channels(); // `?int` (1, 2, ...)
$audio->audio()->channelMode(); // `?string` (mono, stereo, ...)
$audio->audio()->lossless(); // `bool` to know if is lossless
$audio->audio()->compressionRatio(); // `?float`
```

### AudioCover

```php
$audio = Audio::get('path/to/audio.mp3');

$audio->cover()->content(); // `?string` raw file
$audio->cover()->mimeType(); // `?string` (image/jpeg, image/png, ...)
$audio->cover()->width(); // `?int` in pixels
$audio->cover()->height(); // `?int` in pixels
```

## Testing

```bash
composer test
```

## Tools

-   [ffmpeg](https://ffmpeg.org/)
-   [MP3TAG](https://www.mp3tag.de/en/): powerful and easy-to-use tool to edit metadata of audio files (free on Windows).
-   [Audiobook Builder](https://www.splasm.com/audiobookbuilder/): makes it easy to turn audio CDs and files into audiobooks (only macOS and paid).

## FAQ

### I have a specific metadata field in my audio files, what can I do?

In `Audio::class`, you have a property `extras` which contains all raw metadata, if `JamesHeinrich/getID3` support this field, you will find it in this property.

```php
$audio = Audio::get('path/to/audio.mp3');
$extras = $audio->extras();

$custom = null;
$id3v2 = $extras['id3v2'] ?? [];

if ($id3v2) {
  $custom = $id3v2['custom'] ?? null;
}
```

If your field could be added to global properties of `Audio::class`, you could create an [an issue](https://github.com/kiwilan/php-audio/issues/new/choose).

### My favorite format is not supported, what can I do?

You can create [an issue](https://github.com/kiwilan/php-audio/issues/new/choose) with the format name and a link to the format documentation. If `JamesHeinrich/getID3` support this format, I will add it to this package but if you want to contribute, you can create a pull request with the format implementation.

**Please give me an example file to test the format.**

### I have an issue with a supported format, what can I do?

You can create [an issue](https://github.com/kiwilan/php-audio/issues/new/choose) with informations.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

-   [Ewilan Rivière](https://github.com/ewilan-riviere): package author
-   [JamesHeinrich/getID3](https://github.com/JamesHeinrich/getID3): parser used to read audio files
-   [spatie/package-skeleton-php](https://github.com/spatie/package-skeleton-php): package skeleton used to create this package
-   Tests files from [p1pdd.com](https://p1pdd.com/) (episode 00)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[version-src]: https://img.shields.io/packagist/v/kiwilan/php-audio.svg?style=flat-square&colorA=18181B&colorB=777BB4
[version-href]: https://packagist.org/packages/kiwilan/php-audio
[php-version-src]: https://img.shields.io/static/v1?style=flat-square&label=PHP&message=v8.1&color=777BB4&logo=php&logoColor=ffffff&labelColor=18181b
[php-version-href]: https://www.php.net/
[downloads-src]: https://img.shields.io/packagist/dt/kiwilan/php-audio.svg?style=flat-square&colorA=18181B&colorB=777BB4
[downloads-href]: https://packagist.org/packages/kiwilan/php-audio
[license-src]: https://img.shields.io/github/license/kiwilan/php-audio.svg?style=flat-square&colorA=18181B&colorB=777BB4
[license-href]: https://github.com/kiwilan/php-audio/blob/main/README.md
[tests-src]: https://img.shields.io/github/actions/workflow/status/kiwilan/php-audio/run-tests.yml?branch=main&label=tests&style=flat-square&colorA=18181B
[tests-href]: https://packagist.org/packages/kiwilan/php-audio
[codecov-src]: https://codecov.io/gh/kiwilan/php-audio/branch/main/graph/badge.svg?token=4L0D92Z1EZ
[codecov-href]: https://codecov.io/gh/kiwilan/php-audio
