<?php

use Kiwilan\Audio\Audio;
use Kiwilan\Audio\Enums\AudioEngineEnum;

it('can read mp3 info', function () {
    $audio = Audio::read(MP3_THE_WALL, AudioEngineEnum::ffmpeg);
    // ray($audio);
    // save('ffprobe', MP3_THE_WALL, $audio->getEngine()->getJson(true));
});

// it('can read metadata (ffprobe)', function (string $path) {
//     $audio = Audio::read($path, AudioEngineEnum::ffmpeg);
//     ray($audio);
//     save('ffprobe', $path, $audio->getEngine()->getJson(true));
// })->with([...AUDIO]);

// it('can read metadata (exiftool)', function (string $path) {
//     $metadata = ExiftoolEngine::read($path, AudioEngineEnum::ffmpeg);
//     save('exiftool', $path, $metadata);

//     expect($path)->toBeString();
// })->with([...AUDIO_EXIFTOOL]);

function save(string $engine, string $path, mixed $json, bool $pretty = false)
{
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    if ($pretty) {
        $json = json_encode($json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
    file_put_contents(pathTo("{$engine}_{$ext}.json"), $json);
}
