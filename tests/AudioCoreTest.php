<?php

use Kiwilan\Audio\Audio;
use Kiwilan\Audio\Core\AudioCore;
use Kiwilan\Audio\Core\AudioCoreCover;

it('can convert formats', function () {
    $audio = Audio::get(MP3);
    $core = new AudioCore(
        title: $audio->getTitle(),
        artist: $audio->getArtist(),
        album: $audio->getAlbum(),
        genre: $audio->getGenre(),
        year: $audio->getYear(),
        track_number: $audio->getTrackNumber(),
        comment: $audio->getComment(),
        album_artist: $audio->getAlbumArtist(),
        composer: $audio->getComposer(),
        disc_number: $audio->getDiscNumber(),
        is_compilation: $audio->isCompilation(),
        creation_date: $audio->getCreationDate(),
        copyright: $audio->getCopyright(),
        encoding_by: $audio->getEncodingBy(),
        encoding: $audio->getEncoding(),
        description: $audio->getDescription(),
        lyrics: $audio->getLyrics(),
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
