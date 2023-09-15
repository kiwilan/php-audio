# PHP Audio

![Banner with speaker and PHP Audio title](https://raw.githubusercontent.com/kiwilan/php-audio/main/docs/banner.jpg)

[![php][php-version-src]][php-version-href]
[![version][version-src]][version-href]
[![downloads][downloads-src]][downloads-href]
[![license][license-src]][license-href]
[![tests][tests-src]][tests-href]
[![codecov][codecov-src]][codecov-href]

PHP package to parse and update audio files metadata, with [`JamesHeinrich/getID3`](https://github.com/JamesHeinrich/getID3).

> **Note**
>
> You can check formats supported on [Supported formats](#supported-formats) section.

## About

Audio files can use different formats, this package aims to provide a simple way to read them with [`JamesHeinrich/getID3`](https://github.com/JamesHeinrich/getID3). The `JamesHeinrich/getID3` package is excellent to read metadata from audio files, but output is just an array, current package aims to provide a simple way to read audio files with a beautiful API.

## Requirements

-   PHP >= 8.1
-   Optional for update
    -   `FLAC`: `flac` (with `apt`, `brew` or `scoop`)
    -   `OGG`: `vorbis-tools` (with `apt` or `brew`) / `extras/icecast` (with `scoop`)

### Roadmap

-   Add support for more formats with [external packages](https://askubuntu.com/questions/226773/how-to-read-mp3-tags-in-shell)

| Program  |  Version   |  Time / s  |
| :------: | :--------: | :--------: |
| exiftool |   10.25    | 49.5 ± 0.5 |
|  lltag   |   0.14.5   |  41 ± 1.0  |
| ffprobe  | 3.1.3-1+b3 |  33 ± 0.5  |
|  eyeD3   |   0.6.18   |  24 ± 0.5  |
| id3info  |   3.8.3    | 4.2 ± 0.1  |
|  id3v2   |   0.1.12   | 2.9 ± 0.1  |
| id3tool  |    1.2a    | 1.7 ± 0.1  |
| mp3info  |   0.8.5a   | 1.4 ± 0.1  |

## Installation

You can install the package via composer:

```bash
composer require kiwilan/php-audio
```

## Usage

Core metadata:

```php
use Kiwilan\Audio\Audio;

$audio = Audio::get('path/to/audio.mp3');

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
$audio->getCreationDate(); // `?string` to get creation date (audiobook)
$audio->getCopyright(); // `?string` to get copyright (audiobook)
$audio->getEncoding(); // `?string` to get encoding
$audio->getDescription(); // `?string` to get description (audiobook)
$audio->getLyrics(); // `?string` (audiobook)
$audio->getStik(); // `?string` (audiobook)
$audio->getDuration(); // `?float` to get duration in seconds
```

Additional metadata:

```php
use Kiwilan\Audio\Audio;

$audio = Audio::get('path/to/audio.mp3');

$audio->getPath(); // `string` to get path
$audio->hasCover(); // `bool` to know if has cover
$audio->isValid(); // `bool` to know if file is valid audio file
$audio->getFormat(); // `AudioFormatEnum` to get format (mp3, m4a, ...)
$audio->getType(); // `?AudioTypeEnum` ID3 type (id3, riff, asf, quicktime, matroska, ape, vorbiscomment)
$audio->getExtras(); // `array` with raw metadata (could contains some metadata not parsed)
```

Advanced properties:

```php
use Kiwilan\Audio\Audio;

$audio = Audio::get('path/to/audio.mp3');

$audio->getReader(); // `?Id3Reader` reader based on `getID3`
$audio->getWriter(); // `?Id3Writer` writer based on `getid3_writetags`
$audio->getStat(); // `AudioStat` (from `stat` function)
$audio->getAudio(); // `?AudioMetadata` with audio metadata
$audio->getCover(); // `?AudioCover` with cover metadata
```

### Update

You can update audio files metadata with `Audio::class`, but not all formats are supported. [See supported formats](#updatable-formats)

> **Warning**
>
> You can use any property of `Audio::class` but if you use a property not supported by the format, it will be ignored.

```php
use Kiwilan\Audio\Audio;

$audio = Audio::get('path/to/audio.mp3');
$audio->getTitle(); // `Title`

$tag = $audio->update()
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
  ->discNumber('2/2')
  ->encodingBy('New Encoding By')
  ->encoding('New Encoding')
  ->isCompilation()
  ->lyrics('New Lyrics')
  ->stik('New Stik')
  ->cover('path/to/cover.jpg') // you can use file content `file_get_contents('path/to/cover.jpg')`
  ->save();

$audio = Audio::get('path/to/audio.mp3');
$audio->getTitle(); // `New Title`
$audio->getCreationDate(); // `null` because `creationDate` is not supported by `MP3`
```

Some properties are not supported by all formats, for example `MP3` can't handle some properties like `lyrics` or `stik`, if you try to update these properties, they will be ignored.

#### Set tags manually

You can set tags manually with `tags` method, but you need to know the format of the tag, you could use `tagFormats` to set formats of tags (if you don't know the format, it will be automatically detected).

> **Warning**
>
> If you use `tags` method, you have to use key used by metadata container. For example, if you want to set album artist in `id3v2`, you have to use `band` key. If you want to know which key to use check `src/Models/AudioCore.php` file.
>
> If your key is not supported, `save` method will throw an exception, unless you use `preventFailOnErrors`.

```php
use Kiwilan\Audio\Audio;

$audio = Audio::get('path/to/audio.mp3');
$audio->getAlbumArtist(); // `Band`

$tag = $audio->update()
  ->tags([
    'title' => 'New Title',
    'band' => 'New Band', // `band` is used by `id3v2` to set album artist, method is `albumArtist` but `albumArtist` key will throw an exception with `id3v2`
  ])
  ->tagFormats(['id3v1', 'id3v2.4']) // optional
  ->save();

$audio = Audio::get('path/to/audio.mp3');
$audio->getAlbumArtist(); // `New Band`
```

#### Arrow functions

```php
use Kiwilan\Audio\Audio;

$audio = Audio::get('path/to/audio.mp3');
$audio->getAlbumArtist(); // `Band`

$tag = $audio->update()
  ->title('New Title')
  ->albumArtist('New Band') // `albumArtist` will set `band` for `id3v2`, exception safe
  ->save();

$audio = Audio::get('path/to/audio.mp3');
$audio->getAlbumArtist(); // `New Band`
```

#### Prevent fail on errors

You can use `preventFailOnError` to prevent exception if you use unsupported format.

```php
use Kiwilan\Audio\Audio;

$audio = Audio::get('path/to/audio.mp3');

$tag = $audio->update()
  ->tags([
    'title' => 'New Title',
    'title2' => 'New title', // not supported by `id3v2`, will throw an exception
  ])
  ->preventFailOnError() // will prevent exception
  ->save();
```

Arrow functions are exception safe for properties but not for unsupported formats.

```php
use Kiwilan\Audio\Audio;

$audio = Audio::get('path/to/audio.mp3');

$tag = $audio->update()
  ->encoding('New encoding') // not supported by `id3v2`, BUT will not throw an exception
  ->preventFailOnError() // if you have some errors with unsupported format for example, you can prevent exception
  ->save();
```

#### Tags and cover

Of course you can add cover with `tags` method.

```php
use Kiwilan\Audio\Audio;

$audio = Audio::get('path/to/audio.mp3');
$cover = 'path/to/cover.jpg';

$image = getimagesize($cover);
$coverData = file_get_contents($cover);
$coverPicturetypeid = $image[2];
$coverDescription = 'cover';
$coverMime = $image['mime'];

$tag = $audio->update()
  ->tags([
    'title' => 'New Title',
    'band' => 'New Band',
    'attached_picture' => [
      [
        'data' => $coverData,
        'picturetypeid' => $coverPicturetypeid,
        'description' => $coverDescription,
        'mime' => $coverMime,
      ],
    ],
  ])
  ->save();
```

#### Merge tags

Merge `tags` with arrow functions.

```php
use Kiwilan\Audio\Audio;

$audio = Audio::get($path);

$tag = $audio->update()
    ->title('New Title') // will be merged with `tags` and override `title` key
    ->tags([
        'title' => 'New Title tag',
        'band' => 'New Band',
    ]);

$tag->save();

$audio = Audio::get($path);
expect($audio->getTitle())->toBe('New Title');
expect($audio->getAlbumArtist())->toBe('New Band');
```

### Extras

Audio files format metadata with different methods, `JamesHeinrich/getID3` offer to check these metadatas by different methods. In `extras` property of `Audio::class`, you will find raw metadata from `JamesHeinrich/getID3` package, like `id3v2`, `id3v1`, `riff`, `asf`, `quicktime`, `matroska`, `ape`, `vorbiscomment`...

If you want to extract specific field which can be skipped by `Audio::class`, you can use `extras` property.

```php
use Kiwilan\Audio\Audio;

$audio = Audio::get('path/to/audio.mp3');
$extras = $audio->getExtras();

$id3v2 = $extras['id3v2'] ?? [];
```

### AudioMetadata

```php
use Kiwilan\Audio\Audio;

$audio = Audio::get('path/to/audio.mp3');

$audio->getAudio()->getFilesize(); // `?int` in bytes
$audio->getAudio()->getExtension(); // `?string` (mp3, m4a, ...)
$audio->getAudio()->getEncoding(); // `?string` (UTF-8...)
$audio->getAudio()->getMimeType(); // `?string` (audio/mpeg, audio/mp4, ...)
$audio->getAudio()->getDurationSeconds(); // `?float` in seconds
$audio->getAudio()->getDurationReadable(); // `?string` (00:00:00)
$audio->getAudio()->getBitrate(); // `?int` in kbps
$audio->getAudio()->getBitrateMode(); // `?string` (cbr, vbr, ...)
$audio->getAudio()->getSampleRate(); // `?int` in Hz
$audio->getAudio()->getChannels(); // `?int` (1, 2, ...)
$audio->getAudio()->getChannelMode(); // `?string` (mono, stereo, ...)
$audio->getAudio()->getLossless(); // `bool` to know if is lossless
$audio->getAudio()->getCompressionRatio(); // `?float`
```

### AudioCover

```php
use Kiwilan\Audio\Audio;

$audio = Audio::get('path/to/audio.mp3');

$audio->getCover()->getContent(); // `?string` raw file
$audio->getCover()->getMimeType(); // `?string` (image/jpeg, image/png, ...)
$audio->getCover()->getWidth(); // `?int` in pixels
$audio->getCover()->getHeight(); // `?int` in pixels
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

|    ID3 type     |        Original         |  New property   |
| :-------------: | :---------------------: | :-------------: |
|     `id3v2`     |         `band`          |  `albumArtist`  |
|     `id3v2`     |     `track_number`      |  `trackNumber`  |
|     `id3v2`     |     `part_of_a_set`     |  `discNumber`   |
|     `id3v2`     | `part_of_a_compilation` | `isCompilation` |
|   `quicktime`   |     `track_number`      |  `trackNumber`  |
|   `quicktime`   |      `disc_number`      |  `discNumber`   |
|   `quicktime`   |      `compilation`      | `isCompilation` |
|   `quicktime`   |     `creation_date`     | `creationDate`  |
|   `quicktime`   |     `album_artist`      |  `albumArtist`  |
|   `quicktime`   |      `encoded_by`       |  `encodingBy`   |
|   `quicktime`   |     `encoding_tool`     |   `encoding`    |
|   `quicktime`   |   `description_long`    | `description`\* |
|      `asf`      |      `albumartist`      |  `albumArtist`  |
|      `asf`      |       `partofset`       |  `discNumber`   |
|      `asf`      |     `track_number`      |  `trackNumber`  |
|      `asf`      |   `encodingsettings`    |   `encoding`    |
| `vorbiscomment` |        `encoder`        |   `encoding`    |
| `vorbiscomment` |      `albumartist`      |  `albumArtist`  |
| `vorbiscomment` |      `discnumber`       |  `discNumber`   |
| `vorbiscomment` |      `compilation`      | `isCompilation` |
| `vorbiscomment` |      `tracknumber`      |  `trackNumber`  |
|   `matroska`    |     `album_artist`      |  `albumArtist`  |
|   `matroska`    |         `disc`          |  `discNumber`   |
|   `matroska`    |      `part_number`      |  `trackNumber`  |
|   `matroska`    |         `date`          |     `year`      |
|   `matroska`    |      `compilation`      | `isCompilation` |
|   `matroska`    |        `encoder`        |   `encoding`    |
|      `ape`      |     `album_artist`      |  `albumArtist`  |
|      `ape`      |         `disc`          |  `discNumber`   |
|      `ape`      |      `compilation`      | `isCompilation` |
|      `ape`      |         `track`         |  `trackNumber`  |
|      `ape`      |         `date`          |     `year`      |
|      `ape`      |        `encoder`        |   `encoding`    |

\*: if `description_long` has more content than `description`, it replaces `description`.

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

In `Audio::class`, you have a property `extras` which contains all raw metadata, if `JamesHeinrich/getID3` support this field, you will find it in this property.

```php
use Kiwilan\Audio\Audio;

$audio = Audio::get('path/to/audio.mp3');
$extras = $audio->getExtras();

$custom = null;
$id3v2 = $extras['id3v2'] ?? [];

if ($id3v2) {
  $custom = $id3v2['custom'] ?? null;
}
```

If your field could be added to global properties of `Audio::class`, you could create an [an issue](https://github.com/kiwilan/php-audio/issues/new/choose).

### Metadata are `null`, what can I do?

You can check `extras` property to know if some metadata are available.

```php
use Kiwilan\Audio\Audio;

$audio = Audio::get('path/to/audio.mp3');

$extras = $audio->getExtras();
var_dump($extras);
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

-   [Ewilan Rivière](https://github.com/ewilan-riviere): package author
-   [JamesHeinrich/getID3](https://github.com/JamesHeinrich/getID3): parser used to read audio files
-   [spatie/package-skeleton-php](https://github.com/spatie/package-skeleton-php): package skeleton used to create this package
-   Tests files from [p1pdd.com](https://p1pdd.com/) (episode 00)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[<img src="https://user-images.githubusercontent.com/48261459/201463225-0a5a084e-df15-4b11-b1d2-40fafd3555cf.svg" height="120rem" width="100%" />](https://github.com/kiwilan)

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
