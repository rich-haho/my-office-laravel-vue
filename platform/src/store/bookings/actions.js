import api from '@/services/api'

export const getBookingList = async (
  { commit, rootGetters },
  { page, order, orderProp, filters, perPage = 10 }
) => {
  commit('listLoading')
  try {
    let url = '/me/bookings'
    if (rootGetters['auth/isAdmin'] === true) {
      url = '/admin/bookings'
    }
    const response = await api.get(url, {
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

export const destroy = async ({ commit, rootGetters }, id) => {
  commit('deleteLoading')
  try {
    let url = '/me/bookings/'
    if (rootGetters['auth/isAdmin'] === true) {
      url = '/admin/bookings/'
    }
    await api.delete(url + id)
    commit('deleteSuccess', id)
  } catch (e) {
    commit('deleteError', e.message)
  }
}

export const getBooking = async ({ commit, rootGetters }, { id }) => {
  commit('getBookingLoading')
  try {
    let url = '/me/bookings/'
    if (rootGetters['auth/isAdmin'] === true) {
      url = '/admin/bookings/'
    }
    const response = await api.get(url + id)
    commit('getBookingSuccess', response.data.data)
  } catch (e) {
    commit('getBookingError', e.response.data)
  }
}

export const clearBooking = async ({ commit }) => {
  commit('clearBooking')
}

export const save = async (
  { commit, rootGetters },
  { id, user_id, product_id, date, quantity, status }
) => {
  commit('saveLoading')
  try {
    let url = '/me/bookings'
    if (rootGetters['auth/isAdmin'] === true) {
      url = '/admin/bookings'
    }
    const response = await api.post(url + (id ? '/' + id : ''), {
      user_id,
      product_id,
      date,
      quantity,
      status
    })
    commit('saveSuccess', response.data.data)
  } catch (e) {
    commit('saveError', e.response.data)
  }
}

export const getBookingStatuses = async ({ commit, rootGetters }) => {
  commit('getBookingStatusesLoading')
  try {
    let url = '/me/bookings/statuses'
    if (rootGetters['auth/isAdmin'] === true) {
      url = '/admin/bookings/statuses'
    }
    const response = await api.get(url)
    commit('getBookingStatusesSuccess', response.data)
  } catch (e) {
    commit('getBookingStatusesError', e.response.data)
  }
}

export default {
  getBookingList,
  destroy,
  getBooking,
  clearBooking,
  save,
  getBookingStatuses
}
