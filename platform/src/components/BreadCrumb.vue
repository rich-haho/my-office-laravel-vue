<template>
  <div class="bread-crumb vertical-align">
    <i class="icon ion-ios-home"></i>
    <router-link
      v-if="slotSize >= 1"
      tag="div"
      exact
      class="item vertical-align"
      :style="{ cursor: 'pointer' }"
      :to="link1"
    >
      <i class="icon ion-ios-arrow-forward"></i>
      <div :class="['label', { current: slotSize === 1 }]">
        <slot name="1" />
      </div>
    </router-link>
    <div v-if="slotSize >= 2" class="item vertical-align">
      <i class="icon ion-ios-arrow-forward"></i>
      <div :class="['label', { current: slotSize === 2 }]">
        <slot name="2" />
      </div>
    </div>
    <div v-if="slotSize >= 3" class="item vertical-align">
      <i class="icon ion-ios-arrow-forward"></i>
      <div :class="['label', { current: slotSize === 3 }]">
        <slot name="3" />
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    slotSize: {
      required: true,
      type: Number
    },
    link1: {
      required: true,
      type: String
    }
  }
}
</script>
<style lang="scss" scoped>
@import '~@/assets/styles/_variables.scss';

.bread-crumb {
  width: 100%;
  .icon {
    color: $color-light-dark;
    font-size: 20px;
  }
  .item {
    height: 100%;
    padding-left: 15px;
    position: relative;
    top: 1px;
    .icon {
      font-size: 10px;
      padding-right: 10px;
      position: relative;
    }
    .label {
      font-size: 14px;
      &.current {
        color: $color-light-dark;
      }
    }
  }
}
</style>
