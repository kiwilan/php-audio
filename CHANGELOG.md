# Changelog

All notable changes to `php-audio` will be documented in this file.

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
