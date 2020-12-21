<template>
  <div>
    <p class="text-center">
      Votre adresse email a été validée. Vous pouvez désormais vous connecter à
      la plateforme.
    </p>
    <el-button class="mt-20" @click="$router.push('login')">
      Se connecter
    </el-button>
  </div>
</template>

<script>
export default {
  name: 'EmailVerified'
}
</script>
