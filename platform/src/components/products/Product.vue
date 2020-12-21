<template>
  <div class="product">
    <router-link
      exact
      :style="{ cursor: 'pointer' }"
      :to="'/products/' + product.id"
    >
      <div class="image-container vertical-align">
        <el-image
          fit="contain"
          :src="product.assets ? product.assets.full_path : ''"
          class="image"
          @click.stop
        >
          <div slot="error" class="image-slot image-error">
            <i class="el-icon-picture-outline"></i>
          </div>
        </el-image>
      </div>
    </router-link>
    <div class="product-detail-container">
      <router-link
        tag="div"
        exact
        :style="{ cursor: 'pointer' }"
        :to="'/products/' + product.id"
      >
        <div class="product-detail">
          <div class="product-title">
            {{ product.name }}
          </div>
          <div class="product-description">
            {{ product.description }}
          </div>
        </div>
      </router-link>
      <div class="product-buttons horizontal-align">
        <div class="cost-container">
          <div class="product-favorite vertical-align hidden-sm-and-down">
            <favorite
              v-if="withFavorite"
              :value="product.favorite"
              :show-service="false"
              @click.native="toggleFavorite(product.id)"
            />
          </div>
          <div class="product-cost vertical-align-column">
            <div
              v-if="product.price_reduction && product.price_reduction > 0"
              class="scratch-price"
            >
              {{ product.price_reduction }} {{ product.currency.name }}
            </div>
            <div class="real-price vertical-align">
              <span class="price"> {{ product.price }} </span>
              <span class="currency">{{ product.currency.name }}</span>
            </div>
          </div>
        </div>
        <router-link
          :class="[
            'see-button all-align hidden-sm-and-down',
            { hidden: location === 'home' }
          ]"
          tag="div"
          :to="'/products/' + product.id"
          >{{ $t('components.product_see') }}
        </router-link>
      </div>
    </div>
  </div>
</template>
<script>
import Favorite from '../Favorite.vue'
export default {
  components: {
    Favorite
  },
  props: {
    product: {
      type: Object,
      required: true
    },
    location: {
      type: String,
      required: false,
      default: ''
    },
    linkFavorite: {
      type: Function,
      required: false,
      default: () => ''
    },
    withFavorite: {
      type: Boolean,
      default: true
    }
  },
  methods: {
    toggleFavorite(value) {
      this.linkFavorite(value)
      this.$emit('click', value)
    }
  }
}
</script>
<style lang="scss">
@import '~@/assets/styles/_variables.scss';

.image-slot.image-error {
  width: 100%;
  height: 100%;
  background-color: $color-light-grey;
  display: flex;
  justify-content: center;
  align-items: center;
  i {
    /*margin-left: 41%;*/
    /*margin-top: 50%;*/
    color: $color-white;
    font-size: 2rem;
  }
}
</style>
<style lang="scss" scoped>
@import '~@/assets/styles/_variables.scss';
.product {
  min-height: 150px;
  border-radius: 2px;
  box-shadow: 0 12px 36px 0 rgba(0, 0, 0, 0.2);
  background-color: #fff;
  margin-bottom: 25px;
  display: flex;
  align-items: stretch;
  transition: all 0.1s;
  &:hover {
    transform: scale(1.02);
    transition: all 0.3s;
  }
  a {
    text-decoration: none;
  }
}
.image {
  background-color: $color-white;
  width: 150px;
  height: 100%;
}

.image-container {
  height: 100%;
}

.product-detail-container {
  width: 100%;
  border-left: 1px solid $color-light-blue;
  display: flex;
  align-items: stretch;
  justify-content: stretch;
  flex-flow: column;
  .product-detail {
    min-height: 95px;
    padding: 9px 40px;
    display: flex;
    flex-flow: column;
    height: 100%;
    @media screen and (max-width: $--md) {
      justify-content: center;
    }
    @media screen and (max-width: $--md) {
      padding: 16px 20px 0px 20px;
    }
    .product-title {
      font-size: 22px;
      font-weight: 300;
      letter-spacing: 0.33px;
      padding-bottom: 5px;
      @media screen and (max-width: $--sm) {
        font-size: 20px;
      }
    }
    .product-description {
      font-size: 12px;
      color: $color-text-grey;
      letter-spacing: 0.18px;
      display: -webkit-box;
      -webkit-box-orient: vertical;
      -webkit-line-clamp: 3;
      overflow: hidden;
    }
  }
  .product-buttons {
    height: 55px;
    border-top: 1px solid $color-light-blue;
    @media screen and (max-width: $--sm) {
      border-top: none;
    }
    .cost-container {
      width: 100%;
      padding: 2px 15px 2px 40px;
      display: flex;
      justify-content: space-between;
      @media screen and (max-width: $--sm) {
        padding: 0px 15px 0px 20px;
      }
      .scratch-price {
        font-size: 13px;
        font-weight: bold;
        text-decoration: line-through;
        color: $color-light-dark;
      }
      .real-price {
        .price {
          font-size: 22px;
          color: $primary-color;
          margin-right: 5px;
        }
        .currency {
          font-size: 22px;
          color: $color-light-grey;
        }
      }
    }
    .see-button {
      &.hidden {
        display: none;
      }
      width: 170px;
      font-weight: bold;
      color: $primary-color;
      background-color: $color-light-blue;
      font-size: 14px;
      &:hover {
        cursor: pointer;
        background-color: darken($color-light-blue, 5%);
      }
    }
  }
}
</style>
