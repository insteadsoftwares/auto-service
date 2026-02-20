<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
//User
use App\Repositories\User\UserRepository;
use App\Repositories\User\EloquentUser;
//Role
use App\Repositories\Role\RoleRepository;
use App\Repositories\Role\EloquentRole;
//Service
use App\Repositories\Service\ServiceRepository;
use App\Repositories\Service\EloquentService;
//VehicleBrand
use App\Repositories\VehicleBrand\VehicleBrandRepository;
use App\Repositories\VehicleBrand\EloquentVehicleBrand;
//VehicleModele
use App\Repositories\VehicleModele\VehicleModeleRepository;
use App\Repositories\VehicleModele\EloquentVehicleModele;
//VehicleType
use App\Repositories\VehicleType\VehicleTypeRepository;
use App\Repositories\VehicleType\EloquentVehicleType;
//Garage
use App\Repositories\Garage\GarageRepository;
use App\Repositories\Garage\EloquentGarage;
//Appointment
use App\Repositories\Appointment\AppointmentRepository;
use App\Repositories\Appointment\EloquentAppointment;
//ClientVehicle
use App\Repositories\ClientVehicle\ClientVehicleRepository;
use App\Repositories\ClientVehicle\EloquentClientVehicle;
//Notification
use App\Repositories\Notification\NotificationRepository;
use App\Repositories\Notification\EloquentNotification;
//GarageService
use App\Repositories\GarageService\GarageServiceRepository;
use App\Repositories\GarageService\EloquenteGarageService;
//GarageSpecialty
use App\Repositories\GarageSpecialty\GarageSpecialtyRepository;
use App\Repositories\GarageSpecialty\EloquenteGarageSpecialty;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
		$this->app->singleton(UserRepository::class, EloquentUser::class);
		$this->app->singleton(RoleRepository::class, EloquentRole::class);
		$this->app->singleton(ServiceRepository::class, EloquentService::class);
		$this->app->singleton(VehicleBrandRepository::class, EloquentVehicleBrand::class);
		$this->app->singleton(VehicleModeleRepository::class, EloquentVehicleModele::class);
		$this->app->singleton(VehicleTypeRepository::class, EloquentVehicleType::class);
		$this->app->singleton(GarageRepository::class, EloquentGarage::class);
		$this->app->singleton(AppointmentRepository::class, EloquentAppointment::class);
		$this->app->singleton(ClientVehicleRepository::class, EloquentClientVehicle::class);
		$this->app->singleton(NotificationRepository::class, EloquentNotification::class);
		$this->app->singleton(GarageServiceRepository::class, EloquenteGarageService::class);
		$this->app->singleton(GarageSpecialtyRepository::class, EloquenteGarageSpecialty::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
