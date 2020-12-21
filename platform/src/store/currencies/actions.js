import api from '@/services/api'

export const getCurrencies = async ({ commit }) => {
  commit('getCurrenciesLoading')
  try {
    const response = await api.get('/admin/currencies')
    commit('getCurrenciesSuccess', response.data.data)
  } catch (e) {
    commit('getCurrenciesError', e.response.data)
  }
}

export default {
  getCurrencies
}
