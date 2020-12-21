<template>
  <router-link to="/demand" class="no-outline">
    <el-card class="no-more p-5">
      <el-row type="flex" align="middle">
        <el-col :span="3" class="hidden-sm-and-down">
          <img src="~@/assets/images/anna.png" />
        </el-col>
        <el-col :span="18">
          <div class="no-more-title p-5">
            {{ $t('demands.card_title') }}
          </div>
          <div class="no-more-text p-5">
            {{ $t('demands.available') }}
          </div>
        </el-col>
        <el-col :span="3">
          <el-button type="text">
            <i class="el-icon-arrow-right" />
          </el-button>
        </el-col>
      </el-row>
    </el-card>
  </router-link>
</template>

<script>
export default {
  name: 'NoMore'
}
</script>

<style scoped lang="scss">
@import '~@/assets/styles/_variables.scss';
.no-outline {
  text-decoration: none;
}
.no-more {
  background-color: $primary-color-light;
  .no-more-title {
    font-size: 1.8rem;
    font-weight: bold;
    color: $primary-color;
  }
  .no-more-text {
    font-size: 1rem;
    color: $color-text-grey;
  }
  .el-button {
    i {
      color: $color-text-grey;
      font-size: 4rem;
    }
  }
}
</style>
