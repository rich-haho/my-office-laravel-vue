import api from '@/services/api'

export const getSearchesList = async ({ commit }) => {
  commit('listLoading')
  try {
    const response = await api.get('/searches')
    commit('listSuccess', response.data)
  } catch (e) {
    commit('listError', e.response.data)
  }
}

export default {
  getSearchesList
}
