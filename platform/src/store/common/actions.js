export const setBooting = ({ commit }, booting) => {
  commit('setBooting', booting)
}

export const setGlobalLoading = ({ commit }, globalLoading) => {
  commit('setGlobalLoading', globalLoading)
}

export const setShowMenu = ({ commit }, showMenu) => {
  commit('setShowMenu', showMenu)
}

export default {
  setBooting,
  setGlobalLoading,
  setShowMenu
}
