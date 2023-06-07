<?php

use Kiwilan\Audio\Audio;
use Kiwilan\Audio\Enums\AudioFormatEnum;

it('can update file', function (string $path) {
    $audio = Audio::get($path);
    $random = (string) rand(1, 1000);
    $tag = $audio->update()
        ->setTitle($random)
        ->setArtist('New Artist')
        ->setAlbum('New Album')
        ->setGenre('New Genre')
        ->setYear('2022')
        ->setTrackNumber('2/10')
        ->setAlbumArtist('New Album Artist')
        ->setComment('New Comment')
        ->setComposer('New Composer')
        ->setCreationDate('2021-01-01')
        ->setDescription('New Description')
        ->setDiscNumber('2/2')
        ->setEncodingBy('New Encoding By')
        ->setEncoding('New Encoding')
        ->setIsCompilation(false)
        ->setLyrics('New Lyrics')
        ->setStik('New Stik')
        ->setCover(FOLDER);

    $tag->save();

    $audio = Audio::get($path);

    expect($audio->title())->toBe($random);
    expect($audio->artist())->toBe('New Artist');
    expect($audio->album())->toBe('New Album');
    expect($audio->genre())->toBe('New Genre');
    expect($audio->year())->toBe(2022);
    expect($audio->albumArtist())->toBe('New Album Artist');
    expect($audio->comment())->toBe('New Comment');
    expect($audio->composer())->toBe('New Composer');
    expect($audio->discNumber())->toBe('2/2');
    expect($audio->isCompilation())->toBeFalse();

    if ($audio->format() !== AudioFormatEnum::mp3) {
        expect($audio->trackNumber())->toBe('2/10');
    } else {
        expect($audio->trackNumber())->toBe('2');
    }

    if ($tag->core()->hasCover()) {
        $content = file_get_contents(FOLDER);
        expect($tag->core()->cover()->data())->toBe(base64_encode($content));
    }
})->with(AUDIO_WRITER);

it('can read use file content as cover', function (string $path) {
    $audio = Audio::get($path);

    $tag = $audio->update()
        ->setCover(file_get_contents(FOLDER));

    $tag->save();

    $audio = Audio::get($path);

    $content = file_get_contents(FOLDER);
    expect($tag->core()->cover()->data())->toBe(base64_encode($content));
})->with([MP3_WRITER]);

it('can read use tags', function (string $path) {
    $audio = Audio::get($path);

    $random = (string) rand(1, 1000);
    $image = getimagesize(FOLDER);
    $coverData = file_get_contents(FOLDER);
    $coverPicturetypeid = $image[2];
    $coverDescription = 'cover';
    $coverMime = $image['mime'];
    $tag = $audio->update()
        ->noAutomatic()
        ->setTags([
            'title' => $random,
            'attached_picture' => [
                [
                    'data' => $coverData,
                    'picturetypeid' => $coverPicturetypeid,
                    'description' => $coverDescription,
                    'mime' => $coverMime,
                ],
            ],
        ]);

    $tag->save();

    $audio = Audio::get($path);
    expect($audio->title())->toBe($random);

    $content = file_get_contents(FOLDER);
    expect($audio->cover()->content())->toBe($content);
})->with([MP3_WRITER]);

it('can update use tags with tag formats', function (string $path) {
    $audio = Audio::get($path);

    $random = (string) rand(1, 1000);
    $tag = $audio->update()
        ->noAutomatic()
        ->setTags([
            'title' => $random,
        ])
        ->setTagFormats(['id3v1', 'id3v2.4']);

    $tag->save();

    $audio = Audio::get($path);
    expect($audio->title())->toBe($random);
})->with([MP3_WRITER]);
