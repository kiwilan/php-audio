# PHP Audio

[![php][php-version-src]][php-version-href]
[![version][version-src]][version-href]
[![downloads][downloads-src]][downloads-href]
[![license][license-src]][license-href]

[![tests][tests-src]][tests-href]
[![codecov][codecov-src]][codecov-href]

PHP package to read audio files with ID3, parser uses [JamesHeinrich/getID3](https://github.com/JamesHeinrich/getID3).

| Format | Supported |                 Type                 | Notes |
| :----: | :-------: | :----------------------------------: | :---: |
|  AAC   |    ❌     |        Advanced Audio Coding         |       |
|  ALAC  |    ❌     |      Apple Lossless Audio Codec      |       |
|  AIF   |    ❌     | Audio Interchange File Format (aif)  |       |
|  AIFC  |    ❌     | Audio Interchange File Format (aifc) |       |
|  AIFF  |    ❌     | Audio Interchange File Format (aiff) |       |
|  DSF   |    ❌     |     Direct Stream Digital Audio      |       |
|  FLAC  |    ✅     |      Free Lossless Audio Codec       |       |
|  MKA   |    ❌     |               Matroska               |       |
|  MKV   |    ❌     |               Matroska               |       |
|  APE   |    ❌     |            Monkey's Audio            |       |
|  MP3   |    ✅     |          MPEG audio layer 3          |       |
|  MP4   |    ✅     | Digital multimedia container format  |       |
|  M4A   |    ✅     |             mpeg-4 audio             |       |
|  M4B   |    ✅     |              Audiobook               |       |
|  M4V   |    ❌     |             mpeg-4 video             |       |
|  MPC   |    ❌     |               Musepack               |       |
|  OGG   |    ❌     |        Open container format         |       |
|  OPUS  |    ❌     |           IETF Opus audio            |       |
|  OFR   |    ❌     |              OptimFROG               |       |
|  OFS   |    ❌     |              OptimFROG               |       |
|  SPX   |    ❌     |                Speex                 |       |
|  TAK   |    ❌     |        Tom's Audio Kompressor        |       |
|  TTA   |    ❌     |              True Audio              |       |
|  WMA   |    ✅     |         Windows Media Audio          |       |
|   WV   |    ❌     |               WavPack                |       |
|  WAV   |    ✅     |            Waveform Audio            |       |
|  WEBM  |    ❌     |                 WebM                 |       |

## Requirements

-   PHP >= 8.1

## About

Audio files can use different formats, this package aims to provide a simple way to read them with [JamesHeinrich/getID3](https://github.com/JamesHeinrich/getID3). The `JamesHeinrich/getID3` package is excellent to read metadata from audio files, but output is just an array, current package aims to provide a simple way to read audio files with a simple API.

## Installation

You can install the package via composer:

```bash
composer require kiwilan/php-audio
```

## Usage

```php
$audio = Audio::read('path/to/audio.mp3');

$audio->path(); // string to get path
$audio->extension(); // string to get extension
$audio->title(); // ?string to get title
$audio->artist(); // ?string to get artist
$audio->album(); // ?string to get album
$audio->genre(); // ?string to get genre
$audio->year(); // ?int to get year
$audio->trackNumber(); // ?string to get track number
$audio->comment(); // ?string to get comment
$audio->albumArtist(); // ?string to get album artist
$audio->composer(); // ?string to get composer
$audio->discNumber(); // ?string to get disc number
$audio->isCompilation(); // bool to know if is compilation
$audio->creationDate(); // ?string to get creation date (audiobook)
$audio->copyright(); // ?string to get copyright (audiobook)
$audio->encoding(); // ?string to get encoding
$audio->description(); // ?string to get description (audiobook)
$audio->lyrics(); // ?string (audiobook)
$audio->stik(); // ?string (audiobook)
$audio->duration(); // ?float to get duration in seconds
$audio->extras(); // array with raw metadata
$audio->hasCover(); // bool to know if has cover

$audio->id3(); // Id3 metadata
$audio->stat(); // FileStat (from `stat` function)
$audio->audio(); // ?AudioMetadata with audio metadata
$audio->cover(); // ?AudioCover with cover metadata
```

## Id3

Data from `JamesHeinrich/getID3` package with formatting.

```php
$audio = Audio::read('path/to/audio.mp3');

$audio->id3()->raw(); // array with raw metadata
$audio->id3()->item(); // ?Id3Item with item metadata
$audio->id3()->instance(); // getID3 instance
```

## AudioMetadata

```php
$audio = Audio::read('path/to/audio.mp3');

$audio->audio()->filesize(); // ?int in bytes
$audio->audio()->extension(); // ?string (mp3, m4a, ...)
$audio->audio()->encoding(); // ?string (UTF-8...)
$audio->audio()->mimeType(); // ?string (audio/mpeg, audio/mp4, ...)
$audio->audio()->durationSeconds(); // ?float in seconds
$audio->audio()->durationReadable(); // ?string (00:00:00)
$audio->audio()->bitrate(); // ?int in kbps
$audio->audio()->bitrateMode(); // ?string (cbr, vbr, ...)
$audio->audio()->sampleRate(); // ?int in Hz
$audio->audio()->channels(); // ?int (1, 2, ...)
$audio->audio()->channelMode(); // ?string (mono, stereo, ...)
$audio->audio()->lossless(); // bool to know if is lossless
$audio->audio()->compressionRatio(); // ?float
```

## AudioCover

```php
$audio = Audio::read('path/to/audio.mp3');

$audio->cover()->content(); // ?string raw file
$audio->cover()->mimeType(); // ?string (image/jpeg, image/png, ...)
$audio->cover()->width(); // ?int in pixels
$audio->cover()->height(); // ?int in pixels
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

-   [Ewilan Rivière](https://github.com/ewilan-riviere): package author
-   [JamesHeinrich/getID3](https://github.com/JamesHeinrich/getID3): parser used to read audio files
-   [spatie/package-skeleton-php](https://github.com/spatie/package-skeleton-php): package skeleton used to create this package

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
