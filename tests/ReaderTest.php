<?php

use Kiwilan\Audio\Audio;
use Kiwilan\Audio\Id3\Id3Reader;
use Kiwilan\Audio\Id3\Reader\Id3Audio;
use Kiwilan\Audio\Id3\Reader\Id3AudioTag;
use Kiwilan\Audio\Id3\Reader\Id3Comments;

it('can read mp3 stream', function () {
    $audio = Audio::get(MP3);
    $streams = $audio->getId3Reader()->getAudio()->streams();

    expect($streams)->toBeArray();
    expect($streams)->toHaveCount(1);
    expect($streams[0]->dataFormat())->toBe('mp3');
    expect($streams[0]->channels())->toBe(2);
    expect($streams[0]->sampleRate())->toBe(44100);
    expect($streams[0]->bitrate())->toBe(128000.0);
    expect($streams[0]->channelmode())->toBe('joint stereo');
    expect($streams[0]->bitrateMode())->toBe('cbr');
    expect($streams[0]->codec())->toBe('LAME');
    expect($streams[0]->encoder())->toBe('LAME3.100');
    expect($streams[0]->lossless())->toBeFalse();
    expect($streams[0]->encoderOptions())->toBe('CBR128');
    expect($streams[0]->compressionRatio())->toBe(0.09070294784580499);
});

it('can parse ID3 reader', function (string $path) {
    $audio = Audio::get($path);

    $reader = $audio->getId3Reader();
    $raw = $reader->getRaw();

    expect($reader->getInstance())->toBeInstanceOf(getID3::class);
    expect($reader)->toBeInstanceOf(Id3Reader::class);

    expect($raw)->toBeArray();
    expect($reader->getVersion())->toBeString();
    expect($reader->getFilesize())->toBeInt();
    expect($reader->getFilepath())->toBeString();
    expect($reader->getFilename())->toBeString();
    expect($reader->getFilenamepath())->toBeString();
    expect($reader->getAvdataoffset())->toBeInt();
    expect($reader->getAvdataend())->toBeInt();
    expect($reader->getFileformat())->toBeString();
    expect($reader->getAudio())->toBeInstanceOf(Id3Audio::class);
    if ($reader->getTags()) {
        expect($reader->getTags())->toBeInstanceOf(Id3AudioTag::class);
    }
    if ($reader->getComments()) {
        expect($reader->getComments())->toBeInstanceOf(Id3Comments::class);
    }
    expect($reader->getEncoding())->toBeString();
    expect($reader->getMimeType())->toBeString();
    if ($reader->getMpeg()) {
        expect($reader->getMpeg())->toBeArray();
    }
    if ($reader->getPlaytimeSeconds()) {
        expect($reader->getPlaytimeSeconds())->toBeFloat();
    }

    if ($reader->getBitrate()) {
        expect($reader->getBitrate())->toBeFloat();
    }
    if ($reader->getPlaytimeString()) {
        expect($reader->getPlaytimeString())->toBeString();
    }
})->with([...AUDIO]);
