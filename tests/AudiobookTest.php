<?php

use Kiwilan\Audio\Audio;
use Kiwilan\Audio\Enums\AudioFormatEnum;

it('can read audiobook file m4b', function (string $file) {
    $audio = Audio::get($file);

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
    expect($audio->getPodcastDescription())->toBe('Synopsis');
    expect($audio->getLanguage())->toBe('Language');
    expect($audio->getLyrics())->toBe('Lyrics');
    expect($audio->getStik())->toBe('Audiobook');
    expect($audio->getDuration())->toBe(11.00);
    expect($audio->getExtras())->toBeArray();
    expect($audio->toArray())->toBeArray();

    expect($audio->getTags())->toBeArray();
    expect($audio->getTag('title'))->toBe('P1PDD Saison 1');
    expect($audio->getTag('artist'))->toBe('Mr Piouf');
    expect($audio->getTag('album'))->toBe('P1PDD Saison 1');
    expect($audio->getTag('genre'))->toBe('Audiobooks');
    expect($audio->getTag('track_number'))->toBe('1/1');
    expect($audio->getTag('comment'))->toBe('P1PDD team');
})->with([AUDIOBOOK]);

it('can read audiobook file mp3', function (string $file) {
    $audio = Audio::get($file);

    expect(count($audio->getTags()))->toBe(16);
    expect(count($audio->getTags('id3v2')))->toBe(15);

    expect(count($audio->getAudioFormats()))->toBe(3);
    expect($audio->getAudioFormats())->toBeArray();
    expect($audio->toArray())->toBeArray();
})->with([AUDIOBOOK_MP3]);
