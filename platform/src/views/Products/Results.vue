<template>
  <div>
    <div class="home global-padding ">
      <div class="search-container">
        <div class="bottom-box">
          <search-bar
            :default-search-text="$route.params.qs"
            :disable-autocomplete="true"
          />
        </div>
      </div>
      <div v-loading="searchProductsLoading" class="search-result-list">
        <products :product-list="searchProductList.data" />
        <no-more />
      </div>
    </div>
  </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import SearchBar from '@/views/Products/SearchBar'
import Products from '@/components/products/Products'
import NoMore from '@/components/NoMore'

export default {
  components: {
    NoMore,
    Products,
    SearchBar
  },
  data() {
    return {}
  },
  computed: {
    ...mapState('products', ['searchProductList', 'searchProductsLoading'])
  },
  mounted() {
    this.searchProducts({
      perPage: 50,
      filters: this.$route.params.qs
    })
  },
  methods: {
    ...mapActions('products', ['searchProducts'])
  }
}
</script>
