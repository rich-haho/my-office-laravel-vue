<template>
  <el-col :sm="12" :md="4">
    <router-link :key="loggableKey" :to="loggablePath" class="no-underline">
      <zen-card
        :name="search.loggable.name"
        :image="search.loggable.assets.full_path"
      />
    </router-link>
  </el-col>
</template>

<script>
import ZenCard from '@/components/ZenCard.vue'

export default {
  components: {
    ZenCard
  },
  props: {
    search: {
      required: true,
      type: Object
    }
  },
  computed: {
    loggableType: function() {
      const { loggable_type } = this.search
      return loggable_type === 'services' ? 'category' : 'products'
    },
    loggablePath: function() {
      const { loggableType } = this
      const { loggable_id } = this.search
      return `/${loggableType}/${loggable_id}`
    },
    loggableKey: function() {
      const { loggableType } = this
      const { loggable_id } = this.search
      return `${loggableType}-${loggable_id}`
    }
  }
}
</script>

<style scoped>
.no-underline {
  text-decoration: none;
}
</style>
