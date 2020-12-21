<template>
  <el-card
    :class="[
      {
        'auto-sizing': minWidthAuto
      },
      'zen-card mb-30 mb-sm-10 mt-sm-5 mt-20'
    ]"
    :body-style="{ padding: '0px' }"
  >
    <el-image fit="contain" :src="image" class="category-image" @click.stop>
      <div slot="error" class="image-slot image-error-category">
        <i class="el-icon-picture-outline"></i>
      </div>
    </el-image>
    <div class="label all-align">
      {{ name }}
    </div>
  </el-card>
</template>
<script>
export default {
  props: {
    name: {
      required: true,
      type: String
    },
    image: {
      required: true,
      type: String
    },
    minWidthAuto: {
      required: false,
      type: Boolean,
      default: true
    }
  }
}
</script>
<style lang="scss" scoped>
@import '~@/assets/styles/_variables.scss';
.zen-card {
  min-width: 164px;
  border: 0;
  @media screen and (max-width: $--xs) {
    min-width: 130px;
  }

  &.auto-sizing {
    min-width: auto;
  }

  &:hover {
    transform: scale(1.02);
    transition: all 0.3s;
    cursor: pointer;
  }

  .category-image {
    height: 180px;
    display: block;
    @media screen and (max-width: $--xs) {
      height: 150px;
    }
  }

  .label {
    background-color: $primary-color;
    font-size: 13px;
    height: 48px;
    color: #fff;
    padding: 5px;
  }
}
</style>
<style lang="scss">
@import '~@/assets/styles/_variables.scss';
.image-slot.image-error-category {
  width: 100%;
  height: 180px;
  @media screen and (max-width: $--xs) {
    height: 150px;
  }
  background-color: $color-light-grey;
  display: flex;
  align-items: center;
  justify-content: center;

  i {
    margin-top: 10%;
    color: $color-white;
    font-size: 2rem;
  }
}
</style>
