<template>
  <div class="not-found">
    <div>
      <h1>
        {{ $t('home.page_not_found') }}
      </h1>
      <router-link to="home"> {{ $t('home.back_to_homepage') }}</router-link>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.not-found {
  margin: 0 auto;
  text-align: center;
  padding-top: 250px;
}
</style>
