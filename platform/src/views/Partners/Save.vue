<template>
  <el-card v-loading="getPartnerLoading" class="card">
    <div slot="header">
      <span>
        {{ needUpdate ? 'Modifier le partenaire' : 'Créer un partenaire' }}
      </span>
    </div>
    <el-form @submit.native.prevent>
      <el-row :gutter="10">
        <el-col :span="12">
          <el-form-item
            label="Site"
            required
            :error="errorMessage(saveError, 'sites')"
          >
            <el-select
              v-model="item.sites"
              value-key="id"
              multiple
              filterable
              default-first-option
              placeholder="Choisir un site"
              class="el-col-24"
            >
              <el-option
                v-for="site in sitelist.data"
                :key="site.id"
                :label="site.name"
                :value="site"
              >
              </el-option>
            </el-select>
          </el-form-item>
        </el-col>
      </el-row>

      <el-row :gutter="10">
        <el-col :span="8">
          <el-form-item
            label="Nom"
            required
            :error="errorMessage(saveError, 'name')"
          >
            <el-input v-model="item.name" placeholder="Nom"> </el-input>
          </el-form-item>
        </el-col>
        <el-col :span="16">
          <el-form-item
            label="Adresse"
            required
            :error="errorMessage(saveError, 'address')"
          >
            <el-input v-model="item.address" placeholder="Adresse"> </el-input>
          </el-form-item>
        </el-col>
      </el-row>

      <el-row :gutter="10">
        <el-col :span="8">
          <el-form-item
            label="Téléphone"
            required
            :error="errorMessage(saveError, 'phone')"
          >
            <el-input v-model="item.phone" placeholder="Téléphone"> </el-input>
          </el-form-item>
        </el-col>
        <el-col :span="8">
          <el-form-item
            label="Email"
            required
            :error="errorMessage(saveError, 'email')"
          >
            <el-input v-model="item.email" placeholder="Email"> </el-input>
          </el-form-item>
        </el-col>
        <el-col :span="8">
          <el-form-item
            label="Personne à contacter"
            required
            :error="errorMessage(saveError, 'contact_name')"
          >
            <el-input
              v-model="item.contact_name"
              placeholder="Personne à contacter"
            >
            </el-input>
          </el-form-item>
        </el-col>
      </el-row>

      <el-row :gutter="10">
        <el-col :span="24">
          <el-form-item>
            <el-checkbox
              v-model="item.enable_stripe_connect"
              label-position="top"
              :true-label="1"
              :false-label="0"
              :error="errorMessage(saveError, 'enable_stripe_connect')"
              label="Activer les frais de commission et le paiement direct des partenaires"
            />
          </el-form-item>
        </el-col>
      </el-row>
      <transition name="fade">
        <el-row v-show="item.enable_stripe_connect" :gutter="10">
          <el-col :span="8">
            <el-form-item
              label="Commission de la plateforme (en pourcentage)"
              required
              :error="errorMessage(saveError, 'commission_percentage')"
            >
              <el-input-number
                v-model="item.commission_percentage"
                :precision="2"
                :step="5"
                :min="0"
                :max="100"
                placeholder="Pourcentage (0.00%)"
              />
            </el-form-item>
          </el-col>
          <el-col :span="16">
            <el-form-item
              label="Identifiant Stripe du partenaire"
              required
              :error="errorMessage(saveError, 'connected_stripe_id')"
            >
              <el-input v-model="item.connected_stripe_id" placeholder="">
              </el-input>
            </el-form-item>
          </el-col>
        </el-row>
      </transition>
      <el-tabs v-model="activeTab" type="border-card">
        <el-tab-pane
          v-for="(locale, index) in localesList.data"
          :key="index"
          :label="locale.name"
          :name="locale.name"
        >
          <el-form-item label="Description publique">
            <el-input
              v-model="item.public_descriptions[locale.name]"
              type="textarea"
              :rows="3"
            ></el-input>
          </el-form-item>
        </el-tab-pane>
      </el-tabs>

      <el-form-item label="Commentaires privés">
        <el-input v-model="item.private_description" type="textarea" :rows="3">
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
  name: 'PartnerSave',
  data() {
    return {
      activeTab: 'fr'
    }
  },
  computed: {
    ...mapState('partners', [
      'item',
      'saveLoading',
      'saveError',
      'getPartnerLoading'
    ]),
    ...mapState('sites', ['sitelist']),
    ...mapState('clients', ['list']),
    ...mapState('locales', ['localesList']),
    needUpdate() {
      return (
        this.$route.params.id !== undefined && this.$route.params.id !== null
      )
    }
  },
  mounted: function() {
    this.getSiteList({ perPage: 1000 })
    this.getLocales()
    this.getList({ perPage: 1000 })
    this.clearErrorState()
    if (this.needUpdate) {
      this.getPartner({ id: this.$route.params.id })
    } else {
      this.clearPartner()
    }
  },
  methods: {
    ...mapActions('partners', [
      'save',
      'getPartner',
      'clearPartner',
      'clearErrorState'
    ]),
    ...mapActions('sites', ['getSiteList']),
    ...mapActions('clients', ['getList']),
    ...mapActions('locales', ['getLocales']),
    errorMessage,
    async submit() {
      await this.save({
        ...this.item
      })
      if (this.saveError === null) {
        return router.push({
          name: 'partner-list'
        })
      }
    },
    cancel() {
      return router.push({
        name: 'partner-list'
      })
    }
  }
}
</script>

<style scoped lang="scss">
.el-input-number {
  width: 100%;
}
</style>
