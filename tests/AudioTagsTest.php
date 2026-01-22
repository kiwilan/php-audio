<?php

use Kiwilan\Audio\Audio;
use Kiwilan\Audio\Enums\AudioEngineEnum;
use Kiwilan\Audio\Models\AudioTags;

function getTags(): AudioTags
{
    $audio = Audio::read(MP3_THE_WALL, AudioEngineEnum::ffmpeg);

    return $audio->getTags();
}

it('can get tags', function () {
    $tags = getTags();
    expect($tags)->toBeInstanceOf(AudioTags::class);
    expect($tags->getRaw())->toBeArray();
});

it('can basic audio tags', function () {
    $tags = getTags();

    expect($tags->getTitle())->toBe('Another Brick in the Wall, Part 1');
    expect($tags->getAlbum())->toBe('The Wall');
    expect($tags->getArtist())->toBe('Syd Barrett;Nick Mason;Roger Waters;Richard Wright;David Gilmour');
    expect($tags->getAlbumArtist())->toBe('Pink Floyd');
    expect($tags->getComposer())->toBe('Syd Barrett');

    expect($tags->getCompilation())->toBe('1');
    expect($tags->isCompilation())->toBeTrue();

    expect($tags->getDisc())->toBe('1/2');
    expect($tags->getDiscNumber())->toBe(1);

    expect($tags->getGenre())->toBe('Progressive Rock;Rock Opera');
    expect($tags->getGenres(';'))->toBe(['Progressive Rock', 'Rock Opera']);

    expect($tags->getTrack())->toBe('3/13');
    expect($tags->getTrackNumber())->toBe(3);

    expect($tags->getDate())->toBe('1979-11-30');
    expect($tags->getYear())->toBe(1979);
});

it('can advanced audio tags', function () {
    $tags = getTags();

    expect($tags->getComment())->toBe('Recorded at Abbey Road Studios');
    expect($tags->getLanguage())->toBe('English');
    expect($tags->getCopyright())->toBe('© 1979 Pink Floyd');
    expect($tags->getDescription())->toBe('The Wall is the eleventh studio album by the English rock band Pink Floyd');
    expect($tags->getLyrics())->toBe('Hey! Teachers! Leave them kids alone!');
    expect($tags->getSynopsis())->toBe('The Wall is one of the most iconic concept albums and rock operas in music history.');
    expect($tags->getSubtitle())->toBe("All in all, it's just another brick in the wall.");
    expect($tags->getEncodedBy())->toBe('iTunes');
    expect($tags->getEncoder())->toBe('Lavf62.3.100');
});

it('can audiobook tags', function () {
    $tags = getTags();

    expect($tags->getASIN())->toBe('B008Y43GBY');
    expect($tags->getISBN())->toBe('9780007496785');
    expect($tags->getPublisher())->toBe('Pink Floyd Music Publishers Ltd.');
    expect($tags->getSeries())->toBe('The Wall Saga');
    expect($tags->getSeriesPart())->toBe('1');
    expect($tags->getVolume())->toBe(1);
});
