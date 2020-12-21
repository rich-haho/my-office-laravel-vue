import Vue from 'vue'
import Vuex from 'vuex'

import common from './common'
import auth from './auth'
import clients from './clients'
import sites from './sites'
import partners from './partners'
import admins from './admins'
import users from './users'
import services from './services'
import products from './products'
import locales from './locales'
import bookings from './bookings'
import searches from './searches'
import currencies from './currencies'
import demand from './demand'
import fmServices from './fmservices'

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    common,
    auth,
    clients,
    sites,
    services,
    partners,
    admins,
    users,
    products,
    bookings,
    searches,
    currencies,
    demand,
    fmServices,
    locales
  }
})

export default store
