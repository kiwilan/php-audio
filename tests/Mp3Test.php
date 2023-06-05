<?php

use Kiwilan\Audio\Audio;
use Kiwilan\Audio\Enums\AudioFormatEnum;
use Kiwilan\Audio\Models\AudioCover;

it('can read file mp3', function () {
    $audio = Audio::get(MP3);

    expect($audio)->toBeInstanceOf(Audio::class);
    expect($audio->title())->toBe('P1PDD Le conclave de Troie');
    expect($audio->artist())->toBe('Mr Piouf');
    expect($audio->album())->toBe('P1PDD Le conclave de Troie');
    expect($audio->genre())->toBe('Roleplaying game');
    expect($audio->year())->toBe(2016);
    expect($audio->trackNumber())->toBe('1');
    expect($audio->comment())->toBe('http://www.p1pdd.com');
    expect($audio->albumArtist())->toBe('P1PDD & Mr Piouf');
    expect($audio->composer())->toBe('P1PDD & Piouf');
    expect($audio->discNumber())->toBe('1');
    expect($audio->isCompilation())->toBe(true);
    expect($audio->path())->toBe(MP3);
    expect($audio->format())->toBe(AudioFormatEnum::mp3);
    expect($audio->duration())->toBe(11.05);
    expect($audio->extras())->toBeArray();

    $audio = $audio->audio();
    expect($audio->filesize())->toBe(272737);
    expect($audio->extension())->toBe('mp3');
    expect($audio->encoding())->toBe('UTF-8');
    expect($audio->mimeType())->toBe('audio/mpeg');
    expect($audio->durationSeconds())->toBe(11.0496875);
    expect($audio->durationReadable())->toBe('0:11');
    expect($audio->bitrate())->toBe(128000);
    expect($audio->bitrateMode())->toBe('cbr');
    expect($audio->sampleRate())->toBe(44100);
    expect($audio->channels())->toBe(2);
    expect($audio->channelMode())->toBe('joint stereo');
    expect($audio->lossless())->toBe(false);
    expect($audio->compressionRatio())->toBe(0.09070294784580499);
});

it('can extract cover mp3', function () {
    $audio = Audio::get(MP3);
    $cover = $audio->cover();

    expect($cover)->toBeInstanceOf(AudioCover::class);
    expect($cover->content())->toBeString();
    expect($cover->mimeType())->toBe('image/jpeg');
    expect($cover->width())->toBe(640);
    expect($cover->height())->toBe(640);

    $path = 'tests/output/cover.jpg';
    file_put_contents($path, $cover->content());
    expect(file_exists($path))->toBeTrue();
    expect($path)->toBeReadableFile();
});

it('can read file mp3 no meta', function () {
    $audio = Audio::get(MP3_NO_META);

    expect($audio)->toBeInstanceOf(Audio::class);
    expect($audio->title())->toBeNull();
    expect($audio->artist())->toBeNull();
    expect($audio->album())->toBeNull();
    expect($audio->genre())->toBeNull();
    expect($audio->year())->toBeNull();
    expect($audio->trackNumber())->toBeNull();
    expect($audio->comment())->toBeNull();
    expect($audio->albumArtist())->toBeNull();
    expect($audio->composer())->toBeNull();
    expect($audio->discNumber())->toBeNull();
    expect($audio->isCompilation())->toBeFalse();
    expect($audio->path())->toBe(MP3_NO_META);
});
