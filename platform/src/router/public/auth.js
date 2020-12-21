import Login from '@/views/Auth/Login'
import Register from '@/views/Auth/Register'
import RegisterFail from '@/views/Auth/RegisterFail'
import Forgot from '@/views/Auth/Forgot'
import Reset from '@/views/Auth/Reset'
import NeedEmailValidation from '@/views/Auth/NeedEmailValidation'
import EmailVerified from '@/views/Auth/EmailVerified'
import EmailError from '@/views/Auth/EmailError'

const routes = [
  {
    name: 'auth-login',
    path: '/user/login',
    component: Login,
    meta: { layout: 'empty', guest: true }
  },
  {
    name: 'auth-register',
    path: '/user/register',
    component: Register,
    meta: { layout: 'empty', guest: true }
  },
  {
    name: 'auth-register-fail',
    path: '/user/register/fail',
    component: RegisterFail,
    meta: { layout: 'empty', guest: true }
  },
  {
    name: 'auth-forgot',
    path: '/user/forgot',
    component: Forgot,
    meta: { layout: 'empty', guest: true }
  },
  {
    name: 'auth-reset',
    path: '/reset/password/:token/:email',
    component: Reset,
    meta: { layout: 'empty', guest: true }
  },
  {
    name: 'need-email-validation',
    path: '/email-validation',
    component: NeedEmailValidation,
    meta: { layout: 'empty', guest: true }
  },
  {
    name: 'email-verified',
    path: '/email/verified',
    component: EmailVerified,
    meta: { layout: 'empty', guest: true }
  },
  {
    name: 'email-error',
    path: '/email/error/:id',
    component: EmailError,
    meta: { layout: 'empty', guest: true }
  }
]

export default routes
