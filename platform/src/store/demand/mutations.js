export default {
  sendLoading(state) {
    state.sendLoading = true
    state.sendError = null
  },
  sendSuccess(state) {
    state.sendLoading = false
    state.sendError = null
  },
  sendError(state, error) {
    state.sendLoading = false
    state.sendError = error
  }
}
