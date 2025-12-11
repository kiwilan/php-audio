<?php

namespace Kiwilan\Audio\Id3\Reader;

class Id3Comments
{
    protected function __construct(
        public readonly ?string $language = null,
        public readonly ?Id3CommentsPicture $picture = null,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $language = $metadata['language'][0] ?? null;
        $picture = Id3CommentsPicture::make($metadata['picture'][0] ?? null);

        $self = new self(
            language: $language,
            picture: $picture,
        );

        return $self;
    }
}
