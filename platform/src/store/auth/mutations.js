export default {
  loginSuccess(state, user) {
    state.user = user
    state.loginLoading = false
    state.loginError = null
  },
  loginError(state, error) {
    state.user = null
    state.loginLoading = false
    state.loginError = error
  },
  loginLoading(state) {
    state.loginError = null
    state.loginLoading = true
  },
  registerSuccess(state, user) {
    state.user = user
    state.registerLoading = false
    state.registerError = null
  },
  registerError(state, error) {
    state.user = null
    state.registerLoading = false
    state.registerError = error
  },
  registerLoading(state) {
    state.registerError = null
    state.registerLoading = true
  },
  logoutSuccess(state) {
    state.user = null
    state.logoutLoading = false
    state.logoutError = null
  },
  logoutError(state, error) {
    state.logoutLoading = false
    state.logoutError = error
  },
  logoutLoading(state) {
    state.logoutLoading = true
    state.logoutError = null
  },
  meSuccess(state, user) {
    state.user = user
    state.meLoading = false
    state.meError = null
  },
  meError(state, error) {
    state.user = null
    state.meLoading = false
    state.meError = error
  },
  meLoading(state) {
    state.meLoading = true
    state.meError = null
  },
  saveMeSuccess(state, user) {
    state.user = user
    state.saveMeLoading = false
    state.saveMeError = null
  },
  saveMeError(state, error) {
    state.saveMeLoading = false
    state.saveMeError = error
  },
  saveMeLoading(state) {
    state.saveMeLoading = true
    state.saveMeError = null
  },
  forgotSuccess(state, sentResetLink) {
    state.user = null
    state.sentResetLink = sentResetLink
    state.forgotLoading = false
    state.forgotError = null
  },
  forgotError(state, error) {
    state.user = null
    state.sentResetLink = null
    state.forgotLoading = false
    state.forgotError = error
  },
  forgotLoading(state) {
    state.sentResetLink = null
    state.forgotError = null
    state.forgotLoading = true
  },
  resetSuccess(state, resetStatus) {
    state.user = null
    state.resetStatus = resetStatus
    state.resetLoading = false
    state.resetError = null
  },
  resetError(state, error) {
    state.user = null
    state.resetStatus = null
    state.resetLoading = false
    state.resetError = error
  },
  resetLoading(state) {
    state.resetStatus = null
    state.resetError = null
    state.resetLoading = true
  },
  setSiteLocaleLoading(state) {
    state.setSiteLocaleLoading = true
    state.setSiteLocaleError = null
  },
  setSiteLocaleSuccess(state, user) {
    state.user = user
    state.setSiteLocaleSuccess = true
    state.setSiteLocaleLoading = null
    state.setSiteLocaleError = null
  },
  setSiteLocaleError(state, error) {
    state.setSiteLocaleLoading = false
    state.setSiteLocaleError = error
  },
  askMailVerificationNotificationLoading(state) {
    state.askMailVerificationNotificationLoading = true
    state.askMailVerificationNotificationError = null
  },
  askMailVerificationNotificationSuccess(state) {
    state.askMailVerificationNotificationLoading = null
    state.askMailVerificationNotificationError = null
  },
  askMailVerificationNotificationError(state, error) {
    state.askMailVerificationNotificationLoading = false
    state.askMailVerificationNotificationError = error
  },
  showManageAccount(state) {
    state.manageAccountVisible = true
  },
  closeManageAccount(state) {
    state.manageAccountVisible = false
  }
}
