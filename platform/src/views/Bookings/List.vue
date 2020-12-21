<template>
  <el-row
    v-if="
      can('manage my bookings only') || can('manage bookings') || isSuperAdmin
    "
  >
    <el-table
      v-loading="listLoading"
      :data="bookinglist.data"
      :default-sort="{
        prop: orderProp,
        order: order === 'asc' ? 'ascending' : 'descending'
      }"
      fit
      stripe
      @sort-change="sortChange"
    >
      <el-table-column
        label="N°"
        min-width="45"
        prop="bookings.id"
        sortable="custom"
        align="center"
      >
        <template slot-scope="scope">
          <span>
            {{ scope.row.id }}
          </span>
        </template>
      </el-table-column>
      <el-table-column
        label="Date de réservation"
        min-width="170"
        prop="bookings.date"
        sortable="custom"
      >
        <template slot-scope="scope">
          <span>
            {{ scope.row.date | datetime }}
          </span>
        </template>
      </el-table-column>
      <el-table-column label="Utilisateur" min-width="150">
        <template slot-scope="scope">
          <span>
            {{ scope.row.user.name }}
          </span>
        </template>
      </el-table-column>
      <el-table-column label="Produit" min-width="170">
        <template slot-scope="scope">
          <span>
            {{ scope.row.product.name }}
          </span>
        </template>
      </el-table-column>
      <el-table-column
        label="Qté"
        min-width="50"
        prop="bookings.quantity"
        sortable="custom"
        align="center"
      >
        <template slot-scope="scope">
          <span>
            {{ scope.row.quantity }}
          </span>
        </template>
      </el-table-column>
      <el-table-column
        label="Statut"
        min-width="100"
        prop="bookings.status"
        sortable="custom"
      >
        <template slot-scope="scope">
          <span>
            {{ scope.row.status.toUpperCase() }}
          </span>
        </template>
      </el-table-column>

      <el-table-column min-width="75" align="center">
        <template slot-scope="scope">
          <el-row class="action-list">
            <el-button
              type="primary"
              size="small"
              icon="el-icon-edit"
              @click="editBooking(scope.row)"
            >
            </el-button>
          </el-row>
        </template>
      </el-table-column>
    </el-table>

    <paginator
      :meta="bookinglist.meta"
      :page-change="pageChange"
      :size-change="sizeChange"
    />
  </el-row>
</template>

<script>
import { mapState, mapActions, mapGetters } from 'vuex'
import Paginator from '@/components/Paginator'
import _ from 'lodash'

export default {
  name: 'BookingsList',
  components: { Paginator },
  data() {
    return {
      currentPage: 1,
      orderProp: 'bookings.id',
      order: 'asc',
      perPage: 10,
      filters: ''
    }
  },
  computed: {
    ...mapState('bookings', ['bookinglist', 'listLoading']),
    ...mapGetters('auth', ['can', 'isSuperAdmin'])
  },
  created() {
    this.debounceLoadData = _.debounce(this.loadData, 300)
  },
  mounted: function() {
    this.loadData()
  },
  methods: {
    ...mapActions('bookings', ['getBookingList', 'destroy']),
    loadData() {
      this.getBookingList({
        page: this.currentPage,
        perPage: this.perPage,
        order: this.order,
        orderProp: this.orderProp,
        filters: this.filters
      })
    },
    pageChange(current) {
      this.currentPage = current
      this.loadData()
    },
    sizeChange(size) {
      this.perPage = size
      this.loadData()
    },
    sortChange({ prop, order }) {
      this.orderProp = prop
      this.order = order === 'descending' ? 'desc' : 'asc'
      this.loadData()
    },
    filterData() {
      this.currentPage = 1
      this.debounceLoadData()
    },
    editBooking(booking) {
      this.$router.push({ name: 'booking-save', params: { id: booking.id } })
    }
  }
}
</script>
