<?php

namespace Kiwilan\Audio\Engines;

use Kiwilan\Audio\Core\AudioCore;
use Kiwilan\Audio\Utils\AudioProcess;

class Id3Engine extends AudioEngine
{
    public static function read(string $path)
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
        $self->json = json_encode($self->output, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return $self;
    }

    public function toArray(): array
    {
        throw new \Exception('Not implemented');
    }

    public function toAudioCore(): ?AudioCore
    {
        $tags = $this->output['format']['tags'] ?? null;
        if (! $tags) {
            return null;
        }

        return new AudioCore(
            album: $tags['album'] ?? null,
            album_artist: $tags['album_artist'] ?? null,
            artist: $tags['artist'] ?? null,
            comment: $tags['comment'] ?? null,
            composer: $tags['composer'] ?? null,
            copyright: null,
            cover: null,
            creation_date: null,
            description: null,
            disc_number: array_key_exists('disc', $tags)
                ? strval($tags['disc'])
                : null,
            has_cover: false,
            is_compilation: array_key_exists('compilation', $tags)
                ? boolval($tags['compilation'])
                : false,
            encoding: null,
            encoding_by: null,
            genre: $tags['genre'] ?? null,
            language: null,
            lyrics: null,
            synopsis: null,
            title: $tags['title'] ?? null,
            track_number: array_key_exists('track_number', $tags)
                ? strval($tags['track_number'])
                : null,
            year: array_key_exists('year', $tags)
                ? intval($tags['year'])
                : null,
        );
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
