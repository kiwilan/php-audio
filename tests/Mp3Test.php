<?php

use Kiwilan\Audio\Audio;
use Kiwilan\Audio\Enums\AudioFormatEnum;
use Kiwilan\Audio\Models\AudioCover;

it('can read file mp3', function () {
    $audio = Audio::get(MP3);

    expect($audio)->toBeInstanceOf(Audio::class);
    expect($audio->getTitle())->toBe('Introduction');
    expect($audio->getArtist())->toBe('Mr Piouf');
    expect($audio->getAlbum())->toBe('P1PDD Le conclave de Troie');
    expect($audio->getGenre())->toBe('Roleplaying game');
    expect($audio->getYear())->toBe(2016);
    expect($audio->getTrackNumber())->toBe('1');
    expect($audio->getComment())->toBe('http://www.p1pdd.com');
    expect($audio->getAlbumArtist())->toBe('P1PDD & Mr Piouf');
    expect($audio->getComposer())->toBe('P1PDD & Piouf');
    expect($audio->getDiscNumber())->toBe('1');
    expect($audio->isCompilation())->toBe(true);
    expect($audio->getPath())->toBe(MP3);
    expect($audio->getFormat())->toBe(AudioFormatEnum::mp3);
    expect($audio->getDuration())->toBe(11.05);
    expect($audio->getExtras())->toBeArray();

    $audio = $audio->getAudio();
    expect($audio->getFilesize())->toBe(272737);
    expect($audio->getExtension())->toBe('mp3');
    expect($audio->getEncoding())->toBe('UTF-8');
    expect($audio->getMimeType())->toBe('audio/mpeg');
    expect($audio->getDurationSeconds())->toBe(11.0496875);
    expect($audio->getDurationReadable())->toBe('0:11');
    expect($audio->getBitrate())->toBe(128000);
    expect($audio->getBitrateMode())->toBe('cbr');
    expect($audio->getSampleRate())->toBe(44100);
    expect($audio->getChannels())->toBe(2);
    expect($audio->getChannelMode())->toBe('joint stereo');
    expect($audio->getLossless())->toBe(false);
    expect($audio->getCompressionRatio())->toBe(0.09070294784580499);
});

it('can extract cover mp3', function () {
    $audio = Audio::get(MP3);
    $cover = $audio->getCover();

    expect($cover)->toBeInstanceOf(AudioCover::class);
    expect($cover->getContent())->toBeString();
    expect($cover->getMimeType())->toBe('image/jpeg');
    expect($cover->getWidth())->toBe(640);
    expect($cover->getHeight())->toBe(640);

    $path = 'tests/output/cover.jpg';
    file_put_contents($path, $cover->getContent());
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
