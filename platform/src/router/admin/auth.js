import AdminLogin from '@/views/Auth/Login'
import AdminAuthForgot from '@/views/Auth/Forgot'
import AdminReset from '@/views/Auth/Reset'

const routes = [
  {
    name: 'admin-auth-login',
    path: 'user/login',
    component: AdminLogin,
    props: { guard: 'admin' },
    meta: { layout: 'empty', guard: 'admin', guest: true }
  },
  {
    name: 'admin-auth-forgot',
    path: 'user/forgot',
    component: AdminAuthForgot,
    props: { guard: 'admin' },
    meta: { layout: 'empty', guard: 'admin', guest: true }
  },
  {
    name: 'admin-auth-reset',
    path: 'reset/password/:token/:email',
    component: AdminReset,
    props: { guard: 'admin' },
    meta: { layout: 'empty', guard: 'admin', guest: true }
  }
]

export default routes
