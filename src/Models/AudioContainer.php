<?php

namespace Kiwilan\Audio\Models;

use DateTime;
use Kiwilan\Audio\Enums\AudioFormatEnum;

/**
 * Represents audio container from system,
 * like `path`, `size`, `extension`...
 */
class AudioContainer
{
    protected function __construct(
        protected string $path, // /php-audio/tests/media/test-the-wall.mp3
        protected string $extension, // mp3
        protected string $filename, // test-the-wall
        protected string $basename, // test-the-wall.mp3
        protected int $inode, // 23280300
        protected int $size, // 321540
        protected \DateTime $access_time, // 2026-01-22 10:12:00
        protected \DateTime $modification_time, // 2026-01-22 07:49:33
        protected \DateTime $change_time, // 2026-01-22 07:49:33
        protected string $type, // file
        protected bool $writable, // true
        protected bool $readable, // true
        protected bool $is_file, // true
        protected bool $is_directory, // false
        protected bool $is_link, // false
        protected AudioFormatEnum $format, // AudioFormatEnum::mp3
    ) {}

    public static function handle(string $path)
    {
        $fileinfo = new \SplFileInfo($path);
        $extension = $fileinfo->getExtension();
        $filename = pathinfo($fileinfo->getFilename(), PATHINFO_FILENAME);

        $self = new self(
            path: $fileinfo->getPathname(),
            extension: $extension,
            filename: $filename,
            basename: $fileinfo->getBasename(),
            inode: $fileinfo->getInode(),
            size: $fileinfo->getSize(),
            access_time: new DateTime,
            modification_time: new DateTime,
            change_time: new DateTime,
            type: $fileinfo->getType(),
            writable: $fileinfo->isWritable(),
            readable: $fileinfo->isReadable(),
            is_file: $fileinfo->isFile(),
            is_directory: $fileinfo->isDir(),
            is_link: $fileinfo->isLink(),
            format: AudioFormatEnum::tryFrom($extension) ?? AudioFormatEnum::unknown,
        );

        $self->access_time = $self->convertTimestamp($fileinfo->getATime());
        $self->modification_time = $self->convertTimestamp($fileinfo->getMTime());
        $self->change_time = $self->convertTimestamp($fileinfo->getCTime());

        return $self;
    }

    /**
     * Get file path, like `/php-audio/tests/media/test-the-wall.mp3`.
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Get file extension, like `mp3`.
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * Get filename, like `test-the-wall`.
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * Get basename, like `test-the-wall.mp3`.
     */
    public function getBasename(): string
    {
        return $this->basename;
    }

    /**
     * Get file inode, like `23280300`.
     */
    public function getInode(): int
    {
        return $this->inode;
    }

    /**
     * Get file size in bytes, like `321540`.
     *
     * To get size human readable, use `getSizeHuman()`
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Get audio file size human-readable, like `3.32 MB`.
     */
    public function getSizeHuman(int $decimals = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $factor = floor((strlen((string) $this->size) - 1) / 3);

        return sprintf("%.{$decimals}f %s", $this->size / (1024 ** $factor), $units[$factor]);
    }

    /**
     * Get access time, like `2026-01-22 10:12:00`.
     */
    public function getAccessTime(): \DateTime
    {
        return $this->access_time;
    }

    /**
     * Get access time, like `2026-01-22 07:49:33`.
     */
    public function getModificationTime(): \DateTime
    {
        return $this->modification_time;
    }

    /**
     * Get access time, like `2026-01-22 07:49:33`.
     */
    public function getChangeTime(): \DateTime
    {
        return $this->change_time;
    }

    /**
     * Get path type, like `file`.
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Check if path is writable.
     */
    public function isWritable(): bool
    {
        return $this->writable;
    }

    /**
     * Check if path is readable.
     */
    public function isReadable(): bool
    {
        return $this->readable;
    }

    /**
     * Check if path is file.
     */
    public function isFile(): bool
    {
        return $this->is_file;
    }

    /**
     * Check if path is directory.
     */
    public function isDirectory(): bool
    {
        return $this->is_directory;
    }

    /**
     * Check if path is link.
     */
    public function isLink(): bool
    {
        return $this->is_link;
    }

    /**
     * Get audio format if recognized, like `AudioFormatEnum::mp3`.
     */
    public function getFormat(): AudioFormatEnum
    {
        return $this->format;
    }

    /**
     * Convert UNIX timestamp to `DateTime`.
     */
    private function convertTimestamp(int $timestamp)
    {
        return new \DateTime("@$timestamp");
    }
}
