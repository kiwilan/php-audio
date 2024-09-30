<?php

use Kiwilan\Audio\Audio;
use Kiwilan\Audio\Enums\AudioFormatEnum;
use Kiwilan\Audio\Models\AudioCore;

beforeEach(function () {
    $audio = Audio::get(MP3_WRITER);

    $audio->update()
        ->title('Introduction')
        ->artist('Mr Piouf')
        ->album('P1PDD Le conclave de Troie')
        ->genre('Roleplaying game')
        ->year(2016)
        ->trackNumber('1')
        ->comment('http://www.p1pdd.com')
        ->albumArtist('P1PDD & Mr Piouf')
        ->composer('P1PDD & Piouf')
        ->discNumber('1')
        ->isCompilation()
        ->save();
});

it('can update tags', function () {
    $audio = Audio::get(MP3_WRITER);
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
        ->failOnErrors()
        ->save();

    $audio = Audio::get(MP3_WRITER);
    testMp3Writed($audio);
});

// it('can update tags manually', function () {
//     $audio = Audio::get(MP3_WRITER);
//     testMp3Writer($audio);

//     $audio->update()
//         ->tags([
//             'title' => 'New Title',
//             'artist' => 'New Artist',
//             'album' => 'New Album',
//             'genre' => 'New Genre',
//             'year' => '2022',
//             'track_number' => '2/10',
//             'band' => 'New Album Artist',
//             'comment' => 'New Comment',
//             'composer' => 'New Composer',
//             'part_of_a_set' => '2/2',
//             'part_of_a_compilation' => false,
//             'unsynchronised_lyric' => 'New Lyrics',
//             'language' => 'en',
//             'copyright' => 'New Copyright',
//             'text' => 'New Text',
//         ])
//         ->save();

//     $audio = Audio::get(MP3_WRITER);
//     testMp3Writed($audio);
// });

// it('can update file', function (string $path) {
//     $audio = Audio::get($path);
//     $random = (string) rand(1, 1000);
//     $tag = $audio->update()
//         ->title($random)
//         ->artist('New Artist')
//         ->album('New Album')
//         ->genre('New Genre')
//         ->year('2022')
//         ->trackNumber('2/10')
//         ->albumArtist('New Album Artist')
//         ->comment('New Comment')
//         ->composer('New Composer')
//         ->creationDate('2021-01-01')
//         ->description('New Description')
//         ->discNumber('2/2')
//         ->encodingBy('New Encoding By')
//         ->encoding('New Encoding')
//         ->isNotCompilation()
//         ->lyrics('New Lyrics')
//         ->stik('New Stik')
//         ->cover(FOLDER);

//     $core = $tag->getCore();
//     $tag->save();

//     $audio = Audio::get($path);

//     expect($audio->getTitle())->toBe($random);
//     expect($audio->getArtist())->toBe('New Artist');
//     expect($audio->getAlbum())->toBe('New Album');
//     expect($audio->getGenre())->toBe('New Genre');
//     expect($audio->getYear())->toBe(2022);
//     expect($audio->getAlbumArtist())->toBe('New Album Artist');
//     expect($audio->getComment())->toBe('New Comment');
//     expect($audio->getComposer())->toBe('New Composer');
//     expect($audio->getDiscNumber())->toBe('2/2');
//     expect($audio->isCompilation())->toBeFalse();

//     expect($audio->getCreationDate())->toBeNull();
//     if ($audio->getFormat() === AudioFormatEnum::mp3) {
//         expect($audio->getDescription())->toBeNull();
//         expect($audio->getEncoding())->toBeNull();
//     }
//     expect($audio->getEncodingBy())->toBeNull();
//     if ($audio->getLyrics()) {
//         expect($audio->getLyrics())->toBe('New Lyrics');
//     }
//     expect($audio->getStik())->toBeNull();

//     if ($audio->getFormat() !== AudioFormatEnum::mp3) {
//         expect($audio->getTrackNumber())->toBe('2/10');
//     } else {
//         expect($audio->getTrackNumber())->toBe('2');
//     }

//     if ($tag->getCore()->hasCover()) {
//         $content = file_get_contents(FOLDER);
//         expect($tag->getCore()->getCover()->data())->toBe(base64_encode($content));
//     }
// })->with(AUDIO_WRITER);

// it('can read use file content as cover', function (string $path) {
//     $audio = Audio::get($path);

//     $tag = $audio->update()
//         ->cover(file_get_contents(FOLDER));

//     $tag->save();

//     $audio = Audio::get($path);

//     $content = file_get_contents(FOLDER);
//     expect($tag->getCore()->getCover()->data())->toBe(base64_encode($content));
// })->with([MP3_WRITER]);

// it('can read use tags', function (string $path) {
//     $audio = Audio::get($path);

//     $random = (string) rand(1, 1000);
//     $image = getimagesize(FOLDER);
//     $coverData = file_get_contents(FOLDER);
//     $coverPicturetypeid = $image[2];
//     $coverDescription = 'cover';
//     $coverMime = $image['mime'];
//     $tag = $audio->update()
//         ->tags([
//             'title' => $random,
//             'attached_picture' => [
//                 [
//                     'data' => $coverData,
//                     'picturetypeid' => $coverPicturetypeid,
//                     'description' => $coverDescription,
//                     'mime' => $coverMime,
//                 ],
//             ],
//         ]);

//     $tag->save();

//     $audio = Audio::get($path);
//     expect($audio->getTitle())->toBe($random);

//     $content = file_get_contents(FOLDER);
//     expect($audio->getCover()->getContents())->toBe($content);
// })->with([MP3_WRITER]);

// it('can update use tags with tag formats', function (string $path) {
//     $audio = Audio::get($path);

//     $random = (string) rand(1, 1000);
//     $tag = $audio->update()
//         ->tags([
//             'title' => $random,
//         ])
//         ->tagFormats(['id3v1', 'id3v2.4']);

//     $tag->save();

//     $audio = Audio::get($path);
//     expect($audio->getTitle())->toBe($random);
// })->with([MP3_WRITER]);

// it('can update with tags and handle native metadata', function (string $path) {
//     $audio = Audio::get($path);

//     $tag = $audio->update()
//         ->isCompilation()
//         ->tags([
//             'title' => 'New Title',
//             'band' => 'New Band',
//         ])
//         ->tagFormats(['id3v1', 'id3v2.4']);

//     $tag->save();

//     $audio = Audio::get($path);
//     expect($audio->getTitle())->toBe('New Title');
//     expect($audio->getAlbumArtist())->toBe('New Band');
//     expect($audio->isCompilation())->toBeTrue();
// })->with([MP3_WRITER]);

// it('can update with new path', function (string $path) {
//     $audio = Audio::get($path);
//     $newPath = 'tests/output/new.mp3';

//     $tag = $audio->update()
//         ->title('New Title')
//         ->path($newPath);

//     $tag->save();

//     $audio = Audio::get($newPath);
//     expect($audio->getTitle())->toBe('New Title');
// })->with([MP3_WRITER]);

// it('can update with merged tags and core methods', function (string $path) {
//     $audio = Audio::get($path);

//     $tag = $audio->update()
//         ->title('New Title')
//         ->tags([
//             'title' => 'New Title tag',
//             'band' => 'New Band',
//         ]);

//     $tag->save();

//     $audio = Audio::get($path);
//     expect($audio->getTitle())->toBe('New Title');
//     expect($audio->getAlbumArtist())->toBe('New Band');
// })->with([MP3_WRITER]);

// it('can use arrow function safe with unsupported tags', function (string $path) {
//     $audio = Audio::get($path);

//     $tag = $audio->update()
//         ->title('New Title')
//         ->encoding('New encoding');

//     expect(fn () => $tag->save())->not()->toThrow(Exception::class);

//     $audio = Audio::get($path);
//     expect($audio->getTitle())->toBe('New Title');
// })->with([MP3_WRITER]);

// it('can use arrow function safe with unsupported formats', function (string $path) {
//     $audio = Audio::get($path);

//     $tag = $audio->update()
//         ->title('New Title Alac');

//     expect(fn () => $tag->save())->toThrow(Exception::class);
// })->with([ALAC_WRITER]);

// it('can get core before save', function (string $path) {
//     $audio = Audio::get($path);

//     $tag = $audio->update()
//         ->title('New Title')
//         ->tags([
//             'title' => 'New Title tag',
//             'band' => 'New Band',
//         ]);

//     expect($tag->getCore())->toBeInstanceOf(AudioCore::class);
// })->with([MP3_WRITER]);

// it('can handle exceptions', function (string $path) {
//     $audio = Audio::get($path);

//     $tag = $audio->update()
//         ->tags([
//             'title' => 'New Title',
//             'albumArtist' => 'New Album Artist',
//         ])
//         ->options(['encoding' => 'UTF-8']);

//     expect(fn () => $tag->save())->toThrow(Exception::class);
// })->with([MP3_WRITER]);

// it('can skip exceptions', function (string $path) {
//     $audio = Audio::get($path);

//     $tag = $audio->update()
//         ->tags([
//             'title' => 'New Title',
//             'albumArtist' => 'New Album Artist',
//         ])
//         ->preventFailOnError();

//     $tag->save();

//     $audio = Audio::get($path);
//     expect($audio->getTitle())->toBe('New Title');
//     expect($audio->getAlbumArtist())->toBeNull();
// })->with([MP3_WRITER]);

// it('can remove old tags', function (string $path) {
//     $audio = Audio::get($path);

//     $tag = $audio->update()
//         ->title('New Title')
//         ->removeOldTags()
//         ->path('tests/output/new.mp3');

//     $tag->save();

//     $audio = Audio::get('tests/output/new.mp3');
//     expect($audio->getTitle())->toBe('New Title');
//     expect($audio->getAlbumArtist())->toBeNull();
// })->with([MP3]);

// it('can use tags with cover', function (string $path) {
//     $audio = Audio::get($path);

//     $tag = $audio->update()
//         ->tags([
//             'title' => 'New Title',
//         ])
//         ->cover(FOLDER);

//     $tag->save();

//     $audio = Audio::get($path);

//     $content = file_get_contents(FOLDER);
//     expect($audio->getTitle())->toBe('New Title');
//     expect($tag->getCore()->getCover()->data())->toBe(base64_encode($content));
// })->with([MP3_WRITER]);

// it('can change podcast description and language', function () {
//     $audio = Audio::get(AUDIOBOOK);
//     $tag = $audio->update()
//         ->title('New Title')
//         ->podcastDescription('New Podcast Description')
//         ->language('New Language')
//         ->save();
// });

// it('can not override tags', function (string $path) {
//     $audio = Audio::get($path);

//     $tag = $audio->update()
//         ->getTitle('New Title')
//         ->notOverrideTags()
//         ->path('tests/output/new.mp3');

//     $tag->save();

//     $audio = Audio::get('tests/output/new.mp3');
//     expect($audio->getTitle())->toBe('Introduction');
// })->with([MP3]);
