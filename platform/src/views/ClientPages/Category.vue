<template>
  <div class="category">
    <transition name="fade">
      <div v-if="isTop">
        <page-detail-bar
          :title="item.name ? item.name : ''"
          :short-title="item.name ? item.name : ''"
          :description="item.description ? item.description : ''"
          :add-service-favorite="toggleServiceFavorite"
          :category-id="item.id"
          :favorite="item.favorite"
          :image-url="item.assets ? item.assets.full_path : ''"
        >
          <bread-crumb
            slot="breadcrumb"
            :slot-size="2"
            class="hidden-sm-and-down"
            link1="/categories"
          >
            <div slot="1">{{ $t('clientPages.category_concierge') }}</div>
            <div slot="2">{{ item.name }}</div>
          </bread-crumb>
        </page-detail-bar>
      </div>
    </transition>
    <div class="global-padding">
      <el-row :gutter="50">
        <transition name="fade">
          <el-col v-if="isTop" :xs="24" :lg="8" class="mb-20">
            <contact />
          </el-col>
        </transition>

        <el-col
          v-infinite-scroll="loadMore"
          class="infinite-loading"
          :xs="24"
          :lg="16"
          infinite-scroll-disabled="infiniteLoading"
          @scroll.native="handleScroll"
        >
          <products
            :product-list="infiniteList"
            :products-favorite="toggleProductFavorite"
          />
          <div
            v-if="listLoading"
            v-loading="listLoading"
            class="text-center"
          ></div>
          <div v-if="noMore && infiniteList.length <= 0" class="text-center">
            <el-card class="no-product">
              <h2>{{ $t('products.no_result_title') }}</h2>
              <span>
                {{ $t('products.no_result_desc') }}
              </span>
            </el-card>
          </div>
        </el-col>
      </el-row>
    </div>
  </div>
</template>
<script>
import { mapActions, mapState } from 'vuex'
import PageDetailBar from '@/components/PageDetailBar.vue'
import BreadCrumb from '@/components/BreadCrumb.vue'
import Products from '@/components/products/Products.vue'
import Contact from '@/components/Contact.vue'

export default {
  name: 'Category',
  components: {
    PageDetailBar,
    BreadCrumb,
    Products,
    Contact
  },
  data() {
    return {
      infiniteList: [],
      isTop: true,
      currentPage: 1,
      orderProp: 'products.id',
      order: 'asc',
      perPage: 5,
      filters: '',
      serviceId: 0,
      noMore: false
    }
  },
  computed: {
    ...mapState('products', [
      'productlist',
      'getProductLoading',
      'listLoading',
      'productFavorite'
    ]),
    ...mapState('services', ['item']),
    infiniteLoading() {
      return this.noMore || this.listLoading
    }
  },
  watch: {
    productlist(newValue) {
      this.infiniteList = [...this.infiniteList, ...newValue.data]
      if (newValue.data.length === 0) {
        this.noMore = true
      }
    }
  },
  mounted() {
    this.loadData()
    this.loadServices()
  },
  created() {
    window.addEventListener('scroll', this.handleScroll)
  },
  beforeDestroy() {
    window.removeEventListener('scroll', this.handleScroll)
  },
  methods: {
    ...mapActions('services', ['favoriteService', 'getService']),
    ...mapActions('products', ['getProductList', 'favoriteProduct']),

    handleScroll(e) {
      if (window.matchMedia('(max-width: 768px)').matches) {
        this.isTop = e.target.scrollTop > 0 ? false : true
      } else {
        this.isTop = true
      }
    },
    loadData() {
      this.getProductList({
        page: this.currentPage,
        perPage: this.perPage,
        order: this.order,
        orderProp: this.orderProp,
        filters: this.filters,
        serviceId: this.$route.params.id
      })
    },
    loadServices() {
      this.getService({ id: this.$route.params.id })
    },
    async toggleProductFavorite(event) {
      await this.favoriteProduct({
        product: event
      })
      this.changeFavorite()
    },
    async toggleServiceFavorite(value) {
      await this.favoriteService({
        service: value
      })
      this.loadServices()
    },
    loadMore() {
      this.page += this.currentPage += 1
      this.loadData()
    },
    changeFavorite() {
      for (var i in this.infiniteList) {
        if (this.infiniteList[i].id === this.productFavorite.product_id) {
          this.infiniteList[i].favorite = this.productFavorite.status
          break
        }
      }
    }
  }
}
</script>
<style lang="scss" scoped>
@import '~@/assets/styles/_variables.scss';

.fade-enter-active,
.fade-leave-active {
  transition: all 0.3s ease-in-out;
  max-height: 230px;
}
.fade-enter,
.fade-leave-to {
  opacity: 0;
  max-height: 0px;
}

.infinite-loading {
  overflow: auto;
  @media screen and (max-width: $--sm) {
    height: calc(100vh - 90px);
  }
  height: calc(100vh - 80px - 150px - 54px - 20px - 10px);
}
</style>
