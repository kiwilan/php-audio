<?php

namespace Kiwilan\Audio\Engines;

use Kiwilan\Audio\Utils\AudioProcess;

class ExiftoolEngine
{
    public static function read(string $path)
    {
        $self = new self;

        if (! AudioProcess::isCommandAvailable('exiftool -v')) {
            throw new \Exception('`kiwilan/php-audio` with `exiftool` or `exiftool` not available.');
        }

        if (! file_exists($path)) {
            throw new \Exception("`kiwilan/php-audio` with `exiftool`: file at {$path} doesn't exists.");
        }

        $output = AudioProcess::execute([
            'exiftool', '-json',
            $path,
        ]);
        $data = json_decode($output, true);

        return $data;
    }

    public function getCover(string $path)
    {
        $cover = shell_exec('exiftool -b -Picture '.escapeshellarg($path));

        file_put_contents('cover.jpg', $cover);
    }
}
