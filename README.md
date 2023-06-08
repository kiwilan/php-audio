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

## About

Audio files can use different formats, this package aims to provide a simple way to read them with [`JamesHeinrich/getID3`](https://github.com/JamesHeinrich/getID3). The `JamesHeinrich/getID3` package is excellent to read metadata from audio files, but output is just an array, current package aims to provide a simple way to read audio files with a beautiful API.

## Requirements

-   PHP >= 8.1

### Optional

To update audio files, you need to install some packages.

-   `FLAC`: `flac` (with `apt`, `brew` or `scoop`)
-   `OGG`: `vorbis-tools` (with `apt` or `brew`) / `extras/icecast` (with `scoop`)

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

> **Warning**
> You can use any property of `Audio::class` but if you use a property not supported by the format, it will be ignored.

```php
$audio = Audio::get('path/to/audio.mp3');
$audio->title(); // `Title`

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
$audio->title(); // `New Title`
$audio->creationDate(); // `null` because `creationDate` is not supported by `MP3`
```

Some properties are not supported by all formats, for example `MP3` can't handle some properties like `lyrics` or `stik`, if you try to update these properties, they will be ignored.

#### Set tags manually

You can set tags manually with `tags` method, but you need to know the format of the tag, you could use `tagFormats` to set formats of tags (if you don't know the format, it will be automatically detected).

> **Warning**
> If you use `tags` method, you have to use key used by metadata container. For example, if you want to set album artist in `id3v2`, you have to use `band` key. If you want to know which key to use check `src/Models/AudioCore.php` file.
>
> If your key is not supported, `save` method will throw an exception, unless you use `preventFailOnErrors`.

```php
$audio = Audio::get('path/to/audio.mp3');
$audio->albumArtist(); // `Band`

$tag = $audio->update()
  ->tags([
    'title' => 'New Title',
    'band' => 'New Band', // `band` is used by `id3v2` to set album artist, method is `albumArtist` but `albumArtist` key will throw an exception with `id3v2`
  ])
  ->tagFormats(['id3v1', 'id3v2.4']) // optional
  ->save();

$audio = Audio::get('path/to/audio.mp3');
$audio->albumArtist(); // `New Band`
```

#### Arrow functions

```php
$audio = Audio::get('path/to/audio.mp3');
$audio->albumArtist(); // `Band`

$tag = $audio->update()
  ->title('New Title')
  ->albumArtist('New Band') // `albumArtist` will set `band` for `id3v2`, exception safe
  ->save();

$audio = Audio::get('path/to/audio.mp3');
$audio->albumArtist(); // `New Band`
```

#### Prevent fail on errors

You can use `preventFailOnError` to prevent exception if you use unsupported format.

```php
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
$audio = Audio::get('path/to/audio.mp3');

$tag = $audio->update()
  ->encoding('New encoding') // not supported by `id3v2`, BUT will not throw an exception
  ->preventFailOnError() // if you have some errors with unsupported format for example, you can prevent exception
  ->save();
```

#### Tags and cover

Of course you can add cover with `tags` method.

```php
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
$audio = Audio::get($path);

$tag = $audio->update()
    ->title('New Title') // will be merged with `tags` and override `title` key
    ->tags([
        'title' => 'New Title tag',
        'band' => 'New Band',
    ]);

$tag->save();

$audio = Audio::get($path);
expect($audio->title())->toBe('New Title');
expect($audio->albumArtist())->toBe('New Band');
```

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

-   [ffmpeg](https://ffmpeg.org/): free and open-source software project consisting of a suite of libraries and programs for handling video, audio, and other multimedia files and streams.
-   [MP3TAG](https://www.mp3tag.de/en/): powerful and easy-to-use tool to edit metadata of audio files (free on Windows).
-   [Audiobook Builder](https://www.splasm.com/audiobookbuilder/): makes it easy to turn audio CDs and files into audiobooks (only macOS and paid).
-   [Tag Editor](https://github.com/Martchus/tageditor): A tag editor with Qt GUI and command-line interface supporting MP4/M4A/AAC (iTunes), ID3, Vorbis, Opus, FLAC and Matroska.
-   [Tag Editor](https://amvidia.com/tag-editor): a spreadsheet application for editing audio metadata in a simple, fast, and flexible way.

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

### Metadata are `null`, what can I do?

You can check `extras` property to know if some metadata are available.

```php
$audio = Audio::get('path/to/audio.mp3');

$extras = $audio->extras();
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
