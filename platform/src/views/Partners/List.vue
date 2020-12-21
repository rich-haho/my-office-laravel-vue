<template>
  <el-row v-if="can('manage partners') || isSuperAdmin">
    <el-form @submit.native.prevent>
      <el-row class="filters" :gutter="10">
        <el-col :span="8">
          <el-form-item label="Filtres">
            <el-input
              v-model="filters"
              :loading="listLoading"
              placeholder=".Nom,Email,Téléphone,Adresse"
              clearable
              @input="filterData"
            >
            </el-input>
          </el-form-item>
        </el-col>
      </el-row>
    </el-form>
    <el-divider></el-divider>
    <el-row>
      <el-col :span="6" :offset="18" class="mb-10">
        <el-button
          type="success"
          icon="el-icon-circle-plus"
          @click="addPartner"
        >
          Ajouter un partenaire
        </el-button>
      </el-col>
    </el-row>
    <el-table
      v-loading="listLoading"
      :data="partnerlist.data"
      :default-sort="{
        prop: orderProp,
        order: order === 'asc' ? 'ascending' : 'descending'
      }"
      fit
      stripe
      @sort-change="sortChange"
    >
      <el-table-column
        label="ID"
        min-width="20"
        prop="partners.id"
        sortable="custom"
      >
        <template slot-scope="scope">
          <span>
            {{ scope.row.id }}
          </span>
        </template>
      </el-table-column>

      <el-table-column
        label="Nom"
        min-width="50"
        prop="partners.name"
        sortable="custom"
      >
        <template slot-scope="scope">
          <span>
            {{ scope.row.name }}
          </span>
        </template>
      </el-table-column>

      <el-table-column
        label="Adresse"
        min-width="100"
        prop="partners.address"
        sortable="custom"
      >
        <template slot-scope="scope">
          <span>
            {{ scope.row.address }}
          </span>
        </template>
      </el-table-column>

      <el-table-column
        label="Email"
        min-width="100"
        prop="partners.email"
        sortable="custom"
      >
        <template slot-scope="scope">
          <span>
            {{ scope.row.email }}
          </span>
        </template>
      </el-table-column>

      <el-table-column
        label="Téléphone"
        min-width="80"
        prop="partners.phone"
        sortable="custom"
      >
        <template slot-scope="scope">
          <span>
            {{ scope.row.phone }}
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
              @click="editPartner(scope.row)"
            >
            </el-button>
            <el-button
              type="danger"
              size="small"
              icon="el-icon-delete"
              @click="showDeleteModal(scope.row)"
            >
            </el-button>
          </el-row>
        </template>
      </el-table-column>
    </el-table>

    <paginator
      :meta="partnerlist.meta"
      :page-change="pageChange"
      :size-change="sizeChange"
    />
    <removal
      :on-delete="deletePartner"
      :show="confirmDeletion"
      :on-close="
        () => {
          confirmDeletion = false
        }
      "
      :delete-loading="deleteLoading"
    ></removal>
  </el-row>
</template>

<script>
import { mapState, mapActions, mapGetters } from 'vuex'
import Paginator from '@/components/Paginator'
import Removal from '@/components/Removal'
import _ from 'lodash'

export default {
  name: 'PartnersList',
  components: { Removal, Paginator },
  data() {
    return {
      confirmDeletion: false,
      partnerToRemove: null,
      currentPage: 1,
      orderProp: 'partners.id',
      order: 'asc',
      perPage: 10,
      filters: ''
    }
  },
  computed: {
    ...mapState('partners', ['partnerlist', 'listLoading', 'deleteLoading']),
    ...mapGetters('auth', ['can', 'isSuperAdmin'])
  },
  created() {
    this.debounceLoadData = _.debounce(this.loadData, 300)
  },
  mounted: function() {
    this.getPartnerList({})
  },
  methods: {
    ...mapActions('partners', ['getPartnerList', 'destroy']),
    loadData() {
      this.getPartnerList({
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
    addPartner() {
      this.$router.push({ name: 'partner-save' })
    },
    editPartner(partner) {
      this.$router.push({ name: 'partner-save', params: { id: partner.id } })
    },

    showDeleteModal(partner) {
      this.partnerToRemove = partner
      this.confirmDeletion = true
    },
    async deletePartner() {
      await this.destroy(this.partnerToRemove.id)
      this.confirmDeletion = false
      this.loadData()
    }
  }
}
</script>
