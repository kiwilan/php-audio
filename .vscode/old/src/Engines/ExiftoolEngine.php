<?php

namespace Kiwilan\Audio\Engines;

use Kiwilan\Audio\Core\AudioException;
use Kiwilan\Audio\Utils\AudioProcess;

class ExiftoolEngine extends AudioEngine
{
    public static function read(string $path)
    {
        $self = new self;

        if (! AudioProcess::isCommandAvailable('exiftool -v')) {
            throw new AudioException('`exiftool` binary not available.');
        }

        $output = AudioProcess::execute([
            'exiftool', '-json',
            $path,
        ]);
        $data = json_decode($output, true);

        return $data;
    }

    public function toArray(): array
    {
        throw new \Exception('Not implemented');
    }

    public function getCover(string $path)
    {
        $cover = shell_exec('exiftool -b -Picture '.escapeshellarg($path));

        file_put_contents('cover.jpg', $cover);
    }
}
