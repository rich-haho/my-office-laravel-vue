export default {
  listLoading(state) {
    state.listLoading = true
    state.listError = null
  },
  listSuccess(state, searchesList) {
    state.searchesList = searchesList
    state.listLoading = false
    state.listError = null
  },
  listError(state, error) {
    state.listLoading = false
    state.listError = error
  }
}
