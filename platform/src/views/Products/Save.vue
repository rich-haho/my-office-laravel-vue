<template>
  <el-card v-loading="getProductLoading" class="card">
    <div slot="header">
      <span>
        {{ needUpdate ? 'Modifier le produit' : 'Créer un produit' }}
      </span>
    </div>

    <el-form :disabled="isDisabled" @submit.native.prevent>
      <el-tabs v-model="activeName">
        <el-tab-pane label="Informations générales" name="gen">
          <el-row :gutter="10">
            <el-col :span="12">
              <el-form-item
                label="Service"
                required
                :error="errorMessage(saveError, 'service_id')"
              >
                <el-select
                  v-model="item.service_id"
                  placeholder="Service"
                  class="el-col-md-24"
                >
                  <el-option
                    v-for="service in serviceList"
                    :key="service.id"
                    :label="service.name"
                    :value="service.id"
                  >
                  </el-option>
                </el-select>
              </el-form-item>
            </el-col>

            <el-col :span="12">
              <el-form-item
                label="Partenaire"
                required
                :error="errorMessage(saveError, 'partner_id')"
              >
                <el-select
                  v-model="item.partner_id"
                  placeholder="Partenaire"
                  class="el-col-md-24"
                >
                  <el-option
                    v-for="partner in partnerList"
                    :key="partner.id"
                    :label="partner.name"
                    :value="partner.id"
                  >
                  </el-option>
                </el-select>
              </el-form-item>
            </el-col>
          </el-row>

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
                    v-for="site in siteList"
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
            <el-tabs v-model="activeTab" type="border-card" class="ml-5 mr-5">
              <el-tab-pane
                v-for="(locale, index) in localesList.data"
                :key="index"
                :label="locale.name"
                :name="locale.name"
              >
                <el-col :span="12">
                  <el-form-item
                    label="Nom du produit"
                    required
                    :error="errorMessage(saveError, 'names.' + locale.name)"
                  >
                    <el-input
                      v-model="item.names[locale.name]"
                      placeholder="Nom"
                    >
                    </el-input>
                  </el-form-item>
                </el-col>
                <el-col :span="12">
                  <el-form-item
                    label="Description"
                    required
                    :error="
                      errorMessage(saveError, 'descriptions.' + locale.name)
                    "
                  >
                    <el-input
                      v-model="item.descriptions[locale.name]"
                      placeholder="Description"
                      type="textarea"
                      :rows="3"
                    >
                    </el-input>
                  </el-form-item>
                </el-col>
              </el-tab-pane>
            </el-tabs>
          </el-row>
          <el-row :gutter="10">
            <el-col :span="24">
              <el-form-item
                :error="errorMessage(saveError, 'enable_custom_commission')"
              >
                <el-switch
                  v-model="item.enable_custom_commission"
                  :disabled="!item.partner.enable_stripe_connect"
                  active-text="Activer les frais de commission personnalisés pour ce produit"
                />
              </el-form-item>
            </el-col>
          </el-row>
          <el-row :gutter="10">
            <el-col :span="6">
              <el-form-item
                label="Prix"
                required
                :error="errorMessage(saveError, 'price')"
              >
                <el-input v-model="item.price" placeholder="Prix"> </el-input>
              </el-form-item>
            </el-col>
            <el-col :span="6">
              <el-form-item
                label="Prix barré"
                :error="errorMessage(saveError, 'price_reduction')"
              >
                <el-input
                  v-model="item.price_reduction"
                  placeholder="Prix barré"
                >
                </el-input>
              </el-form-item>
            </el-col>
            <el-col :span="6">
              <el-form-item
                label="Devise"
                required
                :error="errorMessage(saveError, 'currency_id')"
              >
                <el-select v-model="item.currency_id" class="el-col-md-24">
                  <el-option
                    v-for="currency in currenciesList.data"
                    :key="currency.id"
                    :label="currency.name"
                    :value="currency.id"
                  >
                  </el-option>
                </el-select>
              </el-form-item>
            </el-col>
            <el-col :span="6">
              <el-form-item
                label="Commission"
                :required="item.enable_custom_commission"
                :error="errorMessage(saveError, 'commission_percentage')"
              >
                <el-input-number
                  v-model="commissionPercentage"
                  :precision="2"
                  :step="1"
                  :min="0"
                  :max="100"
                  :disabled="!item.enable_custom_commission"
                  placeholder="Pourcentage"
                />
              </el-form-item>
            </el-col>
          </el-row>

          <el-form-item :error="errorMessage(saveError, 'manual_product')">
            <el-tooltip
              placement="top"
              :content="
                item.manual_product
                  ? 'La capture du paiement devra être réalisée manuellement'
                  : 'La capture du paiement est automatique'
              "
            >
              <el-switch
                v-model="item.manual_product"
                active-text="Manuel"
                inactive-text="Automatique"
              >
              </el-switch>
            </el-tooltip>
          </el-form-item>

          <el-form-item label="Tags" :error="errorMessage(saveError, 'tags')">
            <el-select
              v-model="item.tags"
              value-key="id"
              multiple
              filterable
              allow-create
              default-first-option
              placeholder="sélectionner ou créer des tags"
              class="el-col-24"
            >
              <el-option
                v-for="tag in tags.data"
                :key="tag.id"
                :label="tag.name"
                :value="tag"
              >
              </el-option>
            </el-select>
          </el-form-item>

          <el-row :gutter="10">
            <el-col :span="8">
              <el-form-item :error="errorMessage(saveError, 'moment_product')">
                <el-checkbox
                  v-model="item.moment_product"
                  :true-label="1"
                  :false-label="0"
                >
                  Produit du moment
                </el-checkbox>
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
        </el-tab-pane>
        <el-tab-pane label="Slots" name="slots">
          <el-row :gutter="10">
            <el-col>
              <el-form-item v-for="(slot, index) in item.slots" :key="index">
                <product-slot
                  :key="index"
                  :product-slot="slot"
                  :product-slots="item.slots"
                />
              </el-form-item>
              <el-button class="mb-15" @click="addSlot">
                Ajouter un slot
              </el-button>
            </el-col>
          </el-row>
        </el-tab-pane>
      </el-tabs>

      <el-row v-show="!isDisabled">
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
import { mapActions, mapGetters, mapState } from 'vuex'
import { errorMessage } from '@/services/api'
import router from '@/router'
import ProductSlot from '@/views/Products/Slot/Index'
export default {
  name: 'ProductSave',
  components: { ProductSlot },
  data() {
    return {
      activeName: 'gen',
      activeTab: 'fr'
    }
  },
  computed: {
    ...mapState('products', [
      'item',
      'saveLoading',
      'saveError',
      'getProductLoading',
      'clientSitesListLoading',
      'clientSites',
      'tags'
    ]),
    ...mapGetters('auth', ['can', 'isSuperAdmin']),
    ...mapState('sites', ['sitelist']),
    ...mapState('clients', ['list']),
    ...mapState('partners', ['partnerlist']),
    ...mapState('services', ['servicelist']),
    ...mapState('locales', ['localesList']),
    ...mapState('currencies', ['currenciesList']),
    commissionPercentage: {
      get(state) {
        const { partner, commission_percentage } = state.item
        return commission_percentage
          ? commission_percentage
          : partner.commission_percentage
      },
      set(value) {
        this.item.commission_percentage = value
      }
    },
    isPartner() {
      const { can, isSuperAdmin } = this
      return can('manage my products only') && isSuperAdmin === false
    },
    serviceList() {
      const { isPartner, item } = this
      return isPartner ? [item.service] : this.servicelist.data
    },
    partnerList() {
      const { isPartner, item } = this
      return isPartner ? [item.partner] : this.partnerlist.data
    },
    siteList() {
      const { isPartner, item } = this
      return isPartner ? item.sites : this.sitelist.data
    },
    isDisabled() {
      const { isPartner, activeName } = this
      return isPartner === true && activeName === 'gen'
    },
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
          if (
            newValue.errors['names.' + this.localesList.data[i].name] ||
            newValue.errors['descriptions.' + this.localesList.data[i].name]
          ) {
            this.activeTab = this.localesList.data[i].name
            break
          }
        }
      }
    }
  },
  mounted: function() {
    this.getLocales()
    this.getTags()
    this.getCurrencies()
    this.clearProduct()

    if (this.can('manage products') || this.isSuperAdmin) {
      this.getList({ perPage: 1000 })
      this.getPartnerList({ perPage: 1000 })
      this.getSiteList({ perPage: 100 })
      this.getServiceList({ perPage: 1000 })
    }
    if (this.needUpdate) {
      this.getProduct({ id: this.$route.params.id })
    }
  },
  methods: {
    ...mapActions('products', [
      'save',
      'getProduct',
      'clearProduct',
      'getTags'
    ]),
    ...mapActions('locales', ['getLocales']),
    ...mapActions('clients', ['getList']),
    ...mapActions('partners', ['getPartner', 'getPartnerList']),
    ...mapActions('services', ['getServiceList']),
    ...mapActions('currencies', ['getCurrencies']),
    ...mapActions('sites', ['getSiteList']),
    errorMessage,
    async submit() {
      let formdata = new FormData()
      if (this.file) {
        formdata.append('assets', this.file)
      }
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
      formdata.append('price', this.item.price ? this.item.price : '')
      formdata.append(
        'price_reduction',
        this.item.price_reduction ? this.item.price_reduction : ''
      )
      formdata.append(
        'partner_id',
        this.item.partner_id ? this.item.partner_id : ''
      )
      formdata.append(
        'client_id',
        this.item.client_id ? this.item.client_id : ''
      )
      formdata.append(
        'currency_id',
        this.item.currency_id ? this.item.currency_id : ''
      )
      formdata.append('manual_product', this.item.manual_product ? 1 : 0)
      if (this.item.tags) {
        this.item.tags.forEach((tag, index) => {
          formdata.append(`tags[${index}][id]`, tag.id ? tag.id : '')
          formdata.append(`tags[${index}][name]`, tag.name ? tag.name : tag)
        })
      }
      formdata.append(
        'moment_product',
        this.item.moment_product ? this.item.moment_product : 0
      )
      formdata.append(
        'commission_percentage',
        this.item.commission_percentage && this.item.enable_custom_commission
          ? this.item.commission_percentage
          : ''
      )
      formdata.append(
        'enable_custom_commission',
        this.item.enable_custom_commission ? 1 : 0
      )
      if (this.item.sites) {
        this.item.sites.forEach(site => {
          formdata.append('sites[]', site.id ? site.id : '')
        })
      }
      formdata.append(
        'service_id',
        this.item.service_id ? this.item.service_id : ''
      )

      if (this.item.slots.length > 0) {
        this.item.slots.forEach((slot, index) => {
          formdata.append(`slots[${index}][id]`, slot.id ? slot.id : '')
          formdata.append(`slots[${index}][days]`, slot.days ? slot.days : '')
          formdata.append(
            `slots[${index}][start_date]`,
            slot.start_date ? slot.start_date : ''
          )
          formdata.append(
            `slots[${index}][end_date]`,
            slot.end_date ? slot.end_date : ''
          )
          slot.times.forEach((time, indexTime) => {
            formdata.append(
              `slots[${index}][times][${indexTime}][start_time]`,
              time.start_time ? time.start_time : ''
            )
            formdata.append(
              `slots[${index}][times][${indexTime}][end_time]`,
              time.end_time ? time.end_time : ''
            )
          })
          formdata.append(
            `slots[${index}][min_participant]`,
            slot.min_participant ? slot.min_participant : 0
          )
          formdata.append(
            `slots[${index}][max_participant]`,
            slot.max_participant ? slot.max_participant : 1
          )
        })
      }

      this.item.formdata = formdata
      await this.save({
        ...this.item
      })
      if (this.saveError === null) {
        return router.push({
          name: 'product-list'
        })
      }
    },
    cancel() {
      return router.push({
        name: 'product-list'
      })
    },
    handleFileUpload() {
      this.file = document.querySelector('[type=file]').files[0]
    },
    addSlot() {
      this.item.slots.push({
        days: '0000000',
        times: [],
        start_date: '',
        end_date: '',
        min_participant: 0,
        max_participant: 1
      })
    }
  }
}
</script>

<style scoped lang="scss">
.el-input-number {
  width: 100%;
  .el-input__inner {
    background-color: transparent;
    border: 1px solid;
    font-weight: normal;
  }
  .el-input-number__increase,
  .el-input-number__decrease {
    font-weight: normal;
    border-radius: 0;
  }
}
</style>
