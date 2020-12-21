<template>
  <div class="favorites-container">
    <div
      v-if="favoriteServiceList.data.length || favoriteProductList.data.length"
      class="list-title"
    >
      {{ $t('homePartials.favorites_container_title') }}
    </div>
    <el-row
      v-loading="favoriteLoading"
      :gutter="30"
      class="cards-container horizontal"
    >
      <el-col
        v-for="category in favoriteServiceList.data"
        :key="'category-' + category.id"
        :xs="12"
        :md="6"
        :lg="4"
      >
        <router-link :to="'/category/' + category.id" class="router-link">
          <zen-card
            :name="category.name"
            :image="category.assets ? category.assets.full_path : ''"
            :min-width-auto="false"
          />
        </router-link>
      </el-col>

      <el-col
        v-for="product in favoriteProductList.data"
        :key="'product-' + product.id"
        :xs="12"
        :md="6"
        :lg="4"
      >
        <router-link :to="'/product/' + product.id" class="router-link">
          <zen-card
            :name="product.name"
            :image="product.assets ? product.assets.full_path : ''"
            :min-width-auto="false"
          />
        </router-link>
      </el-col>
    </el-row>
  </div>
</template>
<script>
import { mapState, mapActions } from 'vuex'
import ZenCard from '@/components/ZenCard.vue'

export default {
  components: {
    ZenCard
  },
  data() {
    return {}
  },
  computed: {
    ...mapState('services', [
      'favoriteServiceList',
      'favoriteServiceListLoading'
    ]),
    ...mapState('products', [
      'favoriteProductList',
      'favoriteProductListLoading'
    ]),
    favoriteLoading() {
      return this.favoriteServiceListLoading || this.favoriteProductListLoading
    }
  },
  mounted: function() {
    this.getServiceFavoriteList()
    this.getProductFavoriteList()
  },
  methods: {
    ...mapActions('services', ['getServiceFavoriteList']),
    ...mapActions('products', ['getProductFavoriteList'])
  }
}
</script>
<style lang="scss" scoped>
.router-link {
  text-decoration: none;
}
</style>
