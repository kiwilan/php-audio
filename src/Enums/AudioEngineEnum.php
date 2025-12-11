<?php

namespace Kiwilan\Audio\Enums;

enum AudioEngineEnum: string
{
    case getid3 = 'getid3';
    case ffmpeg = 'ffmpeg';
    case exiftool = 'exiftool';
}
