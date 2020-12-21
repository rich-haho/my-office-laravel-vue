<template>
  <el-container :class="'zen-admin'">
    <el-header>
      <admin-header />
    </el-header>
    <el-container>
      <el-aside width="200px">
        <admin-aside />
      </el-aside>
      <el-container>
        <el-main><slot /></el-main>
      </el-container>
    </el-container>
  </el-container>
</template>

<script>
import AdminAside from '@/views/Admin/Aside'
import AdminHeader from '@/views/Admin/Header'

export default {
  components: {
    AdminAside,
    AdminHeader
  }
}
</script>
