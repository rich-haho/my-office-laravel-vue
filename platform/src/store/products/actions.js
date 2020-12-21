import api from '@/services/api'

export const getProductList = async (
  { commit, rootGetters },
  {
    list = 0,
    page,
    order,
    orderProp,
    filters,
    perPage = 10,
    momentProducts,
    serviceId
  }
) => {
  commit('listLoading')
  try {
    let url = '/products'
    if (rootGetters['auth/isAdmin'] === true) {
      url = '/admin/products'
    }
    const response = await api.get(url, {
      params: {
        list,
        page,
        order,
        orderProp,
        filters,
        perPage,
        moment: momentProducts,
        serviceId
      }
    })
    commit('listSuccess', response.data)
  } catch (e) {
    commit('listError', e.response.data)
  }
}

export const searchProducts = async ({ commit }, { filters, perPage = 10 }) => {
  commit('searchProductsLoading')
  try {
    let url = '/products'
    const response = await api.get(url, {
      params: {
        filters,
        perPage
      }
    })
    commit('searchProductsSuccess', response.data)
  } catch (e) {
    commit('searchProductsError', e.response.data)
  }
}

export const getProductFavoriteList = async ({ commit }) => {
  commit('favoriteListLoading')
  try {
    const response = await api.get('/products', {
      params: {
        favorite: 1,
        perPage: 50
      }
    })
    commit('favoriteListSuccess', response.data)
  } catch (e) {
    commit('favoriteListError', e.response.data)
  }
}

export const destroy = async ({ commit }, id) => {
  commit('deleteLoading')
  try {
    await api.delete('/admin/products/' + id)
    commit('deleteSuccess', id)
  } catch (e) {
    commit('deleteError', e.message)
  }
}

export const getProduct = async ({ commit, rootGetters }, { id }) => {
  commit('getProductLoading')
  try {
    let response = null
    if (rootGetters['auth/isAdmin'] === true) {
      response = await api.get('/admin/products/' + id)
    } else {
      response = await api.get('/products/' + id)
    }
    commit('getProductSuccess', response.data.data)
  } catch (e) {
    commit('getProductError', e.response.data)
  }
}

export const clearProduct = async ({ commit }) => {
  commit('clearProduct')
}

export const save = async ({ commit }, { id, formdata }) => {
  commit('saveLoading')
  try {
    const response = await api.post(
      '/admin/products' + (id ? '/' + id : ''),
      formdata,
      { headers: { 'Content-Type': 'multipart/form-data' } }
    )
    commit('saveSuccess', response.data.data)
  } catch (e) {
    commit('saveError', e.response.data)
  }
}

export const getClientSiteList = async ({ commit }, { clientId }) => {
  commit('clientSitesListLoading')
  try {
    const response = await api.get('/admin/product/sites', {
      params: {
        clientId
      }
    })
    commit('clientSitesListSuccess', response.data.data)
  } catch (e) {
    commit('clientSitesListError', e.response.data)
  }
}

export const getTags = async ({ commit }) => {
  commit('getTagsLoading')
  try {
    const response = await api.get('/admin/product/tags')
    commit('tagsSuccess', response.data.data)
  } catch (e) {
    commit('getTagsError', e.response.data)
  }
}

export const favoriteProduct = async ({ commit }, { product }) => {
  commit('saveProductFavoriteLoading')
  try {
    const response = await api.post(`/products/${product}/favorites`)
    commit('saveProductFavoriteSuccess', response.data.data)
  } catch (e) {
    commit('saveProductFavoriteError', e.response.data)
  }
}

export const getLinkedProducts = async ({ commit }, { id, count = 2 }) => {
  commit('linkedProductsLoading')
  try {
    const response = await api.get('/products/' + id + '/linked', {
      params: {
        count
      }
    })
    commit('linkedProductsSuccess', response.data.data)
  } catch (e) {
    commit('linkedProductsError', e.response.data)
  }
}

export const saveRating = async ({ commit }, { product_id, rate }) => {
  commit('saveRatingLoading')
  try {
    const response = await api.post('/products/' + product_id + '/rating', {
      product_id,
      rate
    })
    commit('saveRatingSuccess', response.data.data)
  } catch (e) {
    commit('saveRatingError', e.response.data)
  }
}

export default {
  getProductList,
  destroy,
  getProduct,
  clearProduct,
  save,
  saveRating,
  getTags,
  getClientSiteList,
  favoriteProduct,
  getProductFavoriteList,
  getLinkedProducts,
  searchProducts
}
