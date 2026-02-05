import LayoutVertical from '@/layouts/vertical/LayoutVertical.vue'

export default [
  {
    path: '/admin',
    component: LayoutVertical,
    children: [
      {
        path: '', // /admin
        name: 'AdminDashboard',
        component: () => import('@/views/admin/HomeAdmin.vue')
      },
      {
        path: 'second', // /admin/second
        name: 'SecondPage',
        component: () => import('@/views/admin/SecondPage.vue')
      },
      {
        path: 'adminList', // /admin/adminList
        name: 'AdminListPage',
        component: () => import('@/views/admin/usres/admin/AdminList.vue')
      },
      {
        path: 'technicianList', // /admin/adminList
        name: 'TechnicianListPage', 
        component: () => import('@/views/admin/usres/technician/TechnicianList.vue')
      },
      {
        path: 'serviceList', // /admin/serviceList
        name: 'ServiceListPage', 
        component: () => import('@/views/admin/service/ServiceList.vue')
      },
      {
        path: 'vehicleTypeList', // /admin/vehicleTypeList
        name: 'VehicleTypeListPage', 
        component: () => import('@/views/admin/vehicleType/VehicleTypeList.vue')
      },
      {
        path: 'vehiclBrandList', // /admin/vehiclBrandList
        name: 'VehicleBrandListPage', 
        component: () => import('@/views/admin/vehicleBrand/VehicleBrandList.vue')
      },
      {
        path: 'vehiclModeleList', // /admin/vehiclModeleList
        name: 'VehicleModeleListPage', 
        component: () => import('@/views/admin/vehicleModele/VehicleModeleList.vue')
      },
      {
        path: 'garageList', // /admin/garageList
        name: 'GarageListPage', 
        component: () => import('@/views/admin/garage/GarageList.vue')
      },
    ]
  },
  {
    path: '/admin/login',
    name: 'loginAdmin',
    component: () => import('@/views/admin/Login.vue')
  }
]
