<?php

use Kiwilan\Audio\Audio;
use Kiwilan\Audio\Core\AudioCore;
use Kiwilan\Audio\Enums\AudioFormatEnum;

beforeEach(function () {
    resetMp3Writer();
});

it('can update file', function (string $path) {
    $audio = Audio::read($path);
    $random = (string) rand(1, 1000);
    $audio->update()
        ->title($random)
        ->artist('New Artist')
        ->album('New Album')
        ->genre('New Genre')
        ->year('2022')
        ->trackNumber('2/10')
        ->albumArtist('New Album Artist')
        ->comment('New Comment')
        ->composer('New Composer')
        ->creationDate('2021-01-01')
        ->description('New Description')
        ->discNumber('2/2')
        ->encodingBy('New Encoding By')
        ->encoding('New Encoding')
        ->isNotCompilation()
        ->lyrics('New Lyrics')
        ->save();

    $audio = Audio::read($path);

    expect($audio->getTitle())->toBe($random);
    expect($audio->getArtist())->toBe('New Artist');
    expect($audio->getAlbum())->toBe('New Album');
    expect($audio->getGenre())->toBe('New Genre');
    expect($audio->getYear())->toBe(2022);
    expect($audio->getAlbumArtist())->toBe('New Album Artist');
    expect($audio->getComment())->toBe('New Comment');
    expect($audio->getComposer())->toBe('New Composer');
    expect($audio->getDiscNumber())->toBe('2/2');
    expect($audio->isCompilation())->toBeFalse();

    expect($audio->getCreationDate())->toBeNull();
    if ($audio->getFormat() === AudioFormatEnum::mp3) {
        expect($audio->getDescription())->toBeNull();
        expect($audio->getEncoding())->toBeNull();
    }
    expect($audio->getEncodingBy())->toBeNull();
    if ($audio->getLyrics()) {
        expect($audio->getLyrics())->toBe('New Lyrics');
    }

    if ($audio->getFormat() !== AudioFormatEnum::mp3) {
        expect($audio->getTrackNumber())->toBe('2/10');
    } else {
        expect($audio->getTrackNumber())->toBe('2');
    }
})->with(AUDIO_WRITER);

it('can update use tags with tag formats', function (string $path) {
    $audio = Audio::read($path);

    $random = (string) rand(1, 1000);
    $tag = $audio->update()
        ->tags([
            'title' => $random,
        ])
        ->tagFormats(['id3v1', 'id3v2.4']);

    $tag->save();

    $audio = Audio::read($path);
    expect($audio->getTitle())->toBe($random);
})->with([MP3_WRITER]);

it('can update with tags and handle native metadata', function (string $path) {
    $audio = Audio::read($path);

    $tag = $audio->update()
        ->isCompilation()
        ->tags([
            'title' => 'New Title',
            'band' => 'New Band',
        ])
        ->tagFormats(['id3v1', 'id3v2.4']);

    $tag->save();

    $audio = Audio::read($path);
    expect($audio->getTitle())->toBe('New Title');
    expect($audio->getAlbumArtist())->toBe('New Band');
    expect($audio->isCompilation())->toBeFalse();
})->with([MP3_WRITER]);

it('can use arrow function safe with unsupported tags', function (string $path) {
    $audio = Audio::read($path);

    $tag = $audio->update()
        ->title('New Title')
        ->encoding('New encoding');

    expect(fn () => $tag->save())->not()->toThrow(Exception::class);

    $audio = Audio::read($path);
    expect($audio->getTitle())->toBe('New Title');
})->with([MP3_WRITER]);

it('can get core before save', function (string $path) {
    $audio = Audio::read($path);

    $writer = $audio->update()
        ->title('New Title')
        ->tags([
            'title' => 'New Title tag',
            'band' => 'New Band',
        ]);

    expect($writer->getCore())->toBeInstanceOf(AudioCore::class);
})->with([MP3_WRITER]);

it('can handle exceptions', function (string $path) {
    $audio = Audio::read($path);

    $tag = $audio->update()
        ->tags([
            'title' => 'New Title',
            'albumArtist' => 'New Album Artist',
        ])
        ->handleErrors();

    expect(fn () => $tag->save())->toThrow(Exception::class);
})->with([MP3_WRITER]);

it('can skip exceptions', function (string $path) {
    $audio = Audio::read($path);

    $tag = $audio->update()
        ->tags([
            'title' => 'New Title',
            'albumArtist' => 'New Album Artist',
        ]);

    $tag->save();

    $audio = Audio::read($path);
    expect($audio->getTitle())->toBe('New Title');
    expect($audio->getAlbumArtist())->toBeNull();
})->with([MP3_WRITER]);

it('can update with new path', function (string $path) {
    $audio = Audio::read($path);
    $newPath = 'tests/output/new.mp3';

    $tag = $audio->update()
        ->title('New Title')
        ->path($newPath);

    $tag->save();

    $audio = Audio::read($newPath);
    expect($audio->getTitle())->toBe('New Title');
})->with([MP3_WRITER]);

it('can update with merged tags and core methods', function (string $path) {
    $audio = Audio::read($path);

    $tag = $audio->update()
        ->tags([
            'title' => 'New Title tag',
            'band' => 'New Band',
        ]);
    $tag->save();

    $audio = Audio::read($path);
    expect($audio->getTitle())->toBe('New Title tag');
    expect($audio->getAlbumArtist())->toBe('New Band');
})->with([MP3_WRITER]);

it('can use arrow function safe with unsupported formats', function (string $path) {
    $audio = Audio::read($path);

    $tag = $audio->update()
        ->handleErrors()
        ->title('New Title Alac');

    expect(fn () => $tag->save())->toThrow(Exception::class);
})->with([ALAC_WRITER]);

it('can remove old tags', function (string $path) {
    $audio = Audio::read($path);
    $newPath = 'tests/output/new.mp3';

    $tag = $audio->update()
        ->title('New Title')
        ->removeOtherTags()
        ->path($newPath);

    $tag->save();

    $audio = Audio::read($newPath);
    expect($audio->getTitle())->toBe('New Title');
    expect($audio->getAlbumArtist())->toBeNull();
})->with([MP3]);
