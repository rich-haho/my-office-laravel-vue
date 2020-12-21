export default {
  connectedUser: state => state.user,
  isLoggedIn: state => state.user !== null,
  isAdmin: state => state.user && state.user.guard === 'admin',
  isSuperAdmin: state =>
    state.user && state.user.guard === 'admin' && state.user.admin === true,
  can: (state, getters) => permissions => {
    return (
      getters['isSuperAdmin'] === true ||
      (state.user &&
        state.user.permissions.find(permission =>
          Array.isArray(permissions)
            ? permissions.includes(permission.name)
            : permission.name === permissions
        ) !== undefined)
    )
  },
  locale: state => {
    if (
      typeof state.user !== 'undefined' &&
      state.user &&
      typeof state.user.state !== 'undefined' &&
      state.user.state
    ) {
      return state.user.state.locale
        ? state.user.state.locale
        : {
            name: null
          }
    }
    return {
      name: null
    }
  },
  site_id: state => {
    if (null !== state.user && null !== state.user.state) {
      return state.user.state.site_id ? state.user.state.site_id : null
    } else {
      return null
    }
  }
}
