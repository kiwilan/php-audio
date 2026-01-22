<?php

use Kiwilan\Audio\Audio;
use Kiwilan\Audio\Models\AudioCover;

beforeEach(function () {
    clearOutput();
});

it('can extract cover', function (string $path) {
    $audio = Audio::read($path);
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

it('can read as array', function (string $path) {
    $audio = Audio::read($path);
    $cover = $audio->getCover();

    if ($cover) {
        expect($cover->toArray())->toBeArray();
    } else {
        expect($cover)->toBeNull();
    }
})->with([...AUDIO]);
