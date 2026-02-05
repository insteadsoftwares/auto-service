import MinimalLayout from '@/layouts/min/MinimalLayout.vue'

export default [
  {
    path: '/',
    component: MinimalLayout,
    children: [
      {
        path: '',
        name: 'HomePage',
        component: () => import('@/views/home/HomePage.vue')
      }
    ]
  },
  {
    path: '/about',
    component: MinimalLayout,
    children: [
      {
        path: '',
        name: 'AboutPage',
        component: () => import('@/views/home/AboutPage.vue')
      }
    ]
  },
  {
    path: '/services',
    component: MinimalLayout,
    children: [
      {
        path: '',
        name: 'ServicesPage',
        component: () => import('@/views/home/ServicesPage.vue')
      }
    ]
  },
  {
    path: '/contact',
    component: MinimalLayout,
    children: [
      {
        path: '',
        name: 'ContactPage',
        component: () => import('@/views/home/ContactPage.vue')
      }
    ]
  },
//   {
//     path: '/garage',
//     component: MinimalLayout,
//     children: [
//       {
//         path: '',
//         name: 'GaragePage',
//         component: () => import('@/views/home/GaragePage.vue')
//       }
//     ]
//   },
//   {
//     path: '/addAppointment',
//     component: MinimalLayout,
//     children: [
//       {
//         path: '',
//         name: 'AddAppointmentPage',
//         component: () => import('@/views/home/AddAppointmentPage.vue')
//       }
//     ]
//   },
//   {
//     path: '/editAppointment/:id',
//     component: MinimalLayout,
//     children: [
//       {
//         path: '',
//         name: 'EditAppointmentPage',
//         component: () => import('@/views/home/EditAppointmentPage.vue')
//       }
//     ]
//   },
//   {
//     path: '/appointments',
//     component: MinimalLayout,
//     children: [
//       {
//         path: '',
//         name: 'AppointmentsPage',
//         component: () => import('@/views/home/AppointmentsPage.vue')
//       }
//     ]
//   },
//   {
//     path: '/vehicles',
//     component: MinimalLayout,
//     children: [
//       {
//         path: '',
//         name: 'VehiclePage',
//         component: () => import('@/views/home/VehiclePage.vue')
//       }
//     ]
//   },
  {
    path: '/clientSpace',
    component: MinimalLayout,
    children: [
      {
        path: '',
        name: 'ClientSpacePage',
        component: () => import('@/views/home/ClientSpacePage.vue')
      }
    ]
  },
  {
    path: '/garageSpace',
    component: MinimalLayout,
    children: [
      {
        path: '',
        name: 'GarageSpacePage',
        component: () => import('@/views/home/GarageSpacePage.vue')
      }
    ]
  },
]
