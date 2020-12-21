export const getInitialState = () => {
  return {
    partnerlist: {
      data: [],
      meta: {}
    },
    item: {
      public_descriptions: {},
      enable_stripe_connect: false,
      sites: []
    },
    listLoading: false,
    listError: null,
    deleteLoading: false,
    deleteSuccess: null,
    deleteError: null,
    getPartnerLoading: false,
    saveError: null,
    saveLoading: false
  }
}

export default getInitialState()
