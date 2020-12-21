<template>
  <div class="prices">
    <span v-if="oldPrice" class="old-price">
      {{ oldPrice | decimal }} {{ currency }}
    </span>
    <span class="real-price">{{ realPrice | decimal }} {{ currency }}</span>
  </div>
</template>

<script>
export default {
  name: 'Prices',
  filters: {
    decimal(number) {
      return number.toFixed(2)
    }
  },
  props: {
    currency: { type: String, default: 'CHF' },
    oldPrice: { type: Number, required: true },
    realPrice: { type: Number, required: true }
  }
}
</script>

<style scoped lang="scss">
@import '~@/assets/styles/_variables.scss';

.prices {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  .old-price {
    color: $color-light-dark;
    text-decoration: line-through;
  }
  .real-price {
    font-size: 2em;
    font-weight: bold;
    color: $primary-color;
  }
}
</style>
