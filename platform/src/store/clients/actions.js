import api from '@/services/api'

export const getList = async (
  { commit },
  { page, order, orderProp, filters, perPage = 10 }
) => {
  commit('listLoading')
  try {
    const response = await api.get('/admin/clients', {
      params: {
        page,
        order,
        orderProp,
        filters,
        perPage
      }
    })
    commit('listSuccess', response.data)
  } catch (e) {
    commit('listError', e.response.data)
  }
}

export const destroy = async ({ commit }, id) => {
  commit('deleteLoading')
  try {
    await api.delete('/admin/clients/' + id)
    commit('deleteSuccess', id)
  } catch (e) {
    commit('deleteError', e.message)
  }
}

export const getClient = async ({ commit }, { id }) => {
  commit('getClientLoading')
  try {
    const response = await api.get('/admin/clients/' + id)
    commit('getClientSuccess', response.data.data)
  } catch (e) {
    commit('getClientError', e.response.data)
  }
}

export const clearClient = async ({ commit }) => {
  commit('clearClient')
}

export const save = async ({ commit }, { id, name }) => {
  commit('saveLoading')
  try {
    const response = await api.post('/admin/clients' + (id ? '/' + id : ''), {
      name
    })
    commit('saveSuccess', response.data.data)
  } catch (e) {
    commit('saveError', e.response.data)
  }
}

export const destroyDomain = async ({ commit }, { id, client_id }) => {
  commit('deleteDomainLoading')
  try {
    await api.delete('/admin/clients/' + client_id + '/domains/' + id)
    commit('deleteDomainSuccess', id)
  } catch (e) {
    commit('deleteDomainError', e.message)
  }
}

export const saveDomain = async ({ commit }, { id, domain, client_id }) => {
  commit('saveDomainLoading')
  try {
    const response = await api.post(
      '/admin/clients/' + client_id + '/domains' + (id ? '/' + id : ''),
      {
        domain
      }
    )
    commit('saveDomainSuccess', response.data.data)
  } catch (e) {
    commit('saveDomainError', e.response.data)
  }
}
export const clearErrorState = async ({ commit }) => {
  commit('clearErrorState')
}
export default {
  getList,
  destroy,
  getClient,
  clearClient,
  save,
  destroyDomain,
  saveDomain,
  clearErrorState
}
