<template>
  <div class="category">
    <div class="global-padding">
      <el-row v-if="$route.query.session_id" class="mb-20">
        <el-col :span="24">
          <el-alert
            :title="$t('bookings.status.paid')"
            type="success"
            show-icon
            effect="dark"
          >
          </el-alert>
        </el-col>
      </el-row>
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
          <bookings :booking-list="infiniteList" />
          <div
            v-if="listLoading"
            v-loading="listLoading"
            class="text-center"
          ></div>
          <div v-if="noMore && infiniteList.length <= 0" class="text-center">
            <el-card class="no-booking">
              <h2>{{ $t('bookings.no_result_title') }}</h2>
              <span>
                {{ $t('bookings.no_result_desc') }}
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
import Bookings from '@/components/bookings/Bookings.vue'
import Contact from '@/components/Contact.vue'

export default {
  name: 'Category',
  components: {
    Bookings,
    Contact
  },
  data() {
    return {
      infiniteList: [],
      isTop: true,
      currentPage: 1,
      orderProp: 'bookings.id',
      order: 'asc',
      perPage: 10,
      filters: '',
      noMore: false
    }
  },
  computed: {
    ...mapState('bookings', ['bookinglist', 'listLoading']),
    infiniteLoading() {
      return this.noMore || this.listLoading
    }
  },
  watch: {
    bookinglist(newValue) {
      this.infiniteList = [...this.infiniteList, ...newValue.data]
      if (newValue.data.length === 0) {
        this.noMore = true
      }
    }
  },
  mounted() {
    this.loadData()
  },
  created() {
    window.addEventListener('scroll', this.handleScroll)
  },
  beforeDestroy() {
    window.removeEventListener('scroll', this.handleScroll)
  },
  methods: {
    ...mapActions('bookings', ['getBookingList']),
    handleScroll(e) {
      if (window.matchMedia('(max-width: 768px)').matches) {
        this.isTop = e.target.scrollTop > 0 ? false : true
      } else {
        this.isTop = true
      }
    },
    loadData() {
      this.getBookingList({
        page: this.currentPage,
        perPage: this.perPage,
        order: 'desc',
        orderProp: 'bookings.date',
        filters: this.filters
      })
    },
    loadMore() {
      this.page += this.currentPage += 1
      this.loadData()
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
    height: calc(100vh - 50px);
  }
  height: calc(100vh - 80px - 54px - 20px - 10px);
}
</style>
