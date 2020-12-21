<template>
  <div v-show="searchListFilled" class="category-container">
    <div class="list-title">
      {{ $t('homePartials.category_container_title') }}
    </div>
    <el-row v-loading="listLoading" :gutter="30" class="cards-container">
      <template v-for="search in searchesList.data">
        <card-list :key="search.id" :search="search" />
      </template>
    </el-row>
  </div>
</template>
<script>
import { mapState, mapActions } from 'vuex'
import CardList from '@/components/search/CardList.vue'
import _ from 'lodash'

export default {
  components: {
    CardList
  },
  computed: {
    ...mapState('searches', ['searchesList', 'listLoading']),
    searchListFilled: function() {
      return this.searchesList.data.length > 0
    }
  },
  created() {
    this.debounceLoadData = _.debounce(this.loadData, 300)
  },
  mounted: function() {
    this.getSearchesList({ url: '/searches' })
  },
  methods: {
    ...mapActions('searches', ['getSearchesList']),
    loadData() {
      this.getSearchesList()
    }
  }
}
</script>
