<?php

namespace App\Repositories\GarageSpecialty;

interface GarageSpecialtyRepository
{

	/**
     * Finds garage specialty by id.
     *
     * @param $id: int
     * @param $relations: array
     * @return GarageSpecialty
     */
    public function getById($id, $relations = []);

	/**
     * Deletes Garage specialty
     *
     * @param $garage_specialty: GarageSpecialty
     */
    public function delete($garage_specialty);

}
