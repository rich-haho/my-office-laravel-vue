<template>
  <el-row v-if="isSuperAdmin">
    <el-form @submit.native.prevent>
      <el-row class="filters" :gutter="10">
        <el-col :span="12">
          <el-form-item>
            <el-input
              v-model="filters.name"
              :loading="listLoading"
              placeholder="Nom"
              clearable
              @input="filterData"
            >
            </el-input>
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item>
            <el-select
              v-model="filters.client"
              :loading="clientListLoading"
              placeholder="Client"
              clearable
              @change="filterData"
            >
              <el-option
                v-for="client in clientlist.data"
                :key="client.id"
                :label="client.name"
                :value="client.id"
              >
              </el-option>
            </el-select>
          </el-form-item>
        </el-col>
      </el-row>
    </el-form>
    <el-table
      v-loading="listLoading || resendLoading"
      :data="userlist.data"
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
        min-width="25"
        prop="users.id"
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
        prop="users.name"
        sortable="custom"
      >
        <template slot-scope="scope">
          <span>
            {{ scope.row.name }}
          </span>
        </template>
      </el-table-column>
      <el-table-column
        label="Email"
        min-width="150"
        prop="users.email"
        sortable="custom"
      >
        <template slot-scope="scope">
          <span>
            {{ scope.row.email }}
          </span>
        </template>
      </el-table-column>
      <el-table-column label="Client" min-width="150">
        <template slot-scope="scope">
          <span>
            {{ scope.row.client.name }}
          </span>
        </template>
      </el-table-column>
      <el-table-column label="Date d'inscription" min-width="100">
        <template slot-scope="scope">
          <span>
            {{ scope.row.date | datetime }}
          </span>
        </template>
      </el-table-column>
      <el-table-column label="Réservations" min-width="50">
        <template slot-scope="scope">
          <span>
            {{ scope.row.bookings }}
          </span>
        </template>
      </el-table-column>

      <el-table-column min-width="100" align="center">
        <template slot-scope="scope">
          <el-row class="action-list" :gutter="20">
            <el-button
              type="warning"
              size="small"
              icon="el-icon-edit"
              @click="editUser(scope.row)"
            >
            </el-button>
            <el-button
              type="danger"
              size="small"
              icon="el-icon-delete"
              @click="showDeleteModal(scope.row)"
            >
            </el-button>
            <el-button
              type="success"
              size="small"
              icon="el-icon-s-promotion"
              @click="resendVerification(scope.row)"
            >
            </el-button>
            <el-switch
              v-model="scope.row.active"
              class="pl-10"
              @change="showDeactivateModal(scope.row)"
            >
            </el-switch>
          </el-row>
        </template>
      </el-table-column>
    </el-table>

    <paginator
      :meta="userlist.meta"
      :page-change="pageChange"
      :size-change="sizeChange"
    />
    <deactivate
      :title="deactivateTitle"
      :button-text="buttonText"
      :button-type="buttonType"
      :on-deactivate="deactivateUser"
      :show="confirmDeactivate"
      :on-close="
        () => {
          confirmDeactivate = false
          loadData()
        }
      "
      :deactivate-loading="saveLoading"
    />
    <removal
      :on-delete="deleteUser"
      :show="confirmDeletion"
      :on-close="
        () => {
          confirmDeletion = false
        }
      "
      :delete-loading="deleteLoading"
    />
  </el-row>
</template>

<script>
import { mapState, mapActions, mapGetters } from 'vuex'
import Paginator from '@/components/Paginator'
import Deactivate from '@/components/users/Deactivate'
import Removal from '@/components/Removal'
import _ from 'lodash'

export default {
  name: 'UsersList',
  components: { Deactivate, Paginator, Removal },
  data() {
    return {
      confirmDeletion: false,
      userToRemove: null,
      deactivateTitle: '',
      buttonType: '',
      buttonText: '',
      confirmDeactivate: false,
      userToDeactivate: null,
      currentPage: 1,
      orderProp: 'users.id',
      order: 'asc',
      perPage: 10,
      filters: { name: '', client: '' }
    }
  },
  computed: {
    ...mapState('users', [
      'userlist',
      'listLoading',
      'saveLoading',
      'deleteLoading',
      'resendError',
      'resendLoading'
    ]),
    ...mapState('clients', {
      clientlist: 'list',
      clientListLoading: 'listLoading'
    }),
    ...mapGetters('auth', ['isSuperAdmin'])
  },
  created() {
    this.debounceLoadData = _.debounce(this.loadData, 300)
  },
  mounted: function() {
    this.loadData()
  },
  methods: {
    ...mapActions('users', [
      'getUserList',
      'save',
      'destroy',
      'resend',
      'clearErrorState'
    ]),
    ...mapActions('clients', ['getList']),
    loadData() {
      this.getList({ perPage: 1000 })
      this.getUserList({
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
    editUser(user) {
      this.clearErrorState()
      this.$router.push({ name: 'user-save', params: { id: user.id } })
    },
    showDeactivateModal(user) {
      if (user.active) {
        this.deactivateTitle = 'Voulez vous activer cet utilisateur ?'
        this.buttonText = 'Activer'
        this.buttonType = 'success'
      } else {
        this.deactivateTitle = 'Voulez vous désactiver cet utilisateur ?'
        this.buttonText = 'Désactiver'
        this.buttonType = 'danger'
      }
      this.userToDeactivate = user
      this.confirmDeactivate = true
    },
    async deactivateUser() {
      await this.save({
        ...this.userToDeactivate
      })
      this.confirmDeactivate = false
      this.loadData()
    },
    showDeleteModal(user) {
      this.userToRemove = user
      this.confirmDeletion = true
    },
    async deleteUser() {
      await this.destroy(this.userToRemove.id)
      this.confirmDeletion = false
      this.loadData()
    },
    async resendVerification(user) {
      await this.resend(user)
      if (!this.resendError) {
        this.$message({
          message: 'Email envoyé avec succès.',
          type: 'success'
        })
      }
    }
  }
}
</script>
