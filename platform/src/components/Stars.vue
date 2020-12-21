<template>
  <div class="stars">
    <i
      v-for="(star, index) in filledStars"
      :key="`filled-${index}`"
      class="icon ion-ios-star"
    ></i>
    <i v-if="hasHalfStar" class="icon ion-ios-star-half"></i>
    <i
      v-for="(star, index) in emptyStars"
      :key="`empty-${index}`"
      class="icon ion-ios-star-outline"
    ></i>
    <span class="score">{{ current }}/{{ total }}</span>
  </div>
</template>

<script>
export default {
  name: 'Stars',
  props: {
    total: { type: Number, default: 5 },
    current: { type: Number, required: true }
  },
  computed: {
    filledStars() {
      return Math.floor(this.current)
    },
    hasHalfStar() {
      return this.current % 1 !== 0
    },
    emptyStars() {
      return this.total - (this.filledStars + (this.hasHalfStar ? 1 : 0))
    }
  }
}
</script>

<style scoped lang="scss">
@import '~@/assets/styles/_variables.scss';

.stars {
  i,
  .score {
    margin-right: 10px;
    font-weight: bold;
    color: $color-orange;
  }
}
</style>
