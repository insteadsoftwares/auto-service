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
  {
    path: '/addAppointment',
    component: MinimalLayout,
    children: [
      {
        path: '',
        name: 'AddAppointmentPage',
        component: () => import('@/views/home/AddAppointmentPage.vue')
      }
    ]
  },
]
