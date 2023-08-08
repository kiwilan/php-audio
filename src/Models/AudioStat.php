<?php

namespace Kiwilan\Audio\Models;

use DateTime;

class AudioStat
{
    protected function __construct(
        protected string $path,
        protected ?int $deviceNumber = null,
        protected ?int $inodeNumber = null,
        protected ?int $inodeProtectionMode = null,
        protected ?int $numberOfLinks = null,
        protected ?int $userId = null,
        protected ?int $groupId = null,
        protected ?int $deviceType = null,
        protected ?int $size = null,
        protected ?DateTime $lastAccessAt = null,
        protected ?DateTime $createdAt = null,
        protected ?DateTime $modifiedAt = null,
        protected ?int $blockSize = null,
        protected ?int $numberOfBlocks = null,
    ) {
    }

    public static function make(string $path): self
    {
        $self = new self($path);
        $stat = stat($path);

        $self->deviceNumber = $stat['dev'] ?? null;
        $self->inodeNumber = $stat['ino'] ?? null;
        $self->inodeProtectionMode = $stat['mode'] ?? null;
        $self->numberOfLinks = $stat['nlink'] ?? null;
        $self->userId = $stat['uid'] ?? null;
        $self->groupId = $stat['gid'] ?? null;
        $self->deviceType = $stat['rdev'] ?? null;
        $self->size = $stat['size'] ?? null;
        $self->lastAccessAt = $stat['atime'] ? new DateTime('@'.$stat['atime']) : null;
        $self->createdAt = $stat['ctime'] ? new DateTime('@'.$stat['ctime']) : null;
        $self->modifiedAt = $stat['mtime'] ? new DateTime('@'.$stat['mtime']) : null;
        $self->blockSize = $stat['blksize'] ?? null;
        $self->numberOfBlocks = $stat['blocks'] ?? null;

        return $self;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getDeviceNumber(): ?int
    {
        return $this->deviceNumber;
    }

    public function getInodeNumber(): ?int
    {
        return $this->inodeNumber;
    }

    public function getInodeProtectionMode(): ?int
    {
        return $this->inodeProtectionMode;
    }

    public function getNumberOfLinks(): ?int
    {
        return $this->numberOfLinks;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getGroupId(): ?int
    {
        return $this->groupId;
    }

    public function getDeviceType(): ?int
    {
        return $this->deviceType;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function getLastAccessAt(): ?DateTime
    {
        return $this->lastAccessAt;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function getModifiedAt(): ?DateTime
    {
        return $this->modifiedAt;
    }

    public function getBlockSize(): ?int
    {
        return $this->blockSize;
    }

    public function getNumberOfBlocks(): ?int
    {
        return $this->numberOfBlocks;
    }

    public function toArray(): array
    {
        return [
            'path' => $this->path,
            'deviceNumber' => $this->deviceNumber,
            'inodeNumber' => $this->inodeNumber,
            'inodeProtectionMode' => $this->inodeProtectionMode,
            'numberOfLinks' => $this->numberOfLinks,
            'userId' => $this->userId,
            'groupId' => $this->groupId,
            'deviceType' => $this->deviceType,
            'size' => $this->size,
            'lastAccessAt' => $this->lastAccessAt?->format('Y-m-d H:i:s'),
            'createdAt' => $this->createdAt?->format('Y-m-d H:i:s'),
            'modifiedAt' => $this->modifiedAt?->format('Y-m-d H:i:s'),
            'blockSize' => $this->blockSize,
            'numberOfBlocks' => $this->numberOfBlocks,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function __toString(): string
    {
        return $this->toJson();
    }
}
