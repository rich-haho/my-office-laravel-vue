<template>
  <el-container>
    <el-main class="empty-main-wrapper">
      <div class="empty-main">
        <el-row type="flex" justify="center" class="empty-main-row">
          <img src="@/assets/images/anna.png" alt="Anna" class="card-over" />
          <el-col :md="18" :lg="12">
            <el-card class="box-card pl-sm-5 pl-75 pr-sm-5 pr-75 pt-50">
              <div class="text-center mt-20">
                <img
                  src="@/assets/images/logo.png"
                  alt="Zen Office"
                  class="empty-main-logo"
                />
              </div>
              <div class="text-center tagline mb-30 mt-10">
                {{ $t('home.welcome') }}
              </div>
              <slot />
            </el-card>
          </el-col>
        </el-row>
      </div>
    </el-main>
  </el-container>
</template>

<script>
export default {}
</script>

<style scoped>
.empty-main-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
}

.empty-main {
  width: 100%;
}

.card-over {
  display: block;
  position: absolute;
  top: 0;
  transform: translateY(-50%);
}

.tagline {
  font-size: 0.8rem;
}

.empty-main-row {
  position: relative;
  overflow: inherit;
}

@media (max-height: 812px) {
  .card-over {
    height: 14vh;
    transform: translateY(-35%);
  }
}

@media (max-height: 775px) {
  .card-over {
    height: 15vh;
    transform: translateY(-20%);
  }
}
</style>
