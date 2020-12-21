import Vue from 'vue'
import App from './App.vue'
import './registerServiceWorker'
import router from './router'
import store from './store'
import i18n from './plugins/i18n'

Vue.config.productionTip = false

// Layouts
import DefaultLayout from '@/layouts/Default.vue'
import EmptyLayout from '@/layouts/Empty.vue'
import AdminLayout from '@/layouts/Admin.vue'

Vue.component('default-layout', DefaultLayout)
Vue.component('empty-layout', EmptyLayout)
Vue.component('admin-layout', AdminLayout)

// Filters
import filters from './filters'
filters.forEach(f => {
  Vue.filter(f.name, f.filter)
})

// Element UI
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import '@/assets/styles/element-variables.scss'
import locale from 'element-ui/lib/locale/lang/fr'
Vue.use(ElementUI, { locale })

// Ionicons
import 'ionicons/dist/scss/ionicons.scss'

// Global styles
import '@/assets/styles/app.scss'

new Vue({
  i18n,
  router,
  store,
  render: h => h(App)
}).$mount('#app')
