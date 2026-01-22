<?php

use Kiwilan\Audio\Audio;

beforeEach(function () {
    resetMp3Writer();
});

it('can update cover', function (string $path) {
    $audio = Audio::read($path);
    $audio->write()
        ->cover(FOLDER)
        ->save();

    $audio = Audio::read($path);
    expect($audio->getTitle())->toBe('Introduction');

    $content = base64_encode(file_get_contents(FOLDER));
    expect($audio->getCover()->getContents(true))->toBe($content);
})->with([MP3_WRITER]);

it('can read use file content as cover', function (string $path) {
    $audio = Audio::read($path);

    $tag = $audio->write()
        ->cover(file_get_contents(FOLDER));

    $tag->save();

    $audio = Audio::read($path);

    $content = file_get_contents(FOLDER);
    expect($audio->getCover()->getContents(true))->toBe(base64_encode($content));
})->with([MP3_WRITER]);

it('can read use tags', function (string $path) {
    $audio = Audio::read($path);

    $random = (string) rand(1, 1000);
    $coverData = file_get_contents(FOLDER);
    $tag = $audio->write()
        ->tags([
            'title' => $random,
        ])
        ->cover($coverData);

    $tag->save();

    $audio = Audio::read($path);
    expect($audio->getTitle())->toBe($random);

    $content = file_get_contents(FOLDER);
    expect($audio->getCover()->getContents())->toBe($content);
})->with([MP3_WRITER]);

it('can use tags with cover', function (string $path) {
    $audio = Audio::read($path);

    $tag = $audio->write()
        ->tags([
            'title' => 'New Title',
        ])
        ->cover(FOLDER);

    $tag->save();

    $audio = Audio::read($path);

    $content = file_get_contents(FOLDER);
    expect($audio->getTitle())->toBe('New Title');
    expect($tag->getCore()->cover->data)->toBe(base64_encode($content));
})->with([MP3_WRITER]);

it('can update cover with path', function () {
    $audio = Audio::read(MP3_WRITER);

    $path = pathTo('cover.jpg', 'media');

    if (file_exists($path)) {
        unlink($path);
    }
    $audio->getCover()->extractCover($path);
    expect(file_exists($path))->toBeTrue();

    $audio->write()
        ->cover(FOLDER)
        ->save();

    $audio = Audio::read(MP3_WRITER);
    unlink($path);
    $audio->getCover()->extractCover($path);
    expect(file_exists($path))->toBeTrue();

    $content = file_get_contents(FOLDER);
    expect($audio->getCover()->getContents(true))->toBe(base64_encode($content));
});

it('can remove cover', function () {
    $audio = Audio::read(MP3_WRITER);

    $audio->write()
        ->removeCover()
        ->save();

    $audio = Audio::read(MP3_WRITER);

    expect($audio->hasCover())->toBeFalse();
    expect($audio->getCover())->toBeNull();
});
