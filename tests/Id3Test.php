<?php

use Kiwilan\Audio\Audio;
use Kiwilan\Audio\Models\Id3Audio;
use Kiwilan\Audio\Models\Id3AudioTag;
use Kiwilan\Audio\Models\Id3Comments;
use Kiwilan\Audio\Models\Id3Reader;
use Kiwilan\Audio\Models\Id3TagsHtml;

it('can parse ID3 reader', function (string $path) {
    $audio = Audio::get($path);

    $reader = $audio->reader();
    $raw = $reader->raw();

    expect($reader->instance())->toBeInstanceOf(getID3::class);
    expect($reader)->toBeInstanceOf(Id3Reader::class);

    expect($raw)->toBeArray();
    expect($reader->version())->toBeString();
    expect($reader->filesize())->toBeInt();
    expect($reader->filepath())->toBeString();
    expect($reader->filename())->toBeString();
    expect($reader->filenamepath())->toBeString();
    expect($reader->avdataoffset())->toBeInt();
    expect($reader->avdataend())->toBeInt();
    expect($reader->fileformat())->toBeString();
    expect($reader->audio())->toBeInstanceOf(Id3Audio::class);
    if ($reader->tags()) {
        expect($reader->tags())->toBeInstanceOf(Id3AudioTag::class);
    }
    if ($reader->comments()) {
        expect($reader->comments())->toBeInstanceOf(Id3Comments::class);
    }
    expect($reader->encoding())->toBeString();
    expect($reader->mime_type())->toBeString();
    if ($reader->mpeg()) {
        expect($reader->mpeg())->toBeArray();
    }
    if ($reader->playtime_seconds()) {
        expect($reader->playtime_seconds())->toBeFloat();
    }

    if ($reader->tags_html()) {
        expect($reader->tags_html())->toBeInstanceOf(Id3TagsHtml::class);
    }
    if ($reader->bitrate()) {
        expect($reader->bitrate())->toBeFloat();
    }
    if ($reader->playtime_string()) {
        expect($reader->playtime_string())->toBeString();
    }
})->with([...AUDIO]);
