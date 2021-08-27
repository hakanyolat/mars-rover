<?php

namespace App\Helpers;

class Position {
    public int $x;
    public int $y;

    public function __construct(int $x, int $y) {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @param string $direction
     * @return $this
     */
    public function getForward(string $direction): self {
        return match ($direction) {
            Direction::North => new Position($this->x, $this->y + 1),
            Direction::South => new Position($this->x, $this->y - 1),
            Direction::East => new Position($this->x + 1, $this->y),
            Direction::West => new Position($this->x - 1, $this->y),
            default => $this
        };
    }
}
