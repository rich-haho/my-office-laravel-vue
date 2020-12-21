<template>
  <div class="categories">
    <page-detail-bar
      :title="$t('clientPages.categories_title')"
      :short-title="$t('clientPages.categories_title')"
      :description="$t('clientPages.categories_description')"
    />
    <div class="global-padding">
      <el-row v-loading="listLoading" class="cards-container">
        <template v-for="category in servicelist.data">
          <a
            v-if="category.external_link"
            :key="'category-' + category.id"
            :href="category.external_link"
            target="_blank"
          >
            <el-col :xs="24" :sm="12" :md="6" :lg="4">
              <zen-card
                :name="category.name"
                :image="category.assets ? category.assets.full_path : ''"
              />
            </el-col>
          </a>
          <router-link
            v-else
            :key="'category-' + category.id"
            :to="{
              path: '/category/' + category.id
            }"
          >
            <el-col :xs="12" :sm="12" :md="6" :lg="4">
              <zen-card
                :name="category.name"
                :image="category.assets ? category.assets.full_path : ''"
              />
            </el-col>
          </router-link>
        </template>
        <template v-if="listLoading === false">
          <router-link
            v-show="fmServices"
            :to="{
              path: '/fm-services'
            }"
          >
            <el-col :xs="12" :sm="12" :md="6" :lg="4">
              <zen-card
                :name="$t('clientPages.fm_services_service')"
                :image="fmImage"
              />
            </el-col>
          </router-link>
        </template>
      </el-row>
    </div>
  </div>
</template>
<script>
import { mapState, mapActions, mapGetters } from 'vuex'
import PageDetailBar from '@/components/PageDetailBar.vue'
import ZenCard from '@/components/ZenCard.vue'
import fmImage from '@/assets/images/manager.jpg'
import _ from 'lodash'

export default {
  components: {
    PageDetailBar,
    ZenCard
  },
  data() {
    return {
      fmImage
    }
  },
  computed: {
    ...mapState('services', ['servicelist', 'listLoading']),
    ...mapGetters('sites', ['fmServices'])
  },
  created() {
    this.debounceLoadData = _.debounce(this.loadData, 300)
  },
  mounted: function() {
    this.getServiceList({ url: '/services' })
  },
  methods: {
    ...mapActions('services', ['getServiceList']),
    loadData() {
      this.getServiceList()
    }
  }
}
</script>
<style lang="scss" scoped>
@import '~@/assets/styles/_variables.scss';
.cards-container {
  .el-col {
    padding-right: 25px;
    padding-left: 25px;
    @media screen and (max-width: $--sm) {
      padding-right: 10px;
      padding-left: 10px;
    }
  }
}
</style>
