<template>
  <el-form @submit.native.prevent="submit">
    <el-form-item
      label="Adresse email"
      required
      :error="errorMessage(error, 'email')"
    >
      <el-input
        v-model="form.email"
        :autofocus="true"
        prefix-icon="el-icon-message"
      />
    </el-form-item>

    <el-form-item>
      <el-button
        :loading="loading"
        :pain="true"
        type="secondary"
        @click="submit"
      >
        Modifier mon mot de passe
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
  name: 'AuthForgot',
  props: {
    guard: {
      type: String,
      default: 'public'
    }
  },
  data() {
    return {
      form: {
        email: ''
      }
    }
  },
  computed: {
    ...mapState('auth', {
      loading: 'forgotLoading',
      error: 'forgotError',
      sentResetLink: 'sentResetLink'
    })
  },
  methods: {
    errorMessage,
    ...mapActions('auth', ['forgot']),
    async submit() {
      await this.forgot({
        email: this.form.email,
        guard: this.guard
      })
      if (this.sentResetLink) {
        this.$message(this.sentResetLink)
      }
    }
  }
}
</script>
