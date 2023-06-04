<?php

use Kiwilan\Audio\Audio;

it('can read audiobook file m4b', function () {
    $audio = Audio::read(AUDIOBOOK);

    ray($audio);
    expect($audio->title())->toBe('P1PDD Saison 1');
    // expect($audio->artist())->toBe('Mr Piouf');
    // expect($audio->album())->toBe('P1PDD Saison 1');
    // expect($audio->genre())->toBe('Audiobooks');
    // expect($audio->year())->toBeNull();
    // expect($audio->trackNumber())->toBe('1/1');
    // expect($audio->comment())->toBe('P1PDD team');
    // expect($audio->albumArtist())->toBe('Mr Piouf and P1PDD');
    // expect($audio->composer())->toBeNull();
    // expect($audio->discNumber())->toBeNull();
    // expect($audio->isCompilation())->toBe(false);
    // expect($audio->path())->toBe(AUDIOBOOK);
    // expect($audio->extension())->toBe('m4b');
    // expect($audio->creationDate())->toBe('2023-6-4T12:00:00Z');
    // expect($audio->encodedBy())->toBe('Mr Piouf');
    // expect($audio->encodingTool())->toBe('Audiobook Builder 2.2.6 (www.splasm.com), macOS 13.4');
    // expect($audio->description())->toBe('Première campagne de P1PDD');
    // expect($audio->descriptionLong())->toBe('Première campagne de P1PDD');
    // expect($audio->lyrics())->toBe('P1PDD');
    // expect($audio->stik())->toBe('Audiobook');
    // expect($audio->duration())->toBe(11.00);
    // expect($audio->extras())->toBeArray();
});
