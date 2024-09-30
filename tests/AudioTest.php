<?php

use Kiwilan\Audio\Audio;
use Kiwilan\Audio\Enums\AudioFormatEnum;
use Kiwilan\Audio\Id3\Id3Reader;
use Kiwilan\Audio\Models\AudioMetadata;

it('can read basic info', function (string $path) {
    $audio = Audio::get($path);
    $extension = pathinfo($path, PATHINFO_EXTENSION);
    $format = AudioFormatEnum::tryFrom($extension);

    expect($audio)->toBeInstanceOf(Audio::class);
    expect($audio->getPath())->toBe($path);
    expect($audio->getExtension())->toBe($extension);
    expect($audio->getFormat())->toBe($format);

    expect($audio->getMetadata())->toBeInstanceOf(AudioMetadata::class);
    expect($audio->getId3Reader())->toBeInstanceOf(Id3Reader::class);
    expect($audio->getDuration())->toBeFloat();
    expect($audio->getDurationHuman())->toBeString();

    expect($audio->isWritable())->toBeBool();
    expect($audio->isValid())->toBeBool();
    expect($audio->hasCover())->toBeBool();

    expect($audio->getTitle())->toBe('Introduction');
    expect($audio->getArtist())->toBe('Mr Piouf');
    expect($audio->getAlbum())->toBe('P1PDD Le conclave de Troie');
    expect($audio->getGenre())->toBe('Roleplaying game');
    expect($audio->getYear())->toBe(2016);
    expect($audio->getTrackNumber())->toBe('1');
    expect($audio->getTrackNumberInt())->toBe(1);
    expect($audio->getFormat())->toBe($format);
})->with([...AUDIO]);

it('can read disc number', function () {
    $audio = Audio::get(M4A);

    expect($audio->getDiscNumber())->toBe('1/2');
    expect($audio->getDiscNumberInt())->toBe(1);
});

it('can read encoding', function () {
    $audio = Audio::get(M4V);

    expect($audio->getEncoding())->toBe('Lavf60.3.100');
});

it('can read description', function () {
    $audio = Audio::get(FLAC);

    expect($audio->getDescription())->toBe('http://www.p1pdd.com');
});

it('can read creation date', function () {
    $audio = Audio::get(WV);

    expect($audio->getCreationDate())->toBe('2016');
});

it('can read file id3v1', function (string $path) {
    $audio = Audio::get($path);
    $extension = pathinfo($path, PATHINFO_EXTENSION);
    $format = AudioFormatEnum::tryFrom($extension);

    expect($audio->getTitle())->toBeString();
    expect($audio->getArtist())->toBeString();
    expect($audio->getAlbum())->toBeString();
    expect($audio->getTrackNumber())->toBeString();
    expect($audio->getAlbumArtist())->toBeString();
    expect($audio->getComposer())->toBeNull();

    expect($audio->getExtension())->toBe($extension);
    expect($audio->getFormat())->toBe($format);
    expect($audio->getDuration())->toBeFloat();
    expect($audio->getDurationHuman())->toBe('00:00:11');
    expect($audio->getExtras())->toBeArray();

    expect($audio)->toBeInstanceOf(Audio::class);
})->with([...AUDIO_ID3_V1]);

it('can read wrong audio file', function () {
    $audio = Audio::get(MD);

    expect($audio->isValid())->toBeFalse();
});
