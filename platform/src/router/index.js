import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '@/store'
import { loadMessages } from '@/plugins/i18n'

// Import all routes
import publicAuth from './public/auth'
import adminAuth from './admin/auth'
import adminProductRoutes from './admin/product'

import clientRoutes from './routes/client'
import siteRoutes from './routes/site'
import bookingRoutes from './routes/booking'
import partnerRoutes from './routes/partner'
import administratorRoutes from './routes/administrator'
import userRoutes from './routes/user'
import serviceRoutes from './routes/service'
import productRoutes from './routes/product'

// Default views
import Home from '@/views/Home.vue'
import AdminHome from '@/views/Admin/Home.vue'
import Admin from '@/views/Admin/Index.vue'
import PageNotFound from '@/views/PageNotFound'
import TermsAndConditions from '@/views/TermsAndConditions'

const Categories = () => import('@/views/ClientPages/Categories.vue')
const FmServices = () => import('@/views/ClientPages/FmServices.vue')
import Category from '@/views/ClientPages/Category.vue'

import Bookings from '@/views/ClientPages/Booking/List'
import Demand from '@/views/ClientPages/Demand'

Vue.use(VueRouter)

import { defaultLocale } from '@/plugins/i18n'

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home
  },
  {
    path: '/categories',
    name: 'categories',
    component: Categories
  },
  {
    path: '/category/:id?',
    name: 'category',
    component: Category
  },
  {
    path: '/bookings',
    name: 'bookings',
    component: Bookings
  },
  {
    path: '/demand',
    name: 'demand',
    component: Demand
  },
  {
    path: '/fm-services',
    name: 'fm-services',
    component: FmServices
  },
  ...publicAuth,
  ...productRoutes,
  {
    path: '/zen-admin',
    component: Admin,
    children: [
      {
        path: '',
        name: 'admin-home',
        component: AdminHome,
        meta: { layout: 'admin', guard: 'admin' }
      },
      ...clientRoutes,
      ...siteRoutes,
      ...bookingRoutes,
      ...partnerRoutes,
      ...administratorRoutes,
      ...userRoutes,
      ...serviceRoutes,
      ...adminProductRoutes,
      ...adminAuth
    ]
  },
  { path: '/terms', component: TermsAndConditions },
  { path: '*', component: PageNotFound }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

router.beforeEach(async (to, from, next) => {
  store.dispatch('common/setShowMenu', false)

  if (to.meta.guard === 'admin') {
    store.dispatch('common/setGlobalLoading', true)
  }

  await store.dispatch('auth/me', {
    guard: to.meta.guard ? to.meta.guard : 'public'
  })

  if (store.getters['auth/isLoggedIn'] && to.meta.guard !== 'admin') {
    // startup loading
    if (
      !store.getters['auth/locale'] &&
      !store.getters['auth/site_id'] &&
      !store.getters['auth/isAdmin']
    ) {
      await store.dispatch('auth/setSiteLocale', {
        site_id: 1,
        locale_id: 1
      })
    }
    if (!store.getters['sites/sitelist'].length) {
      await store.dispatch('sites/getSites', { perPage: 1000 })
    }

    if (!store.getters['auth/isAdmin']) {
      await loadMessages(store.getters['auth/locale'].name || defaultLocale)
    }
  } else {
    await loadMessages(defaultLocale)
  }

  if (to.meta.guest !== true) {
    if (!store.getters['auth/isLoggedIn']) {
      if (to.meta.guard === 'admin') {
        return next({ name: 'admin-auth-login' })
      }
      return next({ name: 'auth-login' })
    }
  } else {
    if (store.getters['auth/isLoggedIn']) {
      if (to.meta.guard === 'admin') {
        return next({ name: 'admin-home' })
      }
      return next({ name: 'home' })
    }
  }

  // Check if connected user has right permissions
  if (to.meta.guard === 'admin' && to.meta.guest !== true) {
    if (to.meta.permissions !== undefined) {
      if (!store.getters['auth/can'](to.meta.permissions)) {
        return next({ name: 'admin-home' })
      }
    }
  }

  return next()
})

router.afterEach(() => {
  store.dispatch('common/setBooting', false)
  store.dispatch('common/setGlobalLoading', false)
})

export default router
