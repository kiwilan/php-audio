<?php

use Kiwilan\Audio\Audio;
use Kiwilan\Audio\Models\AudioCore;
use Kiwilan\Audio\Models\AudioCoreCover;

it('can convert formats', function () {
    $audio = Audio::get(MP3);
    $core = new AudioCore(
        title: $audio->title(),
        artist: $audio->artist(),
        album: $audio->album(),
        genre: $audio->genre(),
        year: $audio->year(),
        trackNumber: $audio->trackNumber(),
        comment: $audio->comment(),
        albumArtist: $audio->albumArtist(),
        composer: $audio->composer(),
        discNumber: $audio->discNumber(),
        isCompilation: $audio->isCompilation(),
        creationDate: $audio->creationDate(),
        copyright: $audio->copyright(),
        encodingBy: $audio->encodingBy(),
        encoding: $audio->encoding(),
        description: $audio->description(),
        lyrics: $audio->lyrics(),
        stik: $audio->stik(),
    );

    expect($core->title())->toBe('Introduction');
    expect($core->artist())->toBe('Mr Piouf');
    expect($core->album())->toBe('P1PDD Le conclave de Troie');
    expect($core->genre())->toBe('Roleplaying game');
    expect($core->year())->toBe(2016);
    expect($core->trackNumber())->toBe('1');
    expect($core->comment())->toBe('http://www.p1pdd.com');
    expect($core->albumArtist())->toBe('P1PDD & Mr Piouf');
    expect($core->composer())->toBe('P1PDD & Piouf');
    expect($core->discNumber())->toBe('1');
    expect($core->isCompilation())->toBe(true);

    $id3v1 = AudioCore::toId3v1($core);
    $id3v2 = AudioCore::toId3v2($core);
    $quicktime = AudioCore::toQuicktime($core);
    $matroska = AudioCore::toMatroska($core);
    $ape = AudioCore::toApe($core);
    $asf = AudioCore::toAsf($core);

    $core = AudioCore::fromId3v1($id3v1);
    $core = AudioCore::fromId3v2($id3v2);

    $cover = AudioCoreCover::make(FOLDER);

    expect($core->toArray())->toBeArray();
    expect($cover->toArray())->toBeArray();
});
