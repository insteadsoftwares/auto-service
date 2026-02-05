<?php

namespace App\Http\Controllers;

use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Repositories\Role\RoleRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private $userRepo;
	private $roleRepo;

    public function __construct(UserRepository $userRepository, RoleRepository $roleRepository)
    {
        $this->userRepo = $userRepository;
		$this->roleRepo = $roleRepository;
    }

	/**
     * Get paginated list of admin users.
     *
     * @param Request $request
     * @return object Paginated admins list with total count
     */
    public function getAdmins(Request $request)
    {
        return $this->userRepo->searchAndPaginate($request['searchQuery'], $request['perPage'], $request['page'], $request['sortBy'], $request['sortDesc'], 'admin');
    }

	/**
     * Create a new user with a specific role.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
	public function addminAddUserWithRole(Request $request)
    {
		$validator = Validator::make($request->all(), [
            'role' => 'required|string',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:25',
            'email' => 'required|email|unique:aus_users,email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

		$role = $this->roleRepo->getRoleByName($request->role);
		if (!$role) {
			return response()->json([
				'success' => false,
				'message' => 'Rôle introuvable'
			], 404);
		}

        $user = User::create([
            'role_id'  => $role->id,
            'first_name'  => $request->first_name,
            'last_name'   => $request->last_name,
            'address'     => $request->address,
            'phone_number'=> $request->phone_number,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Inscription réussie',
            'user' => $user
        ], 201);
    }

	/**
     * Get paginated list of technician users.
     *
     * @param Request $request
     * @return object Paginated technicians list with total count
     */
    public function getTechnicians(Request $request)
    {
        return $this->userRepo->searchAndPaginate($request['searchQuery'], $request['perPage'], $request['page'], $request['sortBy'], $request['sortDesc'], 'technician');
    }

	/**
     * Get technicians (users with role "technician") who do not have a garage.
     *
     * @return User[]
     */
    public function getTechniciansWithoutGarage(Request $request)
    {
        return $this->userRepo->getTechniciansWithoutGarage();
    }
	
	/**
     * Edit a user.
     *
     * @param Request $request
     */
    public function editUser(Request $request)
    {
        $user = $this->userRepo->getById($request['id']);

        Validator::make($request->all(), [
            'email' => [
				'required',
        		'email',
				Rule::unique('aus_users', 'email')->ignore($request->id),
			],
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:25',
        ])->validate();

		$password = $request['password'] != null ? Hash::make($request['password']) : null;
        return $this->userRepo->edit($user, $request['first_name'], $request['last_name'], $request['address'], $request['phone_number'], $request['email'], $password);
    }

	/**
     * Delete user.
     *
     * @param Request $request
     */
    public function deleteUser(Request $request)
    {
        return $this->userRepo->delete($request['id']);
    }

}
