import UsersList from '@/views/Users/List'
import UsersSave from '@/views/Users/Save'

const routes = [
  {
    name: 'user-list',
    path: 'users',
    component: UsersList,
    meta: { layout: 'admin', guard: 'admin', permissions: ['super admin only'] }
  },
  {
    name: 'user-save',
    path: 'users/save/:id?',
    component: UsersSave,
    meta: { layout: 'admin', guard: 'admin', permissions: ['super admin only'] }
  }
]

export default routes
