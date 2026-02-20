export default [
  {
    title: 'Accueil',
    route: 'AdminDashboard',
    icon: 'HomeIcon',
  },
  {
    title: 'Admins',
    route: 'AdminListPage',
    icon: 'UserIcon',
	meta: {
		roles: ['super_admin']
	}
  },
  {
    title: 'Techniciens',
    route: 'TechnicianListPage',
    icon: 'UserIcon',
	meta: {
		roles: ['super_admin', 'admin']
	}
  },
  {
    title: 'Services',
    route: 'ServiceListPage',
    icon: 'SettingsIcon',
	meta: {
		roles: ['super_admin', 'admin']
	}
  },
  {
    title: 'Types de véhicule',
    route: 'VehicleTypeListPage',
    icon: 'HardDriveIcon',
	meta: {
		roles: ['super_admin', 'admin']
	}
  },
  {
    title: 'Marques de véhicule',
    route: 'VehicleBrandListPage',
    icon: 'TagIcon',
	meta: {
		roles: ['super_admin', 'admin']
	}
  },
  {
    title: 'Modèles de véhicule',
    route: 'VehicleModeleListPage',
    icon: 'ListIcon',
	meta: {
		roles: ['super_admin', 'admin']
	}
  },
  {
    title: 'Garages',
    route: 'GarageListPage',
    icon: 'BoxIcon',
	meta: {
		roles: ['super_admin', 'admin']
	}
  },
]
