<?php

use Kiwilan\Audio\Audio;

beforeEach(function () {
    resetMp3Writer();
});

it('can update tags', function () {
    $audio = Audio::read(MP3_WRITER);
    testMp3Writer($audio);

    $audio->update()
        ->title('New Title')
        ->artist('New Artist')
        ->album('New Album')
        ->genre('New Genre')
        ->year(2022)
        ->trackNumber('2/10')
        ->albumArtist('New Album Artist')
        ->comment('New Comment')
        ->composer('New Composer')
        ->discNumber('2/2')
        ->isNotCompilation()
        ->lyrics('New Lyrics')
        ->creationDate('2021-01-01')
        ->copyright('New Copyright')
        ->encodingBy('New Encoding By')
        ->encoding('New Encoding')
        ->description('New Description')
        ->synopsis('New Synopsis')
        ->language('en')
        ->copyright('New Copyright')
        ->handleErrors()
        ->save();

    $audio = Audio::read(MP3_WRITER);
    testMp3Writed($audio);
    expect($audio->getLanguage())->toBe('en');
    expect($audio->getCopyright())->toBe('New Copyright');
});

it('can update only one tag', function () {
    $audio = Audio::read(MP3_WRITER);
    testMp3Writer($audio);

    $audio->update()
        ->title('New Title')
        ->save();

    $audio = Audio::read(MP3_WRITER);
    expect($audio->getTitle())->toBe('New Title');
    expect($audio->getArtist())->toBe('Mr Piouf');
    expect($audio->getAlbum())->toBe('P1PDD Le conclave de Troie');
    expect($audio->getGenre())->toBe('Roleplaying game');
    expect($audio->getYear())->toBe(2016);
    expect($audio->getTrackNumber())->toBe('1');
    expect($audio->getComment())->toBe('http://www.p1pdd.com');
    expect($audio->getAlbumArtist())->toBe('P1PDD & Mr Piouf');
    expect($audio->getComposer())->toBe('P1PDD & Piouf');
    expect($audio->getDiscNumber())->toBe('1');
    expect($audio->isCompilation())->toBeTrue();
});

it('can update tags manually', function () {
    $audio = Audio::read(MP3_WRITER);
    testMp3Writer($audio);

    $audio->update()
        ->tags([
            'title' => 'New Title',
            'artist' => 'New Artist',
            'album' => 'New Album',
            'genre' => 'New Genre',
            'year' => '2022',
            'track_number' => '2/10',
            'band' => 'New Album Artist',
            'comment' => 'New Comment',
            'composer' => 'New Composer',
            'part_of_a_set' => '2/2',
            'part_of_a_compilation' => false,
            'unsynchronised_lyric' => 'New Lyrics',
            'language' => 'en',
            'copyright' => 'New Copyright',
        ])
        ->save();

    $audio = Audio::read(MP3_WRITER);
    testMp3Writed($audio);
    expect($audio->getLanguage())->toBe('en');
    expect($audio->getCopyright())->toBe('New Copyright');
});
