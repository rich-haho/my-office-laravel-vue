import api from '@/services/api'
import router from '@/router'

export const login = async (
  { commit },
  { email, password, remember, guard = 'public' }
) => {
  commit('loginLoading')
  try {
    const response = await api.post(
      (guard === 'admin' ? '/admin' : '') + '/users/login',
      {
        email: email,
        password: password,
        remember: remember
      }
    )

    commit('loginSuccess', response.data.data)

    return router.push({
      name: (guard === 'admin' ? 'admin-' : '') + 'home'
    })
  } catch (e) {
    commit('loginError', e.response.data)
  }
}

export const register = async (
  { commit },
  { email, password, password_confirmation, name }
) => {
  commit('registerLoading')
  try {
    const response = await api.post('/users/register', {
      email,
      password,
      password_confirmation,
      name
    })
    commit('registerSuccess', response.data.data)

    return router.push({
      name: 'need-email-validation'
    })
  } catch (e) {
    commit('registerError', e.response.data)
    if (e.response.status === 403) {
      return router.push({ name: 'auth-register-fail' })
    }
  }
}

export const logout = async ({ commit }, { guard = 'public' }) => {
  commit('logoutLoading')
  try {
    await api.post((guard === 'admin' ? '/admin' : '') + '/me/logout')
    commit('logoutSuccess')
    return router.push({
      name: (guard === 'admin' ? 'admin-' : '') + 'auth-login'
    })
  } catch (e) {
    commit('logoutError', e.response.data)
  }
}

export const me = async ({ commit }, { guard = 'public' }) => {
  commit('meLoading')
  try {
    const response = await api.get((guard === 'admin' ? '/admin' : '') + '/me')
    commit('meSuccess', response.data.data)
  } catch (e) {
    commit('meError', e.response.data)
  }
}

export const forgot = async ({ commit }, { email, guard = 'public' }) => {
  commit('forgotLoading')
  try {
    const response = await api.post(
      (guard === 'admin' ? '/admin' : '') + '/users/password',
      {
        email: email
      }
    )
    commit('forgotSuccess', response.data)
  } catch (e) {
    commit('forgotError', e.response.data)
  }
}

export const reset = async (
  { commit },
  { token, email, password, password_confirmation, guard = 'public' }
) => {
  commit('resetLoading')
  try {
    const response = await api.post(
      (guard === 'admin' ? '/admin' : '') + '/users/password/reset',
      {
        token,
        email,
        password,
        password_confirmation
      }
    )
    commit('resetSuccess', response.data)
  } catch (e) {
    commit('resetError', e.response.data)
  }
}

export const setSiteLocale = async ({ commit }, { locale_id, site_id }) => {
  commit('setSiteLocaleLoading')
  try {
    const response = await api.post('/me/state', {
      locale_id,
      site_id
    })
    commit('setSiteLocaleSuccess', response.data.data)
    location.reload()
  } catch (e) {
    commit('setSiteLocaleError', e.response.data)
  }
}

export const saveMe = async (
  { commit },
  { name, updatepassword, password, new_password, new_password_confirmation }
) => {
  commit('saveMeLoading')
  try {
    const response = await api.post('/me', {
      name,
      updatepassword,
      password,
      new_password,
      new_password_confirmation
    })
    commit('saveMeSuccess', response.data.data)
  } catch (e) {
    commit('saveMeError', e.response.data)
  }
}

export const showManageAccount = async ({ commit }) => {
  commit('showManageAccount')
}

export const closeManageAccount = async ({ commit }) => {
  commit('closeManageAccount')
}

export const askMailVerificationNotification = async ({ commit }, { id }) => {
  commit('askMailVerificationNotificationLoading')
  try {
    await api.post('/email/resend', {
      id
    })
    commit('askMailVerificationNotificationSuccess')
  } catch (e) {
    commit('askMailVerificationNotificationError', e.response.data)
  }
}

export default {
  login,
  register,
  logout,
  me,
  saveMe,
  forgot,
  reset,
  setSiteLocale,
  showManageAccount,
  closeManageAccount,
  askMailVerificationNotification
}
