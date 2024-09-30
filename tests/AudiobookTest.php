<?php

use Kiwilan\Audio\Audio;
use Kiwilan\Audio\Enums\AudioFormatEnum;
use Kiwilan\Audio\Enums\AudioTypeEnum;
use Kiwilan\Audio\Models\AudioMetadata;

it('can read audiobook', function () {
    $audiobook = Audio::read(AUDIOBOOK_RH);

    expect($audiobook->getPath())->toContain('tests/media/audiobook_rh.m4b');
    expect($audiobook->getExtension())->toBe('m4b');
    expect($audiobook->getFormat())->toBe(AudioFormatEnum::m4b);
    expect($audiobook->getType())->toBe(AudioTypeEnum::quicktime);
    expect($audiobook->getMetadata())->toBeInstanceOf(AudioMetadata::class);
    expect($audiobook->isWritable())->toBeTrue();
    expect($audiobook->isValid())->toBeTrue();
    expect($audiobook->hasCover())->toBeTrue();

    expect($audiobook->getTitle())->toBe('Assassin’s Apprentice');
    expect($audiobook->getArtist())->toBe('Robin Hobb');
    expect($audiobook->getAlbum())->toBe('Assassin’s Apprentice');
    expect($audiobook->getGenre())->toBe('Animals/Political/Epic/Military');
    expect($audiobook->getYear())->toBe(2024);
    expect($audiobook->getTrackNumber())->toBe('1/1');
    expect($audiobook->getTrackNumberInt())->toBe(1);
    expect($audiobook->getComment())->toBe('English');
    expect($audiobook->getAlbumArtist())->toBe('Robin Hobb');
    expect($audiobook->getComposer())->toBe('Paul Boehmer');
    expect($audiobook->getDiscNumber())->toBe('1');
    expect($audiobook->getDiscNumberInt())->toBe(1);
    expect($audiobook->isCompilation())->toBeTrue();
    expect($audiobook->getCreationDate())->toBe('2024-09-30T12:00:00Z');
    expect($audiobook->getCopyright())->toBe('HarperCollins');
    expect($audiobook->getEncodingBy())->toBe('©2012 Robin Hobb (P)2012 HarperCollins Publishers Limited');
    expect($audiobook->getEncoding())->toBe('Audiobook Builder 2.2.9 (www.splasm.com), macOS 15.0');
    expect($audiobook->getDescription())->toBeString();
    expect($audiobook->getSynopsis())->toBeString();
    expect($audiobook->getLanguage())->toBe('English');
    expect($audiobook->getLyrics())->toBe('The Farseer #01');
});

it('can read audiobook raw', function () {
    $audiobook = Audio::read(AUDIOBOOK_RH);

    $raw = $audiobook->getRaw();
    expect($raw['title'])->toBe('Assassin’s Apprentice');
    expect($raw['artist'])->toBe('Robin Hobb');
    expect($raw['album'])->toBe('Assassin’s Apprentice');
    expect($raw['genre'])->toBe('Animals/Political/Epic/Military');
    expect($raw['origyear'])->toBe('2024/09/30');
    expect($raw['track_number'])->toBe('1/1');
    expect($raw['disc_number'])->toBe('1');
    expect($raw['compilation'])->toBe(1);
    expect($raw['creation_date'])->toBe('2024-9-30T12:00:00Z');
    expect($raw['encoding_tool'])->toBe('Audiobook Builder 2.2.9 (www.splasm.com), macOS 15.0');
    expect($raw['subtitle'])->toBe('Subtitle');
    expect($raw['description_long'])->toBeString();
    expect($raw['language'])->toBe('English');
    expect($raw['lyrics'])->toBe('The Farseer #01');
    expect($raw['stik'])->toBe('Audiobook');
    expect($raw['encoded_by'])->toBe('©2012 Robin Hobb (P)2012 HarperCollins Publishers Limited');
    expect($raw['description'])->toBeString();
    expect($raw['copyright'])->toBe('HarperCollins');
    expect($raw['isbn'])->toBe('ISBN');
    expect($raw['composer'])->toBe('Paul Boehmer');
    expect($raw['comment'])->toBe('English');
    expect($raw['asin'])->toBe('ASIN');
    expect($raw['album_artist'])->toBe('Robin Hobb');
    expect($raw['series-part'])->toBe('1');
    expect($raw['series'])->toBe('The Farseer');

    expect($audiobook->getRawKey('title'))->toBe('Assassin’s Apprentice');
    expect($audiobook->getRawKey('artist'))->toBe('Robin Hobb');
    expect($audiobook->getRawKey('album'))->toBe('Assassin’s Apprentice');
    expect($audiobook->getRawKey('genre'))->toBe('Animals/Political/Epic/Military');
    expect($audiobook->getRawKey('origyear'))->toBe('2024/09/30');
    expect($audiobook->getRawKey('track_number'))->toBe('1/1');
    expect($audiobook->getRawKey('disc_number'))->toBe('1');
    expect($audiobook->getRawKey('compilation'))->toBe(1);
    expect($audiobook->getRawKey('creation_date'))->toBe('2024-9-30T12:00:00Z');
    expect($audiobook->getRawKey('encoding_tool'))->toBe('Audiobook Builder 2.2.9 (www.splasm.com), macOS 15.0');
    expect($audiobook->getRawKey('subtitle'))->toBe('Subtitle');
    expect($audiobook->getRawKey('description_long'))->toBeString();
    expect($audiobook->getRawKey('language'))->toBe('English');
    expect($audiobook->getRawKey('lyrics'))->toBe('The Farseer #01');
    expect($audiobook->getRawKey('stik'))->toBe('Audiobook');
    expect($audiobook->getRawKey('encoded_by'))->toBe('©2012 Robin Hobb (P)2012 HarperCollins Publishers Limited');
    expect($audiobook->getRawKey('description'))->toBeString();
    expect($audiobook->getRawKey('copyright'))->toBe('HarperCollins');
    expect($audiobook->getRawKey('isbn'))->toBe('ISBN');
    expect($audiobook->getRawKey('composer'))->toBe('Paul Boehmer');
    expect($audiobook->getRawKey('comment'))->toBe('English');
    expect($audiobook->getRawKey('asin'))->toBe('ASIN');
    expect($audiobook->getRawKey('album_artist'))->toBe('Robin Hobb');
    expect($audiobook->getRawKey('series-part'))->toBe('1');
    expect($audiobook->getRawKey('series'))->toBe('The Farseer');
});

it('can read audiobook file m4b', function (string $file) {
    $audio = Audio::read($file);

    expect($audio->getTitle())->toBe('P1PDD Saison 1');
    expect($audio->getArtist())->toBe('Mr Piouf');
    expect($audio->getAlbum())->toBe('P1PDD Saison 1');
    expect($audio->getGenre())->toBe('Audiobooks');
    expect($audio->getYear())->toBe(2023);
    expect($audio->getTrackNumber())->toBe('1/1');
    expect($audio->getComment())->toBe('P1PDD team');
    expect($audio->getAlbumArtist())->toBe('Mr Piouf and P1PDD');
    expect($audio->getComposer())->toBe('Composer');
    expect($audio->getDiscNumber())->toBe('1');
    expect($audio->isCompilation())->toBe(false);
    expect($audio->getPath())->toBe(AUDIOBOOK);
    expect($audio->getFormat())->toBe(AudioFormatEnum::m4b);
    expect($audio->getCreationDate())->toBe('2023-06-04T12:00:00Z');
    expect($audio->getEncodingBy())->toBe('Mr Piouf');
    expect($audio->getEncoding())->toBe('Audiobook Builder 2.2.6 (www.splasm.com), macOS 13.4');
    expect($audio->getCopyright())->toBe('Copyright');
    expect($audio->getDescription())->toBe('Description');
    expect($audio->getSynopsis())->toBe('Synopsis');
    expect($audio->getLanguage())->toBe('Language');
    expect($audio->getLyrics())->toBe('Lyrics');
    expect($audio->getDuration())->toBe(11.00);
    expect($audio->getDurationHuman())->toBe('00:00:11');
    expect($audio->getExtras())->toBeArray();

    expect($audio->getRaw())->toBeArray();
    expect($audio->getRawKey('title'))->toBe('P1PDD Saison 1');
    expect($audio->getRawKey('artist'))->toBe('Mr Piouf');
    expect($audio->getRawKey('album'))->toBe('P1PDD Saison 1');
    expect($audio->getRawKey('genre'))->toBe('Audiobooks');
    expect($audio->getRawKey('track_number'))->toBe('1/1');
    expect($audio->getRawKey('comment'))->toBe('P1PDD team');
})->with([AUDIOBOOK]);

it('can read audiobook file mp3', function (string $file) {
    $audio = Audio::read($file);

    expect(count($audio->getRaw()))->toBe(15);
    expect(count($audio->getRaw('id3v2')))->toBe(15);
})->with([AUDIOBOOK_MP3]);
