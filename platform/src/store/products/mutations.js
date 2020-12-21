import { getInitialState } from './state'

export default {
  listLoading(state) {
    state.listLoading = true
    state.listError = null
  },
  listSuccess(state, productlist) {
    state.productlist = productlist
    state.listLoading = false
    state.listError = null
  },
  listError(state, error) {
    state.listLoading = false
    state.listError = error
  },
  searchProductsLoading(state) {
    state.searchProductsLoading = true
    state.searchProductsError = null
  },
  searchProductsSuccess(state, payload) {
    state.searchProductList = payload
    state.searchProductsLoading = false
    state.searchProductsError = null
  },
  searchProductsError(state, error) {
    state.searchProductsLoading = false
    state.searchProductsError = error
  },
  favoriteListLoading(state) {
    state.favoriteProductListLoading = true
    state.favoriteListError = null
  },
  favoriteListSuccess(state, list) {
    state.favoriteProductList = list
    state.favoriteProductListLoading = false
    state.favoriteListError = null
  },
  favoriteListError(state, error) {
    state.favoriteProductListLoading = false
    state.favoriteListError = error
  },
  deleteLoading(state) {
    state.deleteLoading = true
    state.deleteError = null
  },
  deleteSuccess(state, id) {
    state.deleteLoading = false
    state.productlist.splice(
      state.productlist.findIndex(item => item.id === id),
      1
    )
    state.deleteError = null
  },
  deleteError(state, error) {
    state.deleteLoading = false
    state.deleteError = error
  },
  saveLoading(state) {
    state.saveLoading = true
    state.saveError = null
  },
  saveSuccess(state, product) {
    state.saveLoading = false
    state.productlist.data = [...state.productlist.data, product]
    state.createError = null
    state.item = getInitialState().item
  },
  saveError(state, error) {
    state.saveLoading = false
    state.saveError = error
  },
  getProductLoading(state) {
    state.getProductLoading = true
    state.getProductError = null
  },
  getProductSuccess(state, item) {
    state.getProductLoading = false
    state.item = item
    state.getProductError = null
  },
  getProductError(state, error) {
    state.getProductLoading = false
    state.getProductError = error
  },
  clearProduct(state) {
    state.getProductLoading = false
    state.getProductError = null
    state.saveError = null
    state.item = getInitialState().item
  },
  clientSitesListLoading(state) {
    state.clientSitesListLoading = true
    state.clientSitesListError = null
  },
  clientSitesListSuccess(state, sites) {
    state.clientSitesListLoading = false
    state.clientSites.data = sites
    state.clientSitesListError = null
  },
  clientSitesListError(state, error) {
    state.clientSitesListLoading = false
    state.clientSitesListError = error
  },
  getTagsLoading(state) {
    state.getTagsLoading = true
    state.getTagsError = null
  },
  tagsSuccess(state, tags) {
    state.getTagsLoading = false
    state.getTagsError = null
    state.tags.data = tags
  },
  getTagsError(state, error) {
    state.getTagsLoading = false
    state.getTagsError = error
  },
  saveProductFavoriteLoading(state) {
    state.saveProductFavoriteLoading = false
    state.saveProductFavoriteError = null
  },
  saveProductFavoriteSuccess(state, favorite) {
    state.saveProductFavoriteLoading = false
    state.saveProductFavoriteError = null
    state.productFavorite = favorite
    state.saveProductFavoriteSuccess = true
  },
  saveProductFavoriteError(state, errors) {
    state.saveProductFavoriteLoading = false
    state.saveProductFavoriteError = errors
  },
  linkedProductsLoading(state) {
    state.linkedProductsLoading = true
    state.linkedProductsError = null
  },
  linkedProductsSuccess(state, likedProducts) {
    state.likedProducts = likedProducts
    state.linkedProductsLoading = false
    state.linkedProductsError = null
  },
  linkedProductsError(state, error) {
    state.linkedProductsLoading = false
    state.linkedProductsError = error
  },
  saveRatingLoading(state) {
    state.saveRatingLoading = true
    state.saveRatingError = null
  },
  saveRatingSuccess(state, product) {
    state.saveRatingLoading = false
    state.saveRatingError = null
    state.item = product
  },
  saveRatingError(state, error) {
    state.saveRatingLoading = false
    state.saveRatingError = error
  }
}
