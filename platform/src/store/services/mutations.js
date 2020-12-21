export default {
  listLoading(state) {
    state.listLoading = true
    state.listError = null
  },
  listSuccess(state, servicelist) {
    state.servicelist = servicelist
    state.listLoading = false
    state.listError = null
  },
  listError(state, error) {
    state.listLoading = false
    state.listError = error
  },
  favoriteListLoading(state) {
    state.favoriteServiceListLoading = true
    state.favoriteListError = null
  },
  favoriteListSuccess(state, list) {
    state.favoriteServiceList = list
    state.favoriteServiceListLoading = false
    state.favoriteListError = null
  },
  favoriteListError(state, error) {
    state.favoriteServiceListLoading = false
    state.favoriteListError = error
  },
  deleteLoading(state) {
    state.deleteLoading = true
    state.deleteError = null
  },
  deleteSuccess(state, id) {
    state.deleteLoading = false
    state.servicelist.splice(
      state.servicelist.findIndex(item => item.id === id),
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
  saveSuccess(state, service) {
    state.saveLoading = false
    state.servicelist.data = [...state.servicelist.data, service]
    state.createError = null
    state.item = {
      names: {},
      descriptions: {}
    }
  },
  saveError(state, error) {
    state.saveLoading = false
    state.saveError = error
  },

  getServiceLoading(state) {
    state.getServiceLoading = true
    state.getServiceError = null
  },
  getServiceSuccess(state, item) {
    state.getServiceLoading = false
    state.item = item
    state.getServiceError = null
  },
  getServiceError(state, error) {
    state.getServiceLoading = false
    state.getServiceError = error
  },
  clearService(state) {
    state.getServiceLoading = false
    state.getServiceError = null
    state.item = {
      names: {},
      descriptions: {}
    }
  },
  clearErrorState(state) {
    state.deleteError = null
    state.saveError = null
  },
  saveServiceFavoriteLoading(state) {
    state.saveProductFavoriteLoading = false
    state.saveProductFavoriteError = null
  },
  saveServiceFavoriteSuccess(state, favorite) {
    state.saveProductFavoriteLoading = false
    state.saveProductFavoriteError = null
    state.productFavorite = favorite
    state.saveProductFavoriteSuccess = true
  },
  saveServiceFavoriteError(state, errors) {
    state.saveProductFavoriteLoading = false
    state.saveProductFavoriteError = errors
  }
}
