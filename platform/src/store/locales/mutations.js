export default {
  getLocalesLoading(state) {
    state.getLocalesLoading = true
    state.getLocalesError = null
  },
  getLocalesSuccess(state, locales) {
    state.getLocalesSuccess = true
    state.localesList.data = locales
    state.getLocalesLoading = false
    state.getLocalesError = null
  },
  getLocalesError(state, errors) {
    state.getLocalesLoading = false
    state.getLocalesError = errors
  }
}
