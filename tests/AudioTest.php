<?php

use Kiwilan\Audio\Audio;
use Kiwilan\Audio\Enums\AudioFormatEnum;
use Kiwilan\Audio\Models\AudioCover;

it('can read file', function (string $path) {
    $audio = Audio::get($path);
    $extension = pathinfo($path, PATHINFO_EXTENSION);
    $format = AudioFormatEnum::tryFrom($extension);

    // ray($audio);
    expect($audio)->toBeInstanceOf(Audio::class);
    expect($audio->title())->toBe('Introduction');
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
    expect($audio->format())->toBe($format);
    expect($audio->duration())->toBeFloat();
    expect($audio->extras())->toBeArray();

    $metadata = $audio->audio();
    expect($metadata->filesize())->toBeInt();
    expect($metadata->extension())->toBeString();
    expect($metadata->encoding())->toBeString();
    expect($metadata->mimeType())->toBeString();
    if ($metadata->durationSeconds()) {
        expect($metadata->durationSeconds())->toBeFloat();
    }
    if ($metadata->durationReadable()) {
        expect($metadata->durationReadable())->toBeString();
    }
    if ($metadata->bitrate()) {
        expect($metadata->bitrate())->toBeInt();
    }
    if ($metadata->bitrateMode()) {
        expect($metadata->bitrateMode())->toBeString();
    }
    if ($metadata->sampleRate()) {
        expect($metadata->sampleRate())->toBeInt();
    }
    if ($metadata->channels()) {
        expect($metadata->channels())->toBeInt();
    }
    if ($metadata->channelMode()) {
        expect($metadata->channelMode())->toBeString();
    }
    expect($metadata->lossless())->toBeBool();
    if ($metadata->compressionRatio()) {
        expect($metadata->compressionRatio())->toBeFloat();
    }
    expect($audio->isValid())->toBeTrue();
})->with([...AUDIO]);

it('can extract cover', function (string $path) {
    $audio = Audio::get($path);
    $ext = pathinfo($path, PATHINFO_EXTENSION);
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

        $path = "tests/output/cover-{$ext}.jpg";
        file_put_contents($path, $cover->content());
        expect(file_exists($path))->toBeTrue();
        expect($path)->toBeReadableFile();
    } else {
        expect($cover)->toBeNull();
    }
})->with([...AUDIO]);

it('can use stat data', function (string $path) {
    $audio = Audio::get($path);
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
    $audio = Audio::get(MP3);
    $streams = $audio->reader()->audio()->streams();

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

it('can read wrong audio file', function () {
    $audio = Audio::get(MD);

    expect($audio->isValid())->toBeFalse();
});
