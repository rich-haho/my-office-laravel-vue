import api from '@/services/api'

export const getUserList = async (
  { commit },
  { page, order, orderProp, filters, perPage = 10 }
) => {
  commit('listLoading')
  try {
    const response = await api.get('/admin/users', {
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
    await api.delete('/admin/users/' + id)
    commit('deleteSuccess', id)
  } catch (e) {
    commit('deleteError', e.message)
  }
}

export const getUser = async ({ commit }, { id }) => {
  commit('getUserLoading')
  try {
    const response = await api.get('/admin/users/' + id)
    commit('getUserSuccess', response.data.data)
  } catch (e) {
    commit('getUserError', e.response.data)
  }
}

export const clearUser = async ({ commit }) => {
  commit('clearUser')
}

export const save = async (
  { commit },
  { id, name, email, client_id, active, password, confirm_password }
) => {
  commit('saveLoading')
  try {
    const response = await api.post('/admin/users' + (id ? '/' + id : ''), {
      name,
      email,
      client_id,
      active,
      password,
      confirm_password
    })
    commit('saveSuccess', response.data.data)
  } catch (e) {
    commit('saveError', e.response.data)
  }
}

export const resend = async ({ commit }, { id }) => {
  commit('resendLoading')
  try {
    const response = await api.post('/admin/users/' + id + '/resend')
    commit('resendSuccess', response.data.data)
  } catch (e) {
    commit('resendError', e.response.data)
  }
}

export const clearErrorState = async ({ commit }) => {
  commit('clearErrorState')
}

export default {
  getUserList,
  destroy,
  getUser,
  clearUser,
  save,
  resend,
  clearErrorState
}
