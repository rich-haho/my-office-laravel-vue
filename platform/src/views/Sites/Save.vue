<template>
  <el-card v-loading="getSiteLoading" class="card">
    <div slot="header">
      <span>
        {{ needUpdate ? 'Modifier le site' : 'Créer un site' }}
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
            v-for="client in list.data"
            :key="client.id"
            :label="client.name"
            :value="client.id"
          >
          </el-option>
        </el-select>
      </el-form-item>

      <el-form-item label="Adresse" :error="errorMessage(saveError, 'address')">
        <el-input
          v-model="item.address"
          placeholder="Adresse"
          type="textarea"
          :rows="3"
        >
        </el-input>
      </el-form-item>

      <el-row :gutter="10">
        <el-col :span="12">
          <el-form-item
            label="Horaires d'ouvertures"
            required
            :error="errorMessage(saveError, 'open_time')"
          >
            <el-input
              v-model="item.open_time"
              placeholder="Horaires d'ouvertures"
            >
            </el-input>
          </el-form-item>
        </el-col>

        <el-col :span="12">
          <el-form-item
            label="Numéro de téléphone"
            required
            :error="errorMessage(saveError, 'phone_number')"
          >
            <el-input
              v-model="item.phone_number"
              placeholder="Numéro de téléphone"
            >
            </el-input>
          </el-form-item>
        </el-col>
      </el-row>

      <el-row :gutter="10">
        <el-col :span="12">
          <el-form-item :error="errorMessage(saveError, 'fm_services')">
            <el-switch
              v-model="item.fm_services"
              active-text="Activer les services FM"
            >
            </el-switch>
          </el-form-item>
        </el-col>
      </el-row>

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
  name: 'SiteSave',
  data() {
    return {}
  },
  computed: {
    ...mapState('sites', [
      'item',
      'saveLoading',
      'saveError',
      'getSiteLoading'
    ]),
    ...mapState('clients', ['list']),
    needUpdate() {
      return (
        this.$route.params.id !== undefined && this.$route.params.id !== null
      )
    }
  },
  mounted: function() {
    this.getList({ perPage: 1000 })
    if (this.needUpdate) {
      this.getSite({ id: this.$route.params.id })
    } else {
      this.clearSite()
    }
  },
  methods: {
    ...mapActions('sites', ['save', 'getSite', 'clearSite']),
    ...mapActions('clients', ['getList']),
    errorMessage,
    async submit() {
      await this.save({
        ...this.item
      })
      if (this.saveError === null) {
        return router.push({
          name: 'site-list'
        })
      }
    },
    cancel() {
      return router.push({
        name: 'site-list'
      })
    }
  }
}
</script>

<style></style>
