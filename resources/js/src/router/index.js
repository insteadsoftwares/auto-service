import Vue from 'vue'
import VueRouter from 'vue-router'
import publicRoutes from './public'
import adminRoutes from './admin'

Vue.use(VueRouter)

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  scrollBehavior() {
    return { x: 0, y: 0 }
  },
  routes: [
    ...publicRoutes,
    ...adminRoutes
  ]
})

// ? For splash screen
// Remove afterEach hook if you are not using splash screen
router.afterEach(() => {
  // Remove initial loading
  const appLoading = document.getElementById('loading-bg')
  if (appLoading) {
    appLoading.style.display = 'none'
  }
})

router.beforeEach((to, from, next) => {
  const user = JSON.parse(localStorage.getItem('currentUser')) || null
  const admin = JSON.parse(localStorage.getItem('currentAdmin')) || null
  const tokenAdmin = localStorage.getItem('token')
  const tokenUser = localStorage.getItem('tokenUser')

  if (to.name === 'loginAdmin' && admin && tokenAdmin && (admin.role.name === 'admin' || admin.role.name === 'super_admin')) {
    next({ name: 'AdminDashboard' }) 
    return
  }

  if (to.path.startsWith('/admin') && to.name !== 'loginAdmin') {
    if (!admin || !tokenAdmin || (admin.role.name !== 'admin' && admin.role.name !== 'super_admin')) {
      next({ name: 'loginAdmin' })
    } else {
      next() 
    }
  } 
  else if (to.name == 'GaragePage') {
    if (!user || !tokenUser || (user.role.name !== 'technician')) {
      next({ name: 'HomePage' })
    } else {
      next() 
    }
  }
  else if (to.name == 'ClientSpacePage') {
    if (!user || !tokenUser || (user.role.name !== 'client')) {
      next({ name: 'HomePage' })
    } else {
      next() 
    }
  }
  else {
    next()
  }
})

export default router
