import ProductList from '@/views/Products/List'
import ProductSave from '@/views/Products/Save'

const routes = [
  {
    name: 'product-list',
    path: 'products',
    component: ProductList,
    meta: {
      layout: 'admin',
      guard: 'admin',
      permissions: ['manage my products only', 'manage products']
    }
  },
  {
    name: 'productSave',
    path: 'products/save/:id?',
    component: ProductSave,
    meta: {
      layout: 'admin',
      guard: 'admin',
      permissions: ['manage my products only', 'manage products']
    }
  }
]

export default routes
