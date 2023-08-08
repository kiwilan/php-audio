<?php

use Kiwilan\Audio\Audio;
use Kiwilan\Audio\Models\Id3Audio;
use Kiwilan\Audio\Models\Id3AudioTag;
use Kiwilan\Audio\Models\Id3Comments;
use Kiwilan\Audio\Models\Id3Reader;
use Kiwilan\Audio\Models\Id3TagsHtml;

it('can parse ID3 reader', function (string $path) {
    $audio = Audio::get($path);

    $reader = $audio->getReader();
    $raw = $reader->getRaw();

    expect($reader->getInstance())->toBeInstanceOf(getID3::class);
    expect($reader)->toBeInstanceOf(Id3Reader::class);

    expect($raw)->toBeArray();
    expect($reader->getVersion())->toBeString();
    expect($reader->getFilesize())->toBeInt();
    expect($reader->getFilepath())->toBeString();
    expect($reader->getFilename())->toBeString();
    expect($reader->getFilenamepath())->toBeString();
    expect($reader->getAvdataoffset())->toBeInt();
    expect($reader->getAvdataend())->toBeInt();
    expect($reader->getFileformat())->toBeString();
    expect($reader->getAudio())->toBeInstanceOf(Id3Audio::class);
    if ($reader->getTags()) {
        expect($reader->getTags())->toBeInstanceOf(Id3AudioTag::class);
    }
    if ($reader->getComments()) {
        expect($reader->getComments())->toBeInstanceOf(Id3Comments::class);
    }
    expect($reader->getEncoding())->toBeString();
    expect($reader->getMimeType())->toBeString();
    if ($reader->getMpeg()) {
        expect($reader->getMpeg())->toBeArray();
    }
    if ($reader->getPlaytimeSeconds()) {
        expect($reader->getPlaytimeSeconds())->toBeFloat();
    }

    if ($reader->getTagsHtml()) {
        expect($reader->getTagsHtml())->toBeInstanceOf(Id3TagsHtml::class);
    }
    if ($reader->getBitrate()) {
        expect($reader->getBitrate())->toBeFloat();
    }
    if ($reader->getPlaytimeString()) {
        expect($reader->getPlaytimeString())->toBeString();
    }
})->with([...AUDIO]);

it('can parse with ID3 methods', function (string $path) {
    $audio = Audio::get($path);
    $type = $audio->getType()->value;
    $tags = $audio->getReader()->getTags();

    if ($type === 'id3') {
        $type = 'id3v2';
    }

    $metadata = $tags->{$type}();
    expect($metadata->toArray())->toBeArray();
})->with([...AUDIO]);
