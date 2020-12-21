import api from '@/services/api'

export const getLocales = async ({ commit, rootGetters }) => {
  commit('getLocalesLoading')
  try {
    let url = '/me/locales'
    if (rootGetters['auth/isAdmin'] === true) {
      url = '/admin/locales'
    }
    const response = await api.get(url)
    commit('getLocalesSuccess', response.data.data)
  } catch (e) {
    commit('getLocalesError', e.response.data)
  }
}

export default {
  getLocales
}
