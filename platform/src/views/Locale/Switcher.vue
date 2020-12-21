<template>
  <div class="language vertical-align">
    <el-select :value="locale.id" @change="changeLocale">
      <el-option
        v-for="locale in localesList.data"
        :key="locale.id"
        :label="locale.name.toUpperCase()"
        :value="locale.id"
      >
      </el-option>
    </el-select>
  </div>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'
import { loadMessages } from '@/plugins/i18n'

export default {
  name: 'LocaleSwitcher',
  computed: {
    ...mapGetters('auth', ['locale', 'site_id']),
    ...mapState('locales', ['localesList'])
  },
  mounted: function() {
    this.getLocales()
  },
  methods: {
    ...mapActions('auth', ['setSiteLocale']),
    ...mapActions('locales', ['getLocales']),
    async changeLocale(value) {
      await this.setSiteLocale({
        locale_id: value,
        site_id: this.site_id
      })
      loadMessages(this.locale.name)
    }
  }
}
</script>
<style lang="scss">
@import '~@/assets/styles/_variables.scss';

.language {
  .el-select {
    width: 70px;
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
