import api from '@/services/api'

export const getServiceList = async (
  { commit, rootGetters },
  { page, order, orderProp, filters, perPage = 10, favorite = 0 }
) => {
  commit('listLoading')
  try {
    let url = '/services'
    if (rootGetters['auth/isAdmin'] === true) {
      url = '/admin/services'
    }

    const response = await api.get(url, {
      params: {
        page,
        order,
        orderProp,
        filters,
        perPage,
        favorite
      }
    })
    commit('listSuccess', response.data)
  } catch (e) {
    commit('listError', e.response.data)
  }
}

export const getServiceFavoriteList = async ({ commit }) => {
  commit('favoriteListLoading')
  try {
    const response = await api.get('/services', {
      params: {
        favorite: 1,
        perPage: 50
      }
    })
    commit('favoriteListSuccess', response.data)
  } catch (e) {
    commit('favoriteListError', e.response.data)
  }
}

export const destroy = async ({ commit }, id) => {
  commit('deleteLoading')
  try {
    await api.delete('/admin/services/' + id)
    commit('deleteSuccess', id)
  } catch (e) {
    commit('deleteError', e.message)
  }
}

export const getService = async ({ commit, rootGetters }, { id }) => {
  commit('getServiceLoading')
  try {
    let url = '/services/'
    if (rootGetters['auth/isAdmin'] === true) {
      url = '/admin/services/'
    }

    const response = await api.get(url + id)
    commit('getServiceSuccess', response.data.data)
  } catch (e) {
    commit('getServiceError', e.response.data)
  }
}

export const clearService = async ({ commit }) => {
  commit('clearService')
}

export const save = async ({ commit }, { id, formdata }) => {
  commit('saveLoading')
  try {
    const response = await api.post(
      '/admin/services' + (id ? '/' + id : ''),
      formdata,
      { headers: { 'Content-Type': 'multipart/form-data' } }
    )
    commit('saveSuccess', response.data.data)
  } catch (e) {
    commit('saveError', e.response.data)
  }
}
export const clearErrorState = async ({ commit }) => {
  commit('clearErrorState')
}

export const favoriteService = async ({ commit }, { service }) => {
  commit('saveServiceFavoriteLoading')
  try {
    const response = await api.post(`/services/${service}/favorites`)
    commit('saveServiceFavoriteSuccess', response.data.data)
  } catch (e) {
    commit('saveServiceFavoriteError', e.response.data)
  }
}

export default {
  getServiceList,
  destroy,
  getService,
  clearService,
  clearErrorState,
  save,
  favoriteService,
  getServiceFavoriteList
}
