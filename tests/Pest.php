<?php

define('MP3_NO_META', __DIR__.'/media/test-no-meta.mp3');
define('AUDIOBOOK', __DIR__.'/media/audiobook.m4b');
define('MD', __DIR__.'/media/test.md');
define('FOLDER', __DIR__.'/media/folder.jpg');

function addWriterFilesForTests()
{
    $files = glob('./tests/media/*');
    foreach ($files as $file) {
        if (is_file($file) && str_contains($file, 'writer')) {
            unlink($file);
        }
    }

    $files = glob('./tests/media/*');
    foreach ($files as $file) {
        $basename = pathinfo($file, PATHINFO_BASENAME);
        if (is_file($file) && str_contains($basename, 'test')) {
            $writer = str_replace('test', 'test-writer', $basename);
            $writer = str_replace($basename, $writer, $file);
            copy($file, $writer);
        }
    }
}
addWriterFilesForTests();
if (PHP_OS_FAMILY === 'Windows') {
    sleep(1);
}

define('ALAC_WRITER', __DIR__.'/media/test-writer-alac.m4a');
define('AAC_WRITER', __DIR__.'/media/test-writer.aac');
define('AIF_WRITER', __DIR__.'/media/test-writer.aif');
define('AIFC_WRITER', __DIR__.'/media/test-writer.aifc');
define('AIFF_WRITER', __DIR__.'/media/test-writer.aiff');
define('FLAC_WRITER', __DIR__.'/media/test-writer.flac');
define('M4A_WRITER', __DIR__.'/media/test-writer.m4a');
define('M4B_WRITER', __DIR__.'/media/test-writer.m4b');
define('M4V_WRITER', __DIR__.'/media/test-writer.m4v');
define('MKA_WRITER', __DIR__.'/media/test-writer.mka');
define('MKV_WRITER', __DIR__.'/media/test-writer.mkv');
define('MP3_WRITER', __DIR__.'/media/test-writer.mp3');
define('MP4_WRITER', __DIR__.'/media/test-writer.mp4');
define('OGG_WRITER', __DIR__.'/media/test-writer.ogg');
define('OPUS_WRITER', __DIR__.'/media/test-writer.opus');
define('SPX_WRITER', __DIR__.'/media/test-writer.spx');
define('TTA_WRITER', __DIR__.'/media/test-writer.tta');
define('WAV_WRITER', __DIR__.'/media/test-writer.wav');
define('WEBM_WRITER', __DIR__.'/media/test-writer.webm');
define('WMA_WRITER', __DIR__.'/media/test-writer.wma');
define('WV_WRITER', __DIR__.'/media/test-writer.wv');

define('ALAC', __DIR__.'/media/test-alac.m4a');
define('AAC', __DIR__.'/media/test.aac');
define('AIF', __DIR__.'/media/test.aif');
define('AIFC', __DIR__.'/media/test.aifc');
define('AIFF', __DIR__.'/media/test.aiff');
define('FLAC', __DIR__.'/media/test.flac');
define('M4A', __DIR__.'/media/test.m4a');
define('M4B', __DIR__.'/media/test.m4b');
define('M4V', __DIR__.'/media/test.m4v');
define('MKA', __DIR__.'/media/test.mka');
define('MKV', __DIR__.'/media/test.mkv');
define('MP3', __DIR__.'/media/test.mp3');
define('MP4', __DIR__.'/media/test.mp4');
define('OGG', __DIR__.'/media/test.ogg');
define('OPUS', __DIR__.'/media/test.opus');
define('SPX', __DIR__.'/media/test.spx');
define('TTA', __DIR__.'/media/test.tta');
define('WAV', __DIR__.'/media/test.wav');
define('WEBM', __DIR__.'/media/test.webm');
define('WMA', __DIR__.'/media/test.wma');
define('WV', __DIR__.'/media/test.wv');

define('AUDIO', [
    ALAC,
    AIF,
    AIFC,
    AIFF,
    FLAC,
    M4A,
    M4B,
    M4V,
    MKA,
    MKV,
    MP3,
    // MP4,
    OGG,
    OPUS,
    SPX,
    TTA,
    WAV,
    WEBM,
    WMA,
    WV,
]);

define('AUDIO_WRITER', [
    // ALAC_WRITER,
    // AIF_WRITER,
    // AIFC_WRITER,
    // AIFF_WRITER,
    FLAC_WRITER,
    // M4A_WRITER,
    // M4B_WRITER,
    // M4V_WRITER,
    // MKA_WRITER,
    // MKV_WRITER,
    MP3_WRITER,
    // MP4_WRITER,
    OGG_WRITER,
    // OPUS_WRITER,
    // SPX_WRITER,
    // TTA_WRITER,
    // WAV_WRITER,
    // WEBM_WRITER,
    // WMA_WRITER,
    // WV_WRITER,
]);
