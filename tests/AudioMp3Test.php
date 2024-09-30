<?php

use Kiwilan\Audio\Audio;
use Kiwilan\Audio\Enums\AudioFormatEnum;
use Kiwilan\Audio\Enums\AudioTypeEnum;
use Kiwilan\Audio\Id3\Id3Reader;
use Kiwilan\Audio\Models\AudioCover;
use Kiwilan\Audio\Models\AudioMetadata;

it('can read mp3 info', function () {
    $audio = Audio::get(MP3);

    expect($audio)->toBeInstanceOf(Audio::class);
    expect($audio->getPath())->toBe(MP3);
    expect($audio->getExtension())->toBe('mp3');
    expect($audio->getFormat())->toBe(AudioFormatEnum::mp3);
    expect($audio->getType())->toBe(AudioTypeEnum::id3);
    expect($audio->getMetadata())->toBeInstanceOf(AudioMetadata::class);
    expect($audio->getId3Reader())->toBeInstanceOf(Id3Reader::class);
    expect($audio->getDuration())->toBe(11.05);
    expect($audio->getDurationHuman())->toBe('00:00:11');

    expect($audio->isWritable())->toBeTrue();
    expect($audio->isValid())->toBeTrue();
    expect($audio->hasCover())->toBeTrue();

    expect($audio->getTitle())->toBe('Introduction');
    expect($audio->getArtist())->toBe('Mr Piouf');
    expect($audio->getAlbum())->toBe('P1PDD Le conclave de Troie');
    expect($audio->getGenre())->toBe('Roleplaying game');
    expect($audio->getYear())->toBe(2016);
    expect($audio->getTrackNumber())->toBe('1');
    expect($audio->getTrackNumberInt())->toBe(1);
    expect($audio->getAlbumArtist())->toBe('P1PDD & Mr Piouf');
    expect($audio->getComposer())->toBe('P1PDD & Piouf');
    expect($audio->getDiscNumber())->toBe('1');
    expect($audio->getDiscNumberInt())->toBe(1);
    expect($audio->isCompilation())->toBeTrue();
    expect($audio->getCreationDate())->toBeNull();
    expect($audio->getEncodingBy())->toBeNull();
    expect($audio->getEncoding())->toBeNull();
    expect($audio->getCopyright())->toBeNull();
    expect($audio->getDescription())->toBeNull();
    expect($audio->getSynopsis())->toBeNull();
    expect($audio->getLanguage())->toBeNull();
    expect($audio->getLyrics())->toBeNull();
    expect($audio->getComment())->toBe('http://www.p1pdd.com');

    expect($audio->getRawAll())->toBeArray();
    expect($audio->getRawAll()['id3v1'])->toBeArray();
    expect($audio->getRawAll()['id3v1'])->toHaveCount(6);
    expect($audio->getRawAll()['id3v2'])->toBeArray();
    expect($audio->getRawAll()['id3v2'])->toHaveCount(11);
    expect($audio->getRaw())->toHaveCount(11);
    expect($audio->getRaw('id3v2'))->toHaveCount(11);
    expect($audio->getRawKey('title'))->toBe('Introduction');
    expect($audio->getExtras())->toBeArray();

    $cover = $audio->getCover();
    expect($cover)->toBeInstanceOf(AudioCover::class);
    expect($cover->getContents())->toBeString();
    expect($cover->getContents(base64: true))->toBeString();
    expect($cover->getMimeType())->toBe('image/jpeg');
    expect($cover->getWidth())->toBe(640);
    expect($cover->getHeight())->toBe(640);

    $metadata = $audio->getMetadata();
    expect($metadata->getFileSize())->toBe(272737);
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
});

it('can extract cover mp3', function () {
    $audio = Audio::get(MP3);
    $cover = $audio->getCover();

    expect($cover)->toBeInstanceOf(AudioCover::class);
    expect($cover->getContents())->toBeString();
    expect($cover->getMimeType())->toBe('image/jpeg');
    expect($cover->getWidth())->toBe(640);
    expect($cover->getHeight())->toBe(640);

    $path = 'tests/output/cover.jpg';
    file_put_contents($path, $cover->getContents());
    expect(file_exists($path))->toBeTrue();
    expect($path)->toBeReadableFile();
});

it('can read file mp3 no meta', function () {
    $audio = Audio::get(MP3_NO_META);

    expect($audio)->toBeInstanceOf(Audio::class);
    expect($audio->getTitle())->toBeNull();
    expect($audio->getArtist())->toBeNull();
    expect($audio->getAlbum())->toBeNull();
    expect($audio->getGenre())->toBeNull();
    expect($audio->getYear())->toBeNull();
    expect($audio->getTrackNumber())->toBeNull();
    expect($audio->getComment())->toBeNull();
    expect($audio->getAlbumArtist())->toBeNull();
    expect($audio->getComposer())->toBeNull();
    expect($audio->getDiscNumber())->toBeNull();
    expect($audio->isCompilation())->toBeFalse();
    expect($audio->getPath())->toBe(MP3_NO_META);
});

it("can fail if file didn't exists", function () {
    expect(fn () => Audio::get('tests/media/unknown.mp3'))->toThrow(Exception::class);
});
