<template>
  <el-container>
    <el-header style="height:80px">
      <v-header />
    </el-header>
    <el-main>
      <slot />
    </el-main>
  </el-container>
</template>

<script>
import VHeader from '@/views/Header'

export default {
  components: {
    VHeader
  }
}
</script>
<style lang="scss" scoped>
.el-main {
  padding: 0 0 10px 0;
}
</style>
