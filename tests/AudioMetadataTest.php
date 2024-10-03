<?php

use Kiwilan\Audio\Audio;
use Kiwilan\Audio\Id3\Reader\Id3AudioQuicktime;
use Kiwilan\Audio\Id3\Reader\Id3AudioQuicktimeChapter;
use Kiwilan\Audio\Id3\Reader\Id3AudioQuicktimeItem;
use Kiwilan\Audio\Models\AudioMetadata;

it('can read mp3 info', function () {
    $audio = Audio::read(MP3);

    $metadata = $audio->getMetadata();
    expect($metadata->getFileSize())->toBe(272737);
    expect($metadata->getDataFormat())->toBe('mp3');
    expect($metadata->getEncoding())->toBe('UTF-8');
    expect($metadata->getMimeType())->toBe('audio/mpeg');
    expect($metadata->getDurationSeconds())->toBe(11.0496875);
    expect($metadata->getBitrate())->toBe(128000);
    expect($metadata->getBitrateMode())->toBe('cbr');
    expect($metadata->getSampleRate())->toBe(44100);
    expect($metadata->getChannels())->toBe(2);
    expect($metadata->getChannelMode())->toBe('joint stereo');
    expect($metadata->isLossless())->toBeFalse();
    expect($metadata->getCompressionRatio())->toBe(0.09070294784580499);
    expect($metadata->getCodec())->toBe('LAME');
    expect($metadata->getEncoderOptions())->toBe('CBR128');
    expect($metadata->getVersion())->toContain('1.9');
    expect($metadata->getAvDataOffset())->toBe(95396);
    expect($metadata->getAvDataEnd())->toBe(272609);
    expect($metadata->getFilePath())->toContain('tests/media');
    expect($metadata->getFilename())->toBe('test.mp3');
    expect($metadata->getLastAccessAt())->toBeInstanceOf(DateTime::class);
    expect($metadata->getCreatedAt())->toBeInstanceOf(DateTime::class);
    expect($metadata->getModifiedAt())->toBeInstanceOf(DateTime::class);
});

it('can read basic info', function (string $path) {
    $audio = Audio::read($path);
    $metadata = $audio->getMetadata();

    expect($metadata)->toBeInstanceOf(AudioMetadata::class);
    expect($metadata->getFileSize())->toBeInt();
    expect($metadata->getSizeHuman())->toBeString();
    expect($metadata->getMimeType())->toBeString();
    expect($metadata->isLossless())->toBeBool();
    expect($metadata->getLastAccessAt())->toBeInstanceOf(DateTime::class);
    expect($metadata->getCreatedAt())->toBeInstanceOf(DateTime::class);
    expect($metadata->getModifiedAt())->toBeInstanceOf(DateTime::class);
    expect($metadata->getVersion())->toContain('1.9');
    expect($metadata->getAvDataOffset())->toBeInt();
    expect($metadata->getAvDataEnd())->toBeInt();
    expect($metadata->getFilePath())->toBeString();
    expect($metadata->getFilename())->toBeString();

    if ($metadata->getChannels()) {
        expect($metadata->getChannels())->toBeInt();
    }
    if ($metadata->getBitrate()) {
        expect($metadata->getBitrate())->toBeInt();
    }
    if ($metadata->getChannelMode()) {
        expect($metadata->getChannelMode())->toBeString();
    }
    if ($metadata->getDataFormat()) {
        expect($metadata->getDataFormat())->toBeString();
    }
    if ($metadata->getEncoding()) {
        expect($metadata->getEncoding())->toBeString();
    }
    if ($metadata->getDurationSeconds()) {
        expect($metadata->getDurationSeconds())->toBeFloat();
        expect($metadata->getDurationSeconds(2))->toBeFloat();
    }
    if ($metadata->getSampleRate()) {
        expect($metadata->getSampleRate())->toBeInt();
    }
    if ($metadata->getBitrateMode()) {
        expect($metadata->getBitrateMode())->toBeString();
    }
    if ($metadata->getCompressionRatio()) {
        expect($metadata->getCompressionRatio())->toBeFloat();
        expect($metadata->getCompressionRatio(2))->toBeFloat();
    }
    if ($metadata->getCodec()) {
        expect($metadata->getCodec())->toBeString();
    }
    if ($metadata->getEncoderOptions()) {
        expect($metadata->getEncoderOptions())->toBeString();
    }
})->with([...AUDIO]);

it('can read as array', function (string $path) {
    $audio = Audio::read($path);
    $metadata = $audio->getMetadata();

    expect($metadata->toArray())->toBeArray();
})->with([...AUDIO]);

it('can read warning', function () {
    $audio = Audio::read(AUDIOBOOK_RH_NOCOVER);
    $metadata = $audio->getMetadata();

    expect($metadata->getWarning())->toBeArray();
});

it('can read quicktime', function () {
    $audio = Audio::read(AUDIOBOOK_RH_NOCOVER);
    $quicktime = $audio->getMetadata()->getQuicktime();

    expect($quicktime)->toBeInstanceOf(Id3AudioQuicktime::class);
    expect($quicktime->getHinting())->toBeBool();
    expect($quicktime->getController())->toBeString();

    expect($quicktime->getFtyp())->toBeInstanceOf(Id3AudioQuicktimeItem::class);
    expect($quicktime->getFtyp()->getFourcc())->toBeString();
    expect($quicktime->getFtyp()->getHierarchy())->toBeString();
    expect($quicktime->getFtyp()->getName())->toBeString();
    expect($quicktime->getFtyp()->getOffset())->toBeInt();
    expect($quicktime->getFtyp()->getSignature())->toBeString();
    expect($quicktime->getFtyp()->getSize())->toBeInt();
    expect($quicktime->getFtyp()->getUnknown1())->toBeInt();

    expect($quicktime->getTimestampsUnix())->toBeArray();
    expect($quicktime->getTimeScale())->toBeInt();
    expect($quicktime->getDisplayScale())->toBeInt();
    expect($quicktime->getVideo())->toBeArray();
    expect($quicktime->getAudio())->toBeArray();
    expect($quicktime->getSttsFramecount())->toBeArray();

    expect($quicktime->getSttsFramecount())->toBeArray();
    expect($quicktime->getSttsFramecount())->each(fn (Pest\Expectation $i) => expect($i->value)->toBeInt());

    expect($quicktime->getComments())->toBeArray();

    expect($quicktime->getChapters())->toBeArray();
    expect($quicktime->getChapters())->each(fn (Pest\Expectation $i) => expect($i->value)->toBeInstanceOf(Id3AudioQuicktimeChapter::class));

    expect($quicktime->getFree())->toBeInstanceOf(Id3AudioQuicktimeItem::class);
    expect($quicktime->getWide())->toBeInstanceOf(Id3AudioQuicktimeItem::class);
    expect($quicktime->getMdat())->toBeInstanceOf(Id3AudioQuicktimeItem::class);
    expect($quicktime->getEncoding())->toBeString();

    expect($quicktime->toArray())->toBeArray();
});
