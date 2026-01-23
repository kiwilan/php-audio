<?php

use Kiwilan\Audio\Audio;
use Kiwilan\Audio\Enums\AudioEngineEnum;

// it('can read with id3', function () {
//     $audio = Audio::read(THE_WALL_MP3, AudioEngineEnum::getid3);
//     expect($audio)->toBeInstanceOf(Audio::class);

//     ray($audio);
//     ray($audio->getEngine()->tags());
// });

// it('can read with ffmpeg', function () {
//     $audio = Audio::read(THE_WALL_MP3, AudioEngineEnum::ffmpeg);
//     expect($audio)->toBeInstanceOf(Audio::class);
//     ray($audio);
// });

it('can read with ffmpeg', function (string $file) {
    $audio = Audio::read($file, AudioEngineEnum::ffmpeg);
    expect($audio)->toBeInstanceOf(Audio::class);
    ray($audio->getEngine()->getOutput());
    ray($audio->getContainer()->getExtension());
    ray($audio->getTags());
})->with(THE_WALL);
