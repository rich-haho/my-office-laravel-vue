import { getInitialState } from './state'

export default {
  listLoading(state) {
    state.listLoading = true
    state.listError = null
  },
  listSuccess(state, partnerlist) {
    state.partnerlist = partnerlist
    state.listLoading = false
    state.listError = null
  },
  listError(state, error) {
    state.listLoading = false
    state.listError = error
  },
  deleteLoading(state) {
    state.deleteLoading = true
    state.deleteError = null
  },
  deleteSuccess(state, id) {
    state.deleteLoading = false
    state.partnerlist.splice(
      state.partnerlist.findIndex(item => item.id === id),
      1
    )
    state.deleteError = null
  },
  deleteError(state, error) {
    state.deleteLoading = false
    state.deleteError = error
  },

  saveLoading(state) {
    state.saveLoading = true
    state.saveError = null
  },
  saveSuccess(state, partner) {
    state.saveLoading = false
    state.partnerlist.data = [...state.partnerlist.data, partner]
    state.createError = null
  },
  saveError(state, error) {
    state.saveLoading = false
    state.saveError = error
  },

  getPartnerLoading(state) {
    state.getPartnerLoading = true
    state.getPartnerError = null
  },
  getPartnerSuccess(state, item) {
    state.getPartnerLoading = false
    state.item = item
    state.getPartnerError = null
  },
  getPartnerError(state, error) {
    state.getPartnerLoading = false
    state.getPartnerError = error
  },
  clearPartner(state) {
    state.getPartnerLoading = false
    state.getPartnerError = null
    state.item = getInitialState().item
  },

  clearErrorState(state) {
    state.saveError = null
    state.deleteError = null
  }
}
