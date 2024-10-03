# Changelog

All notable changes to `php-audio` will be documented in this file.

## v4.0.0 - 2024-10-03

**BREAKING CHANGES**

- internal architecture has been totally redesigned
- `get()` static method is now `read()` (old method is still available)
- `update()` method is now `write()` (old method is still available)
- `getAudio()` is now `getMetadata()`
- `getStat()` has been removed, you can find `getLastAccessAt()`, `getCreatedAt()`, `getModifiedAt()` into `getMetadata()`
- `getWriter()` has been removed, only used when `write()` method is called
- `getReader()` is now `getId3Reader()`
- `getPodcastDescription()` is now `getSynopsis()`
- `getStik()` has been removed (you can find it in `getRaw()` method or with `getRawKey('stik')`)
- cover contents is now base64 encoded into `AudioCover` object
- `toArray()` has been revised to return a more structured array
- `getDuration()` is now `float`
- add `getDurationHuman()` to get human readable duration
- add `getTrackNumberInt()` to get track number as integer
- add `getDiscNumberInt()`
- `getTags()` is now `getRawAll()` as multidimensional array
- new method `getRaw()` will return main format as array
- new method `getRawKey('ANY_KEY')` will return specific key from main format
- `getAudioFormats()` has been removed
- `getExtras()` has been removed (duplicate of `getRawAll()`, `getRaw()` or `toArray()`)
- `writer()` can now use `tag('ANY_KEY', 'ANY_VALUE')` to update directly any tag without use `tags()`
- `writer()` method `tags()` has been modified, it's not native method of `getID3` anymore, just an array of tags
- add `getQuicktime()` to `AudioMetadata` to get quicktime tags, with chapters for audiobooks

*AudioCover*

- now contents are stored as base64 encoded string
- new `getContents()` method to get contents, default is raw string (binary) and you can get base64 encoded string with `true` parameter
- new `getMimeType()` method to get mime type of the cover
- new `getWidth()` method to get width of the cover
- new `getHeight()` method to get height of the cover
- new `toArray()` method to get cover as array

*AudioMetadata*

- `getFilesize()` is now `getFileSize()`
- add `getSizeHuman()` to get human readable size with decimal precision
- add `getDataFormat()` to get data format like `mp3`
- remove `getDurationReadable()` because it's now into `Audio::class`
- `getLossless()` is now `isLossless()`
- add `getCodec()` to get codec of the file, like `LAME`
- add `getEncoderOptions()` to get encoder options of the file, like `CBR`
- add `getVersion()` to get version of `JamesHeinrich/getID3`
- add `getAvDataOffset()` to get offset of audio/video data
- add `getAvDataEnd()` to get end of audio/video data
- add `getFilePath()` to get file path, like `/path/to`
- add `getFilename()` to get filename, like `file.mp3`
- add `getLastAccessAt()`, `getCreatedAt()`, `getModifiedAt()` from `stat()` function
- add `toArray()` method to get metadata as array

**Fix**

- now `write()` won't erase other tags #34

## v3.0.08 - 2024-07-28

Add `getDurationHumanReadable()` method to get the duration in human readable format: `HH:MM:SS`.

## v3.0.07 - 2024-06-04

By @panVag

* use `DateTimeImmutable` super powers to parse date and datetime strings
* catch and ignore exception when instantiating the aforementioned object so the whole script won't fail
* remove the null safe operators when it's not needed

## v3.0.06 - 2024-02-04

- Add `getAudioFormats()` to `Audio::class` to get an array with all audio formats of file.
- Add param to `getTags(?string $audioFormat = null)` to select a specific tag format, default will be maximum tags found.
- Add same param to `getTag(string $tag, ?string $audioFormat = null)` to select a specific tag format, default will be maximum tags found.
- Add `getPodcastDescription()` method to `Audio::class` to get the podcast description.
- Add `getLanguage()` method to `Audio::class` to get the language of the podcast.

## v3.0.05 - 2024-02-03

- Add `toArray()` method to `Audio::class` to get all properties without cover.
- Add `getTags()` method to `Audio::class` to get all tags as `array<string, string>`.
- Add `getTag(string $tag)` method to `Audio::class` to get a single tag.

## v3.0.04 - 2023-11-01

- `AudioCore`, fix `fromId3()` with `null` check `Id3AudioTagV1` and `Id3AudioTagV2`, issue #18 thanks to @cospin

## v3.0.03 - 2023-10-31

- `AudioCore`, `fromId3()` method comment bug, issue #18 thanks to @cospin
- `AudioMetadata`, add `path`, `dataformat`

## v3.0.02 - 2023-09-21

- Id3Writer: `trackNumber()` and `discNumber()` accept now integers (and strings)

## v3.0.01 - 2023-09-20

- Add `getContents()` to AudioCover
- Old method `getContent()` is deprecated

## 3.0.0 - 2023-08-08

### BREAKING CHANGES

- All simple getters have now `get` prefix. For example, `getTitle()` instead of `title()`, `getAlbum()` instead of `album()`, etc. It concerns all simple getters of `AudioCore`, `AudioCover`, `AudioMetadata`, `AudioStat`, `Id3Reader` classes.

> Why?
All these classes have some methods like setters or actions. To be consistent and clear, all simple getters have now `get` prefix.

## 2.0.0 - 2023-06-08

**BREAKING CHANGES**

- `update` chained methods are now without `set` prefix

**Features**

- `update` have now `tags` to set tags manually
- `update` have now `tagFormats` to set tag formats manually
- `update` have now `preventFailOnError` to prevent fail on error

## 1.0.2 - 2023-06-05

- fix ci

## 1.0.1 - 2023-06-05

- fix Windows bugs

## 1.0.0 - 2023-06-05

Official version

### BREAKING CHANGES

For `Audio::class`

- to parse audio file `make` static method is now `get`

### New features

- add `update` method to `Audio::class` to update metadata
- `id3` property is now `reader` and `writer`
- new enums `AudioTypeEnum` and `AudioFormatEnum`

## 0.3.0 - 2023-06-04

- Add more formats

## 0.2.0 - 2023-06-04

- add multiple formats
- add some properties
- refactoring

## 0.1.0 - 2023-06-04

init
