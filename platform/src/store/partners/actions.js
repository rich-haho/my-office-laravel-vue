import api from '@/services/api'

export const getPartnerList = async (
  { commit },
  { page, order, orderProp, filters, perPage = 10 }
) => {
  commit('listLoading')
  try {
    const response = await api.get('/admin/partners', {
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
    await api.delete('/admin/partners/' + id)
    commit('deleteSuccess', id)
  } catch (e) {
    commit('deleteError', e.message)
  }
}

export const getPartner = async ({ commit }, { id }) => {
  commit('getPartnerLoading')
  try {
    const response = await api.get('/admin/partners/' + id)
    commit('getPartnerSuccess', response.data.data)
  } catch (e) {
    commit('getPartnerError', e.response.data)
  }
}

export const clearPartner = async ({ commit }) => {
  commit('clearPartner')
}

export const clearErrorState = async ({ commit }) => {
  commit('clearErrorState')
}

export const save = async (
  { commit },
  {
    id,
    name,
    client_id,
    address,
    email,
    phone,
    contact_name,
    public_description,
    public_descriptions,
    private_description,
    commission_percentage,
    connected_stripe_id,
    enable_stripe_connect,
    sites
  }
) => {
  commit('saveLoading')
  try {
    const response = await api.post('/admin/partners' + (id ? '/' + id : ''), {
      name,
      client_id,
      address,
      email,
      phone,
      contact_name,
      public_description,
      public_descriptions,
      private_description,
      commission_percentage,
      connected_stripe_id,
      enable_stripe_connect,
      sites: sites.map(({ id }) => id)
    })
    commit('saveSuccess', response.data.data)
  } catch (e) {
    commit('saveError', e.response.data)
  }
}

export default {
  getPartnerList,
  destroy,
  getPartner,
  clearErrorState,
  clearPartner,
  save
}
