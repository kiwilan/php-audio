<?php

use Kiwilan\Audio\Audio;

it('can read file', function (string $path) {
    $audio = Audio::get($path);
    // $audio->update()
    //     ->setTitle('New Title')
    //     ->setArtist('New Artist')
    //     ->setAlbum('New Album')
    //     ->setGenre('New Genre')
    //     ->setYear('2021')
    //     ->setTrackNumber('1/10')
    //     ->setAlbumArtist('New Album Artist')
    //     ->setComment('New Comment')
    //     ->setComposer('New Composer')
    //     ->setCreationDate('2021-01-01')
    //     ->setDescription('New Description')
    //     ->setDiscNumber('1/2')
    //     ->setEncoding('New Encoding')
    //     ->setIsCompilation(true)
    //     ->setLyrics('New Lyrics')
    //     ->setStik('New Stik')
    //     ->save();

    // $audio = Audio::get($path);
    // ray($audio);
})->with([MP3_WRITER]);
