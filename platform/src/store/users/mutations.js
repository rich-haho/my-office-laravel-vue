export default {
  listLoading(state) {
    state.listLoading = true
    state.listError = null
  },
  listSuccess(state, userlist) {
    state.userlist = userlist
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
    state.userlist.splice(state.userlist.findIndex(item => item.id === id), 1)
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
  saveSuccess(state, user) {
    state.saveLoading = false
    state.userlist.data = [...state.userlist.data, user]
    state.createError = null
  },
  saveError(state, error) {
    state.saveLoading = false
    state.saveError = error
  },

  resendLoading(state) {
    state.resendLoading = true
    state.resendError = null
  },
  resendSuccess(state) {
    state.resendLoading = false
    state.resendError = null
  },
  resendError(state, error) {
    state.resendLoading = false
    state.resendError = error
  },

  getUserLoading(state) {
    state.getUserLoading = true
    state.getUserError = null
  },
  getUserSuccess(state, item) {
    state.getUserLoading = false
    state.item = item
    state.getUserError = null
  },
  getUserError(state, error) {
    state.getUserLoading = false
    state.getUserError = error
  },
  clearUser(state) {
    state.getUserLoading = false
    state.getUserError = null
    state.item = {}
  },
  clearErrorState(state) {
    state.deleteError = null
    state.saveError = null
  }
}
