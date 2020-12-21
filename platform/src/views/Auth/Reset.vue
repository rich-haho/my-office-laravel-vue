<template>
  <el-form @submit.native.prevent="submit">
    <el-form-item
      label="Adresse email"
      required
      :error="errorMessage(resetError, 'email')"
    >
      <el-input
        v-model="form.email"
        :autofocus="true"
        prefix-icon="el-icon-message"
      />
    </el-form-item>
    <el-form-item
      label="Mot de passe"
      required
      :error="errorMessage(resetError, 'password')"
    >
      <el-input
        v-model="form.password"
        :autofocus="true"
        prefix-icon="el-icon-lock"
        show-password
      />
    </el-form-item>
    <el-form-item
      label="Confirmer le mot de passe"
      required
      :error="errorMessage(resetError, 'password')"
    >
      <el-input
        v-model="form.password_confirmation"
        :autofocus="true"
        prefix-icon="el-icon-lock"
        show-password
      />
    </el-form-item>
    <el-form-item>
      <el-button
        :loading="resetLoading"
        :pain="true"
        type="secondary"
        @click="submit"
      >
        Reset Password
      </el-button>
    </el-form-item>
    <el-form-item>
      <el-button
        type="text"
        @click="
          $router.push({
            name: guard === 'public' ? 'auth-login' : 'admin-auth-login'
          })
        "
      >
        Se connecter
      </el-button>
    </el-form-item>
  </el-form>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import { errorMessage } from '@/services/api'

export default {
  name: 'AuthReset',
  props: {
    guard: {
      type: String,
      default: 'public'
    }
  },
  data() {
    return {
      form: {
        email: this.$route.params.email,
        password: '',
        password_confirmation: ''
      }
    }
  },
  computed: {
    ...mapState('auth', ['resetLoading', 'resetError', 'resetStatus'])
  },
  methods: {
    errorMessage,
    ...mapActions('auth', ['reset', 'logout']),
    async submit() {
      await this.reset({
        token: this.$route.params.token,
        email: this.form.email,
        password: this.form.password,
        password_confirmation: this.form.password_confirmation,
        guard: this.guard
      })
      if (this.resetStatus) {
        this.$message(this.resetStatus)
        this.logout({ guard: this.guard })
      }
    }
  }
}
</script>
