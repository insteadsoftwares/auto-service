<?php

namespace App\Repositories\User;

interface UserRepository
{
    
	/**
     * paginates User by role
     *
     * @param $searchQuery: string
     * @param $perPage: integer
     * @param $page: integer
     * @param $sortBy: string
     * @param $sortDesc: boolean
     * @param $role: string
     * @return User[]
     */
	public function searchAndPaginate($searchQuery, $perPage, $page, $sortBy, $sortDesc, $role);

	/**
     * Get technicians (users with role "technician") who do not have a garage
     *
     * @return User[]
     */
	public function getTechniciansWithoutGarage();

    /**
     * Finds user by id.
     *
     * @return User
     */
    public function getById($id, $relations = []);

	/**
     * edit a user
     *
     * @param $user: User
     * @param $first_name: string
     * @param $last_name: string
     * @param $address: string
     * @param $phone_number: string
     * @param $email: string
     * @param $password: string
     * @return Service
     */
	public function edit($user, $first_name, $last_name, $address, $phone_number, $email, $password);

	/**
     * Deletes user
     *
     * @param $user_id: int
     */
    public function delete($user_id);
	
	/**
     * Find clients number.
     *
	 * @return int
     */
    public function getCLientNumber();

}
