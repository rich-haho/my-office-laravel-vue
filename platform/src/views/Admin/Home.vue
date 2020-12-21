<template>
  <el-row :gutter="50">
    <el-col v-if="can('manage services')" :span="6">
      <el-card v-loading="serviceListLoading">
        <div slot="header" class="slot-header">
          <i class="el-icon-box mr-10"></i>
          <span>Services</span>
        </div>
        <div class="counter">
          {{ serviceList.data.length }}
        </div>
      </el-card>
    </el-col>
    <el-col
      v-if="can('manage products') || can('manage my products only')"
      :span="6"
    >
      <el-card v-loading="productListLoading">
        <div slot="header" class="slot-header">
          <i class="el-icon-shopping-cart-2 mr-10"></i>
          <span>Produits</span>
        </div>
        <div class="counter">
          {{ productList.data.length }}
        </div>
      </el-card>
    </el-col>
    <el-col v-if="can('manage partners')" :span="6">
      <el-card v-loading="partnerListLoading">
        <div slot="header" class="slot-header">
          <i class="el-icon-truck mr-10"></i>
          <span>Partenaires</span>
        </div>
        <div class="counter">
          {{ partnerList.data.length }}
        </div>
      </el-card>
    </el-col>
    <el-col v-if="can('manage sites')" :span="6">
      <el-card v-loading="siteListLoading">
        <div slot="header" class="slot-header">
          <i class="el-icon-location mr-10"></i>
          <span>Sites</span>
        </div>
        <div class="counter">
          {{ siteList.data.length }}
        </div>
      </el-card>
    </el-col>
    <el-col
      v-if="can('manage bookings') || can('manage my bookings only')"
      :span="6"
    >
      <el-card v-loading="bookingListLoading">
        <div slot="header" class="slot-header">
          <i class="el-icon-location mr-10"></i>
          <span>Réservations</span>
        </div>
        <div class="counter">
          {{ bookingList.data.length }}
        </div>
      </el-card>
    </el-col>
  </el-row>
</template>

<script>
import { mapState, mapActions, mapGetters } from 'vuex'

export default {
  computed: {
    ...mapState('auth', ['logoutLoading']),
    ...mapGetters('auth', ['connectedUser', 'isSuperAdmin', 'can']),
    ...mapState('services', {
      serviceList: 'servicelist',
      serviceListLoading: 'listLoading'
    }),
    ...mapState('products', {
      productList: 'productlist',
      productListLoading: 'listLoading'
    }),
    ...mapState('partners', {
      partnerList: 'partnerlist',
      partnerListLoading: 'listLoading'
    }),
    ...mapState('sites', {
      siteList: 'sitelist',
      siteListLoading: 'listLoading'
    }),
    ...mapState('bookings', {
      bookingList: 'bookinglist',
      bookingListLoading: 'listLoading'
    })
  },
  mounted() {
    if (this.can('manage services')) {
      this.getServiceList({
        perPage: 100
      })
    }
    if (this.can('manage products') || this.can('manage my products only')) {
      this.getProductList({
        perPage: 100,
        list: 1
      })
    }
    if (this.can('manage partners')) {
      this.getPartnerList({
        perPage: 100
      })
    }
    if (this.can('manage sites')) {
      this.getSiteList({
        perPage: 100
      })
    }
    if (this.can('manage bookings') || this.can('manage my bookings only')) {
      this.getBookingList({
        perPage: 100
      })
    }
  },
  methods: {
    ...mapActions('auth', ['logout']),
    ...mapActions('services', ['getServiceList']),
    ...mapActions('products', ['getProductList']),
    ...mapActions('partners', ['getPartnerList']),
    ...mapActions('sites', ['getSiteList']),
    ...mapActions('bookings', ['getBookingList'])
  }
}
</script>

<style scoped>
.slot-header {
  font-size: 20px;
  text-align: center;
}
.counter {
  font-size: 50px;
  text-align: center;
}
.el-card {
  margin-bottom: 20px;
}
</style>
