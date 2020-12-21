<template>
  <el-row v-if="can('manage clients') || isSuperAdmin">
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
        <el-button type="success" icon="el-icon-circle-plus" @click="addClient">
          Ajouter un client
        </el-button>
      </el-col>
    </el-row>
    <el-table
      v-loading="listLoading"
      :data="list.data"
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
        prop="clients.id"
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
        prop="clients.name"
        sortable="custom"
      >
        <template slot-scope="scope">
          <span>
            {{ scope.row.name }}
          </span>
        </template>
      </el-table-column>
      <el-table-column label="Domaines et adresses" min-width="250">
        <template slot-scope="scope">
          <el-tag
            v-for="domain in scope.row.domains"
            :key="domain.id"
            class="m-5"
            type="info"
          >
            {{ domain.domain }}
          </el-tag>
          <i v-if="!scope.row.domains.length" class="el-icon-warning">
            Aucun domaine email défini
          </i>
        </template>
      </el-table-column>

      <el-table-column min-width="75" align="center">
        <template slot-scope="scope">
          <el-row class="action-list">
            <el-button
              type="primary"
              size="small"
              icon="el-icon-edit"
              @click="editClient(scope.row)"
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
      :meta="list.meta"
      :page-change="pageChange"
      :size-change="sizeChange"
    />
    <removal
      :on-delete="deleteClient"
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
  name: 'ClientsList',
  components: { Removal, Paginator },
  data() {
    return {
      confirmDeletion: false,
      clientToRemove: null,
      currentPage: 1,
      orderProp: 'clients.id',
      order: 'asc',
      perPage: 10,
      filters: ''
    }
  },
  computed: {
    ...mapState('clients', ['list', 'listLoading', 'deleteLoading']),
    ...mapGetters('auth', ['can', 'isSuperAdmin'])
  },
  created() {
    this.debounceLoadData = _.debounce(this.loadData, 300)
  },
  mounted: function() {
    this.getList({})
  },
  methods: {
    ...mapActions('clients', ['getList', 'destroy']),
    loadData() {
      this.getList({
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
      this.debounceLoadData()
    },
    addClient() {
      this.$router.push({ name: 'client-save' })
    },
    editClient(client) {
      this.$router.push({ name: 'client-save', params: { id: client.id } })
    },

    showDeleteModal(client) {
      this.clientToRemove = client
      this.confirmDeletion = true
    },
    async deleteClient() {
      await this.destroy(this.clientToRemove.id)
      this.confirmDeletion = false
      this.loadData()
    }
  }
}
</script>
