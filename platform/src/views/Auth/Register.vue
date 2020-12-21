<template>
  <el-form @submit.native.prevent="submit">
    <el-form-item
      label="Nom"
      required
      :error="errorMessage(registerError, 'name')"
    >
      <el-input
        v-model="form.name"
        :autofocus="true"
        prefix-icon="el-icon-user"
      />
    </el-form-item>
    <el-form-item
      label="Adresse email"
      required
      :error="errorMessage(registerError, 'email')"
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
      :error="errorMessage(registerError, 'password')"
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
      :error="errorMessage(registerError, 'password')"
    >
      <el-input
        v-model="form.password_confirmation"
        :autofocus="true"
        prefix-icon="el-icon-lock"
        show-password
      />
    </el-form-item>
    <el-form-item>
      <el-button :loading="registerLoading" type="secondary" @click="submit">
        Valider
      </el-button>
    </el-form-item>
    <el-form-item>
      <el-button type="text" @click="$router.push({ name: 'auth-login' })">
        J'ai déjà un compte
      </el-button>
    </el-form-item>
  </el-form>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import { errorMessage } from '@/services/api'

export default {
  name: 'AuthRegister',
  data() {
    return {
      form: {
        email: '',
        name: '',
        password: '',
        password_confirmation: ''
      }
    }
  },
  computed: {
    ...mapState('auth', ['registerLoading', 'registerError'])
  },
  methods: {
    errorMessage,
    ...mapActions('auth', ['register']),
    submit() {
      this.register({
        email: this.form.email,
        name: this.form.name,
        password: this.form.password,
        password_confirmation: this.form.password_confirmation
      })
    }
  }
}
</script>
