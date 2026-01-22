<?php

use Kiwilan\Audio\Audio;
use Kiwilan\Audio\Enums\AudioEngineEnum;

it('can read with id3', function () {
    $audio = Audio::read(MP3_THE_WALL, AudioEngineEnum::getid3);
    expect($audio)->toBeInstanceOf(Audio::class);

    ray($audio);
    ray($audio->getEngine()->tags());
});

it('can read with ffmpeg', function () {
    $audio = Audio::read(MP3_THE_WALL, AudioEngineEnum::ffmpeg);
    expect($audio)->toBeInstanceOf(Audio::class);
    ray($audio);
});
