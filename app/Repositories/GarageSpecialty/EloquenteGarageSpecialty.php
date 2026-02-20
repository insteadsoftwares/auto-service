<?php

namespace App\Repositories\GarageSpecialty;

use App\Models\GarageSpecialty;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EloquenteGarageSpecialty implements GarageSpecialtyRepository
{
    /**
     * @var GarageSpecialty
     */
    private $model;

    public function __construct(GarageSpecialty $model)
    {
        $this->model = $model;
    }

	/**
     * Finds garage specialty by id.
     *
     * @param $id: int
     * @param $relations: array
     * @return GarageSpecialty
     */
    public function getById($id, $relations = [])
    {
        return $this->model::with($relations)->find($id);
    }

	/**
     * Deletes Garage specialty
     *
     * @param $garage_specialty: GarageSpecialty
     */
    public function delete($garage_specialty)
    {
        return $garage_specialty->delete();
    }

}
