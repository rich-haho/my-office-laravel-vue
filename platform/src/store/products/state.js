export const getInitialState = () => {
  return {
    productlist: {
      data: [],
      meta: {}
    },
    item: {
      names: {},
      descriptions: {},
      currency: {},
      partner: {},
      service: {},
      sites: [],
      slots: []
    },
    clientSites: {
      data: []
    },
    tags: {
      data: [],
      meta: {}
    },
    listLoading: false,
    listError: null,
    searchProductList: {
      data: [],
      meta: {}
    },
    searchProductsLoading: false,
    searchProductsError: null,
    deleteLoading: false,
    deleteSuccess: null,
    deleteError: null,
    getProductLoading: false,
    getProductError: null,
    saveError: null,
    saveLoading: false,
    getTagsLoading: false,
    getTagsError: null,
    clientSitesListLoading: false,
    clientSitesListSuccess: null,
    clientSitesListError: null,
    saveProductFavoriteLoading: false,
    saveProductFavoriteSuccess: null,
    saveProductFavoriteError: null,
    productFavorite: null,
    favoriteProductList: {
      data: [],
      meta: {}
    },
    favoriteListError: null,
    favoriteProductListLoading: false,
    likedProducts: [],
    linkedProductsLoading: false,
    linkedProductsError: null,
    commissionPercentage: null,
    saveRatingLoading: false,
    saveRatingError: null
  }
}

export default getInitialState()
