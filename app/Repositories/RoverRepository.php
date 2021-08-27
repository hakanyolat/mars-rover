<?php

namespace App\Repositories;

use App\Models\Rover;
use Illuminate\Database\Eloquent\Collection;

class RoverRepository {
    /**
     * @param array $data
     * @return Rover
     */
    public function create(array $data): Rover {
        $rover = new Rover();
        $rover->x = $data['x'];
        $rover->y = $data['y'];
        $rover->direction = $data['direction'];
        $rover->plateau_id = $data['plateau_id'];
        $rover->save();
        return $rover;
    }

    /**
     * @return Collection
     */
    public function findAll(): Collection {
        return Rover::all();
    }

    /**
     * @param int $id
     * @return Rover|null
     */
    public function find(int $id): ?Rover {
        return Rover::find($id);
    }

    /**
     * @param Rover $rover
     * @param array $data
     * @return Rover|null
     */
    public function update(Rover $rover, array $data): ?Rover {
        $rover->x = $data['x'];
        $rover->y = $data['y'];
        $rover->direction = $data['direction'];
        $rover->plateau_id = $data['plateau_id'];
        $rover->save();
        return $rover;
    }

    /**
     * @param Rover $rover
     */
    public function delete(Rover $rover)
    {
        $rover->delete();
    }

    /**
     * @param Rover $rover
     * @param string $queue
     * @return Rover|null
     */
    public function interrupt(Rover $rover, string $queue): ?Rover {
        $rover->queue = $queue;
        $rover->state = Rover::Interrupted;
        $rover->save();
        return $rover;
    }

    /**
     * @param Rover $rover
     * @param int $x
     * @param int $y
     * @return Rover|null
     */
    public function move(Rover $rover, int $x, int $y): ?Rover {
        $rover->x = $x;
        $rover->y = $y;
        $rover->save();
        return $rover;
    }

    /**
     * @param Rover $rover
     * @param string $direction
     * @return Rover|null
     */
    public function rotate(Rover $rover, string $direction): ?Rover {
        $rover->direction = $direction;
        $rover->save();
        return $rover;
    }

    /**
     * @param Rover $rover
     * @return Rover|null
     */
    public function stop(Rover $rover): ?Rover {
        $rover->state = Rover::Ready;
        $rover->queue = null;
        $rover->save();
        return $rover;
    }
}
