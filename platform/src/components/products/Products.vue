<template>
  <div class="product-list">
    <product
      v-for="product in productList"
      :key="product.id"
      :product="product"
      :link-favorite="productsFavorite"
    />
  </div>
</template>
<script>
import Product from './Product.vue'
export default {
  components: {
    Product
  },
  props: {
    productList: {
      type: Array,
      required: false,
      default: () => []
    },
    productsFavorite: {
      type: Function,
      required: false,
      default: value => value
    }
  }
}
</script>
