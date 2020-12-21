<template>
  <el-card v-loading="getAdminLoading" class="card">
    <div slot="header">
      <span>
        {{
          needUpdate ? "Modifier l'administrateur" : 'Créer un administrateur'
        }}
      </span>
    </div>
    <el-form @submit.native.prevent>
      <el-form-item
        label="Nom"
        required
        :error="errorMessage(saveError, 'name')"
      >
        <el-input v-model="item.name" placeholder="Nom"> </el-input>
      </el-form-item>
      <el-form-item
        label="Email"
        required
        :error="errorMessage(saveError, 'email')"
      >
        <el-input v-model="item.email" placeholder="Email"> </el-input>
      </el-form-item>
      <el-row :gutter="30">
        <el-col :span="12">
          <el-form-item
            label="Role"
            required
            :error="errorMessage(saveError, 'role_id')"
          >
            <el-select
              v-if="!item.admin"
              v-model="item.role_id"
              placeholder="Role"
              class="el-col-24"
            >
              <el-option
                v-for="role in rolelist.data"
                :key="role.id"
                :label="role.name"
                :value="role.id"
                :disabled="role.name === 'Partenaire'"
              >
              </el-option>
            </el-select>
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item>
            <br />
            <el-checkbox v-model="item.admin">Super Administrateur</el-checkbox>
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
  name: 'AdministratorsSave',
  data() {
    return {}
  },
  computed: {
    ...mapState('admins', [
      'item',
      'saveLoading',
      'saveError',
      'getAdminLoading',
      'rolelist'
    ]),
    needUpdate() {
      return (
        this.$route.params.id !== undefined && this.$route.params.id !== null
      )
    }
  },
  mounted: function() {
    this.getRoleList()
    if (this.needUpdate) {
      this.getAdmin({ id: this.$route.params.id })
    } else {
      this.clearAdmin()
    }
  },
  methods: {
    ...mapActions('admins', ['save', 'getAdmin', 'clearAdmin', 'getRoleList']),
    errorMessage,
    async submit() {
      await this.save({
        ...this.item
      })
      if (this.saveError === null) {
        return router.push({
          name: 'admin-list'
        })
      }
    },
    cancel() {
      return router.push({
        name: 'admin-list'
      })
    }
  }
}
</script>

<style></style>
