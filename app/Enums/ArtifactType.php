<?php

namespace App\Enums;

enum ArtifactType: int
{
    case ARTIFACT_5 = 5;
    case ARTIFACT_7 = 7;
    case ARTIFACT_10 = 10;
    case ARTIFACT_12 = 12;
    case ARTIFACT_15 = 15;
    case ARTIFACT_17 = 17;
    case ARTIFACT_20 = 20;
    case ARTIFACT_25 = 25;
    case ARTIFACT_30 = 30;

    public static function getAllArtifacts(): array
    {
        return [
            self::ARTIFACT_5,
            self::ARTIFACT_7,
            self::ARTIFACT_10,
            self::ARTIFACT_12,
            self::ARTIFACT_15,
            self::ARTIFACT_17,
            self::ARTIFACT_20,
            self::ARTIFACT_25,
            self::ARTIFACT_30
        ];
    }
}
