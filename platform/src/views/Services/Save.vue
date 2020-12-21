<template>
  <el-card v-loading="getServiceLoading" class="card">
    <div slot="header">
      <span>
        {{ needUpdate ? 'Modifier le service' : 'Créer un service' }}
      </span>
    </div>
    <el-form @submit.native.prevent>
      <el-tabs v-model="activeTab" type="border-card">
        <el-tab-pane
          v-for="locale in localesList.data"
          :key="locale.name"
          :label="locale.name"
          :name="locale.name"
        >
          <el-form-item
            label="Nom"
            required
            :error="errorMessage(saveError, 'names.' + locale.name)"
          >
            <el-input v-model="item.names[locale.name]" placeholder="Nom">
            </el-input>
          </el-form-item>
          <el-form-item
            label="Description"
            required
            :error="errorMessage(saveError, 'descriptions.' + locale.name)"
          >
            <el-input
              v-model="item.descriptions[locale.name]"
              type="textarea"
              placeholder="Description"
            >
            </el-input>
          </el-form-item>
        </el-tab-pane>
      </el-tabs>

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
      <el-row>
        <el-col :span="24">
          <el-form-item
            label="Lien externe (redirige vers cette page si le champ est rempli)"
            :error="errorMessage(saveError, 'external_link')"
          >
            <el-input v-model="item.external_link" placeholder="Lien externe">
            </el-input>
          </el-form-item>
        </el-col>
      </el-row>
      <el-form-item
        label="Photo"
        required
        :error="errorMessage(saveError, 'assets')"
      >
        <el-input
          ref="files"
          v-model="item.asset"
          type="file"
          accept=".png,.jpg,.jpeg"
          @change="handleFileUpload"
        >
        </el-input>
      </el-form-item>
      <el-row v-if="needUpdate" :gutter="10">
        <el-image
          :src="item.assets ? item.assets.full_path : ''"
          :preview-src-list="item.assets ? [item.assets.full_path] : []"
          style="width: 200px"
        />
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
  name: 'ServiceSave',
  data() {
    return {
      files: [],
      closedAsset: [],
      activeTab: 'fr'
    }
  },
  computed: {
    ...mapState('services', [
      'item',
      'saveLoading',
      'saveError',
      'getServiceLoading'
    ]),
    ...mapState('clients', ['list']),
    ...mapState('locales', ['localesList']),
    ...mapState('sites', ['sitelist']),
    needUpdate() {
      return (
        this.$route.params.id !== undefined && this.$route.params.id !== null
      )
    }
  },
  watch: {
    saveError(newValue) {
      if (newValue) {
        for (let i = 0, len = this.localesList.data.length; i < len; i++) {
          if (newValue.errors['names.' + this.localesList.data[i].name]) {
            this.activeTab = this.localesList.data[i].name
            break
          }
          if (newValue.errors['description.' + this.localesList.data[i].name]) {
            this.activeTab = this.localesList.data[i].name
            break
          }
        }
      }
    }
  },
  mounted: function() {
    this.getSiteList({ perPage: 1000 })
    if (this.needUpdate) {
      this.getService({ id: this.$route.params.id })
    } else {
      this.clearService()
    }
    this.getLocales()
  },
  methods: {
    ...mapActions('locales', ['getLocales']),
    ...mapActions('services', ['save', 'getService', 'clearService']),
    ...mapActions('sites', ['getSiteList']),
    errorMessage,
    async submit() {
      let formdata = new FormData()
      formdata.append(
        'external_link',
        this.item.external_link ? this.item.external_link : ''
      )

      //adding new files to form data.
      this.files.forEach(function(item) {
        formdata.append('assets[]', item)
      })
      formdata.append('client_id', this.item.client_id)
      Object.entries(this.localesList.data).forEach(function(locale) {
        formdata.append(
          `names[${locale[1].name}]`,
          this.item.names[locale[1].name] ? this.item.names[locale[1].name] : ''
        )
        formdata.append(
          `descriptions[${locale[1].name}]`,
          this.item.descriptions[locale[1].name]
            ? this.item.descriptions[locale[1].name]
            : ''
        )
      }, this)

      if (this.item.sites) {
        this.item.sites.forEach(site => {
          formdata.append('sites[]', site.id ? site.id : '')
        })
      }

      this.item.formdata = formdata
      await this.save({
        ...this.item
      })
      if (this.saveError === null) {
        return router.push({
          name: 'service-list'
        })
      }
    },
    cancel() {
      return router.push({
        name: 'service-list'
      })
    },
    handleFileUpload() {
      this.files = document.querySelector('[type=file]').files
    }
  }
}
</script>

<style></style>
