import api from '@/services/api'

export const getAdminList = async (
  { commit },
  { page, order, orderProp, filters, perPage = 10 }
) => {
  commit('listLoading')
  try {
    const response = await api.get('/admin/admins', {
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
    await api.delete('/admin/admins/' + id)
    commit('deleteSuccess', id)
  } catch (e) {
    commit('deleteError', e.message)
  }
}

export const getAdmin = async ({ commit }, { id }) => {
  commit('getAdminLoading')
  try {
    const response = await api.get('/admin/admins/' + id)
    commit('getAdminSuccess', response.data.data)
  } catch (e) {
    commit('getAdminError', e.response.data)
  }
}

export const clearAdmin = async ({ commit }) => {
  commit('clearAdmin')
}

export const save = async (
  { commit },
  { id, name, email, role_id, admin, password, confirm_password }
) => {
  commit('saveLoading')
  try {
    const response = await api.post('/admin/admins' + (id ? '/' + id : ''), {
      name,
      email,
      role_id,
      admin,
      password,
      confirm_password
    })
    commit('saveSuccess', response.data.data)
  } catch (e) {
    commit('saveError', e.response.data)
  }
}

export const getRoleList = async ({ commit }) => {
  try {
    const response = await api.get('/admin/roles')
    commit('listRoleSuccess', response.data)
  } catch (e) {
    commit('listRoleError', e.response.data)
  }
}

export const clearErrorState = async ({ commit }) => {
  commit('clearErrorState')
}

export default {
  getAdminList,
  destroy,
  getAdmin,
  clearAdmin,
  save,
  clearErrorState,
  getRoleList
}
