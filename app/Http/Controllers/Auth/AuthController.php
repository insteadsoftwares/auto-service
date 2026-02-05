<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Role\RoleRepository;
use Illuminate\Support\Str;

class AuthController extends Controller
{
	private $roleRepo;

	/**
     * Constructor
     * 
     * @param RoleRepository $roleRepository
     * Initializes the RoleRepository for role-related operations.
     */
	public function __construct(RoleRepository $roleRepository) {
        $this->roleRepo = $roleRepository;
    }

	/**
     * Authenticate an admin user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * Validates credentials and ensures the user has the "admin" role.
     * Returns user data and an authentication token on success.
     */
    public function loginAdmin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Email ou mot de passe incorrect'
            ], 401);
        }

        $user = Auth::user();

        if ($user->role->name !== 'admin' && $user->role->name !== 'super_admin') {
            Auth::logout(); 
            return response()->json([
                'message' => 'Vous n’êtes pas autorisé à accéder à cette interface'
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie',
            'user' => $user,
            'token' => $token,
        ]);
    }
  
	/**
     * Authenticate a non-admin user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * Validates credentials and ensures the user is not an admin.
     * Returns user data and an authentication token on success.
     */
	public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Email ou mot de passe incorrect'
            ], 401);
        }

        $user = Auth::user();

        if ($user->role->name == 'admin') {
            Auth::logout(); 
            return response()->json([
                'message' => 'Vous n’êtes pas autorisé à accéder à cette interface'
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie',
            'user' => $user,
            'token' => $token,
        ]);
    }

	/**
     * Logout the authenticated user by deleting all tokens.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * Deletes all tokens associated with the authenticated user.
     */
	public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie'
        ]);
    }

	/**
     * Register a new user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * Validates user input, assigns the requested role, and creates a new user.
     * Returns the created user and success status.
     */
	public function register(Request $request)
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
     * Register a new user via Google OAuth.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * Validates input, assigns the default "client" role, creates a user, and generates an auth token.
     */
	public function registerGoogle(Request $request)
	{
		$validator = Validator::make($request->all(), [
            'role' => 'required|string',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:25',
            'email' => 'required|email|unique:aus_users,email',
			'google_id' => 'required|string|unique:aus_users,google_id',
		]);

		if ($validator->fails()) {
			return response()->json([
				'success' => false,
				'errors' => $validator->errors()
			], 422);
		}

		$role = $this->roleRepo->getRoleByName('client');
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
			'google_id' => $request->google_id,
			'password' => Hash::make(Str::random(20))
		]);

		$token = $user->createToken('auth_token')->plainTextToken;

		return response()->json([
			'success' => true,
			'message' => 'Inscription via Google réussie',
			'user' => $user,
			'token' => $token
		], 201);
	}

	/**
     * Login a user via Google OAuth.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * Validates input and authenticates the user using Google ID.
     * Returns user data and authentication token on success.
     */
	public function loginGoogle(Request $request)
	{
		$request->validate([
			'email' => 'required|email',
			'google_id' => 'required|string',
		]);

		$user = User::where('google_id', $request->google_id)->first();

		if (!$user) {
			return response()->json([
				'success' => false,
				'message' => 'Aucun utilisateur trouvé avec ce compte Google.'
			], 404);
		}

		$token = $user->createToken('auth_token')->plainTextToken;

		return response()->json([
			'success' => true,
			'message' => 'Connexion Google réussie',
			'user' => $user,
			'token' => $token
		]);
	}

}
