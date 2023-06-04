<?php

use Kiwilan\Audio\Audio;
use Kiwilan\Audio\Models\AudioCover;
use Kiwilan\Audio\Models\Id3Audio;
use Kiwilan\Audio\Models\Id3AudioTag;
use Kiwilan\Audio\Models\Id3Comments;
use Kiwilan\Audio\Models\Id3Item;
use Kiwilan\Audio\Models\Id3TagsHtml;

it('can read file mp3', function () {
    $audio = Audio::read(MP3);

    expect($audio)->toBeInstanceOf(Audio::class);
    expect($audio->title())->toBe('Episode 00 - Le conclave de Troie');
    expect($audio->artist())->toBe('Mr Piouf, P1PDD');
    expect($audio->album())->toBe('P1PDD: S01 Le conclave de Troie');
    expect($audio->genre())->toBe('Roleplaying game');
    expect($audio->year())->toBe('2016');
    expect($audio->trackNumber())->toBe('1');
    expect($audio->comment())->toBe('http://www.p1pdd.com');
    expect($audio->albumArtist())->toBe('P1PDD & Mr Piouf');
    expect($audio->composer())->toBe('P1PDD & Piouf');
    expect($audio->discNumber())->toBe('1/2');
    expect($audio->isCompilation())->toBe(true);
    expect($audio->path())->toBe(MP3);
    expect($audio->extension())->toBe('mp3');

    $metadata = $audio->metadata();
    expect($metadata->filesize())->toBe(272737);
    expect($metadata->extension())->toBe('mp3');
    expect($metadata->encoding())->toBe('UTF-8');
    expect($metadata->mimeType())->toBe('audio/mpeg');
    expect($metadata->playtimeInSeconds())->toBe(11.0496875);
    expect($metadata->playtimeHumanReadable())->toBe('0:11');
    expect($metadata->bitrate())->toBe(128000);
    expect($metadata->bitrateMode())->toBe('cbr');
    expect($metadata->sampleRate())->toBe(44100);
    expect($metadata->channels())->toBe(2);
    expect($metadata->channelMode())->toBe('joint stereo');
    expect($metadata->lossless())->toBe(false);
    expect($metadata->compressionRatio())->toBe(0.09070294784580499);
});

it('can read file m4b (audiobook)', function () {
    $audio = Audio::read(M4B);

    expect($audio->title())->toBe('P1PDD Le conclave de Troie');
    expect($audio->artist())->toBe('Mr Piouf');
    expect($audio->album())->toBe('P1PDD Le conclave de Troie');
    expect($audio->genre())->toBe('Audiobooks');
    expect($audio->year())->toBeNull();
    expect($audio->trackNumber())->toBe('1/1');
    expect($audio->comment())->toBe('P1PDD');
    expect($audio->albumArtist())->toBe('P1PDD team');
    expect($audio->composer())->toBeNull();
    expect($audio->discNumber())->toBeNull();
    expect($audio->isCompilation())->toBe(false);
    expect($audio->path())->toBe(M4B);
    expect($audio->extension())->toBe('m4b');
    expect($audio->creationDate())->toBe('2023-6-4T12:00:00Z');
    expect($audio->encodedBy())->toBe('Mr Piouf de P1PDD');
    expect($audio->encodingTool())->toBe('Audiobook Builder 2.2.6 (www.splasm.com), macOS 13.4');
    expect($audio->description())->toBe('Les campagnes de P1PDD');
    expect($audio->descriptionLong())->toBe('Les campagnes de P1PDD');
    expect($audio->lyrics())->toBe('Le conclave de Troie');
    expect($audio->stik())->toBe('Audiobook');
});

it('can extract cover mp3', function () {
    $audio = Audio::read(MP3);
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

it('can read file', function (string $path) {
    $audio = Audio::read($path);
    $ext = pathinfo($path, PATHINFO_EXTENSION);

    expect($audio)->toBeInstanceOf(Audio::class);
    // expect($audio->title())->toBeString();
    // expect($audio->artist())->toBeString();
    // expect($audio->album())->toBeString();
    // expect($audio->genre())->toBeString();
    // expect($audio->year())->toBeString();
    // expect($audio->trackNumber())->toBeString();
    // expect($audio->comment())->toBeString();
    // expect($audio->albumArtist())->toBeString();
    // expect($audio->composer())->toBeString();
    // expect($audio->discNumber())->toBeString();
    // expect($audio->isCompilation())->toBeBool();
    expect($audio->path())->toBe($path);
    expect($audio->extension())->toBe($ext);

    $metadata = $audio->metadata();
    expect($metadata->filesize())->toBeInt();
    expect($metadata->extension())->toBeString();
    expect($metadata->encoding())->toBeString();
    expect($metadata->mimeType())->toBeString();
    expect($metadata->playtimeInSeconds())->toBeFloat();
    expect($metadata->playtimeHumanReadable())->toBeString();
    expect($metadata->bitrate())->toBeInt();
    if ($metadata->bitrateMode()) {
        expect($metadata->bitrateMode())->toBeString();
    }
    expect($metadata->sampleRate())->toBeInt();
    expect($metadata->channels())->toBeInt();
    if ($metadata->channelMode()) {
        expect($metadata->channelMode())->toBeString();
    }
    expect($metadata->lossless())->toBeBool();
    if ($metadata->compressionRatio()) {
        expect($metadata->compressionRatio())->toBeFloat();
    }
})->with([...AUDIO]);

it('can extract cover', function (string $path) {
    $audio = Audio::read($path);
    $cover = $audio->cover();

    if ($cover) {
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

it('can parse ID3 item', function (string $path) {
    $audio = Audio::read($path);
    $id3 = $audio->id3();
    $raw = $id3->raw();
    $item = $id3->item();

    expect($id3->instance())->toBeInstanceOf(getID3::class);
    expect($item)->toBeInstanceOf(Id3Item::class);

    expect($raw)->toBeArray();
    expect($item->version())->toBeString();
    expect($item->filesize())->toBeInt();
    expect($item->filepath())->toBeString();
    expect($item->filename())->toBeString();
    expect($item->filenamepath())->toBeString();
    expect($item->avdataoffset())->toBeInt();
    expect($item->avdataend())->toBeInt();
    expect($item->fileformat())->toBeString();
    expect($item->audio())->toBeInstanceOf(Id3Audio::class);
    if ($item->tags()) {
        expect($item->tags())->toBeInstanceOf(Id3AudioTag::class);
    }
    if ($item->comments()) {
        expect($item->comments())->toBeInstanceOf(Id3Comments::class);
    }
    expect($item->encoding())->toBeString();
    expect($item->mime_type())->toBeString();
    if ($item->mpeg()) {
        expect($item->mpeg())->toBeArray();
    }
    expect($item->playtime_seconds())->toBeFloat();
    if ($item->tags_html()) {
        expect($item->tags_html())->toBeInstanceOf(Id3TagsHtml::class);
    }
    expect($item->bitrate())->toBeFloat();
    expect($item->playtime_string())->toBeString();
})->with([...AUDIO]);

it('can read file mp3 no meta', function () {
    $audio = Audio::read(MP3_NO_META);

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
