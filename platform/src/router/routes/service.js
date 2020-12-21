import ServicesList from '@/views/Services/List'
import ServicesSave from '@/views/Services/Save'

const routes = [
  {
    name: 'service-list',
    path: 'services',
    component: ServicesList,
    meta: { layout: 'admin', guard: 'admin', permissions: ['manage services'] }
  },
  {
    name: 'service-save',
    path: 'services/save/:id?',
    component: ServicesSave,
    meta: { layout: 'admin', guard: 'admin', permissions: ['manage services'] }
  }
]

export default routes
