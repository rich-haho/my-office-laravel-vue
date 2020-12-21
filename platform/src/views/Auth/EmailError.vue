<template>
  <div>
    <p class="text-center">
      Une erreur est survenue lors de la validation de votre email.
    </p>
    <div>
      <el-button :loading="loading" class="mt-20" @click="submit">
        Renvoyer le mail de verification
      </el-button>
    </div>
    <div>
      <el-button class="mt-20" @click="$router.push('login')">
        Se connecter
      </el-button>
    </div>
  </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import { errorMessage } from '@/services/api'

export default {
  name: 'EmailError',
  computed: {
    ...mapState('auth', {
      loading: 'askMailVerificationNotificationLoading'
    })
  },
  methods: {
    errorMessage,
    ...mapActions('auth', ['askMailVerificationNotification']),
    async submit() {
      await this.askMailVerificationNotification({
        id: this.$route.params.id
      })
      if (this.success) {
        this.$message('Un nouveau mail de confirmation vous a été adressé.')
      }
    }
  }
}
</script>
