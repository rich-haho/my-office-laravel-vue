import PartnersList from '@/views/Partners/List'
import PartnersSave from '@/views/Partners/Save'

const routes = [
  {
    name: 'partner-list',
    path: 'partners',
    component: PartnersList,
    meta: { layout: 'admin', guard: 'admin', permissions: ['manage partners'] }
  },
  {
    name: 'partner-save',
    path: 'partners/save/:id?',
    component: PartnersSave,
    meta: { layout: 'admin', guard: 'admin', permissions: ['manage partners'] }
  }
]

export default routes
