export default {
  listLoading(state) {
    state.listLoading = true
    state.listError = null
  },
  listSuccess(state, adminlist) {
    state.adminlist = adminlist
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
    state.adminlist.splice(state.adminlist.findIndex(item => item.id === id), 1)
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
  saveSuccess(state, admin) {
    state.saveLoading = false
    state.adminlist.data = [...state.adminlist.data, admin]
    state.createError = null
  },
  saveError(state, error) {
    state.saveLoading = false
    state.saveError = error
  },

  getAdminLoading(state) {
    state.getAdminLoading = true
    state.getAdminError = null
  },
  getAdminSuccess(state, item) {
    state.getAdminLoading = false
    state.item = item
    state.getAdminError = null
  },
  getAdminError(state, error) {
    state.getAdminLoading = false
    state.getAdminError = error
  },
  clearAdmin(state) {
    state.getAdminLoading = false
    state.getAdminError = null
    state.item = {}
  },
  listRoleSuccess(state, rolelist) {
    state.rolelist = rolelist
  },
  listRoleError(state) {
    state.rolelist = {}
  },
  clearErrorState(state) {
    state.deleteError = null
    state.saveError = null
  }
}
