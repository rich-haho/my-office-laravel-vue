export default {
  listLoading(state) {
    state.listLoading = true
    state.listError = null
  },
  listSuccess(state, sitelist) {
    state.sitelist = sitelist
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
    state.sitelist.splice(state.sitelist.findIndex(item => item.id === id), 1)
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
  saveSuccess(state, site) {
    state.saveLoading = false
    state.sitelist.data = [...state.sitelist.data, site]
    state.createError = null
  },
  saveError(state, error) {
    state.saveLoading = false
    state.saveError = error
  },

  getSiteLoading(state) {
    state.getSiteLoading = true
    state.getSiteError = null
  },
  getSiteSuccess(state, item) {
    state.getSiteLoading = false
    state.item = item
    state.getSiteError = null
  },
  getSiteError(state, error) {
    state.getSiteLoading = false
    state.getSiteError = error
  },
  clearSite(state) {
    state.getSiteLoading = false
    state.getSiteError = null
    state.item = {}
  },
  getSitesLoading(state) {
    state.getSitesLoading = true
    state.getSitesErrors = null
  },
  getSitesSuccess(state, sites) {
    state.getSitesLoading = false
    state.userSitesList.data = sites
    state.getSitesErrors = null
  },
  getSitesError(state, errors) {
    state.getSitesLoading = true
    state.getSitesError = errors
  }
}
