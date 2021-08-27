<?php

namespace App\Helpers;

class Direction {
    const North = 'N';
    const West = 'W';
    const South = 'S';
    const East = 'E';

    const All = [
        self::North,
        self::West,
        self::South,
        self::East
    ];

    /**
     * @param string $face
     * @return string
     */
    public static function toRight(string $face): string {
        $len = count(self::All);
        $current = array_search($face, self::All);
        $left = $current === 0 ? ($len - 1) : $current - 1;
        return self::All[$left];
    }

    /**
     * @param string $face
     * @return string
     */
    public static function toLeft(string $face): string {
        $len = count(self::All);
        $current = array_search($face, self::All);
        $right = $current === ($len - 1) ? 0 : $current + 1;
        return self::All[$right];
    }

    /**
     * @param string $face
     * @return bool
     */
    public static function validate(string $face): bool {
        return in_array($face, self::All);
    }
}
