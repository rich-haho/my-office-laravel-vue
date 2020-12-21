<template>
  <div>
    <p class="text-center">
      Merci pour votre inscription ! Nous vous prions de valider votre adresse
      email en cliquant sur le lien que vous allez recevoir par mail à l'adresse
      fournie.
    </p>
    <el-button class="mt-20" @click="$router.push('login')">
      Se connecter
    </el-button>
  </div>
</template>

<script>
export default {
  name: 'NeedEmailValidation'
}
</script>
