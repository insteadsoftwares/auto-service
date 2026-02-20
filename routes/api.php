<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('loginAdmin', [\App\Http\Controllers\Auth\AuthController::class, 'loginAdmin']);
Route::post('login', [\App\Http\Controllers\Auth\AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout']);
Route::post('/register', [App\Http\Controllers\Auth\AuthController::class, 'register']);
Route::post('/registerGoogle', [App\Http\Controllers\Auth\AuthController::class, 'registerGoogle']);
Route::post('/loginGoogle', [App\Http\Controllers\Auth\AuthController::class, 'loginGoogle']);
Route::post('/password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'reset']);

Route::get('services', [\App\Http\Controllers\ServiceController::class, 'getServices']);
Route::get('vehicleTypes', [\App\Http\Controllers\VehicleTypeController::class, 'getVehicleTypes']);
Route::get('vehicleBrands', [\App\Http\Controllers\VehicleBrandController::class, 'getVehicleBrands']);
Route::get('vehicleModeles', [\App\Http\Controllers\VehicleModeleController::class, 'getVehicleModeles']);
Route::get('garagesByService/{service_id}', [\App\Http\Controllers\GarageController::class, 'getGaragesByService']);
Route::get('upcomingGarageAppointments/{garage_id}', [\App\Http\Controllers\AppointmentController::class, 'getUpcomingGarageAppointments']);
Route::post('contact', [\App\Http\Controllers\ContactController::class, 'contact']);
Route::get('statistics', [\App\Http\Controllers\StatisticController::class, 'getStatistics']);
Route::post('addAppointment', [\App\Http\Controllers\AppointmentController::class, 'addAppointment']);

Route::middleware(['auth:sanctum', 'role:super_admin'])->group(function () {
    Route::get('admins', [\App\Http\Controllers\UserController::class, 'getAdmins']);
    Route::post('addAdmin', [\App\Http\Controllers\UserController::class, 'addminAddUserWithRole']);
	Route::post('editAdmin', [\App\Http\Controllers\UserController::class, 'editUser']);
	Route::delete('deleteAdmin/{id}', [\App\Http\Controllers\UserController::class, 'deleteUser']);
});

Route::middleware(['auth:sanctum', 'role:admin,super_admin'])->group(function () {
	//technician
    Route::get('technicians', [\App\Http\Controllers\UserController::class, 'getTechnicians']);
	Route::post('addTechnician', [\App\Http\Controllers\UserController::class, 'addminAddUserWithRole']);
	Route::get('getTechniciansWithoutGarage', [\App\Http\Controllers\UserController::class, 'getTechniciansWithoutGarage']);
	Route::post('editTechnician', [\App\Http\Controllers\UserController::class, 'editUser']);
	Route::delete('deleteTechnician/{id}', [\App\Http\Controllers\UserController::class, 'deleteUser']);

	//service
	Route::post('addService', [\App\Http\Controllers\ServiceController::class, 'addService']);
	Route::post('editService', [\App\Http\Controllers\ServiceController::class, 'editService']);
	Route::delete('deleteService/{id}', [\App\Http\Controllers\ServiceController::class, 'deleteService']);
	
	//Vehicle Type
    Route::post('addVehicleType', [\App\Http\Controllers\VehicleTypeController::class, 'addVehicleType']);
    Route::post('editVehicleType', [\App\Http\Controllers\VehicleTypeController::class, 'editVehicleType']);
    Route::delete('deleteVehicleType/{id}', [\App\Http\Controllers\VehicleTypeController::class, 'deleteVehicleType']);
	
	//Vehicle Brand
    Route::post('addVehicleBrand', [\App\Http\Controllers\VehicleBrandController::class, 'addVehicleBrand']);
    Route::post('editVehicleBrand', [\App\Http\Controllers\VehicleBrandController::class, 'editVehicleBrand']);
	
	//Vehicle Modele
    Route::post('addVehicleModele', [\App\Http\Controllers\VehicleModeleController::class, 'addVehicleModele']);
    Route::post('editVehicleModele', [\App\Http\Controllers\VehicleModeleController::class, 'editVehicleModele']);

	//Garage
    Route::get('garages', [\App\Http\Controllers\GarageController::class, 'getGarages']);
    Route::post('addGarage', [\App\Http\Controllers\GarageController::class, 'addGarage']);
    Route::post('editGarage', [\App\Http\Controllers\GarageController::class, 'editGarage']);
	Route::delete('deleteGarage/{id}', [\App\Http\Controllers\GarageController::class, 'deleteGarage']);

	//statistics
	Route::get('appointmentStatistics', [\App\Http\Controllers\StatisticController::class, 'getAppointmentStatistics']);
	Route::get('sixBestGarages', [\App\Http\Controllers\StatisticController::class, 'getSixBestGarages']);
	Route::get('threeBestServices', [\App\Http\Controllers\StatisticController::class, 'getThreeBestServices']);
	Route::get('appointmentsStatus', [\App\Http\Controllers\StatisticController::class, 'getAppointmentsStatus']);
	
});

Route::middleware(['auth:sanctum', 'role:client'])->group(function () {
	//Client Vehicle
    Route::get('clientVehicles', [\App\Http\Controllers\ClientVehicleController::class, 'getClientVehicles']);
    Route::post('addClientVehicle', [\App\Http\Controllers\ClientVehicleController::class, 'addClientVehicle']);
	Route::post('editClientVehicle', [\App\Http\Controllers\ClientVehicleController::class, 'editClientVehicle']);
	Route::delete('deleteClientVehicle/{id}', [\App\Http\Controllers\ClientVehicleController::class, 'deleteClientVehicle']);
	Route::post('clineAppointmentFromHomePage', [\App\Http\Controllers\ClientVehicleController::class, 'clineAppointmentFromHomePage']);

	//Appointment
	Route::post('editAppointment', [\App\Http\Controllers\AppointmentController::class, 'editAppointment']);
	Route::post('cancelAppointment/{id}', [\App\Http\Controllers\AppointmentController::class, 'cancelAppointment']);
	Route::get('appointmentById/{id}', [\App\Http\Controllers\AppointmentController::class, 'getAppointmentById']);
	Route::get('upcomingClientAppointments', [\App\Http\Controllers\AppointmentController::class, 'getUpcomingClientAppointments']);
	Route::get('expiredClientAppointments', [\App\Http\Controllers\AppointmentController::class, 'getExpiredClientAppointments']);

	//Notification
	Route::get('clientNotifications', [\App\Http\Controllers\NotificationController::class, 'getClientNotifications']);
});

Route::middleware(['auth:sanctum', 'role:technician'])->group(function () {
	//Garage
    Route::get('garageByTechnician', [\App\Http\Controllers\GarageController::class, 'getGarageByTechnician']);
    Route::post('updateGarageInfo', [\App\Http\Controllers\GarageController::class, 'updateGarageInfo']);
    Route::get('upcomingTechnicianAppointments', [\App\Http\Controllers\AppointmentController::class, 'getUpcomingTechnicianAppointments']);
    Route::get('expiredTechnicianAppointments', [\App\Http\Controllers\AppointmentController::class, 'getExpiredTechnicianAppointments']);

	//Notification
    Route::get('technicianNotifications', [\App\Http\Controllers\NotificationController::class, 'getTechnicianNotifications']);

	//Statistics
	Route::get('garageStatistics', [\App\Http\Controllers\StatisticController::class, 'getGarageStatistics']);
	Route::get('appointmentsEvolutionByDate', [\App\Http\Controllers\StatisticController::class, 'getAppointmentsEvolutionByDate']);
	Route::get('appointmentsCountByService', [\App\Http\Controllers\StatisticController::class, 'getAppointmentsCountByService']);
	Route::get('threeBestClients', [\App\Http\Controllers\StatisticController::class, 'getThreeBestClients']);

	//Garage Service
	Route::delete('deleteGarageService/{id}', [\App\Http\Controllers\GarageServiceControler::class, 'deleteGarageService']);
	Route::post('addGarageServices', [\App\Http\Controllers\GarageServiceControler::class, 'addGarageServices']);

	//Garage Specialty
	Route::delete('deleteGarageSpecialty/{id}', [\App\Http\Controllers\GarageSpecialtyControler::class, 'deleteGarageSpecialty']);
	Route::post('addGarageSpecialties', [\App\Http\Controllers\GarageSpecialtyControler::class, 'addGarageSpecialties']);
});

Route::middleware(['auth:sanctum', 'role:technician,client'])->group(function () {
    Route::post('editAppointmentStatus', [\App\Http\Controllers\AppointmentController::class, 'editAppointmentStatus']);
    Route::post('notification/markAsRead/{id}', [\App\Http\Controllers\NotificationController::class, 'markAsRead']);
	Route::post('askQuestion', [\App\Http\Controllers\ContactController::class, 'askQuestion']);
});