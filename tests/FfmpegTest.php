<?php

use Kiwilan\Audio\Audio;
use Kiwilan\Audio\Engines\ExiftoolEngine;
use Kiwilan\Audio\Engines\FfmpegEngine;
use Kiwilan\Audio\Enums\AudioEngineEnum;

// it('can read mp3 info', function () {
//     $metadata = FfmpegEngine::read(MP3);
//     save(MP3, $metadata);
// });

it('can read metadata (ffprobe)', function (string $path) {
    $audio = Audio::read($path, AudioEngineEnum::ffmpeg);
    save('ffprobe', $path, $audio);

    expect($path)->toBeString();
})->with([...AUDIO]);

it('can read metadata (exiftool)', function (string $path) {
    $metadata = ExiftoolEngine::read($path, AudioEngineEnum::ffmpeg);
    save('exiftool', $path, $metadata);

    expect($path)->toBeString();
})->with([...AUDIO_EXIFTOOL]);

function save(string $engine, string $path, mixed $contents)
{
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    $json = json_encode($contents, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    file_put_contents(pathTo("{$engine}_{$ext}.json"), $json);
    ray($json);
}
