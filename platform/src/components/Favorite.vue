<template>
  <div :class="['favorite vertical-align', { 'is-fav': value }]">
    <i v-if="!value" class="icon ion-ios-heart-empty"></i>
    <i v-if="value" class="icon ion-ios-heart"></i>
    <div v-if="showService" class="favourite-text">
      {{
        value
          ? $t('components.favorite_category_remove')
          : $t('components.favorite_category_add')
      }}
    </div>
    <div v-else class="favourite-text">
      {{
        value ? $t('components.favorite_remove') : $t('components.favorite_add')
      }}
    </div>
  </div>
</template>
<script>
export default {
  props: {
    value: {
      type: Boolean,
      required: false,
      default: false
    },
    showService: {
      type: Boolean,
      required: false,
      default: false
    }
  }
}
</script>
<style lang="scss" scoped>
@import '~@/assets/styles/_variables.scss';
.favorite {
  &:hover {
    cursor: pointer;
  }
  .icon {
    font-size: 16px;
    color: $color-light-grey;
  }
  .favourite-text {
    padding-left: 10px;
    color: $color-light-grey;
    font-size: 13px;
  }
  &.is-fav {
    .icon {
      color: $secondary-color;
    }
    .favourite-text {
      color: $primary-color;
    }
  }
}
</style>
