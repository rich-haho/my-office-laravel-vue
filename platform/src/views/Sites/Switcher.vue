<template>
  <div class="location vertical-align">
    <i class="icon ion-ios-pin"></i>
    <div class="location-container">
      <el-select :value="site_id" @change="changeSite">
        <el-option
          v-for="site in userSitesList.data"
          :key="site.id"
          :label="site.name.toUpperCase()"
          :value="site.id"
        >
        </el-option>
      </el-select>
      <div class="location-label">{{ $t('sites.change_site') }}</div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'

export default {
  name: 'SiteSwitcher',
  computed: {
    ...mapGetters('auth', ['locale', 'site_id']),
    ...mapState('sites', ['userSitesList'])
  },
  methods: {
    ...mapActions('auth', ['setSiteLocale']),
    async changeSite(value) {
      if (value) {
        await this.setSiteLocale({
          locale_id: this.locale.id,
          site_id: value ? value : 1
        })
      }
    }
  }
}
</script>

<style lang="scss">
@import '~@/assets/styles/_variables.scss';

.location {
  .icon {
    font-size: 22px;
    color: $color-light-dark;
  }
  .location-container {
    @media screen and (max-width: $--md) {
      width: 100%;
    }
    position: relative;
    .location-label {
      position: absolute;
      font-size: 10px;
      color: red;
      font-weight: 800;
      bottom: -2px;
    }
  }
}

.location {
  .el-select {
    @media screen and (max-width: $--md) {
      width: 100%;
    }
    .el-input__inner {
      background: transparent;
      border: 0;
      font-weight: bold;
    }

    .el-input__suffix {
      .el-select__caret {
        color: $primary-color;
        font-size: 20px;
      }
    }
  }
}
</style>
