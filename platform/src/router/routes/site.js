import SitesList from '@/views/Sites/List'
import SitesSave from '@/views/Sites/Save'

const routes = [
  {
    name: 'site-list',
    path: 'sites',
    component: SitesList,
    meta: { layout: 'admin', guard: 'admin', permissions: ['manage sites'] }
  },
  {
    name: 'site-save',
    path: 'sites/save/:id?',
    component: SitesSave,
    meta: { layout: 'admin', guard: 'admin', permissions: ['manage sites'] }
  }
]

export default routes
