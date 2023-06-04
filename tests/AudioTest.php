<?php

use Kiwilan\Audio\Audio;
use Kiwilan\Audio\Models\AudioCover;

it('can read file', function (string $path) {
    $audio = Audio::read($path);
    $extension = pathinfo($path, PATHINFO_EXTENSION);

    // ray($audio);
    expect($audio)->toBeInstanceOf(Audio::class);
    expect($audio->title())->toBe('P1PDD Le conclave de Troie');
    expect($audio->artist())->toBe('Mr Piouf');
    expect($audio->album())->toBe('P1PDD Le conclave de Troie');
    expect($audio->genre())->toBe('Roleplaying game');
    expect($audio->year())->toBe(2016);
    expect($audio->trackNumber())->toBe('1');
    if ($audio->comment()) {
        expect($audio->comment())->toBe('http://www.p1pdd.com');
    }
    expect($audio->albumArtist())->toBe('P1PDD & Mr Piouf');
    expect($audio->composer())->toBe('P1PDD & Piouf');
    expect($audio->discNumber())->toBeString();
    expect($audio->isCompilation())->toBeBool();
    expect($audio->path())->toBe($path);
    expect($audio->extension())->toBe($extension);
    expect($audio->duration())->toBeGreaterThanOrEqual(11.0);
    expect($audio->extras())->toBeArray();

    $audio = $audio->audio();
    expect($audio->filesize())->toBeInt();
    expect($audio->extension())->toBeString();
    expect($audio->encoding())->toBeString();
    expect($audio->mimeType())->toBeString();
    expect($audio->durationSeconds())->toBeFloat();
    expect($audio->durationReadable())->toBeString();
    expect($audio->bitrate())->toBeInt();
    if ($audio->bitrateMode()) {
        expect($audio->bitrateMode())->toBeString();
    }
    expect($audio->sampleRate())->toBeInt();
    expect($audio->channels())->toBeInt();
    if ($audio->channelMode()) {
        expect($audio->channelMode())->toBeString();
    }
    expect($audio->lossless())->toBeBool();
    if ($audio->compressionRatio()) {
        expect($audio->compressionRatio())->toBeFloat();
    }
})->with([...AUDIO]);

it('can extract cover', function (string $path) {
    $audio = Audio::read($path);
    $cover = $audio->cover();

    if ($audio->hasCover()) {
        expect($cover)->toBeInstanceOf(AudioCover::class);
        expect($cover->content())->toBeString();
        expect($cover->mimeType())->toBeString();
        if ($cover->width()) {
            expect($cover->width())->toBeInt();
        }
        if ($cover->height()) {
            expect($cover->height())->toBeInt();
        }

        $path = 'tests/output/cover.jpg';
        file_put_contents($path, $cover->content());
        expect(file_exists($path))->toBeTrue();
        expect($path)->toBeReadableFile();
    } else {
        expect($cover)->toBeNull();
    }
})->with([...AUDIO]);

it('can use stat data', function (string $path) {
    $audio = Audio::read($path);
    $stat = $audio->stat();

    expect($stat->path())->toBe($path);
    expect($stat->deviceNumber())->toBeInt();
    expect($stat->inodeNumber())->toBeInt();
    expect($stat->inodeProtectionMode())->toBeInt();
    expect($stat->numberOfLinks())->toBeInt();
    expect($stat->userId())->toBeInt();
    expect($stat->groupId())->toBeInt();
    expect($stat->deviceType())->toBeInt();
    expect($stat->lastAccessAt())->toBeInstanceOf(DateTime::class);
    expect($stat->createdAt())->toBeInstanceOf(DateTime::class);
    expect($stat->modifiedAt())->toBeInstanceOf(DateTime::class);
    expect($stat->blockSize())->toBeInt();
    expect($stat->numberOfBlocks())->toBeInt();
    expect($stat->toArray())->toBeArray();
    expect($stat->toJson())->toBeString();
    expect($stat->__toString())->toBeString();
})->with([...AUDIO]);

it('can read mp3 stream', function () {
    $audio = Audio::read(MP3);
    $streams = $audio->id3()->item()->audio()->streams();

    expect($streams)->toBeArray();
    expect($streams)->toHaveCount(1);
    expect($streams[0]->dataformat())->toBe('mp3');
    expect($streams[0]->channels())->toBe(2);
    expect($streams[0]->sample_rate())->toBe(44100);
    expect($streams[0]->bitrate())->toBe(128000.0);
    expect($streams[0]->channelmode())->toBe('joint stereo');
    expect($streams[0]->bitrate_mode())->toBe('cbr');
    expect($streams[0]->codec())->toBe('LAME');
    expect($streams[0]->encoder())->toBe('LAME3.100');
    expect($streams[0]->lossless())->toBeFalse();
    expect($streams[0]->encoder_options())->toBe('CBR128');
    expect($streams[0]->compression_ratio())->toBe(0.09070294784580499);
});
