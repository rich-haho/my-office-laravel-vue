+<template>
  <div id="app">
    <el-row v-if="booting" v-loading="booting" class="booting">
      <el-col>
        <h2>Chargement de la plateforme</h2>
      </el-col>
    </el-row>
    <component :is="layout" v-else v-loading="globalLoading">
      <router-view />
    </component>
  </div>
</template>

<script>
import { mapState } from 'vuex'

export default {
  computed: {
    ...mapState('common', ['booting', 'globalLoading']),
    layout() {
      return (this.$route.meta.layout || 'default') + '-layout'
    }
  }
}
</script>
