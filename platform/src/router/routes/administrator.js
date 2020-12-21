import AdministratorsList from '@/views/Administrators/List'
import AdministratorsSave from '@/views/Administrators/Save'

const routes = [
  {
    name: 'admin-list',
    path: 'admins',
    component: AdministratorsList,
    meta: {
      layout: 'admin',
      guard: 'admin',
      permissions: ['super amdinistrator only']
    }
  },
  {
    name: 'admin-save',
    path: 'admins/save/:id?',
    component: AdministratorsSave,
    meta: {
      layout: 'admin',
      guard: 'admin',
      permissions: ['super amdinistrator only']
    }
  }
]

export default routes
