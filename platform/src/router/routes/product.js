import ProductIndex from '@/views/Products/Index'
import ProductResults from '@/views/Products/Results'

const routes = [
  {
    name: 'product-index',
    path: '/products/:id',
    component: ProductIndex
  },
  {
    name: 'product-search-results',
    path: '/products/search/:qs',
    component: ProductResults
  }
]

export default routes
