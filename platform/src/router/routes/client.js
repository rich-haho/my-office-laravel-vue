import ClientsList from '@/views/Clients/List'
import ClientsSave from '@/views/Clients/Save'

const routes = [
  {
    name: 'client-list',
    path: 'clients',
    component: ClientsList,
    meta: { layout: 'admin', guard: 'admin', permissions: ['manage clients'] }
  },
  {
    name: 'client-save',
    path: 'clients/save/:id?',
    component: ClientsSave,
    meta: { layout: 'admin', guard: 'admin', permissions: ['manage clients'] }
  }
]

export default routes
