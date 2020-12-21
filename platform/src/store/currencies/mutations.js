export default {
  getCurrenciesLoading(state) {
    state.getCurrenciesLoading = true
    state.getCurrenciesError = null
  },
  getCurrenciesSuccess(state, currencies) {
    state.currenciesList.data = currencies
    state.getCurrenciesLoading = false
    state.getCurrenciesError = null
  },
  getCurrenciesError(state, errors) {
    state.getCurrenciesLoading = false
    state.getCurrenciesError = errors
  }
}
