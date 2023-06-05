<?php

use Kiwilan\Audio\Audio;
use Kiwilan\Audio\Enums\AudioFormatEnum;

it('can read audiobook file m4b', function () {
    $audio = Audio::get(AUDIOBOOK);

    expect($audio->title())->toBe('P1PDD Saison 1');
    expect($audio->artist())->toBe('Mr Piouf');
    expect($audio->album())->toBe('P1PDD Saison 1');
    expect($audio->genre())->toBe('Audiobooks');
    expect($audio->year())->toBe(2023);
    expect($audio->trackNumber())->toBe('1/1');
    expect($audio->comment())->toBe('P1PDD team');
    expect($audio->albumArtist())->toBe('Mr Piouf and P1PDD');
    expect($audio->composer())->toBeNull();
    expect($audio->discNumber())->toBeNull();
    expect($audio->isCompilation())->toBe(false);
    expect($audio->path())->toBe(AUDIOBOOK);
    expect($audio->format())->toBe(AudioFormatEnum::m4b);
    expect($audio->creationDate())->toBe('2023-06-04T12:00:00Z');
    expect($audio->encodingBy())->toBe('Mr Piouf');
    expect($audio->encoding())->toBe('Audiobook Builder 2.2.6 (www.splasm.com), macOS 13.4');
    expect($audio->copyright())->toBeString();
    expect($audio->description())->toBe('Première campagne de P1PDD');
    expect($audio->lyrics())->toBe('P1PDD');
    expect($audio->stik())->toBe('Audiobook');
    expect($audio->duration())->toBe(11.00);
    expect($audio->extras())->toBeArray();
});
