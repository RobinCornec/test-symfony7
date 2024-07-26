<?php

namespace App\PandaScore\Enum;

enum WinnerTypeEnum
{
    case Player;
    case Team;

    public static function from(mixed $winnerType): ?WinnerTypeEnum
    {
        return match ($winnerType) {
            'Player' => self::Player,
            'Team' => self::Team,
            default => null
        };
    }
}
