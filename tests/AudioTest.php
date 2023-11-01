<?php

use Kiwilan\Audio\Audio;
use Kiwilan\Audio\Enums\AudioFormatEnum;
use Kiwilan\Audio\Models\AudioCover;

it('can read file', function (string $path) {
    $audio = Audio::get($path);
    $extension = pathinfo($path, PATHINFO_EXTENSION);
    $format = AudioFormatEnum::tryFrom($extension);

    expect($audio)->toBeInstanceOf(Audio::class);
    expect($audio->getTitle())->toBe('Introduction');
    expect($audio->getArtist())->toBe('Mr Piouf');
    expect($audio->getAlbum())->toBe('P1PDD Le conclave de Troie');
    expect($audio->getGenre())->toBe('Roleplaying game');
    expect($audio->getYear())->toBe(2016);
    expect($audio->getTrackNumber())->toBe('1');
    if ($audio->getComment()) {
        expect($audio->getComment())->toBe('http://www.p1pdd.com');
    }
    expect($audio->getAlbumArtist())->toBe('P1PDD & Mr Piouf');
    expect($audio->getComposer())->toBe('P1PDD & Piouf');
    expect($audio->getDiscNumber())->toBeString();
    expect($audio->isCompilation())->toBeBool();
    expect($audio->getPath())->toBe($path);
    expect($audio->getgetExtension())->toBe($extension);
    expect($audio->getFormat())->toBe($format);
    expect($audio->getDuration())->toBeFloat();
    expect($audio->getExtras())->toBeArray();

    $metadata = $audio->getAudio();
    expect($metadata->getPath())->toBeString();
    expect($metadata->getFilesize())->toBeInt();
    expect($metadata->getExtension())->toBeString();
    expect($metadata->getEncoding())->toBeString();
    expect($metadata->getMimeType())->toBeString();
    if ($metadata->getDurationSeconds()) {
        expect($metadata->getDurationSeconds())->toBeFloat();
    }
    if ($metadata->getDurationReadable()) {
        expect($metadata->getDurationReadable())->toBeString();
    }
    if ($metadata->getBitrate()) {
        expect($metadata->getBitrate())->toBeInt();
    }
    if ($metadata->getBitrateMode()) {
        expect($metadata->getBitrateMode())->toBeString();
    }
    if ($metadata->getSampleRate()) {
        expect($metadata->getSampleRate())->toBeInt();
    }
    if ($metadata->getChannels()) {
        expect($metadata->getChannels())->toBeInt();
    }
    if ($metadata->getChannelMode()) {
        expect($metadata->getChannelMode())->toBeString();
    }
    expect($metadata->getLossless())->toBeBool();
    if ($metadata->getCompressionRatio()) {
        expect($metadata->getCompressionRatio())->toBeFloat();
    }
    expect($audio->isValid())->toBeTrue();
})->with([...AUDIO]);

it('can extract cover', function (string $path) {
    $audio = Audio::get($path);
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    $cover = $audio->getCover();

    if ($audio->hasCover()) {
        expect($cover)->toBeInstanceOf(AudioCover::class);
        expect($cover->getContents())->toBeString();
        expect($cover->getContents())->toBeString();
        expect($cover->getMimeType())->toBeString();
        if ($cover->getWidth()) {
            expect($cover->getWidth())->toBeInt();
        }
        if ($cover->getHeight()) {
            expect($cover->getHeight())->toBeInt();
        }

        $path = "tests/output/cover-{$ext}.jpg";
        file_put_contents($path, $cover->getContents());
        expect(file_exists($path))->toBeTrue();
        expect($path)->toBeReadableFile();
    } else {
        expect($cover)->toBeNull();
    }
})->with([...AUDIO]);

it('can use stat data', function (string $path) {
    $audio = Audio::get($path);
    $stat = $audio->getStat();

    expect($stat->getPath())->toBe($path);
    expect($stat->getDeviceNumber())->toBeInt();
    expect($stat->getInodeNumber())->toBeInt();
    expect($stat->getInodeProtectionMode())->toBeInt();
    expect($stat->getNumberOfLinks())->toBeInt();
    expect($stat->getUserId())->toBeInt();
    expect($stat->getGroupId())->toBeInt();
    expect($stat->getDeviceType())->toBeInt();
    expect($stat->getLastAccessAt())->toBeInstanceOf(DateTime::class);
    expect($stat->getCreatedAt())->toBeInstanceOf(DateTime::class);
    expect($stat->getModifiedAt())->toBeInstanceOf(DateTime::class);
    expect($stat->getBlockSize())->toBeInt();
    expect($stat->getNumberOfBlocks())->toBeInt();
    expect($stat->toArray())->toBeArray();
    expect($stat->toJson())->toBeString();
    expect($stat->__toString())->toBeString();
})->with([...AUDIO]);

it('can read mp3 stream', function () {
    $audio = Audio::get(MP3);
    $streams = $audio->getReader()->getAudio()->streams();

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

    expect($audio->getgetExtension())->toBe($extension);
    expect($audio->getFormat())->toBe($format);
    expect($audio->getDuration())->toBeFloat();
    expect($audio->getExtras())->toBeArray();

    expect($audio)->toBeInstanceOf(Audio::class);
})->with([...AUDIO_ID3_V1]);
