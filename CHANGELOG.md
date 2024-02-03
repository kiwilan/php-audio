# Changelog

All notable changes to `php-audio` will be documented in this file.

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
