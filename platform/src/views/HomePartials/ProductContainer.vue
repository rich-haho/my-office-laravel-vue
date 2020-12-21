<template>
  <div class="product-container">
    <div class="list-title">{{ $t('products.moment_products') }}</div>
    <el-row :gutter="30" class="cards-container horizontal">
      <el-col v-for="item in productlist.data" :key="item.id" :md="24" :lg="12">
        <product
          location="home"
          :product="item"
          :link-favorite="toggleFavorite"
        />
      </el-col>
    </el-row>
  </div>
</template>
<script>
import { mapState, mapActions } from 'vuex'
import Product from '@/components/products/Product.vue'
import _ from 'lodash'

export default {
  components: {
    Product
  },
  computed: {
    ...mapState('products', ['productlist', 'listLoading', 'productFavorite'])
  },
  created() {
    this.debounceLoadData = _.debounce(this.loadData, 300)
  },
  mounted: function() {
    this.loadData()
  },
  methods: {
    ...mapActions('products', ['getProductList', 'favoriteProduct']),
    loadData() {
      this.getProductList({ perPage: 1000, momentProducts: 1 })
    },
    async toggleFavorite(id) {
      await this.favoriteProduct({
        product: id
      })
      this.loadData()
    }
  }
}
</script>
