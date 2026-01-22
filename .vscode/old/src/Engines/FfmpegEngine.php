<?php

namespace Kiwilan\Audio\Engines;

use Kiwilan\Audio\Core\AudioCore;
use Kiwilan\Audio\Utils\AudioProcess;

class FfmpegEngine extends AudioEngine
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
        ray($self->process);
        if (! $self->process->isSuccessful()) {
            return $self;
        }

        $self->output = $self->toAssociativeArray($self->process->getOutput());
        $self->json = json_encode($self->output, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return $self;
    }

    private function extractKey(mixed $data, string $key): mixed
    {
        if (array_key_exists($key, $data)) {
            return $data[$key];
        }

        return null;
    }

    public function toArray(): array
    {
        throw new \Exception('Not implemented');
    }

    public function toAudioCore(): ?AudioCore
    {
        $format = $this->extractKey($this->output, 'format');
        if (! $format) {
            return null;
        }

        $tags = $this->extractKey($format, 'tags');
        if (! $tags) {
            return null;
        }

        return new AudioCore(
            album: $this->extractKey($tags, 'album'),
            album_artist: $this->extractKey($tags, 'album_artist'),
            artist: $this->extractKey($tags, 'artist'),
            comment: $this->extractKey($tags, 'comment'),
            composer: $this->extractKey($tags, 'composer'),
            copyright: $this->extractKey($tags, 'copyright'),
            cover: null,
            creation_date: null,
            description: $this->extractKey($tags, 'DESCRIPTION'),
            disc_number: $this->extractKey($tags, 'disc'),
            has_cover: false,
            is_compilation: $this->extractKey($tags, 'compilation'),
            encoding: $this->extractKey($tags, 'encoder'),
            encoding_by: $this->extractKey($tags, 'encoded_by'),
            genre: $this->extractKey($tags, 'genre'),
            language: $this->extractKey($tags, 'language'),
            lyrics: $this->extractKey($tags, 'LYRICS'),
            synopsis: $this->extractKey($tags, 'TDES'),
            title: $this->extractKey($tags, 'title'),
            track_number: $this->extractKey($tags, 'track'),
            year: $this->extractKey($tags, 'date'),
            subtitle: $this->extractKey($tags, 'TIT3'),
            publisher: $this->extractKey($tags, 'publisher'),
            asin: $this->extractKey($tags, 'ASIN'),
            isbn: $this->extractKey($tags, 'ISBN'),
            series: $this->extractKey($tags, 'SERIES'),
            series_part: $this->extractKey($tags, 'SERIES-PART'),
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
