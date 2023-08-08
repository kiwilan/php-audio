<?php

use Kiwilan\Audio\Audio;
use Kiwilan\Audio\Models\AudioCore;
use Kiwilan\Audio\Models\AudioCoreCover;

it('can convert formats', function () {
    $audio = Audio::get(MP3);
    $core = new AudioCore(
        title: $audio->getTitle(),
        artist: $audio->getArtist(),
        album: $audio->getAlbum(),
        genre: $audio->getGenre(),
        year: $audio->getYear(),
        trackNumber: $audio->getTrackNumber(),
        comment: $audio->getComment(),
        albumArtist: $audio->getAlbumArtist(),
        composer: $audio->getComposer(),
        discNumber: $audio->getDiscNumber(),
        isCompilation: $audio->isCompilation(),
        creationDate: $audio->getCreationDate(),
        copyright: $audio->getCopyright(),
        encodingBy: $audio->getEncodingBy(),
        encoding: $audio->getEncoding(),
        description: $audio->getDescription(),
        lyrics: $audio->getLyrics(),
        stik: $audio->getStik(),
    );

    expect($core->getTitle())->toBe('Introduction');
    expect($core->getArtist())->toBe('Mr Piouf');
    expect($core->getAlbum())->toBe('P1PDD Le conclave de Troie');
    expect($core->getGenre())->toBe('Roleplaying game');
    expect($core->getYear())->toBe(2016);
    expect($core->getTrackNumber())->toBe('1');
    expect($core->getComment())->toBe('http://www.p1pdd.com');
    expect($core->getAlbumArtist())->toBe('P1PDD & Mr Piouf');
    expect($core->getComposer())->toBe('P1PDD & Piouf');
    expect($core->getDiscNumber())->toBe('1');
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
