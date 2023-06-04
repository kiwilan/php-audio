<?php

use Kiwilan\Audio\Audio;
use Kiwilan\Audio\Models\Id3Audio;
use Kiwilan\Audio\Models\Id3AudioTag;
use Kiwilan\Audio\Models\Id3Comments;
use Kiwilan\Audio\Models\Id3Item;
use Kiwilan\Audio\Models\Id3TagsHtml;

it('can parse ID3 item', function (string $path) {
    $audio = Audio::read($path);
    $id3 = $audio->id3();
    $raw = $id3->raw();
    $item = $id3->item();

    expect($id3->instance())->toBeInstanceOf(getID3::class);
    expect($item)->toBeInstanceOf(Id3Item::class);

    expect($raw)->toBeArray();
    expect($item->version())->toBeString();
    expect($item->filesize())->toBeInt();
    expect($item->filepath())->toBeString();
    expect($item->filename())->toBeString();
    expect($item->filenamepath())->toBeString();
    expect($item->avdataoffset())->toBeInt();
    expect($item->avdataend())->toBeInt();
    expect($item->fileformat())->toBeString();
    expect($item->audio())->toBeInstanceOf(Id3Audio::class);
    if ($item->tags()) {
        expect($item->tags())->toBeInstanceOf(Id3AudioTag::class);
    }
    if ($item->comments()) {
        expect($item->comments())->toBeInstanceOf(Id3Comments::class);
    }
    expect($item->encoding())->toBeString();
    expect($item->mime_type())->toBeString();
    if ($item->mpeg()) {
        expect($item->mpeg())->toBeArray();
    }
    expect($item->playtime_seconds())->toBeFloat();
    if ($item->tags_html()) {
        expect($item->tags_html())->toBeInstanceOf(Id3TagsHtml::class);
    }
    expect($item->bitrate())->toBeFloat();
    expect($item->playtime_string())->toBeString();
})->with([...AUDIO]);
