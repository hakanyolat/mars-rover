<?php

namespace App\Repositories;

use App\Models\Plateau;
use Illuminate\Database\Eloquent\Collection;

class PlateauRepository {

    /**
     * @param array $data
     * @return Plateau
     */
    public function create(array $data): Plateau {
        $plateau = new Plateau();
        $plateau->width = $data['x'];
        $plateau->height = $data['y'];
        $plateau->save();
        return $plateau;
    }

    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return Plateau::all();
    }

    /**
     * @param int $id
     * @return Plateau|null
     */
    public function find(int $id): ?Plateau
    {
        return Plateau::find($id);
    }

    /**
     * @param Plateau $plateau
     * @param array $data
     * @return Plateau|null
     */
    public function update(Plateau $plateau, array $data): ?Plateau
    {
        $plateau->width = $data['x'];
        $plateau->height = $data['y'];
        $plateau->save();
        return $plateau;
    }

    /**
     * @param Plateau $plateau
     */
    public function delete(Plateau $plateau)
    {
        $plateau->delete();
    }
}
