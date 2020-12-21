<template>
  <el-form @submit.native.prevent="submit" @keyup.enter.native="submit()">
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
    <el-form-item
      label="Mot de passe"
      required
      :error="errorMessage(error, 'password')"
    >
      <el-input
        v-model="form.password"
        :autofocus="true"
        prefix-icon="el-icon-lock"
        show-password
      />
    </el-form-item>
    <el-form-item>
      <el-row>
        <el-col :md="12">
          <el-switch v-model="form.remember" active-text="Se souvenir de moi">
          </el-switch>
        </el-col>
        <el-col :md="12" class="text-right">
          <router-link
            :to="{
              name: guard === 'public' ? 'auth-forgot' : 'admin-auth-forgot'
            }"
          >
            Mot de passe oublié ?
          </router-link>
        </el-col>
      </el-row>
    </el-form-item>
    <el-form-item>
      <el-button :loading="loading" type="secondary" @click="submit">
        Se connecter
      </el-button>
    </el-form-item>
    <el-form-item>
      <el-button
        v-if="guard === 'public'"
        type="text"
        @click="$router.push({ name: 'auth-register' })"
      >
        Créer un compte
      </el-button>
    </el-form-item>
  </el-form>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import { errorMessage } from '@/services/api'

export default {
  name: 'AuthLogin',
  props: {
    guard: {
      type: String,
      default: 'public'
    }
  },
  data() {
    return {
      form: {
        email: '',
        password: '',
        remember: false
      }
    }
  },
  computed: {
    ...mapState('auth', {
      loading: 'loginLoading',
      error: 'loginError'
    })
  },
  methods: {
    errorMessage,
    ...mapActions('auth', ['login']),
    submit() {
      this.login({
        email: this.form.email,
        password: this.form.password,
        remember: this.form.remember,
        guard: this.guard
      })
    }
  }
}
</script>
