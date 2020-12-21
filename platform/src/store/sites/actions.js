import api from '@/services/api'

export const getSiteList = async (
  { commit },
  { page, order, orderProp, filters, perPage = 10 }
) => {
  commit('listLoading')
  try {
    const response = await api.get('/admin/sites', {
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
    await api.delete('/admin/sites/' + id)
    commit('deleteSuccess', id)
  } catch (e) {
    commit('deleteError', e.message)
  }
}

export const getSite = async ({ commit }, { id }) => {
  commit('getSiteLoading')
  try {
    const response = await api.get('/admin/sites/' + id)
    commit('getSiteSuccess', response.data.data)
  } catch (e) {
    commit('getSiteError', e.response.data)
  }
}

export const clearSite = async ({ commit }) => {
  commit('clearSite')
}

export const save = async (
  { commit },
  { id, name, client_id, phone_number, open_time, address, fm_services }
) => {
  commit('saveLoading')
  try {
    const response = await api.post('/admin/sites' + (id ? '/' + id : ''), {
      name,
      client_id,
      phone_number,
      open_time,
      address,
      fm_services
    })
    commit('saveSuccess', response.data.data)
  } catch (e) {
    commit('saveError', e.response.data)
  }
}

export const getSites = async ({ commit }) => {
  commit('getSitesLoading')
  try {
    const response = await api.get('/sites')
    commit('getSitesSuccess', response.data.data)
  } catch (e) {
    commit('getSitesError', e.response.data)
  }
}

export default {
  getSiteList,
  destroy,
  getSite,
  clearSite,
  save,
  getSites
}
