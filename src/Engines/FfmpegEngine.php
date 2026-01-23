<?php

namespace Kiwilan\Audio\Engines;

use Kiwilan\Audio\Utils\AudioProcess;

class FfmpegEngine extends AudioEngine
{
    public static function handle(string $path)
    {
        $self = new self;

        $self->process = AudioProcess::execute([
            'ffprobe',
            '-v', 'quiet',
            '-print_format', 'json',
            '-show_format',
            '-show_streams',
            $path,
        ]);
        if (! $self->process->isSuccessful()) {
            return $self;
        }

        $self->output = $self->toAssociativeArray($self->process->getOutput());

        return $self;
    }

    public function tags(): ?array
    {
        $format = $this->output['format'] ?? null;
        $tags = $format['tags'] ?? null;

        if (! $tags) {
            // If tags are in `streams[0][tags]`
            $stream_audio = $this->output['streams'][0] ?? null;
            $tags = $stream_audio['tags'] ?? null;
        }

        return $tags;
    }

    public function mapping(): array
    {
        return [
            'album' => 'album',
            'album_artist' => 'album_artist',
            'artist' => 'artist',
            'comment' => 'comment',
            'composer' => 'composer',
            'copyright' => 'copyright',
            'description' => [
                'description',
                'PODCASTDESC',
                'comment',
            ],
            'disc' => 'disc',
            'compilation' => 'compilation',
            'encoder' => [
                'encoder',
                'ENCODERSETTINGS',
                'ENCODER_SETTINGS',
            ],
            'encoded_by' => [
                'encoded_by',
                'encoder',
            ],
            'genre' => 'genre',
            'language' => 'language',
            'lyrics' => 'lyrics',
            'synopsis' => [
                'TDES',
                'synopsis',
            ],
            'title' => 'title',
            'track' => 'track',
            'date' => [
                'TDRC',
                'TYER',
                'TDAT',
                'date',
            ],
            'subtitle' => [
                'TIT3',
                'subtitle',
            ],
            'publisher' => [
                'publisher',
                'ORGANIZATION',
            ],
            'asin' => 'ASIN',
            'isbn' => 'ISBN',
            'series' => 'SERIES',
            'series_part' => 'SERIES-PART',
        ];
    }

    // public function getMetadata($filePath)
    // {
    //     // Échapper le chemin pour la sécurité
    //     $filePathEscaped = escapeshellarg($filePath);

    //     // Commande ffprobe
    //     $cmd = "ffprobe -v quiet -print_format json -show_format -show_streams $filePathEscaped";

    //     // Exécution
    //     $output = shell_exec($cmd);

    //     // Transformation en tableau PHP
    //     return json_decode($output, true);
    // }

    // public function mp3HasCover(string $filePath): bool
    // {
    //     $file = escapeshellarg($filePath);

    //     $cmd = "ffprobe -v quiet -print_format json -show_streams $file";
    //     $json = shell_exec($cmd);
    //     $data = json_decode($json, true);

    //     if (! isset($data['streams'])) {
    //         return false;
    //     }

    //     foreach ($data['streams'] as $stream) {
    //         if (
    //             isset($stream['disposition']['attached_pic'])
    //             && $stream['disposition']['attached_pic'] == 1
    //         ) {
    //             return true;
    //         }
    //     }

    //     return false;
    // }

    // public function mp3ExtractRawCover(string $filePath, string $outputTempPath): bool
    // {
    //     $file = escapeshellarg($filePath);
    //     $output = escapeshellarg($outputTempPath);

    //     // -map 0:v sélectionne l’image ID3
    //     $cmd = "ffmpeg -i $file -an -vcodec copy -map 0:v -y $output 2>&1";
    //     shell_exec($cmd);

    //     return file_exists($output);
    // }

    // public function extractCoverWithMime(string $mp3Path, string $outputDirectory): ?string
    // {
    //     if (! mp3HasCover($mp3Path)) {
    //         return null;
    //     }

    //     // Fichier temporaire sans extension
    //     $temp = rtrim($outputDirectory, '/').'/cover_tmp';

    //     if (! mp3ExtractRawCover($mp3Path, $temp)) {
    //         return null;
    //     }

    //     // Détecter le mimetype
    //     $mime = mime_content_type($temp);

    //     // Choix de l’extension
    //     $ext = match ($mime) {
    //         'image/jpeg' => 'jpg',
    //         'image/png' => 'png',
    //         default => null,
    //     };

    //     if ($ext === null) {
    //         // Type inconnu
    //         unlink($temp);

    //         return null;
    //     }

    //     $finalPath = rtrim($outputDirectory, '/')."/cover.$ext";

    //     // Déplacer avec la bonne extension
    //     rename($temp, $finalPath);

    //     return $finalPath;
    // }

}
