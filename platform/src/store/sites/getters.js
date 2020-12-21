import store from '@/store'

export default {
  sitelist: state =>
    state.userSitesList.data !== undefined && state.userSitesList.data.length
      ? state.userSitesList.data
      : [],
  userSite: state => {
    if (state.userSitesList.data.length) {
      const site = state.userSitesList.data.find(
        i => i.id === store.getters['auth/site_id']
      )
      return site ? site : {}
    } else {
      return {}
    }
  },
  fmServices: state => {
    if (state.userSitesList.data.length) {
      const { fm_services } = state.userSitesList.data.find(
        i => i.id === store.getters['auth/site_id']
      )
      return fm_services ? fm_services : false
    } else {
      return false
    }
  }
}
