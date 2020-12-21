<template>
  <div>
    <page-detail-bar
      :title="$t('clientPages.fm_services_title')"
      :short-title="$t('clientPages.fm_services_title')"
      :description="$t('clientPages.fm_services_description')"
    >
      <bread-crumb
        slot="breadcrumb"
        :slot-size="2"
        class="hidden-sm-and-down"
        link1="/categories"
      >
        <div slot="1">{{ $t('clientPages.categories_title') }}</div>
        <div slot="2">{{ $t('clientPages.fm_services_service') }}</div>
      </bread-crumb>
    </page-detail-bar>
    <div class="global-padding">
      <el-row :gutter="50" class="cards-container">
        <el-col :xs="24" :lg="16">
          <el-card v-loading="sendLoading" class="card">
            <el-form @submit.native.prevent>
              <el-row :gutter="10">
                <el-col :span="24">
                  <el-form-item
                    :label="$t('clientPages.fm_services_form_subject')"
                    required
                    :error="errorMessage(sendError, 'subject')"
                  >
                    <el-select
                      v-model="item.subject"
                      :placeholder="
                        $t('clientPages.fm_services_form_subject_placeholder')
                      "
                      class="el-col-24"
                    >
                      <el-option-group
                        v-for="(category, i) in translatedCategories"
                        :key="i"
                        :label="category.title"
                      >
                        <el-option
                          v-for="subcategory in category.subcategories"
                          :key="subcategory"
                          :label="subcategory"
                          :value="subcategory"
                        >
                        </el-option>
                      </el-option-group>
                    </el-select>
                  </el-form-item>
                </el-col>
                <el-col :span="24">
                  <el-form-item
                    :label="$t('clientPages.fm_services_form_description')"
                    required
                    :error="errorMessage(sendError, 'description')"
                  >
                    <el-input
                      v-model="item.description"
                      type="textarea"
                      :rows="3"
                    >
                    </el-input>
                  </el-form-item>
                </el-col>

                <el-col :span="24">
                  <el-form-item
                    :label="$t('clientPages.fm_services_form_contact_details')"
                    required
                    :error="errorMessage(sendError, 'contact')"
                  >
                    <el-input
                      v-model="item.contact"
                      type="textarea"
                      :rows="3"
                      :placeholder="
                        $t(
                          'clientPages.fm_services_form_contact_details_placeholder'
                        )
                      "
                    >
                    </el-input>
                  </el-form-item>
                </el-col>

                <el-col :span="24">
                  <el-form-item
                    :label="$t('clientPages.fm_services_form_building')"
                    required
                    :error="errorMessage(sendError, 'building')"
                  >
                    <el-input
                      v-model="item.building"
                      type="textarea"
                      :rows="3"
                    />
                  </el-form-item>
                </el-col>
              </el-row>

              <el-row>
                <el-col :span="24">
                  <el-button
                    :loading="sendLoading"
                    type="success"
                    @click="submit"
                  >
                    {{ $t('clientPages.fm_services_form_send') }}
                  </el-button>
                </el-col>
              </el-row>
            </el-form>
          </el-card>
        </el-col>
        <el-col :xs="24" :lg="8" class="mb-20">
          <contact />
        </el-col>
      </el-row>
    </div>
  </div>
</template>
<script>
import { mapGetters, mapState, mapActions } from 'vuex'
import { errorMessage } from '@/services/api'
import PageDetailBar from '@/components/PageDetailBar.vue'
import BreadCrumb from '@/components/BreadCrumb.vue'
import Contact from '@/components/Contact.vue'

export default {
  components: {
    PageDetailBar,
    Contact,
    BreadCrumb
  },
  data() {
    return {
      item: {},
      categories: [
        {
          fr: 'Technique',
          en: 'Technical',
          subcategories: [
            { fr: 'Chauffage', en: 'Heating' },
            { fr: 'Éclairage et luminaires', en: 'Lighting and luminaires' },
            { fr: 'Électricité', en: 'Electricity' },
            { fr: 'Menuiserie', en: 'Carpentry' },
            { fr: 'Plomberie - Sanitaire', en: 'Plumbing - Sanitation' },
            { fr: 'Portes, Fenêtres, Stores', en: 'Doors, windows and blinds' },
            { fr: 'Murs et cloisons', en: 'Walls and partition walls' }
          ]
        },
        {
          fr: 'Conciergerie',
          en: 'Concierge Services',
          subcategories: [
            { fr: 'Déchets', en: 'Waste management' },
            { fr: 'Nettoyage', en: 'Cleaning' },
            { fr: 'La Poste', en: 'Postal Service' },
            { fr: 'Déménagement', en: 'Relocation' },
            { fr: 'Plantes intérieures', en: 'Indoor plants' },
            { fr: 'Extérieur Batîment', en: 'Building outdoors' }
          ]
        },
        {
          fr: 'Traîteur - Distribution automatique',
          en: 'Catering - Vending',
          subcategories: [
            { fr: 'Service traîteur', en: 'Catering services' },
            { fr: 'Machine à café', en: 'Coffee Machine' },
            { fr: 'Automate Snacks', en: 'Snack Machine' },
            { fr: 'Fontaines à eau', en: 'Water fountains' }
          ]
        },
        {
          fr: 'Vêtements de travail',
          en: 'Work clothing',
          subcategories: [
            { fr: 'Prise de mesure', en: 'Measurement' },
            { fr: 'Remplacement - Changement', en: 'Replacement' }
          ]
        },
        {
          fr: 'Salle de réunion',
          en: 'Meeting room',
          subcategories: [
            { fr: 'Aménagement', en: 'Layout organization' },
            { fr: 'Besoin en équipement', en: 'Equipment needs' },
            { fr: 'Équipement à réviser', en: 'Equipment maintenance' }
          ]
        },
        {
          fr: 'Transport',
          en: 'Transport',
          subcategories: [
            { fr: 'Demande de transport', en: 'Transport request' }
          ]
        },
        {
          fr: 'Projets',
          en: 'Projects',
          subcategories: [{ fr: 'Demande de projets', en: 'Project request' }]
        },
        {
          fr: 'Logistique',
          en: 'Logistics',
          subcategories: [
            { fr: 'Archivage', en: 'Archival storage' },
            { fr: 'Stockage', en: 'Storage' }
          ]
        },
        {
          fr: 'Autre',
          en: 'Other',
          subcategories: [{ fr: 'Autres demandes', en: 'Other requests' }]
        }
      ]
    }
  },
  computed: {
    ...mapState('fmServices', ['sendError', 'sendLoading']),
    ...mapGetters('sites', ['fmServices']),
    ...mapGetters('auth', ['locale']),
    translatedCategories() {
      const { name } = this.locale
      return this.categories.map(({ [name]: locale, subcategories }) => {
        return {
          title: locale,
          subcategories: subcategories.map(
            ({ [name]: subcategoryLocal }) => subcategoryLocal
          )
        }
      })
    }
  },
  created() {},
  mounted: function() {
    if (this.fmServices === false) {
      this.$router.push({ name: 'categories' })
    }
  },
  methods: {
    ...mapActions('fmServices', ['send']),
    errorMessage,
    async submit() {
      await this.send({
        ...this.item
      })
      if (!this.sendError) {
        this.$message({
          showClose: true,
          message: this.$t('clientPages.fm_services_form_success'),
          type: 'success'
        })
        this.item = {}
      }
    }
  }
}
</script>
