<template>
  <el-form class="text-center">
    <h2 class="text-center">{{ $t('auths.register_domain_fail') }}</h2>
    <a href="mailto:anna@yourzenoffice.com">{{
      $t('auths.register_domain_fail_desc')
    }}</a>
    <el-row>
      <el-col :span="24" class="mt-25">
        <el-button
          type="secondary"
          @click="$router.push({ name: 'auth-register' })"
        >
          Créer un compte
        </el-button>
      </el-col>
      <el-col :span="24" class="mt-25">
        <el-button type="primary" @click="$router.push({ name: 'auth-login' })">
          J'ai déjà un compte
        </el-button>
      </el-col>
    </el-row>
  </el-form>
</template>

<script>
import { mapState } from 'vuex'

export default {
  name: 'AuthRegisterFail',
  data() {
    return {}
  },
  computed: {
    ...mapState('auth', ['registerError'])
  },
  methods: {}
}
</script>
