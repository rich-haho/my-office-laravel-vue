export default {
  listLoading(state) {
    state.listLoading = true
    state.listError = null
  },
  listSuccess(state, list) {
    state.list = list
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
    state.list.splice(state.list.findIndex(item => item.id === id), 1)
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
  saveSuccess(state, client) {
    state.saveLoading = false
    state.list.data = [...state.list.data, client]
    state.createError = null
  },
  saveError(state, error) {
    state.saveLoading = false
    state.saveError = error
  },

  getClientLoading(state) {
    state.getClientLoading = true
    state.getClientError = null
  },
  getClientSuccess(state, item) {
    state.getClientLoading = false
    state.item = item
    state.getClientError = null
  },
  getClientError(state, error) {
    state.getClientLoading = false
    state.getClientError = error
  },
  clearClient(state) {
    state.getClientLoading = false
    state.getClientError = null
    state.item = {
      services: []
    }
  },
  deleteDomainLoading(state) {
    state.deleteDomainLoading = true
    state.deleteDomainError = null
  },
  deleteDomainSuccess(state) {
    state.deleteDomainLoading = false
    state.deleteDomainError = null
  },
  deleteDomainError(state, error) {
    state.deleteDomainLoading = false
    state.deleteDomainError = error
  },

  saveDomainLoading(state) {
    state.saveDomainLoading = true
    state.saveDomainError = null
  },
  saveDomainSuccess(state, domain) {
    state.saveDomainLoading = false
    state.domain = domain
    state.saveDomainError = null
  },
  saveDomainError(state, error) {
    state.saveDomainLoading = false
    state.saveDomainError = error
  },
  clearErrorState(state) {
    state.listError = null
    state.deleteError = null
    state.saveError = null
    state.deleteDomainError = null
    state.saveDomainError = null
  }
}
