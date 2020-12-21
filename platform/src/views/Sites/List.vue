<template>
  <el-row v-if="can('manage sites') || isSuperAdmin">
    <el-form @submit.native.prevent>
      <el-row class="filters" :gutter="10">
        <el-col :span="8">
          <el-form-item label="Filtres">
            <el-input
              v-model="filters"
              :loading="listLoading"
              placeholder="Nom"
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
        <el-button type="success" icon="el-icon-circle-plus" @click="addSite">
          Ajouter un site
        </el-button>
      </el-col>
    </el-row>
    <el-table
      v-loading="listLoading"
      :data="sitelist.data"
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
        min-width="50"
        prop="sites.id"
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
        min-width="150"
        prop="sites.name"
        sortable="custom"
      >
        <template slot-scope="scope">
          <span>
            {{ scope.row.name }}
          </span>
        </template>
      </el-table-column>
      <el-table-column label="Client" min-width="200">
        <template slot-scope="scope">
          <span>
            {{ scope.row.client.name }}
          </span>
        </template>
      </el-table-column>
      <el-table-column label="Adresse" min-width="200">
        <template slot-scope="scope">
          <span>
            {{ scope.row.address }}
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
              @click="editSite(scope.row)"
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
      :meta="sitelist.meta"
      :page-change="pageChange"
      :size-change="sizeChange"
    />
    <removal
      :on-delete="deleteSite"
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
  name: 'SitesList',
  components: { Removal, Paginator },
  data() {
    return {
      confirmDeletion: false,
      siteToRemove: null,
      currentPage: 1,
      orderProp: 'sites.id',
      order: 'asc',
      perPage: 10,
      filters: ''
    }
  },
  computed: {
    ...mapState('sites', ['sitelist', 'listLoading', 'deleteLoading']),
    ...mapGetters('auth', ['can', 'isSuperAdmin'])
  },
  created() {
    this.debounceLoadData = _.debounce(this.loadData, 300)
  },
  mounted: function() {
    this.getSiteList({})
  },
  methods: {
    ...mapActions('sites', ['getSiteList', 'destroy']),
    loadData() {
      this.getSiteList({
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
    addSite() {
      this.$router.push({ name: 'site-save' })
    },
    editSite(site) {
      this.$router.push({ name: 'site-save', params: { id: site.id } })
    },

    showDeleteModal(site) {
      this.siteToRemove = site
      this.confirmDeletion = true
    },
    async deleteSite() {
      await this.destroy(this.siteToRemove.id)
      this.confirmDeletion = false
      this.loadData()
    }
  }
}
</script>
