<template>
  <el-card v-loading="getUserLoading && clientListLoading" class="card">
    <div slot="header">
      <span>
        {{ needUpdate ? "Modifier l'user" : 'Créer un user' }}
      </span>
    </div>
    <el-form @submit.native.prevent>
      <el-form-item
        label="Nom"
        required
        :error="errorMessage(saveError, 'name')"
      >
        <el-input v-model="item.name" placeholder="Nom" :disabled="needUpdate">
        </el-input>
      </el-form-item>
      <el-form-item
        label="Email"
        required
        :error="errorMessage(saveError, 'email')"
      >
        <el-input
          v-model="item.email"
          placeholder="Email"
          :disabled="needUpdate"
        >
        </el-input>
      </el-form-item>
      <el-row>
        <el-col :span="12">
          <el-form-item
            label="Client"
            required
            :error="errorMessage(saveError, 'client_id')"
          >
            <el-select
              v-model="item.client_id"
              placeholder="Client"
              class="el-col-24"
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
      <el-form-item
        v-if="!needUpdate"
        label="Mot de passe"
        required
        :error="errorMessage(saveError, 'password')"
      >
        <el-input
          v-model="item.password"
          placeholder="Mot de passe"
          show-password
        >
        </el-input>
        <el-input
          v-model="item.confirm_password"
          placeholder="Confirmez le mot de passe"
          show-password
        >
        </el-input>
      </el-form-item>
      <el-row>
        <el-col :span="12">
          <el-button type="text" @click="cancel">
            Annuler
          </el-button>
        </el-col>
        <el-col :span="12">
          <el-button :loading="saveLoading" type="success" @click="submit">
            {{ needUpdate ? 'Modifier' : 'Enregistrer' }}
          </el-button>
        </el-col>
      </el-row>
    </el-form>
  </el-card>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import { errorMessage } from '@/services/api'
import router from '@/router'
export default {
  name: 'UseristratorsSave',
  data() {
    return {}
  },
  computed: {
    ...mapState('users', [
      'item',
      'saveLoading',
      'saveError',
      'getUserLoading'
    ]),
    ...mapState('clients', {
      clientlist: 'list',
      clientListLoading: 'listLoading'
    }),
    needUpdate() {
      return (
        this.$route.params.id !== undefined && this.$route.params.id !== null
      )
    }
  },
  mounted: function() {
    this.getList({ perPage: 1000 })
    if (this.needUpdate) {
      this.getUser({ id: this.$route.params.id })
    } else {
      this.clearUser()
    }
  },
  methods: {
    ...mapActions('users', ['save', 'getUser', 'clearUser']),
    ...mapActions('clients', ['getList']),
    errorMessage,
    async submit() {
      await this.save({
        ...this.item
      })
      if (this.saveError === null) {
        return router.push({
          name: 'user-list'
        })
      }
    },
    cancel() {
      return router.push({
        name: 'user-list'
      })
    }
  }
}
</script>

<style></style>
