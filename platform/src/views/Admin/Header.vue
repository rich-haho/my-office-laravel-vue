<template>
  <el-row type="flex" justify="center" align="middle">
    <el-col :span="4">
      <img alt="Zen Office" src="@/assets/images/logo.png" />
    </el-col>
    <el-col :span="1" :offset="19">
      <el-button
        icon="el-icon-switch-button"
        size="medium"
        :loading="logoutLoading"
        @click="logout({ guard: 'admin' })"
      >
      </el-button>
    </el-col>
  </el-row>
</template>

<script>
import { mapState, mapActions } from 'vuex'
export default {
  computed: {
    ...mapState('auth', ['logoutLoading'])
  },
  methods: {
    ...mapActions('auth', ['logout'])
  }
}
</script>
