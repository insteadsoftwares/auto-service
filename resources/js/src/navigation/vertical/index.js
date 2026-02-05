export default [
  {
    title: 'Accueil',
    route: 'AdminDashboard',
    icon: 'HomeIcon',
  },
  {
    title: 'Admin',
    route: 'AdminListPage',
    icon: 'UserIcon',
	meta: {
		roles: ['super_admin']
	}
  },
  {
    title: 'Technicien',
    route: 'TechnicianListPage',
    icon: 'UserIcon',
	meta: {
		roles: ['super_admin', 'admin']
	}
  },
  {
    title: 'Service',
    route: 'ServiceListPage',
    icon: 'SettingsIcon',
	meta: {
		roles: ['super_admin', 'admin']
	}
  },
  {
    title: 'Type de véhicule',
    route: 'VehicleTypeListPage',
    icon: 'HardDriveIcon',
	meta: {
		roles: ['super_admin', 'admin']
	}
  },
  {
    title: 'Marque de véhicule',
    route: 'VehicleBrandListPage',
    icon: 'TagIcon',
	meta: {
		roles: ['super_admin', 'admin']
	}
  },
  {
    title: 'Modèle de véhicule',
    route: 'VehicleModeleListPage',
    icon: 'ListIcon',
	meta: {
		roles: ['super_admin', 'admin']
	}
  },
  {
    title: 'Garage',
    route: 'GarageListPage',
    icon: 'BoxIcon',
	meta: {
		roles: ['super_admin', 'admin']
	}
  },
]
