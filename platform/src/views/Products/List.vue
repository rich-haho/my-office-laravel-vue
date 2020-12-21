<template>
  <el-row
    v-if="
      can('manage my products only') || can('manage bookings') || isSuperAdmin
    "
  >
    <el-row type="flex" class="search-container" :gutter="10">
      <el-col :span="8" class="filters">
        <el-form @submit.native.prevent>
          <el-row>
            <el-col>
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
      </el-col>
      <el-col :span="16" class="moment-switch">
        <div class="switch-caption">
          Produits du moment seulement
        </div>
        <el-switch
          v-model="momentProducts"
          active-value="1"
          inactive-value="0"
          active-color="#039be4"
          inactive-color="#c8c8c8"
          @change="onSearchMomentProjectsChange"
        >
        </el-switch>
      </el-col>
    </el-row>

    <el-divider></el-divider>
    <el-row v-show="can('manage products')">
      <el-col :span="6" :offset="18" class="mb-10">
        <el-button
          type="success"
          icon="el-icon-circle-plus"
          @click="addProduct"
        >
          Ajouter un produit
        </el-button>
      </el-col>
    </el-row>
    <el-table
      v-loading="listLoading"
      :data="productlist.data"
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
        prop="products.id"
        sortable="custom"
      >
        <template slot-scope="scope">
          <span>
            {{ scope.row.id }}
          </span>
        </template>
      </el-table-column>

      <el-table-column
        label="Service"
        min-width="70"
        prop="products.service_id"
        sortable="custom"
      >
        <template slot-scope="scope">
          <span>
            {{ scope.row.service ? scope.row.service : '' }}
          </span>
        </template>
      </el-table-column>

      <el-table-column
        label="Nom"
        min-width="70"
        prop="products.name"
        sortable="custom"
      >
        <template slot-scope="scope">
          <span>
            {{ scope.row.name ? scope.row.name : '' }}
          </span>
        </template>
      </el-table-column>

      <el-table-column
        label="Prix"
        min-width="50"
        prop="products.price"
        sortable="custom"
      >
        <template slot-scope="scope">
          <span>
            {{ scope.row.price | money }}
          </span>
        </template>
      </el-table-column>

      <el-table-column label="Devise" min-width="50">
        <template slot-scope="scope">
          <span>
            {{ scope.row.currency }}
          </span>
        </template>
      </el-table-column>

      <el-table-column label="Partenaire" min-width="70">
        <template slot-scope="scope">
          <span>
            {{ scope.row.partner ? scope.row.partner : '' }}
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
              @click="editProduct(scope.row)"
            >
            </el-button>
            <el-button
              v-if="can('manage products')"
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
      :meta="productlist.meta"
      :page-change="pageChange"
      :size-change="sizeChange"
    />
    <removal
      :on-delete="deleteProduct"
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
  name: 'ProductsList',
  components: { Removal, Paginator },
  data() {
    return {
      confirmDeletion: false,
      productToRemove: null,
      currentPage: 1,
      orderProp: 'products.id',
      order: 'asc',
      perPage: 10,
      filters: '',
      momentProducts: 0
    }
  },
  computed: {
    ...mapState('products', ['productlist', 'listLoading', 'deleteLoading']),
    ...mapGetters('auth', ['can', 'isSuperAdmin'])
  },
  created() {
    this.debounceLoadData = _.debounce(this.loadData, 300)
  },
  mounted: function() {
    this.getProductList({
      list: 1
    })
  },
  methods: {
    ...mapActions('products', ['getProductList', 'destroy']),
    loadData() {
      this.getProductList({
        list: 1,
        page: this.currentPage,
        perPage: this.perPage,
        order: this.order,
        orderProp: this.orderProp,
        filters: this.filters,
        momentProducts: this.momentProducts
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
    addProduct() {
      this.$router.push({ name: 'productSave' })
    },
    editProduct(product) {
      this.$router.push({ name: 'productSave', params: { id: product.id } })
    },

    showDeleteModal(product) {
      this.productToRemove = product
      this.confirmDeletion = true
    },
    async deleteProduct() {
      await this.destroy(this.productToRemove.id)
      this.confirmDeletion = false
      this.loadData()
    },
    onSearchMomentProjectsChange(value) {
      this.momentProducts = value
      this.loadData()
    }
  }
}
</script>
<style lang="scss" scoped>
.search-container {
  margin-top: 0;
  margin-bottom: 0;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.moment-switch {
  display: flex;
  justify-content: flex-start;
  align-items: flex-end;
  min-width: 380px;
  cursor: pointer;
  padding-right: 5px;

  .switch-caption {
    margin-right: 10px;
  }
}
</style>
