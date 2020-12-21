<template>
  <div>
    <page-detail-bar
      :title="$t('clientPages.demand_title')"
      :short-title="$t('clientPages.demand_title')"
      :description="$t('clientPages.demand_description')"
    >
      <bread-crumb
        slot="breadcrumb"
        :slot-size="2"
        class="hidden-sm-and-down"
        link1="/"
      >
        <div slot="1">{{ $t('clientPages.demand_home') }}</div>
        <div slot="2">{{ $t('clientPages.demand_service') }}</div>
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
                    :label="$t('clientPages.demand_form_category')"
                    :error="errorMessage(sendError, 'category')"
                  >
                    <el-select
                      v-model="item.category"
                      :placeholder="
                        $t('clientPages.demand_form_category_placeholder')
                      "
                      class="el-col-24"
                    >
                      <el-option
                        v-for="(category, i) in translatedCategories"
                        :key="i"
                        :label="category"
                        :value="category"
                      >
                      </el-option>
                    </el-select>
                  </el-form-item>
                </el-col>
                <el-col :span="24">
                  <el-form-item
                    :label="$t('clientPages.demand_form_subject')"
                    required
                    :error="errorMessage(sendError, 'subject')"
                  >
                    <el-input v-model="item.subject"> </el-input>
                  </el-form-item>
                </el-col>

                <el-col :span="24">
                  <el-form-item
                    :label="$t('clientPages.demand_form_description')"
                    required
                    :error="errorMessage(sendError, 'description')"
                  >
                    <el-input
                      v-model="item.description"
                      type="textarea"
                      :rows="3"
                      :placeholder="
                        $t('clientPages.demand_form_description_placeholder')
                      "
                    >
                    </el-input>
                  </el-form-item>
                </el-col>

                <el-col :span="24">
                  <el-form-item
                    :label="$t('clientPages.demand_form_contact_details')"
                    required
                    :error="errorMessage(sendError, 'contact')"
                  >
                    <el-input
                      v-model="item.contact"
                      type="textarea"
                      :rows="3"
                      :placeholder="
                        $t(
                          'clientPages.demand_form_contact_details_placeholder'
                        )
                      "
                    >
                    </el-input>
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
                    {{ $t('clientPages.demand_form_send') }}
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
import { mapState, mapGetters, mapActions } from 'vuex'
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
        { fr: 'Vie quotidienne', en: 'Daily Life' },
        { fr: 'Shopping', en: 'Shopping' },
        { fr: 'Voyage / Mobilité', en: 'Travel / Mobility' },
        { fr: 'Loisirs', en: 'Leisure' },
        { fr: 'Bien-être', en: 'Well-being' },
        { fr: 'Autre', en: 'Others' }
      ]
    }
  },
  computed: {
    ...mapState('demand', ['sendError', 'sendLoading']),
    ...mapGetters('auth', ['locale']),
    translatedCategories() {
      const { name } = this.locale
      return this.categories.map(item => item[`${name}`])
    }
  },
  created() {},
  mounted: function() {},
  methods: {
    ...mapActions('demand', ['send']),
    errorMessage,
    async submit() {
      await this.send({
        ...this.item
      })
      if (!this.sendError) {
        this.$message({
          showClose: true,
          message: this.$t('clientPages.demand_form_success'),
          type: 'success'
        })
        this.item = {}
      }
    }
  }
}
</script>
