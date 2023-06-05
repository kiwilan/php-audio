<?php

namespace Kiwilan\Audio\Enums;

enum AudioTypeEnum: string
{
    case id3 = 'id3';
    case vorbiscomment = 'vorbiscomment';
    case quicktime = 'quicktime';
    case matroska = 'matroska';
    case ape = 'ape';
    case asf = 'asf';
}
