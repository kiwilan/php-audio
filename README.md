# PHP Audio

![Banner with speaker and PHP Audio title](https://raw.githubusercontent.com/kiwilan/php-audio/main/docs/banner.jpg)

[![php][php-version-src]][php-version-href]
[![version][version-src]][version-href]
[![downloads][downloads-src]][downloads-href]
[![license][license-src]][license-href]
[![tests][tests-src]][tests-href]
[![codecov][codecov-src]][codecov-href]

PHP package to parse and update audio files metadata, with [`JamesHeinrich/getID3`](https://github.com/JamesHeinrich/getID3).

> [!NOTE]
>
> You can check formats supported on [Supported formats](#supported-formats) section.

## About

Audio files can use different formats, this package aims to provide a simple way to read them with [`JamesHeinrich/getID3`](https://github.com/JamesHeinrich/getID3). The `JamesHeinrich/getID3` package is excellent to read metadata from audio files, but output is just an array, current package aims to provide a simple way to read audio files with a beautiful API.

## Requirements

-   PHP `8.1` minimum
-   Optional for update
    -   `FLAC`: `flac` (with `apt`, `brew` or `scoop`)
    -   `OGG`: `vorbis-tools` (with `apt` or `brew`) / `extras/icecast` (with `scoop`)

### Roadmap

-   Add support for more formats with [external packages](https://askubuntu.com/questions/226773/how-to-read-mp3-tags-in-shell)

## Installation

You can install the package via [composer](https://getcomposer.org/):

```bash
composer require kiwilan/php-audio
```

## Usage

Core metadata:

```php
use Kiwilan\Audio\Audio;

$audio = Audio::read('path/to/audio.mp3');

$audio->getTitle(); // `?string` to get title
$audio->getArtist(); // `?string` to get artist
$audio->getAlbum(); // `?string` to get album
$audio->getGenre(); // `?string` to get genre
$audio->getYear(); // `?int` to get year
$audio->getTrackNumber(); // `?string` to get track number
$audio->getComment(); // `?string` to get comment
$audio->getAlbumArtist(); // `?string` to get album artist
$audio->getComposer(); // `?string` to get composer
$audio->getDiscNumber(); // `?string` to get disc number
$audio->isCompilation(); // `bool` to know if is compilation
$audio->getCreationDate(); // `?string` to get creation date
$audio->getCopyright(); // `?string` to get copyright
$audio->getEncoding(); // `?string` to get encoding
$audio->getDescription(); // `?string` to get description
$audio->getSynopsis(); // `?string` to get synopsis
$audio->getLanguage(); // `?string` to get language
$audio->getLyrics(); // `?string`
$audio->getDuration(); // `?float` to get duration in seconds
$audio->getDurationHuman(); // `?string` to get duration in human readable format
```

Raw tags:

```php
use Kiwilan\Audio\Audio;

$audio = Audio::read('path/to/audio.mp3');

$raw_all = $audio->getRawAll(); // `array` with all tags
$raw = $audio->getRaw(); // `array` with main tag
$title = $audio->getRawKey('title'); // `?string` to get title same as `$audio->getTitle()`

$format = $audio->getRaw('id3v2'); // `?array` with all tags with format `id3v2`
$title = $audio->getRawKey('title', 'id3v2'); // `?string` to get title with format `id3v2`
```

Additional metadata:

```php
use Kiwilan\Audio\Audio;

$audio = Audio::read('path/to/audio.mp3');

$audio->getPath(); // `string` to get path
$audio->getExtension(); // `string` to get extension
$audio->hasCover(); // `bool` to know if has cover
$audio->isValid(); // `bool` to know if file is valid audio file
$audio->isWritable(); // `bool` to know if file is writable
$audio->getFormat(); // `AudioFormatEnum` to get format (mp3, m4a, ...)
$audio->getType(); // `?AudioTypeEnum` ID3 type (id3, riff, asf, quicktime, matroska, ape, vorbiscomment)
```

You can use `toArray()` method to get raw info:

```php
use Kiwilan\Audio\Audio;

$audio = Audio::read('path/to/audio.mp3');

$audio->toArray(); // `array` with all metadata
```

Advanced properties:

```php
use Kiwilan\Audio\Audio;

$audio = Audio::read('path/to/audio.mp3');

$audio->getId3Reader(); // `?Id3Reader` reader based on `getID3`
$audio->getMetadata(); // `?AudioMetadata` with audio metadata
$audio->getCover(); // `?AudioCover` with cover metadata
```

### Update

You can update audio files metadata with `Audio::class`, but not all formats are supported. [See supported formats](#updatable-formats)

> [!WARNING]
>
> You can use any property of `Audio::class` but if you use a property not supported by the format, it will be ignored.

```php
use Kiwilan\Audio\Audio;

$audio = Audio::read('path/to/audio.mp3');
$audio->getTitle(); // `Title`

$tag = $audio->write()
  ->title('New Title')
  ->artist('New Artist')
  ->album('New Album')
  ->genre('New Genre')
  ->year('2022')
  ->trackNumber('2/10')
  ->albumArtist('New Album Artist')
  ->comment('New Comment')
  ->composer('New Composer')
  ->creationDate('2021-01-01')
  ->description('New Description')
  ->synopsis('New Synopsis')
  ->discNumber('2/2')
  ->encodingBy('New Encoding By')
  ->encoding('New Encoding')
  ->isCompilation()
  ->lyrics('New Lyrics')
  ->cover('path/to/cover.jpg') // you can use file content `file_get_contents('path/to/cover.jpg')`
  ->save();

$audio = Audio::read('path/to/audio.mp3');
$audio->getTitle(); // `New Title`
$audio->getCreationDate(); // `null` because `creationDate` is not supported by `MP3`
```

Some properties are not supported by all formats, for example `MP3` can't handle some properties like `lyrics` or `stik`, if you try to update these properties, they will be ignored.

#### Set tags manually

You can set tags manually with `tag()` or `tags()` methods, but you need to know the format of the tag, you could use `tagFormats` to set formats of tags (if you don't know the format, it will be automatically detected).

> [!WARNING]
>
> If you use `tags` method, you have to use key used by metadata container. For example, if you want to set album artist in `id3v2`, you have to use `band` key. If you want to know which key to use check [`src/Core/AudioCore.php`](https://github.com/kiwilan/php-audio/blob/main/src/Core/AudioCore.php) file.
>
> If your key is not supported, `save` method will throw an exception, unless you use `skipErrors`.

```php
use Kiwilan\Audio\Audio;

$audio = Audio::read('path/to/audio.mp3');
$audio->getAlbumArtist(); // `Band`

$tag = $audio->write()
    ->tag('composer', 'New Composer')
    ->tag('genre', 'New Genre') // can be chained
    ->tags([
        'title' => 'New Title',
        'band' => 'New Band', // `band` is used by `id3v2` to set album artist, method is `albumArtist` but `albumArtist` key will throw an exception with `id3v2`
    ])
    ->tagFormats(['id3v1', 'id3v2.4']) // optional
    ->save();

$audio = Audio::read('path/to/audio.mp3');
$audio->getAlbumArtist(); // `New Band`
```

#### Arrow functions

```php
use Kiwilan\Audio\Audio;

$audio = Audio::read('path/to/audio.mp3');
$audio->getAlbumArtist(); // `Band`

$tag = $audio->write()
  ->title('New Title')
  ->albumArtist('New Band') // `albumArtist` will set `band` for `id3v2`, exception safe
  ->save();

$audio = Audio::read('path/to/audio.mp3');
$audio->getAlbumArtist(); // `New Band`
```

#### Skip errors

You can use `skipErrors` to prevent exception if you use unsupported format.

```php
use Kiwilan\Audio\Audio;

$audio = Audio::read('path/to/audio.mp3');

$tag = $audio->write()
  ->tags([
    'title' => 'New Title',
    'title2' => 'New title', // not supported by `id3v2`, will throw an exception
  ])
  ->skipErrors() // will prevent exception
  ->save();
```

> [!NOTE]
>
> Arrow functions are exception safe for properties but not for unsupported formats.

### Raw tags

Audio files format metadata with different methods, `JamesHeinrich/getID3` offer to check these metadatas by different methods. In `raw_all` property of `Audio::class`, you will find raw metadata from `JamesHeinrich/getID3` package, like `id3v2`, `id3v1`, `riff`, `asf`, `quicktime`, `matroska`, `ape`, `vorbiscomment`...

If you want to extract specific field which can be skipped by `Audio::class`, you can use `raw_all` property.

```php
use Kiwilan\Audio\Audio;

$audio = Audio::read('path/to/audio.mp3');
$raw_all = $audio->getRawAll(); // all formats
$raw = $audio->getRaw(); // main format
```

### AudioMetadata

```php
use Kiwilan\Audio\Audio;

$audio = Audio::read('path/to/audio.mp3');
$metadata = $audio->getMetadata();

$metadata->getFileSize(); // `?int` in bytes
$metadata->getSizeHuman(); // `?string` (1.2 MB, 1.2 GB, ...)
$metadata->getExtension(); // `?string` (mp3, m4a, ...)
$metadata->getEncoding(); // `?string` (UTF-8...)
$metadata->getMimeType(); // `?string` (audio/mpeg, audio/mp4, ...)
$metadata->getDurationSeconds(); // `?float` in seconds
$metadata->getDurationReadable(); // `?string` (00:00:00)
$metadata->getBitrate(); // `?int` in kbps
$metadata->getBitrateMode(); // `?string` (cbr, vbr, ...)
$metadata->getSampleRate(); // `?int` in Hz
$metadata->getChannels(); // `?int` (1, 2, ...)
$metadata->getChannelMode(); // `?string` (mono, stereo, ...)
$metadata->isLossless(); // `bool` to know if is lossless
$metadata->getCompressionRatio(); // `?float`
$metadata->getFilesize(); // `?int` in bytes
$metadata->getSizeHuman(); // `?string` (1.2 MB, 1.2 GB, ...)
$metadata->getDataFormat(); // `?string` (mp3, m4a, ...)
$metadata->getWarning(); // `?array`
$metadata->getQuicktime(); // `?Id3AudioQuicktime
$metadata->getCodec(); // `?string` (mp3, aac, ...)
$metadata->getEncoderOptions(); // `?string`
$metadata->getVersion(); // `?string`
$metadata->getAvDataOffset(); // `?int` in bytes
$metadata->getAvDataEnd(); // `?int` in bytes
$metadata->getFilePath(); // `?string`
$metadata->getFilename(); // `?string`
$metadata->getLastAccessAt(); // `?DateTime`
$metadata->getCreatedAt(); // `?DateTime`
$metadata->getModifiedAt(); // `?DateTime`
$metadata->toArray();
```

### Quicktime

For `quicktime` type, like for M4B audiobook, you can use `Id3TagQuicktime` to get more informations.

```php
use Kiwilan\Audio\Audio;

$audio = Audio::read('path/to/audio.m4b');
$quicktime = $audio->getMetadata()->getQuicktime();

$quicktime->getHinting();
$quicktime->getController();
$quicktime->getFtyp();
$quicktime->getTimestampsUnix();
$quicktime->getTimeScale();
$quicktime->getDisplayScale();
$quicktime->getVideo();
$quicktime->getAudio();
$quicktime->getSttsFramecount();
$quicktime->getComments();
$quicktime->getFree();
$quicktime->getWide();
$quicktime->getMdat();
$quicktime->getEncoding();
$quicktime->getChapters(); // ?Id3AudioQuicktimeChapter[]
```

### AudioCover

```php
use Kiwilan\Audio\Audio;

$audio = Audio::read('path/to/audio.mp3');
$cover = $audio->getCover();

$cover->getContents(); // `?string` raw file
$cover->getMimeType(); // `?string` (image/jpeg, image/png, ...)
$cover->getWidth(); // `?int` in pixels
$cover->getHeight(); // `?int` in pixels
```

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
|  MP4   |    ✅     | Digital multimedia container format  |   `quicktime`   | _Partially supported_ |
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
> -   FLAC comments (need `flac`)

| Format |         Notes         |    Requires    |
| :----: | :-------------------: | :------------: |
|  FLAC  | _Cover not supported_ |     `flac`     |
|  MP3   |                       |                |
|  OGG   | _Cover not supported_ | `vorbis-tools` |

-   `flac`: with `apt`, `brew` or `scoop`
-   `vorbis-tools`: with `apt`, `brew` or `scoop`
    -   With `scoop`, `vorbis-tools` is not available, you can use `extras/icecast` instead.

### Convert properties

`Audio::class` convert some properties to be more readable.

-   `ape` format: [`Id3TagApe`](https://github.com/kiwilan/php-audio/blob/main/src/Id3/Tag/Id3TagApe.php)
-   `asf` format: [`Id3TagAsf`](https://github.com/kiwilan/php-audio/blob/main/src/Id3/Tag/Id3TagAsf.php)
-   `id3v1` format: [`Id3TagAudioV1`](https://github.com/kiwilan/php-audio/blob/main/src/Id3/Tag/Id3TagAudioV1.php)
-   `id3v2` format: [`Id3TagAudioV2`](https://github.com/kiwilan/php-audio/blob/main/src/Id3/Tag/Id3TagAudioV2.php)
-   `matroska` format: [`Id3TagMatroska`](https://github.com/kiwilan/php-audio/blob/main/src/Id3/Tag/Id3TagMatroska.php)
-   `quicktime` format: [`Id3TagQuicktime`](https://github.com/kiwilan/php-audio/blob/main/src/Id3/Tag/Id3TagQuicktime.php)
-   `vorbiscomment` format: [`Id3TagVorbisComment`](https://github.com/kiwilan/php-audio/blob/main/src/Id3/Tag/Id3TagVorbisComment.php)
-   `riff` format: [`Id3TagRiff`](https://github.com/kiwilan/php-audio/blob/main/src/Id3/Tag/Id3TagRiff.php)
-   `unknown` format: [`Id3TagVorbisComment`](https://github.com/kiwilan/php-audio/blob/main/src/Id3/Tag/Id3TagVorbisComment.php)

|    ID3 type     |        Original         |   New property   |
| :-------------: | :---------------------: | :--------------: |
|     `id3v2`     |         `band`          |  `album_artist`  |
|     `id3v2`     |     `part_of_a_set`     |  `disc_number`   |
|     `id3v2`     | `part_of_a_compilation` | `is_compilation` |
|   `quicktime`   |      `compilation`      | `is_compilation` |
|   `quicktime`   |      `encoded_by`       |  `encoding_by`   |
|   `quicktime`   |     `encoding_tool`     |    `encoding`    |
|   `quicktime`   |   `description_long`    |    `synopsis`    |
|      `asf`      |      `albumartist`      |  `album_artist`  |
|      `asf`      |       `partofset`       |  `disc_number`   |
|      `asf`      |   `encodingsettings`    |    `encoding`    |
| `vorbiscomment` |        `encoder`        |    `encoding`    |
| `vorbiscomment` |      `albumartist`      |  `album_artist`  |
| `vorbiscomment` |      `discnumber`       |  `disc_number`   |
| `vorbiscomment` |      `compilation`      | `is_compilation` |
| `vorbiscomment` |      `tracknumber`      |  `track_number`  |
|   `matroska`    |         `disc`          |  `disc_number`   |
|   `matroska`    |      `part_number`      |  `track_number`  |
|   `matroska`    |         `date`          |      `year`      |
|   `matroska`    |      `compilation`      | `is_compilation` |
|   `matroska`    |        `encoder`        |    `encoding`    |
|      `ape`      |         `disc`          |  `disc_number`   |
|      `ape`      |      `compilation`      | `is_compilation` |
|      `ape`      |         `track`         |  `track_number`  |
|      `ape`      |         `date`          |      `year`      |
|      `ape`      |        `encoder`        |    `encoding`    |

## Testing

```bash
composer test
```

## Tools

-   [ffmpeg](https://ffmpeg.org/): free and open-source software project consisting of a suite of libraries and programs for handling video, audio, and other multimedia files and streams.
-   [MP3TAG](https://www.mp3tag.de/en/): powerful and easy-to-use tool to edit metadata of audio files (free on Windows).
-   [Audiobook Builder](https://www.splasm.com/audiobookbuilder/): makes it easy to turn audio CDs and files into audiobooks (only macOS and paid).
-   [Tag Editor](https://github.com/Martchus/tageditor): A tag editor with Qt GUI and command-line interface supporting MP4/M4A/AAC (iTunes), ID3, Vorbis, Opus, FLAC and Matroska.
-   [Tag Editor](https://amvidia.com/tag-editor): a spreadsheet application for editing audio metadata in a simple, fast, and flexible way.

## FAQ

### I have a specific metadata field in my audio files, what can I do?

In `Audio::class`, you have a property `raw_all` which contains all raw metadata, if `JamesHeinrich/getID3` support this field, you will find it in this property.

```php
use Kiwilan\Audio\Audio;

$audio = Audio::read('path/to/audio.mp3');
$raw_all = $audio->getRawAll());

$custom = null;
$id3v2 = $raw_all['id3v2'] ?? [];

if ($id3v2) {
  $custom = $id3v2['custom'] ?? null;
}
```

If your field could be added to global properties of `Audio::class`, you could create an [an issue](https://github.com/kiwilan/php-audio/issues/new/choose).

### Metadata are `null`, what can I do?

You can check `extras` property to know if some metadata are available.

```php
use Kiwilan\Audio\Audio;

$audio = Audio::read('path/to/audio.mp3');

$raw_all = $audio->getRawAll();
var_dump($raw_all);
```

If you find metadata which are not parsed by `Audio::class`, you can create [an issue](https://github.com/kiwilan/php-audio/issues/new/choose), otherwise `JamesHeinrich/getID3` doesn't support this metadata.z

### My favorite format is not supported, what can I do?

You can create [an issue](https://github.com/kiwilan/php-audio/issues/new/choose) with the format name and a link to the format documentation. If `JamesHeinrich/getID3` support this format, I will add it to this package but if you want to contribute, you can create a pull request with the format implementation.

**Please give me an example file to test the format.**

### I have an issue with a supported format, what can I do?

You can create [an issue](https://github.com/kiwilan/php-audio/issues/new/choose) with informations.

### How to convert audio files?

This package doesn't provide a way to convert audio files, but you can use [ffmpeg](https://ffmpeg.org/) to convert audio files and [PHP-FFMpeg/PHP-FFMpeg](https://github.com/PHP-FFMpeg/PHP-FFMpeg).

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

-   [`ewilan-riviere`](https://github.com/ewilan-riviere): package author
-   [`JamesHeinrich/getID3`](https://github.com/JamesHeinrich/getID3): parser used to read audio files
-   [`spatie/package-skeleton-php`](https://github.com/spatie/package-skeleton-php): package skeleton used to create this package

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[<img src="https://user-images.githubusercontent.com/48261459/201463225-0a5a084e-df15-4b11-b1d2-40fafd3555cf.svg" height="120rem" width="100%" />](https://github.com/kiwilan)

[version-src]: https://img.shields.io/packagist/v/kiwilan/php-audio.svg?style=flat&colorA=18181B&colorB=777BB4
[version-href]: https://packagist.org/packages/kiwilan/php-audio
[php-version-src]: https://img.shields.io/static/v1?style=flat&label=PHP&message=v8.1&color=777BB4&logo=php&logoColor=ffffff&labelColor=18181b
[php-version-href]: https://www.php.net/
[downloads-src]: https://img.shields.io/packagist/dt/kiwilan/php-audio.svg?style=flat&colorA=18181B&colorB=777BB4
[downloads-href]: https://packagist.org/packages/kiwilan/php-audio
[license-src]: https://img.shields.io/github/license/kiwilan/php-audio.svg?style=flat&colorA=18181B&colorB=777BB4
[license-href]: https://github.com/kiwilan/php-audio/blob/main/README.md
[tests-src]: https://img.shields.io/github/actions/workflow/status/kiwilan/php-audio/run-tests.yml?branch=main&label=tests&style=flat&colorA=18181B
[tests-href]: https://packagist.org/packages/kiwilan/php-audio
[codecov-src]: https://img.shields.io/codecov/c/gh/kiwilan/php-audio/main?style=flat&colorA=18181B&colorB=777BB4
[codecov-href]: https://codecov.io/gh/kiwilan/php-audio
